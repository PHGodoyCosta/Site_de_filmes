<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Site com Filmes</title>

    @php
        require __DIR__ . "/../../../resources/Components/head.blade.php";
    @endphp

    @env("local")
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endenv
</head>
<body>
    <h1>Olá, sou o site com filmes</h1>
    <a href="/filme/9d308500-9d3a-42f6-b2c9-b8d8c96a7055">Assita o filme</a>
</body>
</html>
