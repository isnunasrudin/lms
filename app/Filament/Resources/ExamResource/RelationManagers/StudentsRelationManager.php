<?php

namespace App\Filament\Resources\ExamResource\RelationManagers;

use App\Models\Rombel;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'students';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('name')
                //     ->required()
                //     ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('rombel.name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()->label('Tambah Siswa'),
                Tables\Actions\Action::make('attach_rombel')
                    ->label('Tambah Rombongan belajar')
                    ->form([
                        Select::make('rombel')
                            ->options(Rombel::all(['name', 'id'])
                            ->pluck('name', 'id'))
                            ->multiple()
                            ->searchable() 
                    ])
                    ->action(function(array $data, RelationManager $livewire)
                    {
                        $livewire->getOwnerRecord()->students()->syncWithoutDetaching(
                            Rombel::with('students')->whereIn('id', $data['rombel'])->get()->pluck('students', 'id')->collapse()->pluck('id')
                        );
    
                    })
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
