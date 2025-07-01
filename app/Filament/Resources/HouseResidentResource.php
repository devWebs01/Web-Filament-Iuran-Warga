<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HouseResidentResource\Pages;
use App\Filament\Resources\HousesResidentResource\RelationManagers\HouseRelationManager;
use App\Models\House;
use App\Models\HouseResident;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HouseResidentResource extends Resource
{
    protected static ?string $model = HouseResident::class;

    protected static ?string $navigationGroup = 'Rumah';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Penghuni Rumah';

    public static function form(Form $form): Form
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

    public static function table(Table $table): Table
    {
        return $table
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
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->alignCenter(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->button(),
                Tables\Actions\ViewAction::make()
                    ->button(),
                Tables\Actions\DeleteAction::make()
                    ->button(),
                Tables\Actions\ForceDeleteAction::make()
                    ->button(),
                Tables\Actions\RestoreAction::make()
                    ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            HouseRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHouseResidents::route('/'),
            'create' => Pages\CreateHouseResident::route('/create'),
            'edit' => Pages\EditHouseResident::route('/{record}/edit'),
        ];
    }
}
