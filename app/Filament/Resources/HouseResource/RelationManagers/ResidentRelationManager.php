<?php

namespace App\Filament\Resources\HousesResidentResource\RelationManagers;

use App\Models\House;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ResidentRelationManager extends RelationManager
{
    protected static string $relationship = 'house_residents';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Forms\Components\Select::make('house_id')
                            ->label('Rumah')
                            ->options(fn () => House::get()->mapWithKeys(fn ($house) => [
                                $house->id => "No. Rumah {$house->house_number} - ".
                                    ($house->status === 'dihuni' ? 'Dihuni' : 'Tidak Dihuni'),
                            ]))

                            ->required()
                            ->searchable(),
                        Forms\Components\Select::make('user_id')
                            ->label('Nama')
                            ->options(fn () => User::where('role', 'warga')->get()->mapWithKeys(fn ($user) => [
                                $user->id => "{$user->name} - {$user->phone_number}",
                            ]))
                            ->required()
                            ->searchable(),
                        Forms\Components\DatePicker::make('start_date')
                            ->required(),
                        Forms\Components\DatePicker::make('end_date'),
                    ])
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user.user_id')
            ->columns([
                Tables\Columns\TextColumn::make('house.house_number')
                    ->label('No. Rumah')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Penghuni')
                    ->searchable()
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->searchable()
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->searchable()
                    ->sortable()
                    ->alignCenter(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }
}
