<?php
namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;

class MeuPerfil extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'Meu Perfil';
    protected static ?string $title = 'Meu Perfil';
    protected static string $view = 'filament.pages.meu-perfil';
    protected static ?int $navigationSort = 100;

    public ?array $data = [];

    public function mount(): void
    {
        $user = auth()->user();
        $user->load('servidor.cargo', 'servidor.secretaria', 'servidor.lotacao');

        $this->form->fill([
            'nome'  => $user->nome,
            'email' => $user->email,
        ]);
    }

    public function form(Form $form): Form
    {
        $user = auth()->user();
        $servidor = $user->servidor;

        $secoes = [
            Forms\Components\Section::make('Dados Pessoais')
                ->schema([
                    Forms\Components\TextInput::make('nome')
                        ->label('Nome completo')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(2),
                    Forms\Components\TextInput::make('email')
                        ->label('E-mail')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->rule(fn () => \Illuminate\Validation\Rule::unique('users', 'email')->ignore(auth()->id())),
                    Forms\Components\TextInput::make('cpf')
                        ->label('CPF')
                        ->disabled()
                        ->formatStateUsing(fn () => $user->cpf ? preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $user->cpf) : '—'),
                    Forms\Components\TextInput::make('matricula')
                        ->label('Matrícula')
                        ->disabled()
                        ->formatStateUsing(fn () => $user->matricula ?? '—'),
                ])->columns(2),
        ];

        // Só mostra Dados Funcionais se tiver vínculo de servidor
        if ($servidor) {
            $secoes[] = Forms\Components\Section::make('Dados Funcionais')
                ->description('Informações do seu vínculo com a Prefeitura. Para alterar, procure a COMPAQ.')
                ->schema([
                    Forms\Components\TextInput::make('servidor_cargo')
                        ->label('Cargo')
                        ->disabled()
                        ->formatStateUsing(fn () => $servidor->cargo?->nome ?? '—'),
                    Forms\Components\TextInput::make('servidor_secretaria')
                        ->label('Secretaria')
                        ->disabled()
                        ->formatStateUsing(fn () => $servidor->secretaria?->nome ?? '—'),
                    Forms\Components\TextInput::make('servidor_lotacao')
                        ->label('Lotação')
                        ->disabled()
                        ->formatStateUsing(fn () => $servidor->lotacao?->nome ?? '—'),
                    Forms\Components\TextInput::make('servidor_categoria')
                        ->label('Categoria')
                        ->disabled()
                        ->formatStateUsing(fn () => $servidor->categoria?->label() ?? '—'),
                    Forms\Components\TextInput::make('servidor_classe')
                        ->label('Classe')
                        ->disabled()
                        ->formatStateUsing(fn () => $servidor->classe ?? '—'),
                    Forms\Components\DatePicker::make('servidor_admissao')
                        ->label('Data de admissão')
                        ->disabled()
                        ->formatStateUsing(fn () => $servidor->data_admissao?->format('Y-m-d')),
                ])->columns(3);
        }

        $secoes[] = Forms\Components\Section::make('Segurança — Alterar Senha')
            ->description('Deixe os campos em branco para manter a senha atual.')
            ->schema([
                Forms\Components\TextInput::make('current_password')
                    ->label('Senha atual')
                    ->password()
                    ->revealable()
                    ->autocomplete('current-password')
                    ->requiredWith('password'),

                Forms\Components\TextInput::make('password')
                    ->label('Nova senha')
                    ->password()
                    ->revealable()
                    ->autocomplete('new-password')
                    ->confirmed()
                    ->minLength(8)
                    ->helperText('Mínimo de 8 caracteres.'),

                Forms\Components\TextInput::make('password_confirmation')
                    ->label('Confirmar nova senha')
                    ->password()
                    ->revealable()
                    ->autocomplete('new-password'),
            ])->columns(3);

        return $form->schema($secoes)->statePath('data');
    }

    public function salvar(): void
    {
        $data = $this->form->getState();
        $user = auth()->user();

        // ============================================================
        // 1. Atualiza dados pessoais (nome e email)
        // ============================================================
        $dadosPessoais = [];
        if (isset($data['nome']) && $data['nome'] !== $user->nome) {
            $dadosPessoais['nome'] = $data['nome'];
        }
        if (isset($data['email']) && $data['email'] !== $user->email) {
            $dadosPessoais['email'] = $data['email'];
        }

        if (! empty($dadosPessoais)) {
            $user->update($dadosPessoais);

            activity()
                ->causedBy($user)
                ->performedOn($user)
                ->withProperties(['alterados' => array_keys($dadosPessoais)])
                ->log('Usuário atualizou seus dados pessoais');

            Notification::make()
                ->title('Dados atualizados')
                ->body('Seus dados pessoais foram salvos com sucesso.')
                ->success()
                ->send();
        }

        // ============================================================
        // 2. Troca de senha (opcional)
        // ============================================================
        if (! empty($data['password'])) {

            if (! Hash::check($data['current_password'], $user->password)) {
                Notification::make()
                    ->title('Senha atual incorreta')
                    ->body('A senha que você informou não confere com a atual.')
                    ->danger()
                    ->send();
                return;
            }

            if (Hash::check($data['password'], $user->password)) {
                Notification::make()
                    ->title('Senha repetida')
                    ->body('A nova senha deve ser diferente da atual.')
                    ->warning()
                    ->send();
                return;
            }

            $user->update(['password' => Hash::make($data['password'])]);

            activity()
                ->causedBy($user)
                ->performedOn($user)
                ->withProperties(['tipo' => 'alteracao_senha'])
                ->log('Usuário alterou sua própria senha');

            Notification::make()
                ->title('Senha alterada com sucesso')
                ->body('Use a nova senha no próximo acesso.')
                ->success()
                ->send();
        }

        // ============================================================
        // 3. Se não alterou nada
        // ============================================================
        if (empty($dadosPessoais) && empty($data['password'])) {
            Notification::make()
                ->title('Nada para salvar')
                ->body('Você não fez nenhuma alteração.')
                ->info()
                ->send();
            return;
        }

        // Recarrega o form com os dados atualizados
        $user->refresh();
        $this->form->fill([
            'nome'  => $user->nome,
            'email' => $user->email,
        ]);
    }


    public function getTitle(): string
    {
        return 'Meu Perfil';
    }
}
