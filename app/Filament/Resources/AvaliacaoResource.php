<?php
namespace App\Filament\Resources;

use App\Enums\StatusAvaliacao;
use App\Filament\Resources\AvaliacaoResource\Pages;
use App\Models\Avaliacao;
use App\Models\CicloAvaliacao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
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
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Vínculo')
                ->schema([
                    Forms\Components\Select::make('ciclo_id')
                        ->label('Ciclo')
                        ->relationship('ciclo', 'nome')
                        ->required()->searchable()->preload()
                        ->live()
                        ->afterStateUpdated(function ($state, Set $set) {
                            if ($ciclo = CicloAvaliacao::find($state)) {
                                $set('periodo_inicio', $ciclo->data_inicio?->format('Y-m-d'));
                                $set('periodo_fim', $ciclo->data_fim?->format('Y-m-d'));
                            }
                        }),

                    Forms\Components\Select::make('formulario_id')
                        ->label('Formulário')
                        ->relationship('formulario', 'titulo')
                        ->required()->searchable()->preload(),

                    Forms\Components\Select::make('servidor_id')
                        ->label('Servidor Avaliado')
                        ->relationship('servidor', 'id')
                        ->getOptionLabelFromRecordUsing(fn ($record) => $record->user?->nome . ' — ' . $record->user?->matricula)
                        ->required()->searchable()->preload(),

                    Forms\Components\Select::make('avaliador_id')
                        ->label('Avaliador')
                        ->relationship('avaliador', 'nome')
                        ->required()->searchable()->preload(),

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
                        ->helperText('O status é controlado pelo fluxo.'),
                ])->columns(2),

            Forms\Components\Section::make('Resultado')
                ->schema([
                    Forms\Components\TextInput::make('nota_total')->label('Nota total')->numeric()->disabled(),
                    Forms\Components\Textarea::make('observacoes')->label('Observações adicionais')->rows(3)->columnSpanFull(),
                ]),

            Forms\Components\Hidden::make('periodo_inicio'),
            Forms\Components\Hidden::make('periodo_fim'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('servidor.user.nome')->label('Servidor')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('servidor.user.matricula')->label('Matrícula')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('ciclo.nome')->label('Ciclo')->badge(),
                Tables\Columns\TextColumn::make('formulario.titulo')->label('Formulário')->limit(30)->tooltip(fn ($state) => $state),
                Tables\Columns\TextColumn::make('avaliador.nome')->label('Avaliador')->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => $state instanceof StatusAvaliacao ? $state->label() : $state)
                    ->badge()
                    ->color(fn ($state) => $state instanceof StatusAvaliacao ? $state->cor() : 'gray'),
                Tables\Columns\TextColumn::make('nota_total')->label('Nota')->numeric(decimalPlaces: 2)->badge()->color('success'),
                Tables\Columns\TextColumn::make('data_avaliacao')->label('Avaliado em')->date('d/m/Y')->sortable()->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
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
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicadores = [];
                        if ($data['de'] ?? null) $indicadores[] = 'De: ' . \Carbon\Carbon::parse($data['de'])->format('d/m/Y');
                        if ($data['ate'] ?? null) $indicadores[] = 'Até: ' . \Carbon\Carbon::parse($data['ate'])->format('d/m/Y');
                        return $indicadores;
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
                    ->options(collect(StatusAvaliacao::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()])->toArray()),
                Tables\Filters\SelectFilter::make('tipo')
                    ->options([
                        'estagio_probatorio' => 'Estágio Probatório',
                        'desempenho'         => 'Avaliação de Desempenho',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('preencher')
                    ->label('Preencher')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->visible(fn (Avaliacao $record) =>
                        in_array($record->status, [StatusAvaliacao::PENDENTE, StatusAvaliacao::PREENCHIDA])
                        && ! $record->assinaturas()->where('tipo_assinante', 'avaliado')->exists()
                    )
                    ->url(fn (Avaliacao $record) => AvaliacaoResource::getUrl('preencher', ['record' => $record])),

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

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'      => Pages\ListAvaliacoes::route('/'),
            'create'     => Pages\CreateAvaliacao::route('/create'),
            'edit'       => Pages\EditAvaliacao::route('/{record}/edit'),
            'preencher'  => Pages\PreencherAvaliacao::route('/{record}/preencher'),
            'visualizar' => Pages\VisualizarAvaliacao::route('/{record}/visualizar'),
        ];
    }
}
