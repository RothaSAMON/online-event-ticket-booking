<?php

namespace App\Filament\Resources\EventSectionResource\Pages;

use App\Filament\Resources\EventSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEventSections extends ListRecords
{
    protected static string $resource = EventSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
