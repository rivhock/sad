<?php
namespace App\Filament\Resources;

use App\Filament\Resources\LotacaoResource\Pages;
use App\Models\Lotacao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LotacaoResource extends Resource
{
    protected static ?string $model = Lotacao::class;
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationGroup = 'Cadastros';
    protected static ?string $navigationLabel = 'Lotações';
    protected static ?string $modelLabel = 'Lotação';
    protected static ?string $pluralModelLabel = 'Lotações';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nome')->label('Nome')->required()->maxLength(255),
            Forms\Components\Select::make('secretaria_id')
                ->label('Secretaria')
                ->relationship('secretaria', 'nome')
                ->searchable()
                ->preload(),
            Forms\Components\Toggle::make('ativo')->label('Ativo')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')->label('Nome')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('secretaria.nome')->label('Secretaria')->searchable(),
                Tables\Columns\IconColumn::make('ativo')->label('Ativo')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('secretaria')->relationship('secretaria', 'nome'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLotacoes::route('/'),
            'create' => Pages\CreateLotacao::route('/create'),
            'edit'   => Pages\EditLotacao::route('/{record}/edit'),
        ];
    }
}
