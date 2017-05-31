<p>Prix pour editer une annonce: {{  \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsEdit(true),'EUR',true) }} HT</p>
<p>
    Cette option permet aux annonceurs d'éditer leurs annonces pour les champs "titre", "description", "catégorie",
    "prix", "lieu", "photo", "vidéo".
    <br />L'édition des champs "quantitée totale" et "lot minimal de vente" sont GRATUITS.
</p>
<p>
    Une annonce n'est éligible à l'édition que si sa durée de publication restante est supérieure à
    {{ env('REMAINING_HOURS_FOR_EDIT_ELIGIBILITY') }} heures
    <br />Toutes les options déjà présentes restent acquises.
    <br />Par exemple, si l'annonce éditée contient
    {{ config('runtime.nbFreePictures')+1 }} photos (donc 1 payante), alors l'annonceur peut remplacer ces
    {{ config('runtime.nbFreePictures')+1 }} photos sans coût supplémentaire à celui de cette présente option d'édition.
    Si celui-ci souhaite en rajouter 1 et passer son annonce à {{ config('runtime.nbFreePictures')+2 }} photos, alors
    le tarif applicable aux photos supplémentaires sera appliqué à cette dernière.
    <br />Le fait de supprimer une option pendant l'édition rendra cette suppression définitive après validation des
    modifications.
</p>
