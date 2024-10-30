<?php

namespace App\Filament\Resources\ExamResource\RelationManagers;

use App\Http\Controllers\PercobaanController;
use App\Models\Exam;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
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
use Illuminate\Support\Facades\Storage;

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
                Tables\Actions\Action::make('importExcel')
                    ->label('Import Soal (DOCX)')
                    ->color('info')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        FileUpload::make('attachment')->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.wordprocessingml.document']),
                    ])
                    ->action(function(array $data, RelationManager $livewire)
                    {
                        $file_path = Storage::disk('public')->path($data['attachment']);
                        
                        $questions = (new PercobaanController)->extractTables($file_path);
                        foreach ($questions as $question) {
                            $livewire->getOwnerRecord()->questions()->create([
                                'content' => $question->content,
                                'options' => array_map(function($a, $b) use($question) {
                                    return [
                                        'value' => $a,
                                        'is_correct' => $b === $question->correct
                                    ];
                                }, $question->options, array_keys($question->options))
                            ]);
                        }
    
                    })
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
