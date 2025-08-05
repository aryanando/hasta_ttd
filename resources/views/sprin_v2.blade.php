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
    <div class="container">
        <div class="mx-auto bg-white rounded mt-3 shadow" style="width: 680px;">
            <div class="p-3">
                <div class="row">
                    <div class="col-3">Nomor Surat</div>
                    <div class="col-1 text-end">:</div>
                    <div class="col-8">{{ $data['nosurat'] }}</div>
                </div>
                <div class="row">
                    <div class="col-3">Pejabat yang Menandatangani</div>
                    <div class="col-1 text-end">:</div>
                    <div class="col-8">{{ $data['ttd'] }}</div>
                </div>
                <div class="row">
                    <div class="col-3">Ditandatangani Pada</div>
                    <div class="col-1 text-end">:</div>
                    <div class="col-8">{{ $data['waktu'] }}</div>
                </div>
                <div class="d-flex justify-content-end">
                    <img src="{{ getTtd($data['ttd']) }}" alt="" srcset="" style="height: 150px" class="mt-3 me-3">
                </div>
            </div>
        </div>
    </div>
</body>
</html>

@php
    function getTtd($nama) {
        if ($nama == 'Kompol Arif Dian Aprianto, AMD.Kep., S.H. / NRP 81040450') {
            return url('assets/img/TTD_STEMPEL_PAK_ARIF.png');
        }
        elseif ($nama == 'PEMBINA drg. Akhmadi Prabowo, MMRS / NIP 197906192005011004') {
            return url('assets/img/TTD_PAK_ACHMADI.png');
        }
        elseif ($nama == 'AKBP dr. Ananingati, Sp.OG(K) / NRP 71100512') {
            return url('assets/img/TTD_STEMPEL_DR_ANANING.png');
        }
        elseif ($nama == 'AKBP Dr. dr. ANANINGATI, Sp.OG(K), Subsp.Obginsos / NRP 71100512') {
            return url('assets/img/TTD_STEMPEL_DR_ANANING.png');
        }
        elseif ($nama == 'AKP Dodik Bintoro, S.Psi / NRP 78090637') {
            return url('assets/img/pDodik.png');
        }
        elseif ($nama == 'IPTU dr. Arifian Juari, Sp.OG(K) / NRP 84071828') {
            return url('assets/img/drArifian.png');
        }
        elseif ($nama == 'BRIPKA Agus Purwanto / NRP 77081143') {
            return url('assets/img/pAgus.png');
        }
        elseif ($nama == 'BRIPKA Gandi Ari Setioko, Amd.Kep / NRP 89100114') {
            return url('assets/img/pGandi.png');
        }
        elseif ($nama == 'BRIPDA Dwiki Maulana Abdullah, A.md.Kep / NRP 97110966') {
            return url('assets/img/pDwiki.png');
        }
    }
@endphp
