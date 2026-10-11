<?php
namespace App\Filament\Compaq\Resources;

use App\Filament\Compaq\Resources\CicloAvaliacaoResource\Pages;
use App\Models\CicloAvaliacao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CicloAvaliacaoResource extends Resource
{
    protected static ?string $model = CicloAvaliacao::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?string $navigationGroup = 'Consultas';
    protected static ?string $navigationLabel = 'Ciclos';
    protected static ?string $modelLabel = 'Ciclo';
    protected static ?string $pluralModelLabel = 'Ciclos';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nome')->label('Nome')->required()->maxLength(255),
            Forms\Components\DatePicker::make('data_inicio')->label('Início')->required()->native(false),
            Forms\Components\DatePicker::make('data_fim')->label('Fim')->required()->native(false)->after('data_inicio'),
            Forms\Components\Toggle::make('ativo')->label('Ativo')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')->label('Nome')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('data_inicio')->label('Início')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('data_fim')->label('Fim')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('avaliacoes_count')->label('Avaliações')->counts('avaliacoes')->badge()->color('info'),
                Tables\Columns\IconColumn::make('ativo')->label('Ativo')->boolean(),
            ])
            ->defaultSort('data_inicio', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCiclos::route('/'),
            'edit'  => Pages\EditCiclo::route('/{record}/edit'),
        ];
    }
}
