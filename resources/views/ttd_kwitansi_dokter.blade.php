<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/js/app.js', 'resources/sass/app.scss'])
    <title>{{ config('app.name', 'Laravel') }}</title>
</head>

<body>
    <div class="container mt-4">
        <div id="receipt-box" class="p-3">
            <div class="receipt-box">
                <div class="row">
                    <!-- Left Side -->
                    {{-- <div class="col-3 text-center vertical-text">
                        <strong>RS BHAYANGKARA TK. III<br>HASTA BRATA BATU</strong>
                    </div> --}}

                    <!-- Right Side -->
                    <div class="col">
                        <div class="header mb-4">
                            <h5 class="text-uppercase">
                                <strong>KEPOLISIAN DAERAH JAWA TIMUR</strong> <br>
                                <strong>BIDANG KEDOKTERAN DAN KESEHATAN</strong> <br>
                                <strong>RS BHAYANGKARA TK.III HASTA BRATA BATU</strong>
                            </h5>
                        </div>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Terima Dari:</strong></td>
                                <td>{{ $receipt->received_from }}</td>
                            </tr>
                            <tr>
                                <td><strong>Terbilang:</strong></td>
                                <td>{{ $receipt->amount_in_words }}</td>
                            </tr>
                            <tr>
                                <td><strong>Untuk Pembayaran:</strong></td>
                                <td>{{ $receipt->payment_purpose }}</td>
                            </tr>
                        </table>

                        <div class="amount-box">
                            <strong>Rp. {{ number_format($receipt->amount, 2, ',', '.') }}</strong>
                        </div>

                        <div class="signature-box text-end">
                            <p class="text-end"><strong>{{ $receipt->recipient }}</strong></p>
                            @if ($receipt->signature)
                                <img src="{{ $receipt->signature }}" width="120" alt="Signature">
                                <div class="text-right mt-3 d-none">
                                    <button type="button" id="open-signature-modal" class="btn btn-primary"
                                        data-bs-toggle="modal" data-bs-target="#signatureModal">
                                        Tandatangani
                                    </button>
                                </div>
                            @else
                                <!-- Signature Section -->

                                @if ($receipt->signature)
                                    <p class="text-center">✅ Signature Saved</p>
                                    <img src="{{ $receipt->signature }}" class="border rounded mx-auto d-block"
                                        width="300" alt="Digital Signature">
                                @else
                                    <div class="text-right mt-3">
                                        <button type="button" id="open-signature-modal" class="btn btn-primary"
                                            data-bs-toggle="modal" data-bs-target="#signatureModal">
                                            Tandatangani
                                        </button>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Print Button -->
        <div class="text-center mt-4">
            <button onclick="window.print()" class="btn btn-success">Print Receipt</button>
            <button id="save-as-image" class="btn btn-primary">Save as Image</button>
            <a href="{{ route('receipts.pdf', $receipt->public_code) }}" class="btn btn-danger">Save as PDF</a>
        </div>
    </div>

    <!-- Signature Modal -->
    <div class="modal fade" id="signatureModal" tabindex="-1" aria-labelledby="signatureModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="signatureModalLabel">Tandatangani Kwitansi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <canvas id="signature-pad" class="border" width="400" height="200"></canvas>
                </div>
                <div class="modal-footer">
                    <button type="button" id="clear-signature" class="btn btn-warning">Bersihkan</button>
                    <button type="button" id="save-signature" class="btn btn-success">Simpan Kwitansi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden form to submit signature -->
    <form id="signature-form" action="{{ route('receipts.sign', $receipt->public_code) }}" method="POST">
        @csrf
        <input type="hidden" name="signature" id="signature-data">
    </form>

    <!-- Print Styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        /* Receipt Box Styling */
        .receipt-box {
            border: 3px solid #1c1c1c;
            padding: 15px;
            width: 80%;
            margin: auto;
        }

        .vertical-text {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            font-size: 18px;
            font-weight: bold;
            border-right: 2px solid black;
            padding-right: 10px;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
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
            margin-top: 30px;
        }

        .signature-placeholder {
            text-decoration: underline;
        }

        @media print {
            body {
                transform: scale(0.9);
            }

            @page {
                size: A4;
                margin: 20mm;
            }

            .btn {
                display: none !important;
            }

            .receipt-box {
                width: 100%;
                border: 3px solid black;
            }
        }
    </style>
    <script type="module">
        document.addEventListener("DOMContentLoaded", function() {
            const canvas = document.getElementById("signature-pad");
            const modal = document.getElementById("signatureModal");

            if (canvas && modal) {
                const signaturePad = new SignaturePad(canvas);

                // Open modal
                document.getElementById("open-signature-modal").addEventListener("click", function() {
                    signaturePad.clear(); // Reset signature pad when modal opens
                });

                // Clear signature button
                document.getElementById("clear-signature").addEventListener("click", function() {
                    signaturePad.clear();
                });

                // Save signature button
                document.getElementById("save-signature").addEventListener("click", function() {
                    if (signaturePad.isEmpty()) {
                        alert("Please sign before saving.");
                    } else {
                        const signatureData = signaturePad.toDataURL(); // Get base64 image
                        document.getElementById("signature-data").value =
                            signatureData; // Store in hidden input
                        document.getElementById("signature-form").submit(); // Submit form
                    }
                });
            }

            const saveAsImageButton = document.getElementById("save-as-image");

            if (saveAsImageButton) {
                saveAsImageButton.addEventListener("click", function() {
                    const receiptElement = document.getElementById("receipt-box");

                    html2canvas(receiptElement).then(canvas => {
                        const link = document.createElement("a");
                        link.href = canvas.toDataURL("image/png");
                        link.download = "receipt.png";
                        link.click();
                    });
                });
            }
        });
    </script>
</body>

</html>
