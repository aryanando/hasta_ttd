<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/js/app.js'])
    <title>Document</title>
</head>

<body>
    <canvas id="canvas"></canvas>

    <script type="module">
        qrcode.toCanvas(document.getElementById('canvas'), 'sample text', function(error) {
            if (error) console.error(error)
            console.log('success!');
        })
    </script>
</body>

</html>
