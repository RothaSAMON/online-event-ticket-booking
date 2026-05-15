<?php

namespace App\Filament\Resources\EventSectionResource\Pages;

use App\Filament\Resources\EventSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEventSection extends EditRecord
{
    protected static string $resource = EventSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
