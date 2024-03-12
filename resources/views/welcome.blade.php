<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>

    @vite(['resources/assets/style.css'])

    <!-- @vite('resources/css/app.css') -->
</head>
<body class="text-gray-800 font-inter">
    <div id="app" >
        <router-view></router-view>
    </div>
    @vite('resources/js/app.js')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
    <!-- <script src="/resources/assets/script.js"></script> -->
</body>
</html>


