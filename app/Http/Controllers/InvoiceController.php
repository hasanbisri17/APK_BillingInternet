<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function download(Payment $payment)
    {
        try {
            if (!$payment->customer || !$payment->internetPackage) {
                return back()->with('error', 'Invalid payment data');
            }

            // Create temp directory if it doesn't exist
            $tempPath = storage_path('app/temp');
            if (!file_exists($tempPath)) {
                mkdir($tempPath, 0755, true);
            }

            $filePath = $tempPath . '/' . $payment->invoice_number . '.pdf';

            // Generate PDF
            $pdf = PDF::loadView('invoice-simple', ['payment' => $payment])
                ->setPaper('a4');

            // Save to temp file
            $pdf->save($filePath);

            // Stream the file and delete after sending
            return response()->download($filePath)->deleteFileAfterSend();

        } catch (\Exception $e) {
            Log::error('Invoice generation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate invoice');
        }
    }
}
