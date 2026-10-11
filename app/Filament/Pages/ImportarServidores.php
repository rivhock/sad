<?php
namespace App\Filament\Pages;

use App\Services\ImportacaoServidoresService;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ImportarServidores extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-tray';
    protected static ?string $navigationGroup = 'Administração';
    protected static ?string $navigationLabel = 'Importar Servidores';
    protected static ?string $title = 'Importar Servidores (CSV)';
    protected static ?int $navigationSort = 5;
    protected static string $view = 'filament.pages.importar-servidores';

    public ?array $data = [];
    public array $resultado = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('arquivo')
                    ->label('Arquivo CSV')
                    ->acceptedFileTypes(['text/csv', 'text/plain', 'application/csv', 'application/vnd.ms-excel'])
                    ->disk('local')
                    ->directory('importacoes')
                    ->required()
                    ->helperText('Use vírgula (,) ou ponto-e-vírgula (;) como separador. A primeira linha deve ser o cabeçalho.'),

                Forms\Components\Placeholder::make('instrucoes')
                    ->label('Como preparar o arquivo')
                    ->content(new \Illuminate\Support\HtmlString(
                        '<div style="font-size:13px;color:#374151;line-height:1.8;">' .
                        '<strong>1.</strong> Clique em <strong>"Baixar modelo CSV"</strong> no topo da página<br>' .
                        '<strong>2.</strong> Abra o arquivo no Excel (ou similar) e substitua as linhas de exemplo pelos dados reais<br>' .
                        '<strong>3.</strong> Salve como <strong>CSV (separado por ponto-e-vírgula)</strong><br>' .
                        '<strong>4.</strong> Faça o upload aqui e clique em <strong>Processar Importação</strong>' .
                        '</div>'
                    )),

                Forms\Components\Placeholder::make('colunas')
                    ->label('Colunas aceitas no cabeçalho')
                    ->content(new \Illuminate\Support\HtmlString(
                        '<code style="font-size:11px;line-height:1.8;">' .
                        'nome, email, cpf, matricula, cargo, secretaria, lotacao, categoria, data_admissao, data_fim_estagio' .
                        '</code>' .
                        '<div style="margin-top:8px;font-size:12px;color:#6b7280;">' .
                        '<strong>Obrigatórios:</strong> apenas <strong>nome</strong><br>' .
                        '<strong>Email:</strong> opcional (obrigatório apenas se o servidor for acessar o sistema)<br>' .
                        '<strong>Categoria:</strong> saude, magisterio, guarda, quadro_geral (padrão: quadro_geral)<br>' .
                        '<strong>Datas:</strong> dd/mm/aaaa ou aaaa-mm-dd<br>' .
                        '<strong>Senha padrão dos novos:</strong> mudar123 (devem trocar no primeiro acesso)' .
                        '</div>'
                    )),
            ])
            ->statePath('data');
    }

    public function importar(): void
    {
        $caminho = $this->data['arquivo'] ?? null;

        if (! $caminho) {
            Notification::make()->title('Envie um arquivo primeiro')->warning()->send();
            return;
        }

        $caminhoCompleto = storage_path('app/' . $caminho);

        if (! file_exists($caminhoCompleto)) {
            Notification::make()->title('Arquivo não encontrado')->danger()->send();
            return;
        }

        // Detecta separador
        $primeiraLinha = fgets(fopen($caminhoCompleto, 'r'));
        $separador = (substr_count($primeiraLinha, ';') > substr_count($primeiraLinha, ',')) ? ';' : ',';

        // Lê o CSV
        $handle = fopen($caminhoCompleto, 'r');
        $cabecalho = fgetcsv($handle, 0, $separador);
        $cabecalho = array_map(fn ($c) => strtolower(trim($c)), $cabecalho);

        $linhas = [];
        while (($dados = fgetcsv($handle, 0, $separador)) !== false) {
            if (count($dados) === count($cabecalho)) {
                $linhas[] = array_combine($cabecalho, $dados);
            }
        }
        fclose($handle);

        if (empty($linhas)) {
            Notification::make()->title('Nenhum registro encontrado no arquivo')->warning()->send();
            return;
        }

        // Processa
        $resultado = ImportacaoServidoresService::processar($linhas);
        $this->resultado = $resultado;

        $mensagem = "{$resultado['criados']} criado(s), {$resultado['ignorados']} ignorado(s)";
        if (! empty($resultado['erros'])) {
            $mensagem .= ", " . count($resultado['erros']) . " erro(s)";
        }

        Notification::make()
            ->title('Importação concluída')
            ->body($mensagem)
            ->success()
            ->persistent()
            ->send();

        // Limpa o form
        $this->form->fill();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('baixar_modelo')
                ->label('Baixar modelo CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->url(route('modelo.servidores'))
                ->openUrlInNewTab(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('importar')
                ->label('Processar Importação')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Confirmar importação')
                ->modalDescription('Os registros serão criados no banco. Linhas com email/matrícula/CPF duplicado serão ignoradas. Confirma?')
                ->action('importar'),
        ];
    }
}
