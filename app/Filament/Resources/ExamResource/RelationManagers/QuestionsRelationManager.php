<?php

namespace App\Filament\Resources\ExamResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    public function form(Form $form): Form
    {
        return $form
            ->columns(1)->schema([
                Grid::make(1)->schema([
                    Forms\Components\RichEditor::make('content')
                    ->label('Pertanyaan')
                    ->required()
                ]),
                
                Repeater::make('options')->label('Opsi Jawaban')->schema([
                    RichEditor::make('value')->toolbarButtons([
                        'attachFiles',
                        'bold',
                        'italic',
                        'redo',
                        'underline',
                        'undo',
                    ]),
                    Checkbox::make('is_correct')->label('Jawaban Benar')
                ])
                ->grid(2)->itemLabel(function (array $state, $component): ?string {
                    static $position = 1;
                    return "Jawaban " . $position++;
                })
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('content')->limit(50),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
