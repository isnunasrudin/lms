<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Models\Ban;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class BansRelationManager extends RelationManager
{
    protected static string $relationship = 'bans';

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('student_id')->relationship('student', 'name')->searchable()->required(),
            DateTimePicker::make('until')->required()->default(Carbon::now()->addMinutes(10)),
            TextInput::make('description')->default('Blokir Manual')->columnSpanFull()
        ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('student.name'),
            TextEntry::make('student.rombel.name'),
            TextEntry::make('created_at')->dateTime(),
            TextEntry::make('until')->dateTime(),
            TextEntry::make('description')
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('student.name')
            ->columns([
                Tables\Columns\TextColumn::make('student.name'),
                Tables\Columns\TextColumn::make('student.rombel.name'),
                Tables\Columns\TextColumn::make('created_at')->sortable(),
                Tables\Columns\TextColumn::make('until')->formatStateUsing(fn (string $state): string => Carbon::now()->lte($state) ? Carbon::parse($state)->diffForHumans() : 'Mati'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Banned Manual'),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Lepas Blokir')->action(function (Ban $record){

                    $record->update([
                        'until' => Carbon::now()
                    ]);
                    
                })->visible(fn(Ban $record) => Carbon::now()->lte($record->until))->requiresConfirmation(),
                Tables\Actions\ViewAction::make()
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->defaultGroup(Group::make('created_at')->date()->label('Tanggal'));
    }
}
