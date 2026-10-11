<?php
namespace App\Filament\Resources;

use App\Filament\Resources\TipoAvaliacaoResource\Pages;
use App\Models\TipoAvaliacao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TipoAvaliacaoResource extends Resource
{
    protected static ?string $model = TipoAvaliacao::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Avaliações';
    protected static ?string $navigationLabel = 'Tipos de Avaliação';
    protected static ?string $modelLabel = 'Tipo de Avaliação';
    protected static ?string $pluralModelLabel = 'Tipos de Avaliação';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('codigo')
                ->label('Código')->required()->unique(ignoreRecord: true)->maxLength(50)
                ->helperText('Ex: estagio_probatorio, desempenho'),
            Forms\Components\TextInput::make('nome')
                ->label('Nome')->required()->maxLength(255),
            Forms\Components\TextInput::make('anexo')
                ->label('Anexo')->maxLength(50)
                ->helperText('Ex: ANEXO I, ANEXO II'),
            Forms\Components\Toggle::make('ativo')->label('Ativo')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo')->label('Código')->searchable(),
                Tables\Columns\TextColumn::make('nome')->label('Nome')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('anexo')->label('Anexo')->badge(),
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
            'index'  => Pages\ListTipoAvaliacoes::route('/'),
            'create' => Pages\CreateTipoAvaliacao::route('/create'),
            'edit'   => Pages\EditTipoAvaliacao::route('/{record}/edit'),
        ];
    }
}
