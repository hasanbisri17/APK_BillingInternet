<?php

namespace App\Filament\Resources\InternetPackageResource\Pages;

use App\Filament\Resources\InternetPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInternetPackages extends ListRecords
{
    protected static string $resource = InternetPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Paket'),
        ];
    }
}
