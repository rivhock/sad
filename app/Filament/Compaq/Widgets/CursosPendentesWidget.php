<?php
namespace App\Filament\Compaq\Widgets;

use App\Models\Qualificacao;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class CursosPendentesWidget extends BaseWidget
{
    protected static ?string $heading = 'Cursos aguardando análise';
    protected static ?int $sort = 5;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Qualificacao::query()
                    ->where('status', 'pendente')
                    ->with('servidor.user')
                    ->orderBy('created_at')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('servidor.user.nome')
                    ->label('Servidor')->searchable(),
                Tables\Columns\TextColumn::make('titulo')
                    ->label('Título')->limit(40)->tooltip(fn ($state) => $state),
                Tables\Columns\TextColumn::make('carga_horaria')
                    ->label('Carga')->suffix(' h')->badge()->color('info'),
                Tables\Columns\TextColumn::make('data_fim')
                    ->label('Conclusão')->date('d/m/Y'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Enviado')->since(),
            ])
            ->paginated(false)
            ->actions([
                Tables\Actions\Action::make('ver')
                    ->label('Analisar')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Qualificacao $record) => \App\Filament\Compaq\Resources\QualificacaoResource::getUrl('index')),
            ]);
    }
}
