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

    <canvas id="canvas"></canvas>

    <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>No Surat</th>
                <th>TTD</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($full as $key=>$ttd)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $ttd['nosurat'] }}</td>
                    <td>{{ $ttd['ttd'] }}</td>
                    <td>{{ $ttd['waktu'] }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>No.</th>
                <th>No Surat</th>
                <th>TTD</th>
                <th>Waktu</th>
            </tr>
        </tfoot>
    </table>

    <script type="module">
        qrcode.toCanvas(document.getElementById('canvas'), 'ASdasdadwqeSAdsadqwesad', function(error) {
            if (error) console.error(error)
            console.log('success!');
        })

        new datatable('#example');
    </script>
</body>

</html>
