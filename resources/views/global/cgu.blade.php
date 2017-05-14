@extends('layouts.portal')

@section('content')

    <!-- main page -->
    <div class="ui grid">
        <div class="four wide tablet only four wide computer only column">
            <double-square
                    :centered="true"
            ></double-square>
        </div>
        <div class="sixteen wide mobile twelve wide tablet twelve wide computer column">
            <div class="ui segment">
                <div class="ui grid">
                    <div class="sixteen wide column">
                        <div class="ui segment">
                            <h1 class="ui header">Conditions Générales d'utilisation</h1>
                            @include('global.tempo.inCreation')

                            <h2  class="ui dividing header">Préambule: définitions</h2>
                            @include('global.cg.common.definitions')

                            <h2  class="ui dividing header">Article 1: Objet</h2>
                            @include('global.cg.u.object')

                            <h2  class="ui dividing header">Article 2: Acceptation</h2>
                            @include('global.cg.u.accept')

                            <h2  class="ui dividing header">Article 3: Utilisation du service {{ env('LEGAL_COMPAGNY_PSEUDONAME') }}</h2>
                            @include('global.cg.u.proAccount')

                            <h2 class="ui dividing header">Article 4: Règles de diffusion et modération des annonces</h2>
                            @include('global.cg.common.diffusionRules')

                            <h2 class="ui dividing header">Article 5: Engagements de l'annonceur</h2>
                            @include('global.cg.u.advertisserEngagment')

                            <h2  class="ui dividing header">Article 6: Propriété intellectuelle</h2>
                            @include('global.cg.u.propertyIntellect')

                            <h2 class="ui dividing header">Article 7: Responsabilité et garanties de {{ env('LEGAL_COMPAGNY_PSEUDONAME') }}</h2>
                            @include('global.cg.u.responsabilityDestock')
                            @include('global.cg.u.warrantyDestock')
                            @include('global.cg.u.subcontractor')

                            <h2  class="ui dividing header">Article 8: Cookies</h2>
                            @include('global.cg.u.cookies')

                            <h2  class="ui dividing header">Article 9: Modification du service {{ env('LEGAL_COMPAGNY_PSEUDONAME') }}</h2>
                            @include('global.cg.u.modification')

                            <h2  class="ui dividing header">Article 10: Attribution de Juridiction et disposition diverses</h2>
                            @include('global.cg.common.juridicAttribution')
                            @include('global.cg.u.illegale')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection