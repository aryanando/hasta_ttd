<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script type="text/javascript" src="https://unpkg.com/qr-code-styling@1.5.0/lib/qr-code-styling.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @vite(['resources/js/app.js', 'resources/sass/app.scss'])
    <title>{{ config('app.name', 'Laravel') }}</title>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <button type="button" class="btn btn-success"
                            onclick="window.location.href='{{ route('kwitansi-dokter-dashboard') }}'">
                            <i class="fa fa-receipt"></i> Kwitansi Dokter
                        </button>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-3">
        @if (Session::has('message'))
            <p class="alert alert-info">{{ Session::get('message') }}</p>
        @endif
        <div class="row">
            <h1 class="text-center">Kwitansi Dokter RS Hasta Brata Batu</h1>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-tittle">
                            BUAT KWITANSI PEMBAYARAN
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('receipts.store') }}" method="POST">
                            @csrf
                            <!-- Received From -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Terima Dari</label>
                                <input type="text" class="form-control" name="received_from"
                                    value="RS BHAYANGKARA HASTA BRATA BATU" readonly>
                            </div>

                            <!-- Amount -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Jumlah (Rp)</label>
                                <input type="text" id="jumlah" class="form-control" name="amount"
                                    placeholder="Masukkan jumlah">
                            </div>

                            <!-- Amount in Words -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Terbilang</label>
                                <div class="input-group">
                                    <textarea id="terbilang" class="form-control" rows="2" name="amount_in_words" readonly></textarea>
                                    <button type="button" class="btn btn-success"
                                        onclick="updateTerbilang()">Generate</button>
                                </div>
                            </div>

                            <!-- Payment Purpose -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Untuk Pembayaran</label>
                                <textarea class="form-control" rows="2" name="payment_purpose" required></textarea>
                            </div>

                            <!-- Recipient Name (Dropdown) -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Penerima</label>
                                <select id="penerima" name="recipient" class="form-select">
                                    <option value="" selected disabled>Pilih Penerima</option>
                                    <option value="Gigih Prasetyo">Gigih Prasetyo</option>
                                    <option value="Andi Susanto">Andi Susanto</option>
                                    <option value="Budi Raharjo">Budi Raharjo</option>
                                    <option value="Dewi Kartika">Dewi Kartika</option>
                                </select>
                            </div>

                            <!-- Submit Button (For Example Purpose) -->
                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-primary">Cetak Kwitansi</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-tittle">
                            DATA KWITANSI
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="dataKwitansi" class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Terima Dari</th>
                                    <th>Jumlah (Rp)</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($receipts as $receipt)
                                    <tr>
                                        <td>{{ $receipt->received_from }}</td>
                                        <td>Rp {{ number_format($receipt->amount, 2, ',', '.') }}</td>
                                        <td>{{ $receipt->created_at->format('d-m-Y') }}</td>
                                        <td>
                                            <a href="{{ route('receipts.public', $receipt->public_code) }}"
                                                target="_blank" class="btn btn-warning btn-sm">Public Link</a>
                                            <a href="{{ route('receipts.show', $receipt->id) }}"
                                                class="btn btn-info btn-sm">View</a>
                                            <form action="{{ route('receipts.destroy', $receipt->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="module">
        new datatable('#dataKwitansi');
    </script>
    <script>
        function numberToWords(num) {
            if (num === 0) return "Nol Rupiah";

            const satuan = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan"];
            const belasan = ["Sepuluh", "Sebelas", "Dua Belas", "Tiga Belas", "Empat Belas", "Lima Belas", "Enam Belas",
                "Tujuh Belas", "Delapan Belas", "Sembilan Belas"
            ];
            const puluhan = ["", "", "Dua Puluh", "Tiga Puluh", "Empat Puluh", "Lima Puluh", "Enam Puluh", "Tujuh Puluh",
                "Delapan Puluh", "Sembilan Puluh"
            ];
            const ribuan = ["", "Ribu", "Juta", "Miliar", "Triliun"];

            function convertLessThanThousand(n) {
                let words = "";

                if (n >= 100) {
                    words += satuan[Math.floor(n / 100)] + " Ratus ";
                    n %= 100;
                }
                if (n >= 20) {
                    words += puluhan[Math.floor(n / 10)] + " ";
                    n %= 10;
                } else if (n >= 10) {
                    words += belasan[n - 10] + " ";
                    n = 0;
                }
                if (n > 0) {
                    words += satuan[n] + " ";
                }

                return words.trim();
            }

            let result = "";
            let unitIndex = 0;

            while (num > 0) {
                let part = num % 1000;
                if (part > 0) {
                    let partText = convertLessThanThousand(part);
                    if (unitIndex === 1 && part === 1) {
                        result = "Seribu " + result;
                    } else {
                        result = partText + " " + ribuan[unitIndex] + " " + result;
                    }
                }
                num = Math.floor(num / 1000);
                unitIndex++;
            }

            return result.trim() + " Rupiah";
        }

        function updateTerbilang() {
            let jumlahInput = document.getElementById("jumlah").value.replace(/[^0-9]/g,
                ""); // Remove non-numeric characters
            let num = parseInt(jumlahInput);
            if (!isNaN(num) && num > 0) {
                document.getElementById("terbilang").value = numberToWords(num);
            } else {
                document.getElementById("terbilang").value = "Masukkan jumlah yang valid!";
            }
        }

    </script>
</body>

</html>
