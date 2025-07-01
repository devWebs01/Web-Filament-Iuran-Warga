<?php

namespace App\Filament\Resources\HouseResidentResource\Pages;

use App\Filament\Resources\HouseResidentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHouseResidents extends ListRecords
{
    protected static string $resource = HouseResidentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
