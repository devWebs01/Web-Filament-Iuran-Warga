<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResidentResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ResidentResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Warga';

    protected static ?string $navigationGroup = 'Pengguna';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->where('role', 'warga');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->minLength(8)
                            ->required(fn (string $context) => $context === 'create')
                            ->dehydrated(fn ($state) => filled($state)),
                        Forms\Components\TextInput::make('phone_number')
                            ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                            ->tel()
                            ->required(),
                        Forms\Components\FileUpload::make('ktp_photo')
                            ->required()
                            ->image()
                            ->directory('ktp')
                            ->columnSpan(2),
                        Forms\Components\Select::make('status')
                            ->options([
                                'kontrak' => 'Kontrak',
                                'tetap' => 'Tetap',
                            ])
                            ->required()
                            ->in(['tetap', 'kontrak']),
                        Forms\Components\Select::make('role')
                            ->options([
                                'rt' => 'rt',
                                'bendahara' => 'bendahara',
                                'warga' => 'warga',
                            ])
                            ->required()
                            ->in(['bendahara', 'rt', 'warga']),
                        Forms\Components\Select::make('is_married')
                            ->options([
                                true => 'Sudah Menikah',
                                false => 'Belum Menikah',
                            ])
                            ->required()
                            ->in([false, true]),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->alignCenter(),
                Tables\Columns\ImageColumn::make('ktp_photo')
                    ->label('KTP')
                    ->rounded()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->alignCenter(),
                Tables\Columns\IconColumn::make('is_married')
                    ->label('Status Pernikahan')
                    ->boolean()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'kontrak' => 'Kontrak',
                        'tetap' => 'Tetap',
                    ]),
                Tables\Filters\SelectFilter::make('is_married')
                    ->label('Status Pernikahan')
                    ->options([
                        true => 'Sudah Menikah',
                        false => 'Belum Menikah',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->button(),
                Tables\Actions\ViewAction::make()
                    ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResidents::route('/'),
            'create' => Pages\CreateResident::route('/create'),
            'edit' => Pages\EditResident::route('/{record}/edit'),
        ];
    }
}
