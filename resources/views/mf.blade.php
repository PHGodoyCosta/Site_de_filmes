<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Site com Filmes</title>

    @env("local")
        @vite(['resources/css/app.css', 'resources/css/home.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('/build/assets/app.css') }}">
        <link rel="stylesheet" href="{{ asset('/build/assets/home.css') }}">
        <script src="{{ asset('/build/assets/app2.js') }}"></script>
    @endenv

    {{-- @env("local")
        @vite(['resources/css/app.css', 'resources/css/home.css', 'resources/js/app.js'])
    @endenv

    @production
        <link rel="stylesheet" href="/build/assets/app-DQux0av7.css">
        <link rel="stylesheet" href="/build/assets/home-C0wZ_Mbf.css">
        <script src="/build/assets/app-Xaw6OIO1.js"></script>
    @endproduction --}}
</head>
<body>
    <div class="w-100 d-flex justify-content-center mt-3">
        <div class="card mb-3" style="width: 60rem;max-width: 70%">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="/images/mf.jpeg" class="img-fluid rounded-start" alt="...">
                </div>
                <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">Para minha mulher amada ❤️</h5>
                    <p class="card-text">Para você, deixo essa área especial. Espero que possa se divertir e aproveitar todos os filmes que você gosta.</p>
                    <p>Com amor, Pedro Godoy ✨</p>
                </div>
                </div>
            </div>
        </div>
    </div>
    <h1 class="mt-3 text-center mb-3">Filmes Maria Fernanda</h1>
    {{-- <a href="/filme/9d308500-9d3a-42f6-b2c9-b8d8c96a7055">Assita o filme</a> --}}
    <div class="container">
        <div class="cards d-flex gap-4 flex-wrap justify-content-center mb-3">
            @foreach ($filmes as $filme)
                @include("partials.card-filme", ["filme" => $filme, "prefixUrl" => "/mf/"])
            @endforeach
        </div>
    </div>
</body>
</html>
