<?php
namespace App\Filament\Resources;

use App\Filament\Resources\CicloAvaliacaoResource\Pages;
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
    protected static ?string $navigationGroup = 'Avaliações';
    protected static ?string $navigationLabel = 'Ciclos de Avaliação';
    protected static ?string $modelLabel = 'Ciclo de Avaliação';
    protected static ?string $pluralModelLabel = 'Ciclos de Avaliação';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nome')
                ->label('Nome')->required()->maxLength(255),
            Forms\Components\DatePicker::make('data_inicio')
                ->label('Data de início')->required()->native(false),
            Forms\Components\DatePicker::make('data_fim')
                ->label('Data de fim')->required()->native(false)
                ->after('data_inicio'),
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
                Tables\Columns\IconColumn::make('ativo')->label('Ativo')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCiclos::route('/'),
            'create' => Pages\CreateCiclo::route('/create'),
            'edit'   => Pages\EditCiclo::route('/{record}/edit'),
        ];
    }
}
