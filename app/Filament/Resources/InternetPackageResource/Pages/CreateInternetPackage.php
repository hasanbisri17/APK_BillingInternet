<?php

namespace App\Filament\Resources\InternetPackageResource\Pages;

use App\Filament\Resources\InternetPackageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInternetPackage extends CreateRecord
{
    protected static string $resource = InternetPackageResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
