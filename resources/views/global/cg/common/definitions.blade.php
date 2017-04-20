<ul>
    <li>Annonceur : Toute personne physique majeure ou morale déposant une Annonce via le
        service {{ env('LEGAL_COMPAGNY_PSEUDONAME') }}.</li>
    <li>Annonce : Composition des textes, images et vidéos publiés par l'annonceur via la
        service {{ env('LEGAL_COMPAGNY_PSEUDONAME') }}.</li>
    <li>Compte Pro: Espace sécurisé gratuit d'un annonceur permettant l'accès aux données
        personnelles et ouvrant droit à la mise en ligne gratuite ou payante d'annonces</li>
    <li>Site Internet: désigne le site internet exploité par
        {{ env('LEGAL_COMPAGNY_PSEUDONAME') }}, notament toutes les URL du nom de domaine
        {!! substr (Request::root(), 7) !!}
    </li>
</ul>