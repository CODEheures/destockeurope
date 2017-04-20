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
                            <h1 class="ui header">Conditions Générales de Vente</h1>
                            @include('global.tempo.inCreation')

                            <h2  class="ui dividing header">Préambule: Définitions</h2>
                            @include('global.cg.common.definitions')

                            <h2  class="ui dividing header">Article 1: Objet</h2>
                            @include('global.cg.v.object')

                            <h2  class="ui dividing header">Article 2: Acceptation</h2>
                            @include('global.cg.v.accept')

                            <h2  class="ui dividing header">Article 3: Passage d'une commande</h2>
                            @include('global.cg.v.souscription')

                            <h2 class="ui dividing header">Article 4: Descriptif et tarifs</h2>
                            @include('global.cg.v.description')

                            <h2  class="ui dividing header">Article 5: Cas de force majeure</h2>
                            @include('global.cg.v.major')

                            <h2  class="ui dividing header">Article 6: Modification des <a href="{{ route('cgv') }}">CGV</a> </h2>
                            @include('global.cg.v.modification')

                            <h2  class="ui dividing header">Article 7: Attribution de juridiction et dispositions diverses</h2>
                            @include('global.cg.common.juridicAttribution')
                            @include('global.cg.v.illegale')


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection