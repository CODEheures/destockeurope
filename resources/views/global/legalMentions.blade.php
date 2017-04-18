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
                            <h1 class="ui header">Informations légales</h1>
                            <div class="cgv">
                                <p class="ui orange big label">Cette société est en cours de création et ce site web n'est pas encore ouvert au
                                    public. Cet accès n'est qu'à but de test "live" pour les développeurs web
                                </p>
                                <h2  class="ui dividing header">Présentation du site</h2>
                                <p>Le site Internet <a href="{{ route('portal') }}">{{ route('portal') }}</a>, ci-après
                                    dénommé «  {{ env('LEGAL_COMPAGNY_PSEUDONAME') }} » propose un service de dépôt et de consultation de
                                    petites annonces sur Internet plus spécifiquement destiné aux professionnels.
                                </p>

                                <p>Le site est édité par, <b>{{ env('LEGAL_COMPAGNY_NAME') }}</b> <small>(au capital de
                                        {{ env('LEGAL_COMPAGNY_CAPITAL') }})</small>,
                                    <br />Siret: immatriculée au registre du commerce et des sociétés de Tours sous le
                                    numéro {{ env('LEGAL_COMPAGNY_SIRET') }}.
                                    <br />Siège social: {{ env('LEGAL_COMPAGNY_ADDRESS') }}.
                                    <br />Téléphone: {{ env('LEGAL_COMPAGNY_PHONE') }}.
                                    <br />Numéro de TVA intracommunautaire: {{ env('TVA_REQUESTER_VAT_NUMBER') }}.
                                </p>

                                <p>Le directeur de publication de {{ env('LEGAL_COMPAGNY_PSEUDONAME') }} est
                                    {{ env('LEGAL_COMPAGNY_PUBLICATION_DIRECTOR') }}.
                                </p>

                                <p>Le webmaster de {{ env('LEGAL_COMPAGNY_PSEUDONAME') }} est
                                    {{ env('LEGAL_COMPAGNY_WEBMASTER') }}.
                                </p>

                                <p>L'hebergeur de {{ env('LEGAL_COMPAGNY_PSEUDONAME') }} est
                                    {{ env('LEGAL_COMPAGNY_HOST') }}.
                                </p>

                                <h2  class="ui dividing header">Conditions d'utilisations</h2>
                                <p>L'accès au site, sa consultation et son utilisation sont subordonnés à l'acceptation
                                    sans réserve des présentes Conditions Générales d'Utilisation de
                                    {{ env('LEGAL_COMPAGNY_PSEUDONAME') }} disponibles à l’adresse suivante:
                                    <a href="{{ route('cgu') }}">{{ route('cgu') }}</a>
                                </p>

                                <h2  class="ui dividing header">Nous joindre</h2>
                                <h3 class="ui header">Pour le public</h3>
                                <p>Pour toute question sur l'entreprise ou toute demande ne provenant pas des autorités
                                    publiques, nous vous invitons à utiliser notre formulaire de Contact en cliquant sur
                                    le lien: <a href="{{ route('contact') }}">{{ route('contact') }}</a>. Vous pourrez
                                    expliquer votre situation en détail. L’équipe dédiée prendra toutes les mesures
                                    nécessaires.
                                </p>
                                <h3 class="ui header">Service réquisition</h3>
                                <p>Un service réquisition répond uniquement aux demandes des autorités publiques
                                    (gendarmerie, police...).
                                    <br />Pour les Réquisitions judiciaires et les Droits de communication, merci de
                                    nous faire parvenir le droit de communication (sur papier à en-tête daté, signé et
                                    tamponné) ou la réquisition (datée, signée et tamponnée) précisant la référence de
                                    l'annonce, l'adresse email de l'annonceur et/ou son numéro de téléphone, en pièce
                                    jointe par mail à <a href="#">{{ env('SERVICE_MAIL_REQUISITION') }}</a> (le délai de
                                    réponse est d'environ 24h les jours ouvrés).
                                    <br />La référence de l'annonce est le numéro figurant dans l'adresse internet de la
                                    page de présentation de l'annonce.
                                    <br />Exemple : {{ $randomAdvertUrl }} La référence est {{ $randomAdvertId }}.
                                    <br />Nous vous transmettrons les informations demandées dans les meilleurs délais.
                                    Ces recherches sont effectuées à titre gracieux.
                                    <br />Pour simplifier nos procédures, merci de bien vouloir indiquer dans votre
                                    réquisition l'adresse email à laquelle vous faire parvenir notre réponse.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection