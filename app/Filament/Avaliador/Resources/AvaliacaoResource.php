<?php
namespace App\Filament\Avaliador\Resources;

use App\Enums\StatusAvaliacao;
use App\Filament\Avaliador\Resources\AvaliacaoResource\Pages;
use App\Models\Avaliacao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AvaliacaoResource extends Resource
{
    protected static ?string $model = Avaliacao::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Minhas Avaliações';
    protected static ?string $navigationLabel = 'Avaliações para mim';
    protected static ?string $modelLabel = 'Avaliação';
    protected static ?string $pluralModelLabel = 'Minhas Avaliações';

    /**
     * Filtra: o avaliador só vê as avaliações em que ele é o avaliador.
     * Administrador vê tudo (para facilitar testes).
     */
    public static function getEloquentQuery(): Builder
    {
        // No painel do Avaliador, TODOS veem apenas as avaliações em que são o avaliador.
        // Inclusive o Administrador — que tem os painéis /admin e /compaq para ver tudo.
        return parent::getEloquentQuery()
            ->with(['servidor.user', 'ciclo', 'formulario'])
            ->where('avaliador_id', auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informações')
                ->schema([
                    Forms\Components\Placeholder::make('ciclo')
                        ->label('Ciclo')
                        ->content(fn ($record) => $record?->ciclo?->nome ?? '—'),
                    Forms\Components\Placeholder::make('formulario')
                        ->label('Formulário')
                        ->content(fn ($record) => $record?->formulario?->titulo ?? '—'),
                    Forms\Components\Placeholder::make('tipo')
                        ->label('Tipo')
                        ->content(fn ($record) => $record?->tipo === 'estagio_probatorio' ? 'Estágio Probatório' : 'Avaliação de Desempenho'),
                ])->columns(3),

            Forms\Components\Section::make('Observações')
                ->schema([
                    Forms\Components\Textarea::make('observacoes')
                        ->label('Observações gerais')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('servidor.user.nome')
                    ->label('Servidor')
                    ->searchable(['nome', 'matricula'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('servidor.user.matricula')
                    ->label('Matrícula')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('ciclo.nome')
                    ->label('Ciclo')->badge()->sortable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')->badge()
                    ->formatStateUsing(fn ($state) => $state === 'estagio_probatorio' ? 'Estágio' : 'Desempenho')
                    ->color(fn ($state) => $state === 'estagio_probatorio' ? 'warning' : 'info'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => $state instanceof StatusAvaliacao ? $state->label() : $state)
                    ->badge()
                    ->color(fn ($state) => $state instanceof StatusAvaliacao ? $state->cor() : 'gray'),
                Tables\Columns\TextColumn::make('nota_total')
                    ->label('Nota')->numeric(decimalPlaces: 2)->badge()->color('success')->placeholder('—'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Recebida')->since()->sortable()->toggleable(),
            ])
            ->defaultSort('status', 'asc')
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

                Tables\Filters\SelectFilter::make('ciclo')->relationship('ciclo', 'nome'),
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(StatusAvaliacao::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()])->toArray()),
            ])
            ->actions([
                Tables\Actions\Action::make('preencher')
                    ->label('Preencher')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->visible(fn (Avaliacao $record) => in_array($record->status, [StatusAvaliacao::PENDENTE, StatusAvaliacao::PREENCHIDA]))
                    ->url(fn (Avaliacao $record) => AvaliacaoResource::getUrl('preencher', ['record' => $record])),

                Tables\Actions\Action::make('link_avaliado_avaliador')
                    ->label('Link do avaliado')
                    ->icon('heroicon-o-link')
                    ->color('gray')
                    ->visible(fn (Avaliacao $record) =>
                        $record->assinaturas()->where('tipo_assinante', 'chefia')->exists()
                        && $record->token_acesso !== null
                    )
                    ->modalHeading('Link para o servidor avaliado')
                    ->modalContent(fn (Avaliacao $record) => view('filament.avaliador.avaliacao-link', ['avaliacao' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Fechar'),

                Tables\Actions\Action::make('visualizar')
                    ->label('Visualizar')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn (Avaliacao $record) => AvaliacaoResource::getUrl('visualizar', ['record' => $record])),

                Tables\Actions\Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->url(fn (Avaliacao $record) => route('avaliacao.pdf.download', $record))
                    ->openUrlInNewTab(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'      => Pages\ListAvaliacoes::route('/'),
            'preencher'  => Pages\PreencherAvaliacao::route('/{record}/preencher'),
            'visualizar' => Pages\VisualizarAvaliacao::route('/{record}/visualizar'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
