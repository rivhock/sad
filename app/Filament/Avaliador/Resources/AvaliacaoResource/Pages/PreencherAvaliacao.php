<?php
namespace App\Filament\Avaliador\Resources\AvaliacaoResource\Pages;

use App\Enums\StatusAvaliacao;
use App\Filament\Avaliador\Resources\AvaliacaoResource;
use App\Models\Avaliacao;
use App\Models\RespostaItem;
use App\Services\AssinaturaService;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;

class PreencherAvaliacao extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = AvaliacaoResource::class;
    protected static string $view = 'filament.avaliador.pages.preencher-avaliacao';

    public ?array $data = [];
    public Avaliacao $record;

    public function mount(Avaliacao $record): void
    {
        if ($record->avaliador_id !== auth()->id()) {
            abort(403, 'Você não tem permissão para preencher esta avaliação.');
        }

        // Bloqueia se o avaliado já assinou
        if ($this->avaliadoJaAssinou($record)) {
            \Filament\Notifications\Notification::make()
                ->title('Avaliação bloqueada')
                ->body('Esta avaliação já foi assinada pelo servidor e não pode mais ser editada.')
                ->danger()
                ->send();

            $this->redirect(AvaliacaoResource::getUrl('index'));
            return;
        }

        $this->record = $record;
        $this->form->fill($this->getInitialData());
    }

    protected function avaliadoJaAssinou(Avaliacao $record): bool
    {
        return $record->assinaturas()
            ->where('tipo_assinante', 'avaliado')
            ->exists()
            || $record->status === \App\Enums\StatusAvaliacao::CONCLUIDA;
    }

    protected function getInitialData(): array
    {
        $respostas = $this->record->respostas->keyBy('item_id');
        $itens = $this->record->formulario->itens;

        $itensData = $itens->map(function ($item) use ($respostas) {
            return [
                'item_id'    => $item->id,
                'ordem'      => $item->ordem,
                'descricao'  => $item->descricao,
                'nota'       => $respostas->get($item->id)?->nota,
                'comentario' => $respostas->get($item->id)?->comentario,
            ];
        })->values()->toArray();

        return [
            'observacoes' => $this->record->observacoes,
            'itens'       => $itensData,
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Itens da Avaliação')
                    ->description('Atribua nota de 0 a 10 para cada item.')
                    ->schema([
                        Forms\Components\Repeater::make('itens')
                            ->hiddenLabel()
                            ->schema([
                                Forms\Components\Hidden::make('item_id'),
                                Forms\Components\Hidden::make('ordem'),
                                Forms\Components\Textarea::make('descricao')
                                    ->label('Item')->disabled()->dehydrated(false)
                                    ->rows(2)->columnSpan(4),
                                Forms\Components\TextInput::make('nota')
                                    ->label('Nota')->numeric()
                                    ->minValue(0)->maxValue(10)->step(0.5)->required()
                                    ->live(onBlur: true)
                                    ->columnSpan(1),
                                Forms\Components\Textarea::make('comentario')
                                    ->label('Comentário (opcional)')
                                    ->rows(1)->columnSpanFull(),
                            ])
                            ->columns(5)
                            ->addable(false)->deletable(false)->reorderable(false)
                            ->collapsible(),
                    ]),

                Forms\Components\Section::make('Observações')
                    ->schema([
                        Forms\Components\Textarea::make('observacoes')->label('Observações adicionais')->rows(3),
                    ]),
            ])
            ->statePath('data');
    }

    public function getNotaTotalProperty(): float
    {
        $itens = collect($this->data['itens'] ?? []);
        $soma = $itens->sum(fn ($i) => (float) ($i['nota'] ?? 0));

        return match ($this->record->formulario->calculo) {
            'media'           => $itens->count() ? round($soma / $itens->count(), 2) : 0,
            'media_ponderada' => $this->calcularMediaPonderada(),
            default           => round($soma, 2),
        };
    }

    protected function calcularMediaPonderada(): float
    {
        $itens = $this->record->formulario->itens->keyBy('id');
        $somaPonderada = 0; $somaPesos = 0;

        foreach ($this->data['itens'] ?? [] as $item) {
            $peso = (float) ($itens[$item['item_id']]->peso ?? 1);
            $nota = (float) ($item['nota'] ?? 0);
            $somaPonderada += $nota * $peso;
            $somaPesos += $peso;
        }

        return $somaPesos > 0 ? round($somaPonderada / $somaPesos, 2) : 0;
    }

    protected function getFormActions(): array { return []; }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('voltar')
                ->label('Voltar')->color('gray')
                ->url(AvaliacaoResource::getUrl('index')),

            Action::make('salvar')
                ->label('Salvar')->action('salvar'),

            Action::make('salvar_notificar')
                ->label('Assinar e Enviar')
                ->disabled(fn () => ! $this->todasNotasPreenchidas())
                ->tooltip(fn () => $this->todasNotasPreenchidas()
                    ? 'Assinar e enviar para o avaliado'
                    : 'Preencha todas as notas para liberar este botão'
                )
                ->color('success')
                ->icon('heroicon-o-paper-airplane')
                ->requiresConfirmation()
                ->modalHeading('Assinar e enviar para o avaliado')
                ->modalDescription('As notas serão salvas, sua assinatura como chefia será registrada (hash + IP) e o avaliado receberá e-mail com link para tomar ciência. Confirma?')
                ->action('salvarENotificar'),
        ];
    }

    public function salvar(): void
    {
        // Validação servidor: todas as notas preenchidas
        if (! $this->todasNotasPreenchidas()) {
            Notification::make()
                ->title('Preencha todas as notas')
                ->body('É necessário preencher a nota de todos os itens antes de salvar/assinar.')
                ->danger()
                ->send();
            return;
        }


        if ($this->record->avaliador_id !== auth()->id()) {
            Notification::make()->title('Acesso negado')->body('Você não é o avaliador desta avaliação.')->danger()->send();
            return;
        }
        if ($this->avaliadoJaAssinou($this->record)) {
            Notification::make()->title('Avaliação bloqueada')->body('O avaliado já assinou esta avaliação.')->danger()->send();
            return;
        }

        DB::transaction(function () {
            foreach ($this->data['itens'] as $item) {
                if ($item['nota'] === null || $item['nota'] === '') continue;
                $itemModel = $this->record->formulario->itens->firstWhere('id', $item['item_id']);

                RespostaItem::updateOrCreate(
                    ['avaliacao_id' => $this->record->id, 'item_id' => $item['item_id']],
                    [
                        'nota'           => $item['nota'],
                        'comentario'     => $item['comentario'] ?? null,
                        'descricao_item' => $itemModel?->descricao,
                        'ordem_item'     => $itemModel?->ordem,
                    ]
                );
            }
            $this->record->update([
                'nota_total'     => $this->notaTotal,
                'observacoes'    => $this->data['observacoes'] ?? null,
                'data_avaliacao' => now(),
                'status'         => $this->record->status === StatusAvaliacao::NOTIFICADA
                    ? StatusAvaliacao::NOTIFICADA
                    : StatusAvaliacao::PREENCHIDA,
            ]);
        });

        Notification::make()->title('Avaliação salva com sucesso')->success()->send();
        $this->record->refresh();
    }

    public function salvarEAssinar(): void
    {
        // Validação servidor: todas as notas preenchidas
        if (! $this->todasNotasPreenchidas()) {
            Notification::make()
                ->title('Preencha todas as notas')
                ->body('É necessário preencher a nota de todos os itens antes de salvar/assinar.')
                ->danger()
                ->send();
            return;
        }


        $this->salvar();
        AssinaturaService::assinar($this->record->refresh(), auth()->user(), 'chefia');
        Notification::make()->title('Avaliação assinada')->body('Assinatura da chefia registrada.')->success()->send();
    }

    public function salvarENotificar(): void
    {
        // Validação servidor: todas as notas preenchidas
        if (! $this->todasNotasPreenchidas()) {
            Notification::make()
                ->title('Preencha todas as notas')
                ->body('É necessário preencher a nota de todos os itens antes de salvar/assinar.')
                ->danger()
                ->send();
            return;
        }

        // Impede reenvio: já assinada pela chefia?
        if ($this->record->assinaturas()->where('tipo_assinante', 'chefia')->exists()) {
            Notification::make()
                ->title('Avaliação já assinada')
                ->body('Esta avaliação já foi assinada pela chefia e não pode ser reenviada.')
                ->danger()
                ->send();
            return;
        }


        $this->salvar();
        $this->record->refresh();

        // Assina como chefia (se ainda não assinou)
        if (! $this->record->assinaturas()->where('tipo_assinante', 'chefia')->exists()) {
            AssinaturaService::assinar($this->record, auth()->user(), 'chefia');
            $this->record->refresh();
        }

        $this->record->update([
            'status'           => StatusAvaliacao::NOTIFICADA,
            'data_notificacao' => now(),
            'prazo_defesa'     => now()->addDays(3),
        ]);

        Notification::make()->title('Avaliação notificada')->body('O servidor tem 3 dias para defesa.')->success()->send();
        $this->redirect(AvaliacaoResource::getUrl('index'));
    }

    /**
     * Verifica se todas as notas foram preenchidas.
     */
    public function todasNotasPreenchidas(): bool
    {
        $itens = $this->data['itens'] ?? [];
        if (empty($itens)) {
            return false;
        }

        foreach ($itens as $item) {
            $nota = $item['nota'] ?? null;
            if ($nota === null || $nota === '' || $nota === 0 || $nota === '0') {
                return false;
            }
        }

        return true;
    }

    public function getTitle(): string
    {
        return 'Avaliar — ' . ($this->record->servidor?->user?->nome ?? '');
    }

    public function getBreadcrumbs(): array
    {
        return [AvaliacaoResource::getUrl('index') => 'Minhas Avaliações', 'Preencher'];
    }
}
