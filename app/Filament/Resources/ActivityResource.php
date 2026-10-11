<?php
namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'Administração';
    protected static ?string $navigationLabel = 'Log de Auditoria';
    protected static ?string $modelLabel = 'Registro';
    protected static ?string $pluralModelLabel = 'Log de Auditoria';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Placeholder::make('created_at')
                ->label('Data/hora')
                ->content(fn ($record) => $record?->created_at?->format('d/m/Y H:i:s')),
            Forms\Components\Placeholder::make('event')
                ->label('Ação')
                ->content(fn ($record) => $record?->event ?? '—'),
            Forms\Components\Placeholder::make('causer')
                ->label('Usuário')
                ->content(fn ($record) => $record?->causer?->nome ?? 'Sistema'),
            Forms\Components\Placeholder::make('subject')
                ->label('Entidade')
                ->content(fn ($record) => $record?->subject_type ? class_basename($record->subject_type) . ' #' . $record->subject_id : '—'),
            Forms\Components\KeyValue::make('properties.old')
                ->label('Antes')
                ->keyLabel('Campo')
                ->valueLabel('Valor')
                ->columnSpanFull(),
            Forms\Components\KeyValue::make('properties.attributes')
                ->label('Depois')
                ->keyLabel('Campo')
                ->valueLabel('Valor')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data/hora')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),

                Tables\Columns\TextColumn::make('causer.nome')
                    ->label('Usuário')
                    ->placeholder('Sistema')
                    ->badge()
                    ->color('info')
                    ->searchable(),

                Tables\Columns\TextColumn::make('event')
                    ->label('Ação')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'created'  => 'Criado',
                        'updated'  => 'Alterado',
                        'deleted'  => 'Excluído',
                        'restored' => 'Restaurado',
                        default    => $state ?? '—',
                    })
                    ->color(fn ($state) => match($state) {
                        'created'  => 'success',
                        'updated'  => 'warning',
                        'deleted'  => 'danger',
                        default    => 'gray',
                    }),

                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Entidade')
                    ->formatStateUsing(fn ($state) => $state ? class_basename($state) : '—')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('subject_id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Descrição')
                    ->limit(50)
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->label('Ação')
                    ->options([
                        'created' => 'Criado',
                        'updated' => 'Alterado',
                        'deleted' => 'Excluído',
                    ]),
                Tables\Filters\SelectFilter::make('subject_type')
                    ->label('Entidade')
                    ->options(fn () => Activity::query()
                        ->distinct()
                        ->pluck('subject_type')
                        ->filter()
                        ->mapWithKeys(fn ($t) => [$t => class_basename($t)])
                        ->toArray()
                    ),
                Tables\Filters\Filter::make('periodo')
                    ->form([
                        Forms\Components\DatePicker::make('de')->label('De')->native(false),
                        Forms\Components\DatePicker::make('ate')->label('Até')->native(false),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['de'] ?? null, fn ($q, $d) => $q->whereDate('created_at', '>=', $d))
                            ->when($data['ate'] ?? null, fn ($q, $d) => $q->whereDate('created_at', '<=', $d));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Detalhes'),
            ])
            ->bulkActions([]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
            'view'  => Pages\ViewActivity::route('/{record}'),
        ];
    }
}
