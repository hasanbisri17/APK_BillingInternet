<?php

namespace App\Filament\Resources\InternetPackageResource\Pages;

use App\Filament\Resources\InternetPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInternetPackage extends EditRecord
{
    protected static string $resource = InternetPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
