@extends('layouts.portal')

@section('content')
    <!-- banner -->
    <div class="ui grid">
        <div class="four wide tablet only four wide computer only column"></div>
        <div class="twelve wide computer only column">
            <div class="ui leaderboard test ad" data-text="728 x 90"></div>
        </div>
        <div class="twelve wide tablet only column">
            <div class="ui banner test ad" data-text="banner"></div>
        </div>
        <div class="sixteen wide mobile only column">
            <div class="ui half banner test centered ad" data-text="Half Banner"></div>
        </div>
    </div>

    <!-- main page -->
    <div class="ui grid">
        <div class="four wide tablet only four wide computer only column">
            <div class="ui small rectangle test ad " data-text="180 x 150"></div>
            <div class="ui small rectangle test ad spaced-top-2" data-text="180 x 150"></div>
            <!--<div class="ui wide skyscraper test ad welcome-ads" data-text="160 x 600"></div>-->
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