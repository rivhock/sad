<?php
namespace App\Filament\Avaliador\Resources\AvaliacaoResource\Pages;

use App\Filament\Avaliador\Resources\AvaliacaoResource;
use App\Models\Avaliacao;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;

class VisualizarAvaliacao extends Page
{
    protected static string $resource = AvaliacaoResource::class;
    protected static string $view = 'filament.avaliador.pages.visualizar-avaliacao';

    public Avaliacao $record;

    public function mount(Avaliacao $record): void
    {
        if ($record->avaliador_id !== auth()->id()) {
            abort(403, 'Você não tem permissão para ver esta avaliação.');
        }

        $this->record = $record->load([
            'servidor.user','servidor.cargo','servidor.lotacao',
            'avaliador','ciclo','formulario.itens',
            'respostas.item','assinaturas.user','defesas','qualificacoes',
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('voltar')
                ->label('Voltar')->color('gray')
                ->url(AvaliacaoResource::getUrl('index')),

            Action::make('preencher')
                ->label('Preencher / Editar')
                ->icon('heroicon-o-pencil-square')
                ->color('warning')
                ->visible(fn () => $this->podeEditar())
                ->url(fn () => AvaliacaoResource::getUrl('preencher', ['record' => $this->record])),
        ];
    }

    public function podeEditar(): bool
    {
        if ($this->record->status === \App\Enums\StatusAvaliacao::CONCLUIDA) {
            return false;
        }

        return ! $this->record->assinaturas()->where('tipo_assinante', 'avaliado')->exists();
    }

    public function getTitle(): string
    {
        return 'Avaliação — ' . ($this->record->servidor?->user?->nome ?? '');
    }

    public function getBreadcrumbs(): array
    {
        return [AvaliacaoResource::getUrl('index') => 'Minhas Avaliações', 'Visualizar'];
    }
}
