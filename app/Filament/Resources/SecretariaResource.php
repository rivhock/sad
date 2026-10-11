<?php
namespace App\Filament\Resources;

use App\Filament\Resources\SecretariaResource\Pages;
use App\Models\Secretaria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SecretariaResource extends Resource
{
    protected static ?string $model = Secretaria::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Cadastros';
    protected static ?string $navigationLabel = 'Secretarias';
    protected static ?string $modelLabel = 'Secretaria';
    protected static ?string $pluralModelLabel = 'Secretarias';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nome')
                ->label('Nome')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('sigla')
                ->label('Sigla')
                ->maxLength(20),
            Forms\Components\Toggle::make('ativo')
                ->label('Ativo')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')->label('Nome')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('sigla')->label('Sigla')->searchable(),
                Tables\Columns\IconColumn::make('ativo')->label('Ativo')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->label('Criado')->since()->toggleable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('ativo')->label('Ativo'),
            ])
            ->actions([
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
            'index'  => Pages\ListSecretarias::route('/'),
            'create' => Pages\CreateSecretaria::route('/create'),
            'edit'   => Pages\EditSecretaria::route('/{record}/edit'),
        ];
    }
}
