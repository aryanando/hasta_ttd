<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script type="text/javascript" src="https://unpkg.com/qr-code-styling@1.5.0/lib/qr-code-styling.js"></script>
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
                        {{-- <button type="button" class="btn btn-light"
                            onclick="window.location.href='{{ route('kwitansi-dokter-dashboard') }}'">
                            <i class="fa fa-receipt"></i> Kwitansi Dokter
                        </button> --}}
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
            <h1 class="text-center">SPRIN Versi 2.0 RS Hasta Brata Batu</h1>
        </div>
        <div class="row">
            <div class="col">
                <form action="{{ url('/v2/add') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="InputNoSurat" class="form-label">No Surat</label>
                        <input name="nosurat" type="text" class="form-control" id="InputNoSurat"
                            aria-describedby="InputNoSuratHelp">
                        <div id="InputNoSuratHelp" class="form-text">Tentukan no surat.</div>
                    </div>
                    <div class="mb-3">
                        <label for="SelectYangTTD" class="form-label">Menandatangani</label>
                        <select name="ttd" class="form-select" aria-label="Pilih yang TTD" id="SelectYangTTD">
                            <option selected>Pilih yang menandatangani</option>
                            <option value="AKBP dr. Ananingati, Sp.OG(K) / NRP 71100512">AKBP dr. Ananingati, Sp.OG(K) /
                                NRP 71100512</option>
                            <option value="Kompol Arif Dian Aprianto, AMD.Kep., S.H. / NRP 81040450">Kompol Arif Dian
                                Aprianto, AMD.Kep., S.H. / NRP 81040450</option>
                            <option value="AKP Dodik Bintoro, S.Psi / NRP 78090637">AKP Dodik Bintoro, S.Psi / NRP
                                78090637</option>
                            <option value="IPTU dr. Arifian Juari, Sp.OG(K) / NRP 84071828">IPTU dr. Arifian Juari,
                                Sp.OG(K) / NRP 84071828</option>
                            <option value="BRIPKA Agus Purwanto / NRP 77081143">BRIPKA Agus Purwanto / NRP 77081143
                            </option>
                            <option value="BRIPKA Gandi Ari Setioko, Amd.Kep / NRP 89100114">BRIPKA Gandi Ari Setioko,
                                Amd.Kep / NRP 89100114</option>
                        </select>
                    </div>
                    <div class="mb-3 col-3">
                        <label for="InputTanggalSurat" class="form-label">Tanggal (Optional)</label>
                        <input name="waktu" type="datetime-local" class="form-control" id="InputTanggalSurat"
                            aria-describedby="InputTanggalSuratHelp">
                        <div id="InputTanggalSuratHelp" class="form-text">Pilih tanggal.</div>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
        <table id="dataTTD" class="display" style="width:100%">
            <thead>
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">No Surat</th>
                    <th scope="col">TTD</th>
                    <th scope="col">Waktu</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($full as $key => $ttd)
                    <tr>
                        <td scope="row">{{ ++$key }}</td>
                        <td>{{ $ttd['nosurat'] }} {!! $key == 1 ? '<span class="badge bg-success">Terbaru</span>' : '' !!}</td>
                        <td>{{ $ttd['ttd'] }}</td>
                        <td>{{ $ttd['waktu'] }}</td>
                        <td>
                            <!-- Button trigger modal -->
                            @if ($ttd['id'] < 1000)
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal"
                                    onclick="generateQR('{{ url('sprin') . '/' . $ttd['id'] }}')">
                                    QR
                                </button>
                                <button type="button" class="btn btn-success"
                                    onclick="window.open('{{ url('sprin') . '/' . $ttd['id'] }}', '_blank')">
                                    Lihat
                                </button>
                            @else
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal"
                                    onclick="generateQR('{{ url('v2/sprin') . '/' . $ttd['unique_id'] }}')">
                                    QR
                                </button>
                                <button type="button" class="btn btn-success"
                                    onclick="window.open('{{ url('v2/sprin') . '/' . $ttd['unique_id'] }}', '_blank')">
                                    Lihat
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">No Surat</th>
                    <th scope="col">TTD</th>
                    <th scope="col">Waktu</th>
                    <th scope="col">Aksi</th>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="d-flex justify-content-center">
                            <div id="canvas"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function generateQR(ttd) {
            const qrCode = new QRCodeStyling({
                width: 300,
                height: 300,
                type: "canvas",
                data: `${ttd}`,
                image: "{{ url('assets/img/logo.png') }}",
                dotsOptions: {},
                backgroundOptions: {
                    color: "#ffffff",
                },
                imageOptions: {
                    crossOrigin: "anonymous",
                    margin: 10
                }
            });
            console.log("Alh");
            qrCode.append(document.getElementById("canvas"));
        }
        const modal = document.getElementById('exampleModal')
        const canvasQR = document.getElementById('canvas')

        modal.addEventListener('hidden.bs.modal', () => {
            console.log('hide instance method called!');
            canvasQR.innerHTML = "";
        })
    </script>
    <script type="module">
        new datatable('#dataTTD');
    </script>
</body>

</html>
