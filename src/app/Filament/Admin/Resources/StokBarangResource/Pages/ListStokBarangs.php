<?php

namespace App\Filament\Admin\Resources\StokBarangResource\Pages;

use App\Filament\Admin\Resources\StokBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStokBarangs extends ListRecords
{
    protected static string $resource = StokBarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
