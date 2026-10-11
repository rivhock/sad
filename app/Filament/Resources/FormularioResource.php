<?php
namespace App\Filament\Resources;

use App\Filament\Resources\FormularioResource\Pages;
use App\Models\Formulario;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FormularioResource extends Resource
{
    protected static ?string $model = Formulario::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Avaliações';
    protected static ?string $navigationLabel = 'Formulários';
    protected static ?string $modelLabel = 'Formulário';
    protected static ?string $pluralModelLabel = 'Formulários';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identificação')
                ->schema([
                    Forms\Components\TextInput::make('titulo')
                        ->label('Título')->required()->maxLength(255)->columnSpanFull(),
                    Forms\Components\TextInput::make('versao')
                        ->label('Versão')->numeric()->default(1)->required(),
                    Forms\Components\Select::make('calculo')
                        ->label('Cálculo da nota')
                        ->options([
                            'soma'             => 'Soma simples',
                            'media'            => 'Média',
                            'media_ponderada'  => 'Média ponderada',
                        ])
                        ->default('soma')->required(),
                    Forms\Components\Toggle::make('ativo')->label('Ativo')->default(true),
                ])->columns(3),

            Forms\Components\Section::make('Itens do Formulário')
                ->description('Estes itens são usados tanto para Estágio Probatório quanto para Avaliação de Desempenho.')
                ->schema([
                    Forms\Components\Repeater::make('itens')
                        ->relationship('itens')
                        ->schema([
                            Forms\Components\TextInput::make('ordem')->label('Nº')->numeric()->required()->default(1),
                            Forms\Components\Textarea::make('descricao')->label('Descrição')->required()->rows(2)->columnSpan(2),
                            Forms\Components\TextInput::make('peso')->label('Peso')->numeric()->default(1)->required(),
                            Forms\Components\TextInput::make('nota_min')->label('Nota mínima')->numeric()->default(0)->required(),
                            Forms\Components\TextInput::make('nota_max')->label('Nota máxima')->numeric()->default(10)->required(),
                        ])
                        ->columns(5)
                        ->defaultItems(0)
                        ->reorderable('ordem')
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string =>
                            isset($state['ordem'], $state['descricao'])
                                ? $state['ordem'] . '. ' . \Illuminate\Support\Str::limit($state['descricao'], 80)
                                : null
                        ),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('titulo')->label('Título')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('versao')->label('Versão')->badge(),
                Tables\Columns\TextColumn::make('itens_count')->label('Itens')->counts('itens')->badge(),
                Tables\Columns\TextColumn::make('calculo')->label('Cálculo')->badge()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('avaliacoes_count')->label('Usado em')->counts('avaliacoes')->badge()->color('info'),
                Tables\Columns\IconColumn::make('ativo')->label('Ativo')->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('ativo')->label('Ativo'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFormularios::route('/'),
            'create' => Pages\CreateFormulario::route('/create'),
            'edit'   => Pages\EditFormulario::route('/{record}/edit'),
        ];
    }
}
