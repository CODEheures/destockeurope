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