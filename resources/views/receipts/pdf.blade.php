<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Receipt PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .receipt-box {
            border: 3px solid black;
            padding: 20px;
            width: 100%;
            text-align: left;
        }

        .header {
            text-align: left;
            font-weight: bold;
            font-size: 14px;
        }

        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        .amount-box {
            border: 2px solid black;
            padding: 5px;
            width: 200px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-top: 15px;
        }

        .signature-box {
            margin-top: 40px;
            margin-left: 40px;
            text-align: right;
        }
    </style>
</head>
<body>

    <div class="receipt-box">
        <div class="header">
            KEPOLISIAN DAERAH JAWA TIMUR <br>
            BIDANG KEDOKTERAN DAN KESEHATAN <br>
            RS BHAYANGKARA TK. III HASTA BRATA BATU
        </div>

        <div class="title">KWITANSI PEMBAYARAN</div>

        <p><strong>Terima Dari:</strong> {{ $receipt->received_from }}</p>
        <p><strong>Jumlah:</strong> Rp {{ number_format($receipt->amount, 2, ',', '.') }}</p>
        <p><strong>Terbilang:</strong> {{ $receipt->amount_in_words }}</p>
        <p><strong>Untuk Pembayaran:</strong> {{ $receipt->payment_purpose }}</p>

        <div class="amount-box">
            <strong>Rp. {{ number_format($receipt->amount, 2, ',', '.') }}</strong>
        </div>

        <div class="signature-box">
            <p><strong>{{ $receipt->recipient }}</strong></p>
            @if ($receipt->signature)
                <img src="{{ $receipt->signature }}" width="120" alt="Signature">
            @else
                <p>________________</p>
            @endif
        </div>
    </div>

</body>
</html>
