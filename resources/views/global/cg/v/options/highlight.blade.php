<p>Prix pour placer une annonce en Une:
    <br />Le prix est variable. Il est fixé au moment de la demande. Il dépend du nombre d'annonce déjà à la Une [notées 'nbU'].
    <br />La formule de calcul est la suivante: {{ config('runtime.highlightCost') }}/<span style="font-size: large">&Sqrt;</span>nbU.
    <br />Examples:
</p>
<ul>
    <?php $cases = [0,1,2,3,5,10,20] ?>
    @foreach($cases as $case)
    <li>nbU={{ $case }} => prix = {{ \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsHighlight(true, $case),'EUR', true) }} HT</li>
    @endforeach
</ul>
<p>
    La mise à la Une permet de placer l'annonce dans une seconde liste à coté des resultats de recherche.
    Cette seconde liste est composée de {{ env('HIGHLIGHT_QUANTITY') }} emplacements.
    L'annonce etant en concurrence avec les autres annonces à la Une déjà présentes, celle-ci sera choisie et placée
    de manière aléatoire durant la durée de vie de l'option.
    La durée de vie de cette option est fixée à {{ env('HIGHLIGHT_HOURS_DURATION') }} heures.
    Cette option ne garantie pas le nombre d'affichage à la Une.
</p>
