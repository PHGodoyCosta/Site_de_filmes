<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Site com Filmes</title>

    @env("local")
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endenv
</head>
<body>
    <div class="w-100 d-flex justify-content-center" style="max-height: 50vh">
        <img class="rounded-0" style="width: 100%;max-height: 400px;max-width: 1200px" src="/images/poster.webp" alt="Backdrop de Cristo">
    </div>
    <h1 class="mt-3 text-center">Filmes Cristãos</h1>
    <a href="/filme/9d308500-9d3a-42f6-b2c9-b8d8c96a7055">Assita o filme</a>
    <div class="container">
        <div class="cards d-flex gap-4 flex-wrap justify-content-center justify-content-md-start">
           @foreach ($filmes as $filme)
                <div class="card" style="width: 13rem;">
                    <a href="/filme/{{ $filme->hash }}">
                        <img src="https://image.tmdb.org/t/p/w200{{ $filme->poster }}" class="card-img-top" alt="...">
                    </a>
                    <div class="card-body">
                        <a href="/filme/{{ $filme->hash }}">
                            <h5 class="card-title">{{ $filme->name }} (2005)</h5>
                        </a>
                        <p class="fs-6 text-muted">Drama, Ação, Aventura, História, Guerra - 2h 24m</p>
                        <p class="card-text">Ainda em luto pela repentina morte de sua esposa, o ferreiro Balian junta-se ao seu distante pai, Baron Godfrey, nas cruzadas a caminho de Jerusalém. Após uma jornada muito difícil até à cidade santa, o jovem valente entra no séquito do rei leproso Balduíno IV, que deseja lutar contra os muçulmanos para seu próprio ganho político e pessoal.</p>
                    </div>
                </div>
           @endforeach
        </div>
    </div>
</body>
</html>
