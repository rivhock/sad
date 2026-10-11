<?php
namespace App\Filament\Compaq\Resources;

use App\Filament\Compaq\Resources\QualificacaoResource\Pages;
use App\Models\Qualificacao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class QualificacaoResource extends Resource
{
    protected static ?string $model = Qualificacao::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Avaliações';
    protected static ?string $navigationLabel = 'Qualificações';
    protected static ?string $modelLabel = 'Qualificação';
    protected static ?string $pluralModelLabel = 'Qualificações';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('status')->label('Status')
                ->options(['pendente'=>'Em análise','aprovado'=>'Aprovado','rejeitado'=>'Rejeitado'])
                ->required(),
            Forms\Components\Textarea::make('observacoes')->label('Observações / Justificativa')->rows(3)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('servidor.user.nome')->label('Servidor')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('titulo')->label('Título')->searchable()->limit(40)->tooltip(fn ($state) => $state),
                Tables\Columns\TextColumn::make('tipo')->label('Tipo')->badge()
                    ->formatStateUsing(fn ($state) => Qualificacao::tipos()[$state] ?? $state),
                Tables\Columns\TextColumn::make('carga_horaria')->label('Carga')->suffix(' h')->badge()->color('info'),
                Tables\Columns\TextColumn::make('data_fim')->label('Conclusão')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'aprovado'  => 'Aprovado',
                        'rejeitado' => 'Rejeitado',
                        default     => 'Em análise',
                    })
                    ->color(fn ($state) => match($state) {
                        'aprovado'  => 'success',
                        'rejeitado' => 'danger',
                        default     => 'warning',
                    }),
                Tables\Columns\TextColumn::make('created_at')->label('Enviado')->since()->sortable()->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(['pendente'=>'Em análise','aprovado'=>'Aprovado','rejeitado'=>'Rejeitado'])
                    ->default('pendente'),
                Tables\Filters\SelectFilter::make('tipo')->options(Qualificacao::tipos()),
            ])
            ->actions([
                Tables\Actions\Action::make('ver_pdf')->label('Ver PDF')
                    ->icon('heroicon-o-document-text')->color('gray')
                    ->url(fn (Qualificacao $record) => $record->arquivo_url)
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('aprovar')->label('Aprovar')
                    ->icon('heroicon-o-check-circle')->color('success')
                    ->visible(fn (Qualificacao $record) => $record->status === 'pendente')
                    ->requiresConfirmation()
                    ->form([Forms\Components\Textarea::make('observacoes')->label('Observações (opcional)')->rows(2)])
                    ->action(function (Qualificacao $record, array $data) {
                        $record->update([
                            'status'         => 'aprovado',
                            'avaliado_por'   => auth()->id(),
                            'data_avaliacao' => now(),
                            'observacoes'    => $data['observacoes'] ?? null,
                        ]);
                        Notification::make()->title('Certificado aprovado')->success()->send();
                    }),

                Tables\Actions\Action::make('rejeitar')->label('Rejeitar')
                    ->icon('heroicon-o-x-circle')->color('danger')
                    ->visible(fn (Qualificacao $record) => $record->status === 'pendente')
                    ->requiresConfirmation()
                    ->form([Forms\Components\Textarea::make('observacoes')->label('Justificativa')->required()->rows(3)])
                    ->action(function (Qualificacao $record, array $data) {
                        $record->update([
                            'status'         => 'rejeitado',
                            'avaliado_por'   => auth()->id(),
                            'data_avaliacao' => now(),
                            'observacoes'    => $data['observacoes'],
                        ]);
                        Notification::make()->title('Certificado rejeitado')->warning()->send();
                    }),

                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListQualificacoes::route('/')];
    }
}
