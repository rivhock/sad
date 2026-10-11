<?php
namespace App\Filament\Compaq\Pages;

use App\Enums\CategoriaServidor;
use App\Models\CicloAvaliacao;
use App\Models\Secretaria;
use App\Services\GerarAvaliacoesService;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class GerarAvaliacoesMassa extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'Avaliações';
    protected static ?string $navigationLabel = 'Gerar Avaliações em Massa';
    protected static ?string $title = 'Gerar Avaliações em Massa';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.compaq.pages.gerar-avaliacoes-massa';

    public ?array $data = [];
    public array $resultado = [];
    public array $simulacao = [];

    public function mount(): void
    {
        $this->form->fill([
            'tipo'      => 'desempenho',
            'so_chefia' => true,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('1. Ciclo e Tipo')
                    ->schema([
                        Forms\Components\Select::make('ciclo_id')
                            ->label('Ciclo')
                            ->options(CicloAvaliacao::where('ativo', true)->orderByDesc('data_inicio')->pluck('nome', 'id'))
                            ->required()
                            ->searchable(),

                        Forms\Components\Select::make('tipo')
                            ->label('Tipo de Avaliação')
                            ->options([
                                'estagio_probatorio' => 'Estágio Probatório',
                                'desempenho'         => 'Avaliação de Desempenho',
                            ])
                            ->default('desempenho')
                            ->required()
                            ->helperText('Todas as avaliações deste lote serão deste tipo.'),
                    ])->columns(2),

                Forms\Components\Section::make('2. Filtros de Servidores')
                    ->description('Deixe em branco para incluir todos.')
                    ->schema([
                        Forms\Components\Select::make('secretarias')
                            ->label('Secretarias')
                            ->multiple()
                            ->options(Secretaria::where('ativo', true)->orderBy('nome')->pluck('nome', 'id'))
                            ->searchable()
                            ->placeholder('Todas as secretarias'),

                        Forms\Components\Select::make('categorias')
                            ->label('Categorias')
                            ->multiple()
                            ->options(CategoriaServidor::options())
                            ->placeholder('Todas as categorias'),

                        Forms\Components\DatePicker::make('admissao_de')
                            ->label('Admissão — de')
                            ->native(false)
                            ->displayFormat('d/m/Y'),

                        Forms\Components\DatePicker::make('admissao_ate')
                            ->label('Admissão — até')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->after('admissao_de'),

                        Forms\Components\DatePicker::make('estagio_de')
                            ->label('Fim do estágio — de')
                            ->native(false)
                            ->displayFormat('d/m/Y'),

                        Forms\Components\DatePicker::make('estagio_ate')
                            ->label('Fim do estágio — até')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->after('estagio_de'),
                    ])->columns(3),

                Forms\Components\Section::make('3. Opções')
                    ->schema([
                        Forms\Components\Toggle::make('so_chefia')
                            ->label('Gerar apenas para servidores com chefia vinculada')
                            ->default(true)
                            ->helperText('Se desmarcado, o próprio usuário logado vira o avaliador padrão para servidores sem chefia.'),
                    ]),
            ])
            ->statePath('data');
    }

    public function simular(): void
    {
        $ciclo = CicloAvaliacao::find($this->data['ciclo_id'] ?? null);
        if (! $ciclo) {
            Notification::make()->title('Selecione um ciclo primeiro')->warning()->send();
            return;
        }

        $this->simulacao = GerarAvaliacoesService::simular($ciclo, $this->data);
        $this->resultado = [];

        Notification::make()
            ->title('Simulação concluída')
            ->body("Seriam criadas {$this->simulacao['criadas']} avaliações.")
            ->info()
            ->send();
    }

    public function gerar(): void
    {
        $ciclo = CicloAvaliacao::find($this->data['ciclo_id'] ?? null);
        if (! $ciclo) {
            Notification::make()->title('Selecione um ciclo primeiro')->danger()->send();
            return;
        }

        $this->resultado = GerarAvaliacoesService::gerar(
            $ciclo,
            $this->data,
            $this->data['tipo'] ?? 'desempenho',
        );

        $this->simulacao = [];

        $msg = sprintf(
            "%d criada(s), %d ignorada(s), %d sem chefia, %d sem formulário.",
            $this->resultado['criadas'],
            $this->resultado['ignoradas'],
            $this->resultado['sem_chefia'],
            $this->resultado['sem_formulario'],
        );

        Notification::make()
            ->title('Avaliações geradas')
            ->body($msg)
            ->success()
            ->persistent()
            ->send();
    }

}
