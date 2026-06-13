<?php

namespace App\Filament\Admin\Resources\TestimoniResource\Pages;

use App\Filament\Admin\Resources\TestimoniResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTestimonis extends ListRecords
{
    protected static string $resource = TestimoniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
