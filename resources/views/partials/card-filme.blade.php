<div class="card card-filme">
    <a href="{{ $prefixUrl }}filme/{{ $filme->hash }}">
        <img src="https://image.tmdb.org/t/p/w200{{ $filme->poster }}" class="card-img-top" alt="...">
    </a>
    <div class="card-body">
        <a href="{{ $prefixUrl }}filme/{{ $filme->hash }}">
            <h5 class="card-title">{{ $filme->name }}
                @if ($filme->year)
                    <span>({{ $filme->year }})</span>
                @endif
            </h5>
        </a>
        @if ($filme->categories)
            <p class="fs-6 text-muted m-0">{{ $filme->categories }}</p>
        @endif
        @if ($filme->duration)
            <p class="fs-6 text-muted m-0 pt-1">{{ $filme->duration }}</p>
        @endif
        {{-- <p class="card-text">Ainda em luto pela repentina morte de sua esposa, o ferreiro Balian junta-se ao seu distante pai, Baron Godfrey, nas cruzadas a caminho de Jerusalém. Após uma jornada muito difícil até à cidade santa, o jovem valente entra no séquito do rei leproso Balduíno IV, que deseja lutar contra os muçulmanos para seu próprio ganho político e pessoal.</p> --}}
    </div>
</div>
