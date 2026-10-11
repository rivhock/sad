<?php
namespace App\Filament\Compaq\Resources;

use App\Enums\StatusAvaliacao;
use App\Filament\Compaq\Resources\AvaliacaoResource\Pages;
use App\Models\Avaliacao;
use App\Services\AssinaturaService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AvaliacaoResource extends Resource
{
    protected static ?string $model = Avaliacao::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Avaliações';
    protected static ?string $navigationLabel = 'Avaliações';
    protected static ?string $modelLabel = 'Avaliação';
    protected static ?string $pluralModelLabel = 'Avaliações';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Vínculo')
                ->schema([
                    Forms\Components\Select::make('ciclo_id')
                        ->label('Ciclo')->relationship('ciclo', 'nome')->required()->searchable()->preload(),
                    Forms\Components\Select::make('formulario_id')
                        ->label('Formulário')->relationship('formulario', 'titulo')->required()->searchable()->preload(),
                    Forms\Components\Select::make('servidor_id')
                        ->label('Servidor Avaliado')
                        ->relationship('servidor', 'id')
                        ->getOptionLabelFromRecordUsing(fn ($record) => $record->user?->nome . ' — ' . $record->user?->matricula)
                        ->required()->searchable()->preload(),
                    Forms\Components\Select::make('avaliador_id')
                        ->label('Avaliador')
                        ->relationship('avaliador', 'nome')
                        ->searchable()
                        ->preload()
                        ->placeholder('Deixe em branco para usar link externo')
                        ->helperText('Se deixar em branco, você poderá gerar um link para a chefia preencher sem login.'),
                    Forms\Components\Select::make('tipo')
                        ->label('Tipo')
                        ->options([
                            'estagio_probatorio' => 'Estágio Probatório',
                            'desempenho'         => 'Avaliação de Desempenho',
                        ])
                        ->default('desempenho')->required(),
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options(collect(StatusAvaliacao::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()])->toArray())
                        ->default(StatusAvaliacao::PENDENTE->value)
                        ->required()
                        ->disabled()
                        ->dehydrated()
                        ->helperText('O status é controlado pelo fluxo (preencher → assinar → notificar → homologar).'),
                ])->columns(2),

            Forms\Components\Section::make('Observações')
                ->schema([
                    Forms\Components\Textarea::make('observacoes')->label('Observações')->rows(3)->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // ============================================
                // SERVIDOR — nome + matrícula agrupados
                // ============================================
                Tables\Columns\TextColumn::make('servidor.user.nome')
                    ->label('Servidor')
                    ->searchable(['nome', 'matricula'])
                    ->sortable()
                    ->description(fn (Avaliacao $record) =>
                        'Mat. ' . ($record->servidor?->user?->matricula ?? '—')
                        . ' • ' . ($record->servidor?->cargo?->nome ?? 'sem cargo')
                    )
                    ->weight('bold')
                    ->size('lg'),

                // ============================================
                // AVALIADOR — badge interna ou externa
                // ============================================
                Tables\Columns\TextColumn::make('avaliador.nome')
                    ->label('Avaliador')
                    ->searchable()
                    ->placeholder('—')
                    ->badge()
                    ->color(fn (Avaliacao $record) =>
                        $record->avaliador_id ? 'info' : 'warning'
                    )
                    ->formatStateUsing(fn (Avaliacao $record) =>
                        $record->avaliador?->nome
                            ?? ($record->assinaturas->firstWhere('tipo_assinante','chefia')?->nome_assinante
                                ? $record->assinaturas->firstWhere('tipo_assinante','chefia')->nome_assinante . ' (externo)'
                                : 'Link externo')
                    )
                    ->toggleable(),

                // ============================================
                // CICLO
                // ============================================
                Tables\Columns\TextColumn::make('ciclo.nome')
                    ->label('Ciclo')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                // ============================================
                // STATUS — destaque
                // ============================================
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => $state instanceof \App\Enums\StatusAvaliacao ? $state->label() : $state)
                    ->badge()
                    ->color(fn ($state) => $state instanceof \App\Enums\StatusAvaliacao ? $state->cor() : 'gray')
                    ->sortable(),

                // ============================================
                // NOTA — destaque numérico
                // ============================================
                Tables\Columns\TextColumn::make('nota_total')
                    ->label('Nota')
                    ->numeric(decimalPlaces: 2)
                    ->badge()
                    ->size('lg')
                    ->weight('bold')
                    ->color(fn ($state) => match(true) {
                        $state === null      => 'gray',
                        $state >= 90         => 'success',
                        $state >= 70         => 'info',
                        $state >= 50         => 'warning',
                        default              => 'danger',
                    })
                    ->placeholder('—')
                    ->alignCenter(),

                // ============================================
                // ÍCONES COMPACTOS — cursos, defesa, compaq
                // ============================================
                Tables\Columns\IconColumn::make('servidor.qualificacoes_count')
                    ->label('Cursos')
                    ->getStateUsing(fn (Avaliacao $record) => $record->servidor?->qualificacoes()->where('status','aprovado')->count() ?? 0)
                    ->icon(fn ($state) => $state > 0 ? 'heroicon-o-academic-cap' : 'heroicon-o-minus')
                    ->color(fn ($state) => $state > 0 ? 'info' : 'gray')
                    ->tooltip(fn (Avaliacao $record) =>
                        ($record->servidor?->qualificacoes()->where('status','aprovado')->count() ?? 0)
                        . ' curso(s) aprovado(s)'
                    )
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('defesas_count')
                    ->label('Defesa')
                    ->counts('defesas')
                    ->icon(fn ($state) => $state > 0 ? 'heroicon-o-document-text' : 'heroicon-o-minus')
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'gray')
                    ->tooltip(fn ($state) => $state > 0 ? 'Clique para ver a defesa e o parecer' : 'Sem defesa')
                    ->alignCenter()
                    ->action(
                        Tables\Actions\Action::make('ver_defesa_parecer')
                            ->modalHeading('Defesa e Parecer')
                            ->modalWidth('3xl')
                            ->modalContent(fn (Avaliacao $record) => view('filament.compaq.defesa-historico', [
                                'avaliacao' => $record,
                                'defesa'    => $record->defesas()->latest()->first(),
                            ]))
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Fechar')
                            ->visible(fn (Avaliacao $record) => $record->defesas()->exists())
                    ),

                Tables\Columns\IconColumn::make('compaq_assinou')
                    ->label('COMPAQ')
                    ->getStateUsing(fn (Avaliacao $record) => $record->assinaturas()->where('tipo_assinante', 'compaq')->exists())
                    ->icon(fn ($state) => $state ? 'heroicon-o-check-badge' : 'heroicon-o-clock')
                    ->color(fn ($state) => $state ? 'success' : 'warning')
                    ->tooltip(fn ($state) => $state ? 'Homologada' : 'Aguardando homologação')
                    ->alignCenter(),

                // ============================================
                // DATA (oculta por padrão)
                // ============================================
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criada')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('data_notificacao')
                    ->label('Notificado')
                    ->date('d/m/Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->filters([
                Tables\Filters\Filter::make('avaliado_em')
                    ->label('Avaliado em')
                    ->form([
                        Forms\Components\DatePicker::make('de')->label('De')->native(false),
                        Forms\Components\DatePicker::make('ate')->label('Até')->native(false),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['de'] ?? null, fn ($q, $d) => $q->whereDate('data_avaliacao', '>=', $d))
                            ->when($data['ate'] ?? null, fn ($q, $d) => $q->whereDate('data_avaliacao', '<=', $d));
                    }),

                Tables\Filters\Filter::make('criada_em')
                    ->label('Criada em')
                    ->form([
                        Forms\Components\DatePicker::make('de')->label('De')->native(false),
                        Forms\Components\DatePicker::make('ate')->label('Até')->native(false),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['de'] ?? null, fn ($q, $d) => $q->whereDate('created_at', '>=', $d))
                            ->when($data['ate'] ?? null, fn ($q, $d) => $q->whereDate('created_at', '<=', $d));
                    }),

                Tables\Filters\SelectFilter::make('ciclo')->relationship('ciclo', 'nome'),
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(\App\Enums\StatusAvaliacao::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()])->toArray()),
                Tables\Filters\SelectFilter::make('avaliador')->relationship('avaliador', 'nome'),
                Tables\Filters\SelectFilter::make('secretaria')
                    ->label('Secretaria')
                    ->relationship('servidor.secretaria', 'nome'),
                Tables\Filters\Filter::make('aguardando_compaq')
                    ->label('Aguardando COMPAQ')
                    ->query(fn ($query) => $query
                        ->whereHas('assinaturas', fn ($q) => $q->where('tipo_assinante', 'chefia'))
                        ->whereDoesntHave('assinaturas', fn ($q) => $q->where('tipo_assinante', 'compaq'))
                    )
                    ->toggle(),
                Tables\Filters\Filter::make('aguardando_chefia')
                    ->label('Aguardando chefia')
                    ->query(fn ($query) => $query
                        ->whereDoesntHave('assinaturas', fn ($q) => $q->where('tipo_assinante', 'chefia'))
                    )
                    ->toggle(),
            ])
            ->actions([
                // ============================================
                // AÇÃO PRINCIPAL — ASSINAR COMPAQ (destacada)
                // ============================================
                Tables\Actions\Action::make('assinar_compaq')
                    ->label('Assinar')
                    ->icon('heroicon-o-shield-check')
                    ->color('success')
                    ->button()
                    ->size('md')
                    ->visible(fn (Avaliacao $record) =>
                        ! $record->assinaturas()->where('tipo_assinante', 'compaq')->exists()
                        && $record->assinaturas()->where('tipo_assinante', 'chefia')->exists()
                        && ! ($record->defesas()->exists() && ! $record->defesas()->whereNotNull('parecer_decisao')->exists())
                    )
                    ->requiresConfirmation()
                    ->modalHeading('Assinar como membro da COMPAQ')
                    ->modalDescription('Ao assinar, esta avaliação será homologada. A ação será registrada com data, hora, IP e hash.')
                    ->action(function (Avaliacao $record) {
                        \App\Services\AssinaturaService::assinar($record, auth()->user(), 'compaq');
                        if ($record->status !== \App\Enums\StatusAvaliacao::CONCLUIDA) {
                            $record->update(['status' => \App\Enums\StatusAvaliacao::CONCLUIDA]);
                        }
                        \Filament\Notifications\Notification::make()
                            ->title('Avaliação homologada')
                            ->success()
                            ->send();
                    }),

                // ============================================
                // AÇÕES SECUNDÁRIAS — ícones compactos
                // ============================================
                Tables\Actions\Action::make('analisar_defesa')
                    ->label('Analisar Defesa')
                    ->icon('heroicon-o-scale')
                    ->color('warning')
                    ->button()
                    ->visible(fn (Avaliacao $record) =>
                        $record->defesas()->exists()
                        && ! $record->defesas()->whereNotNull('parecer_decisao')->exists()
                    )
                    ->modalHeading('Analisar defesa')
                    ->modalWidth('3xl')
                    ->modalContent(fn (Avaliacao $record) => view('filament.compaq.parecer-modal', [
                        'avaliacao' => $record,
                        'defesa'    => $record->defesas()->latest()->first(),
                    ]))
                    ->form(function (Avaliacao $record) {
                        $defesa = $record->defesas()->latest()->first();
                        $itens = $record->formulario->itens;
                        $respostas = $record->respostas->keyBy('item_id');

                        $fields = [
                            Forms\Components\Select::make('decisao')
                                ->label('Decisão')
                                ->options(\App\Models\Defesa::decisoes())
                                ->required()
                                ->live()
                                ->helperText('Escolha o tipo de parecer.'),

                            Forms\Components\Textarea::make('texto')
                                ->label('Parecer da COMPAQ')
                                ->required()
                                ->rows(5)
                                ->minLength(20)
                                ->maxLength(5000)
                                ->helperText('Justifique a decisão. Este texto vai no PDF final e no histórico do servidor.'),
                        ];

                        // Só mostra ajuste de notas se for deferida/parcialmente deferida
                        $itensFields = [];
                        foreach ($itens as $item) {
                            $notaAtual = $respostas->get($item->id)?->nota ?? 0;
                            $itensFields[] = Forms\Components\TextInput::make("notas_ajuste.{$item->id}")
                                ->label("Item {$item->ordem} (atual: {$notaAtual})")
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(10)
                                ->step(0.5)
                                ->default($notaAtual);
                        }

                        $fields[] = Forms\Components\Section::make('Ajuste de notas (opcional)')
                            ->description('Altere apenas as notas que precisam mudar. Ao salvar, a nota total será recalculada.')
                            ->schema($itensFields)
                            ->columns(2)
                            ->visible(fn (Forms\Get $get) => in_array($get('decisao'), ['deferida', 'parcialmente_deferida']))
                            ->collapsible();

                        $fields[] = Forms\Components\Placeholder::make('aviso_pad')
                            ->label('')
                            ->content('⚠️ Ao encaminhar para PAD, a avaliação NÃO será concluída e ficará pendente de decisão superior.')
                            ->visible(fn (Forms\Get $get) => $get('decisao') === 'pad');

                        return $fields;
                    })
                    ->action(function (Avaliacao $record, array $data) {
                        try {
                            \App\Services\ParecerDefesaService::emitir($record, $data);

                            \Filament\Notifications\Notification::make()
                                ->title('Parecer emitido com sucesso')
                                ->body($data['decisao'] === 'pad'
                                    ? 'Avaliação encaminhada para PAD.'
                                    : 'A avaliação voltou para o servidor assinar a versão final.'
                                )
                                ->success()
                                ->send();
                        } catch (\Throwable $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Erro ao emitir parecer')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('visualizar')
                        ->label('Visualizar avaliação')
                        ->icon('heroicon-o-eye')
                        ->url(fn (Avaliacao $record) => AvaliacaoResource::getUrl('visualizar', ['record' => $record])),

                    Tables\Actions\Action::make('pdf')
                        ->label('Baixar PDF')
                        ->icon('heroicon-o-document-arrow-down')
                        ->url(fn (Avaliacao $record) => route('avaliacao.pdf.download', $record))
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('link_avaliado')
                        ->label('Link do avaliado')
                        ->icon('heroicon-o-user')
                        ->visible(fn (Avaliacao $record) => $record->token_acesso !== null)
                        ->modalHeading('Link do servidor avaliado')
                        ->modalContent(fn (Avaliacao $record) => view('filament.compaq.avaliacao-link', ['avaliacao' => $record]))
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Fechar'),

                    Tables\Actions\Action::make('link_avaliador')
                        ->label('Link do avaliador')
                        ->icon('heroicon-o-user-circle')
                        ->visible(fn (Avaliacao $record) => ! $record->assinaturas()->where('tipo_assinante', 'chefia')->exists())
                        ->modalHeading('Link do avaliador externo')
                        ->modalContent(fn (Avaliacao $record) => view('filament.compaq.avaliador-externo-link', ['avaliacao' => $record]))
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Fechar'),

                    Tables\Actions\Action::make('gerar_token_avaliador')
                        ->label('Gerar link do avaliador')
                        ->icon('heroicon-o-plus-circle')
                        ->visible(fn (Avaliacao $record) =>
                            ! $record->assinaturas()->where('tipo_assinante', 'chefia')->exists()
                            && ! $record->token_avaliador
                        )
                        ->requiresConfirmation()
                        ->modalHeading('Gerar link do avaliador externo')
                        ->modalDescription('Será criado um link válido por 30 dias para a chefia preencher a avaliação sem login.')
                        ->action(function (Avaliacao $record) {
                            $record->gerarTokenAvaliador(30);
                            \Filament\Notifications\Notification::make()
                                ->title('Link gerado (30 dias)')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('regenerar_token_avaliador')
                        ->label('Renovar link do avaliador')
                        ->icon('heroicon-o-arrow-path')
                        ->visible(fn (Avaliacao $record) =>
                            ! $record->assinaturas()->where('tipo_assinante', 'chefia')->exists()
                            && $record->token_avaliador
                        )
                        ->requiresConfirmation()
                        ->action(function (Avaliacao $record) {
                            $record->gerarTokenAvaliador(30);
                            \Filament\Notifications\Notification::make()
                                ->title('Link renovado (30 dias)')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\EditAction::make()
                        ->label('Editar'),

                    Tables\Actions\DeleteAction::make()
                        ->label('Excluir'),
                ])
                    ->label('Ações')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->color('gray')
                    ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('assinar_compaq_lote')
                    ->label('Assinar COMPAQ em lote')
                    ->icon('heroicon-o-shield-check')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Assinar em lote como COMPAQ')
                    ->modalDescription('Todas as avaliações selecionadas que já tenham assinatura da chefia serão homologadas.')
                    ->action(function ($records) {
                        $ok = 0;
                        $ignoradas = 0;
                        foreach ($records as $record) {
                            if ($record->assinaturas()->where('tipo_assinante', 'compaq')->exists()) {
                                $ignoradas++;
                                continue;
                            }
                            if (! $record->assinaturas()->where('tipo_assinante', 'chefia')->exists()) {
                                $ignoradas++;
                                continue;
                            }
                            \App\Services\AssinaturaService::assinar($record, auth()->user(), 'compaq');
                            if ($record->status !== \App\Enums\StatusAvaliacao::CONCLUIDA) {
                                $record->update(['status' => \App\Enums\StatusAvaliacao::CONCLUIDA]);
                            }
                            $ok++;
                        }
                        \Filament\Notifications\Notification::make()
                            ->title($ok . ' avaliação(ões) homologada(s)')
                            ->body($ignoradas > 0 ? $ignoradas . ' ignorada(s).' : null)
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),

                Tables\Actions\BulkAction::make('atribuir_avaliador')
                    ->label('Atribuir avaliador em lote')
                    ->icon('heroicon-o-user-plus')
                    ->color('warning')
                    ->form([
                        Forms\Components\Select::make('avaliador_id')
                            ->label('Novo avaliador')
                            ->options(\App\Models\User::orderBy('nome')->pluck('nome', 'id'))
                            ->required()->searchable(),
                    ])
                    ->action(function ($records, array $data) {
                        $qtd = 0;
                        foreach ($records as $a) {
                            $a->update(['avaliador_id' => $data['avaliador_id']]);
                            $qtd++;
                        }
                        \Filament\Notifications\Notification::make()->title("Avaliador atribuído em {$qtd} avaliação(ões)")->success()->send();
                    })
                    ->deselectRecordsAfterCompletion(),

                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAvaliacoes::route('/'),
            'create' => Pages\CreateAvaliacao::route('/create'),
            'edit'   => Pages\EditAvaliacao::route('/{record}/edit'),
            'visualizar' => Pages\VisualizarAvaliacao::route('/{record}/visualizar'),
        ];
    }
}
