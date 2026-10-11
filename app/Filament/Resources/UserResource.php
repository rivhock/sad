<?php
namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Administração';
    protected static ?string $navigationLabel = 'Usuários';
    protected static ?string $modelLabel = 'Usuário';
    protected static ?string $pluralModelLabel = 'Usuários';
    protected static ?int $navigationSort = 1;

    /**
     * Exclui da listagem os usuários que SÓ têm papel "Avaliado".
     * Esses são servidores comuns, gerenciados em Cadastros → Servidores.
     * Quem tem outros papéis (Admin, RH, COMPAQ, Avaliador) ou combinações
     * (ex: Avaliador + Avaliado) continua aparecendo aqui.
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('roles', function ($q) {
                $q->whereIn('name', ['Administrador', 'RH', 'COMPAQ', 'Avaliador']);
            });
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Dados do Usuário')
                ->schema([
                    Forms\Components\TextInput::make('nome')
                        ->label('Nome completo')->required()->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->label('E-mail (opcional)')->email()->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->helperText('Deixe em branco se não for usar login no sistema.'),
                    Forms\Components\TextInput::make('cpf')
                        ->label('CPF')->maxLength(11)
                        ->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('matricula')
                        ->label('Matrícula')->maxLength(20)
                        ->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('password')
                        ->label('Senha')
                        ->password()->revealable()
                        ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $operation) => $operation === 'create')
                        ->helperText('Deixe vazio para manter a senha atual.'),
                    Forms\Components\Toggle::make('ativo')
                        ->label('Ativo')->default(true),
                ])->columns(2),

            Forms\Components\Section::make('Papéis')
                ->description('Defina quais perfis este usuário pode acessar.')
                ->schema([
                    Forms\Components\CheckboxList::make('roles')
                        ->label('')
                        ->relationship('roles', 'name')
                        ->options(fn () => Role::orderBy('name')->pluck('name', 'id')->toArray())
                        ->columns(3)
                        ->required(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')->label('Nome')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->label('E-mail')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('matricula')->label('Matrícula')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Papéis')
                    ->badge()
                    ->separator(',')
                    ->color('info'),
                Tables\Columns\IconColumn::make('ativo')->label('Ativo')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->label('Criado')->since()->toggleable(),
            ])
            ->defaultSort('nome')
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->label('Papel')
                    ->relationship('roles', 'name'),
                Tables\Filters\TernaryFilter::make('ativo')->label('Ativo'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (User $record) => $record->id !== auth()->id()),
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
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
