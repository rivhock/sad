<?php
namespace App\Filament\Resources;

use App\Enums\CategoriaServidor;
use App\Filament\Resources\CargoResource\Pages;
use App\Models\Cargo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CargoResource extends Resource
{
    protected static ?string $model = Cargo::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Cadastros';
    protected static ?string $navigationLabel = 'Cargos';
    protected static ?string $modelLabel = 'Cargo';
    protected static ?string $pluralModelLabel = 'Cargos';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nome')->label('Nome')->required()->maxLength(255),
            Forms\Components\Select::make('categoria')
                ->label('Categoria')
                ->options(CategoriaServidor::options())
                ->required(),
            Forms\Components\Toggle::make('ativo')->label('Ativo')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')->label('Nome')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('categoria')
                    ->label('Categoria')
                    ->formatStateUsing(fn ($state) => $state instanceof CategoriaServidor ? $state->label() : $state)
                    ->badge(),
                Tables\Columns\IconColumn::make('ativo')->label('Ativo')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('categoria')->options(CategoriaServidor::options()),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCargos::route('/'),
            'create' => Pages\CreateCargo::route('/create'),
            'edit'   => Pages\EditCargo::route('/{record}/edit'),
        ];
    }
}
