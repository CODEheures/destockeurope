<h4 class="ui header">Composition des tarifs</h4>
<p>
    Les tarifs appliqués à la mise en ligne d'une nouvelle annonce sont composés de la somme:
</p>
<ul>
    <li>Des frais d'insertion: ce sont les frais appliqués à chaque annonce lors de la mise en ligne quelque soit
        la catégorie et les options payantes choisies</li>
    <li>Des options payantes: ce sont des options « Vidéo », «photos supplémentaire», « Urgent », « à Négocier »</li>
</ul>
<p>
    Le tarif appliqué au renouvellement d'une annonce est fixe et ne dépend pas des options choisies à la première
    mise en ligne de l'annonce
</p>

<h4 class="ui header">Frais d'insertion</h4>
<p>A ce jour les frais d'insertion sont offerts.</p>

<h4 class="ui header">Les Options Payantes</h4>
<p>Les options payantes sont souscrites au moment du depot de l'Annonce et pour la durée de diffusion de l'Annonce
    soit pour une durée maximum de {{ env('ADVERT_LIFE_TIME') }} jours
</p>
<p>Une option payante n'est souscrite que pour une seule Annonce. En conséquence, il n'est pas possible de transférer le
    bénéfice d'une option payante d'une Annonce à une autre.
</p>
<p>Il est possible de souscrire à plusieurs options payantes pour une même Annonce.</p>
<p>Le prix de chaque option payante varie en fonction de ladite option.</p>
<p>La souscription d'une option payante ne proroge pas la durée de l'Annonce.</p>
<p>Lors du retrait anticipé de l'Annonce (soit du fait de l'Annonceur, soit du fait
    de {{ env('LEGAL_COMPAGNY_PSEUDONAME') }} notamment en cas de non-respect des présentes CGU ou des Règles de
    diffusion sur le Service {{ env('LEGAL_COMPAGNY_PSEUDONAME') }}) ou à l'expiration de sa durée de diffusion,
    l'Option Payante cesse de produire ses effets.
</p>
<p>Le renouvellement d'une Annonce prolonge la durée de toutes ses options payantes.</p>
<p>
    L'Annonceur reconnait et accepte que toute Annonce déposée dans une catégorie ne correspondant pas au
    produit ou au service proposé puisse être supprimée à tout moment, par {{ env('LEGAL_COMPAGNY_PSEUDONAME') }} et ce
    sans indemnité ni droit à remboursement des sommes engagées aux fins de la souscription à des Options Payantes.
</p>

<h5 class="ui header">Ajouter l’option « Urgent »</h5>
@include('global.cg.v.options.urgent')

<h5 class="ui header">Ajouter l’option « à Négocier »</h5>
@include('global.cg.v.options.toNegociate')

<h5 class="ui header">Ajouter une option vidéo</h5>
@include('global.cg.v.options.video')


<h5 class="ui header">Ajouter l’option Photos supplémentaires</h5>
@include('global.cg.v.options.photos')

<h4 class="ui header">Renouvellement d'une annonce</h4>
@include('global.cg.v.options.renew')

<h4 class="ui header">Remontée en tête de liste d'une annonce</h4>
@include('global.cg.v.options.backToTop')