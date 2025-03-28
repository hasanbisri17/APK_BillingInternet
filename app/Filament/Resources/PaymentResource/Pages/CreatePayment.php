<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // If payment_date is set, automatically set status to paid
        if (!empty($data['payment_date'])) {
            $data['status'] = 'paid';
        }
        
        // If due date has passed and no payment, set to overdue
        if (empty($data['payment_date']) && strtotime($data['due_date']) < time()) {
            $data['status'] = 'overdue';
        }

        // Ensure internet_package_id is set from customer's package
        if (!empty($data['customer_id'])) {
            $customer = \App\Models\Customer::find($data['customer_id']);
            if ($customer) {
                $data['internet_package_id'] = $customer->internet_package_id;
            }
        }

        // Ensure payment_method_id is null for new payments
        if (empty($data['payment_date'])) {
            $data['payment_method_id'] = null;
        }

        return $data;
    }
}
