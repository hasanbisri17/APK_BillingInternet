<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice {{ $payment->invoice_number }}</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .details {
            margin-bottom: 30px;
        }
        .customer {
            float: left;
            width: 50%;
        }
        .info {
            float: right;
            width: 50%;
            text-align: right;
        }
        .clear {
            clear: both;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .amount {
            text-align: right;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">INVOICE</div>
        <div>{{ config('app.name') }}</div>
    </div>

    <div class="details">
        <div class="customer">
            <strong>Ditagihkan kepada:</strong><br>
            {{ $payment->customer->name }}<br>
            {{ $payment->customer->address }}<br>
            {{ $payment->customer->phone }}
        </div>
        <div class="info">
            <strong>No. Invoice:</strong> {{ $payment->invoice_number }}<br>
            <strong>Tanggal:</strong> {{ $payment->created_at->format('d/m/Y') }}<br>
            <strong>Jatuh Tempo:</strong> {{ $payment->due_date->format('d/m/Y') }}<br>
            <strong>Status:</strong> {{ ucfirst($payment->status) }}
        </div>
        <div class="clear"></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th width="150">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $payment->internetPackage->name }} - Layanan Internet</td>
                <td class="amount">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th style="text-align: right">Total</th>
                <th class="amount">Rp {{ number_format($payment->amount, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    @if($payment->payment_date)
    <div class="payment-info">
        <strong>Informasi Pembayaran:</strong><br>
        Tanggal Pembayaran: {{ $payment->payment_date->format('d/m/Y') }}<br>
        Metode Pembayaran: {{ $payment->paymentMethod->name ?? '-' }}
    </div>
    @endif

    <div class="footer">
        Terima kasih atas kepercayaan Anda menggunakan layanan kami.
    </div>
</body>
</html>
