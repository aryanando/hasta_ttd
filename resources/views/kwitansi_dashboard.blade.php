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
    @if (session('success'))
        <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3">
            <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif
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
                                <div class="autocomplete-container">
                                    <input type="text" name="recipient" id="autocomplete-input"
                                        class="form-control" placeholder="Search for a doctor or assistant...">
                                    <div id="autocomplete-list" class="autocomplete-dropdown"></div>
                                </div>
                            </div>

                            <!-- Submit Button (For Example Purpose) -->
                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-primary">Simpan</button>
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
                                    <th>Penerima</th>
                                    <th>Jumlah (Rp)</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($receipts as $receipt)
                                    <tr>
                                        <td>{{ $receipt->recipient }}</td>
                                        <td>Rp {{ number_format($receipt->amount, 2, ',', '.') }}</td>
                                        <td>{{ $receipt->created_at->format('d-m-Y') }}</td>
                                        <td>
                                            <a href="{{ route('receipts.public', $receipt->public_code) }}"
                                                target="_blank" class="btn btn-warning btn-sm">Public Link</a>
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

        document.addEventListener("DOMContentLoaded", function() {
            // Fade In and Fade Out Toast
            const toastElement = document.getElementById("successToast");

            if (toastElement) {
                const toast = new bootstrap.Toast(toastElement, {
                    delay: 3000
                }); // Auto hide after 3 seconds
                toast.show();
            }

            // Auto Complete Form
            const doctorNames = [
                "dr. ANANINGATI, Sp. OG (K)",
                "dr. WIDJANARKO ANDANG, Sp.OG",
                "dr. ARIFIAN JUARI, Sp. OG (K)",
                "dr. BAMBANG RISHARDANA, Sp.B",
                "dr. ANANTO SATYA PRADANA, SP. OT",
                "dr. AGUNG RIYANTO BUDI SANTOSO, Sp. OT",
                "dr. ANTON WURI HANDAYANTO, Sp.AN",
                "dr. VILDA PRASASTRI YUWONO, Sp. AN",
                "dr. TEGUH SETIADI, Sp. AN",
                "dr. CANDRA, Sp. AN",
                "dr. SEPTINA RAHAYU, Sp.U",
                "dr. YOYOK SUBAGIO, Sp.BS",
                "dr. REVITA WIDYA PRASANTI Sp.BP-RE",
                "dr. JULIA WIDIATI, Sp.M",
                "dr. FARIZ NUFIARWAN, Sp.M",
                "dr. LINA PUSPITA HUTASOIT, Sp. M",
                "dr. FARIDATUL JANNAH, Sp.THT",
                "dr. EVELYN CHRISTINA, Sp.Rad",
                "dr. FRANS JOHANNIS HUWAE, Msi. Med, Sp.A",
                "dr. HAYKAL AFFANDI, Sp. A",
                "dr. BERNANDUS ANGGARU, Sp.PD",
                "dr. RACHMAD ARI IRMAWAN, Sp.PD",
                "dr. DEDEN PERMANA , Sp.P",
                "dr. LUCIA PUJIASTUTI, Sp.S",
                "dr. FATHIA ANNIS PRAMESTI, Sp.S",
                "dr. RINO PUJI DWI SUKMAWAN, Sp. KFR",
                "dr. RAKA DWIMAREFFY HARO",
                "dr. YEREMI DWI PURNOMO",
                "dr. AGENG BAGUS SADEWO",
                "dr. OLIVIA DEVINA PERMATA",
                "dr. ANTONIA JUNITA DWIRAHMASARI",
                "dr. ANITA RAHMAWATI",
                "dr. DANY SATRIYA",
                "dr. SULISWATI",
                "dr. ZAHROTUL FITRIA",
                "dr. NABILAH NOOR RABBANI",
                "drg. HENDRA PUTRA SETYAWAN",
                "drg. FRESYNANDIA KARYNEIS, Sp. KG",
                "dr. NUR KAPUTRIN DWIGUSTININGRUM, Sp.JP(K)",
                "dr. PRIMA URSILA, Sp.JP",
                "dr. RASMI ADELAIDE INDAH JUWITA, Sp.KJ",
                "dr. NURYATI, Sp.THT-BKL",
                "dr. DEWANGGA, Sp.A",
                "ASS BAMBANG SUGIARTO",
                "ASS SUWIYARNO",
                "ASS STEFANUS TAMIL",
                "ASS PONIDAH",
                "ASS GIGIH",
                "ASS HELMI",
                "ASS WILLEN",
                "ASS MAYA",
                "ASS EDI SUGIARTO",
                "ASS PRIMA",
                "ASS HENDRA",
                "dr. KEMALA HAYATI, Sp.PK",
                "dr. DEKA BAGUS BINARSA, Sp.F",
                "dr. CITRA DWI HARNINGTYAS, Sp.DV., M.Ked.Klin"
            ];

            const inputField = document.getElementById("autocomplete-input");
            const suggestionsContainer = document.getElementById("autocomplete-list");

            inputField.addEventListener("input", function() {
                const inputValue = this.value.toLowerCase();
                suggestionsContainer.innerHTML = "";

                if (inputValue.length > 0) {
                    const filteredNames = doctorNames.filter(name =>
                        name.toLowerCase().includes(inputValue)
                    );

                    filteredNames.forEach(name => {
                        const suggestion = document.createElement("div");
                        suggestion.classList.add("autocomplete-suggestion");
                        suggestion.textContent = name;
                        suggestion.addEventListener("click", function() {
                            inputField.value = name;
                            suggestionsContainer.innerHTML = "";
                        });
                        suggestionsContainer.appendChild(suggestion);
                    });
                }
            });

            // Hide suggestions when clicking outside
            document.addEventListener("click", function(e) {
                if (!inputField.contains(e.target) && !suggestionsContainer.contains(e.target)) {
                    suggestionsContainer.innerHTML = "";
                }
            });
        });
    </script>
</body>

</html>
