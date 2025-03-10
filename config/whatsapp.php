<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Fonnte WhatsApp Gateway Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure your Fonnte WhatsApp Gateway settings.
    |
    */

    'api_token' => env('FONNTE_API_TOKEN', ''),
    
    'api_url' => env('FONNTE_API_URL', 'https://api.fonnte.com'),

    'message_templates' => [
        'billing' => [
            'new' => "Yth. {customer_name},\n\n".
                    "Tagihan internet Anda untuk periode {period} telah dibuat:\n".
                    "No. Invoice: {invoice_number}\n".
                    "Jumlah: Rp {amount}\n".
                    "Jatuh Tempo: {due_date}\n\n".
                    "Mohon melakukan pembayaran sebelum jatuh tempo.\n".
                    "Terima kasih.",
            
            'reminder' => "Yth. {customer_name},\n\n".
                         "Mengingatkan tagihan internet Anda yang akan jatuh tempo:\n".
                         "No. Invoice: {invoice_number}\n".
                         "Jumlah: Rp {amount}\n".
                         "Jatuh Tempo: {due_date}\n\n".
                         "Mohon segera melakukan pembayaran.\n".
                         "Terima kasih.",
            
            'overdue' => "Yth. {customer_name},\n\n".
                        "Tagihan internet Anda telah melewati jatuh tempo:\n".
                        "No. Invoice: {invoice_number}\n".
                        "Jumlah: Rp {amount}\n".
                        "Jatuh Tempo: {due_date}\n\n".
                        "Mohon segera melakukan pembayaran untuk menghindari pemutusan layanan.\n".
                        "Terima kasih.",
            
            'paid' => "Yth. {customer_name},\n\n".
                     "Terima kasih, pembayaran tagihan internet Anda telah kami terima:\n".
                     "No. Invoice: {invoice_number}\n".
                     "Jumlah: Rp {amount}\n".
                     "Tanggal Pembayaran: {payment_date}\n\n".
                     "Terima kasih atas kerjasamanya."
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Country Code
    |--------------------------------------------------------------------------
    |
    | The default country code to be prepended to phone numbers
    | that don't start with '+' or country code.
    |
    */
    'default_country_code' => '62',
];
