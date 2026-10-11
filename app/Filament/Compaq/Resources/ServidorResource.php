<?php
namespace App\Filament\Compaq\Resources;

use App\Enums\CategoriaServidor;
use App\Filament\Compaq\Resources\ServidorResource\Pages;
use App\Models\Servidor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;

class ServidorResource extends Resource
{
    protected static ?string $model = Servidor::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Consultas';
    protected static ?string $navigationLabel = 'Servidores';
    protected static ?string $modelLabel = 'Servidor';
    protected static ?string $pluralModelLabel = 'Servidores';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Dados do Usuário')
                ->schema([
                    Forms\Components\TextInput::make('user.nome')
                        ->label('Nome completo')->required()->maxLength(255),

                    Forms\Components\TextInput::make('user.email')
                        ->label('E-mail (opcional)')->email()->maxLength(255)
                        ->rule(fn ($record) => Rule::unique('users', 'email')->ignore($record?->user_id))
                        ->helperText('Deixe em branco se o servidor não tiver e-mail.'),

                    Forms\Components\TextInput::make('user.cpf')
                        ->label('CPF')->maxLength(11)
                        ->rule(fn ($record) => Rule::unique('users', 'cpf')->ignore($record?->user_id)),

                    Forms\Components\TextInput::make('user.matricula')
                        ->label('Matrícula')->required()->maxLength(20)
                        ->rule(fn ($record) => Rule::unique('users', 'matricula')->ignore($record?->user_id)),

                    Forms\Components\TextInput::make('user.password')
                        ->label('Senha (deixe vazio para manter)')
                        ->password()->revealable()
                        ->dehydrated(fn ($state) => filled($state))
                        ->helperText('Se não informada na criação, será definida como mudar123.'),
                ])->columns(2),

            Forms\Components\Section::make('Dados Funcionais')
                ->schema([
                    Forms\Components\Select::make('cargo_id')
                        ->label('Cargo')->relationship('cargo', 'nome')->searchable()->preload(),
                    Forms\Components\Select::make('secretaria_id')
                        ->label('Secretaria')->relationship('secretaria', 'nome')->searchable()->preload(),
                    Forms\Components\Select::make('lotacao_id')
                        ->label('Lotação')->relationship('lotacao', 'nome')->searchable()->preload(),
                    Forms\Components\Select::make('categoria')
                        ->label('Categoria')->options(CategoriaServidor::options())->required(),
                    Forms\Components\TextInput::make('classe')
                        ->label('Classe')
                        ->maxLength(10)
                        ->placeholder('Ex: A, B, C...')
                        ->helperText('Informada pela COMPAQ conforme progressão do servidor.'),
                    Forms\Components\DatePicker::make('data_admissao')->label('Data de admissão')->native(false),
                    Forms\Components\DatePicker::make('data_fim_estagio')->label('Fim do estágio probatório')->native(false),
                    Forms\Components\Toggle::make('ativo')->label('Ativo')->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.nome')->label('Nome')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('user.matricula')->label('Matrícula')->searchable(),
                Tables\Columns\TextColumn::make('cargo.nome')->label('Cargo')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('secretaria.sigla')->label('Secretaria')->badge(),
                Tables\Columns\TextColumn::make('categoria')
                    ->label('Categoria')
                    ->formatStateUsing(fn ($state) => $state instanceof CategoriaServidor ? $state->label() : $state)
                    ->badge(),

                Tables\Columns\TextColumn::make('classe')
                    ->label('Classe')
                    ->badge()
                    ->color('warning')
                    ->placeholder('—')
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('ativo')->label('Ativo')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('categoria')->options(CategoriaServidor::options()),
                Tables\Filters\SelectFilter::make('secretaria')->relationship('secretaria', 'nome'),
                Tables\Filters\TernaryFilter::make('ativo')->label('Ativo'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('desativar')
                    ->label('Desativar')
                    ->icon('heroicon-o-user-minus')
                    ->color('warning')
                    ->visible(fn ($record) => $record->ativo)
                    ->requiresConfirmation()
                    ->modalHeading('Desativar servidor')
                    ->modalDescription('O servidor ficara inativo mas todo o historico sera preservado.')
                    ->action(function ($record) {
                        $record->update(['ativo' => false]);
                        $record->user?->update(['ativo' => false]);
                        \Filament\Notifications\Notification::make()
                            ->title('Servidor desativado')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('reativar')
                    ->label('Reativar')
                    ->icon('heroicon-o-user-plus')
                    ->color('success')
                    ->visible(fn ($record) => ! $record->ativo)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['ativo' => true]);
                        $record->user?->update(['ativo' => true]);
                        \Filament\Notifications\Notification::make()
                            ->title('Servidor reativado')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('forcar_exclusao')
                    ->label('Excluir permanentemente')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(fn ($record) => ! $record->ativo)
                    ->requiresConfirmation()
                    ->modalHeading('Excluir permanentemente')
                    ->modalDescription('Acao IRREVERSIVEL. So e permitida para cadastros sem historico. Se houver avaliacoes ou cursos, desative em vez de excluir.')
                    ->modalSubmitActionLabel('Excluir definitivamente')
                    ->action(function ($record) {
                        $temAvaliacoes = \App\Models\Avaliacao::where('servidor_id', $record->id)->exists();
                        $temQualificacoes = \App\Models\Qualificacao::where('servidor_id', $record->id)->exists();

                        if ($temAvaliacoes || $temQualificacoes) {
                            \Filament\Notifications\Notification::make()
                                ->title('Nao e possivel excluir')
                                ->body('Este servidor possui historico. Desative em vez de excluir.')
                                ->danger()
                                ->persistent()
                                ->send();
                            return;
                        }

                        $nome = $record->user?->nome;
                        $userId = $record->user_id;

                        \Illuminate\Support\Facades\DB::transaction(function () use ($record, $userId) {
                            $record->forceDelete();
                            if ($userId) {
                                \App\Models\User::find($userId)?->forceDelete();
                            }
                        });

                        \Filament\Notifications\Notification::make()
                            ->title('Servidor excluido')
                            ->body("O cadastro de {$nome} foi removido definitivamente.")
                            ->success()
                            ->send();
                    }),
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
            'index'  => Pages\ListServidores::route('/'),
            'create' => Pages\CreateServidor::route('/create'),
            'edit'   => Pages\EditServidor::route('/{record}/edit'),
        ];
    }
}
