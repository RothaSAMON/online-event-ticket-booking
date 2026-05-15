<?php

namespace App\Filament\Resources\AnalyticsCacheResource\Pages;

use App\Filament\Resources\AnalyticsCacheResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnalyticsCache extends EditRecord
{
    protected static string $resource = AnalyticsCacheResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
