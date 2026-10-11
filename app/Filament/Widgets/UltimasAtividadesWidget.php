<?php
namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Spatie\Activitylog\Models\Activity;

class UltimasAtividadesWidget extends BaseWidget
{
    protected static ?string $heading = 'Últimas atividades';
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Activity::query()
                    ->with('causer')
                    ->orderByDesc('created_at')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Quando')
                    ->since()
                    ->sortable(),

                Tables\Columns\TextColumn::make('causer.nome')
                    ->label('Usuário')
                    ->placeholder('Sistema')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('event')
                    ->label('Ação')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'created'  => 'Criou',
                        'updated'  => 'Alterou',
                        'deleted'  => 'Excluiu',
                        default    => $state ?? '—',
                    })
                    ->color(fn ($state) => match($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default   => 'gray',
                    }),

                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Entidade')
                    ->formatStateUsing(fn ($state) => $state ? class_basename($state) : '—')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('subject_id')
                    ->label('ID'),
            ])
            ->paginated(false)
            ->actions([])
            ->headerActions([
                Tables\Actions\Action::make('ver_tudo')
                    ->label('Ver tudo')
                    ->icon('heroicon-o-arrow-right')
                    ->color('gray')
                    ->url(fn () => \App\Filament\Resources\ActivityResource::getUrl('index')),
            ]);
    }
}
