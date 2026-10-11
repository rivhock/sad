<?php
namespace App\Filament\Compaq\Widgets;

use App\Enums\StatusAvaliacao;
use App\Models\Avaliacao;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UltimasAvaliacoesWidget extends BaseWidget
{
    protected static ?string $heading = 'Últimas avaliações movimentadas';
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Avaliacao::query()
                    ->with(['servidor.user', 'ciclo', 'avaliador'])
                    ->orderByDesc('updated_at')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('servidor.user.nome')
                    ->label('Servidor')->searchable(),
                Tables\Columns\TextColumn::make('ciclo.nome')
                    ->label('Ciclo')->badge(),
                Tables\Columns\TextColumn::make('avaliador.nome')
                    ->label('Avaliador')->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => $state instanceof StatusAvaliacao ? $state->label() : $state)
                    ->badge()
                    ->color(fn ($state) => $state instanceof StatusAvaliacao ? $state->cor() : 'gray'),
                Tables\Columns\TextColumn::make('nota_total')
                    ->label('Nota')->numeric(decimalPlaces: 2)
                    ->badge()->color('success')->placeholder('—'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Movimentada')->since(),
            ])
            ->paginated(false)
            ->actions([
                Tables\Actions\Action::make('ver')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Avaliacao $record) => \App\Filament\Compaq\Resources\AvaliacaoResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
