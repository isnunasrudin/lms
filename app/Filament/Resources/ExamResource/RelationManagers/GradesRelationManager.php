<?php

namespace App\Filament\Resources\ExamResource\RelationManagers;

use App\Infolists\Components\GradeEntry;
use App\Infolists\Components\MissedQuestionEntry;
use Filament\Forms\Form;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class GradesRelationManager extends RelationManager
{
    protected static string $relationship = 'grades';
    
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('grade')->label('Nilai'),
                TextEntry::make('status'),
                TextEntry::make('created_at')->label('Mulai Pada')->dateTime(),
                TextEntry::make('finished_at')->label('Selesai Pukul')->dateTime(),

                Section::make('Hasil Jawaban')->schema([
                    RepeatableEntry::make('questions')->schema([
                        GradeEntry::make('')
                    ])->label('')->grid(2)
                ]),

                Section::make('Tidak Dijawab')->schema([
                    MissedQuestionEntry::make('')->columnSpanFull()
                ])
            ])->columns(2);
    }

    public function form(Form $form): Form
    {
        return $form;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('student.name')
            ->columns([
                Tables\Columns\TextColumn::make('student.name')->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('grade')->label('Nilai')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'FINISH' => 'FINISH',
                    'PROGRESS' => 'PROGRESS'
                ])
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
