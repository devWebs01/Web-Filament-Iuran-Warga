<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Pengguna';

    protected static ?string $modelLabel = 'Administrator';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->whereIn('role', ['rt', 'bendahara'])
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
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
                            ->in([false, true])
                            ->columnSpan(2),
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
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => $state ? 'Terhapus' : 'Aktif')
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
