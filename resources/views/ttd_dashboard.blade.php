<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/js/app.js', 'resources/sass/app.scss'])
    <title>Document</title>
</head>

<body>

    <script type="text/javascript">
        function greetFromHtml() {
            alert("Hello");
        }
        const module = {};
    </script>
    <script type="module">
        function generateQR(ttd) {
            let qrcode = new QrCodeWithLogo({
                canvas: document.getElementById("canvas"),
                content: `${ttd}`,
                width: 380,
                download: false,
                image: document.getElementById("image"),
                logo: {
                    src: "{{ url('assets/img/logo.png') }}"
                }
            });

            qrcode.toCanvas().then(() => {
                qrcode.toImage().then(() => {
                    // qrcode.downloadImage("hello world");
                });
            });
        }

        module.generateQR = generateQR;
        new datatable('#dataTTD');
    </script>
    <div class="container">
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
                            <option value="BRIPKA Gandi Ari Setioko, Amd.Kep / NRP 89100114">BRIPKA Gandi Ari Setioko, Amd.Kep / NRP 89100114</option>
                        </select>
                    </div>
                    <div class="mb-3 col-3">
                        <label for="InputTanggalSurat" class="form-label">Tanggal</label>
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
                        <td>{{ $ttd['nosurat'] }}</td>
                        <td>{{ $ttd['ttd'] }}</td>
                        <td>{{ $ttd['waktu'] }}</td>
                        <td>
                            <!-- Button trigger modal -->
                            @if ($ttd['id'] < 1000)
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal"
                                    onclick="module.generateQR('{{ url('sprin') . '/' . $ttd['id'] }}')">
                                    QR
                                </button>
                                <button type="button" class="btn btn-success"
                                    onclick="window.open('{{ url('sprin') . '/' . $ttd['id'] }}', '_blank')">
                                    Lihat
                                </button>
                            @else
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal"
                                    onclick="module.generateQR('{{ url('sprin') . '/' . $ttd['unique_id'] }}')">
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
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="d-flex justify-content-center">
                            <canvas id="canvas"></canvas>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>



</body>

</html>
