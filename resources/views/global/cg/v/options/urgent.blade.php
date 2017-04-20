<p>Prix de l'option: {{  \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsUrgent(true),'EUR',true) }} HT</p>
<p>Cette option permet :</p>
<ul>
    <li>d’afficher un logo « urgent » sur l’annonce</li>
    <li>de faire partie du critère de filtre global « urgent » dans la recherche de résultat</li>
</ul>
<p>Pour en bénéficier, il convient de la selectionner directement sur la page de création de l'Annonce en cochant
    l’option « URGENT ».
</p>
<p>
    Si l'Annonce est validée par le service de modération de {{ env('LEGAL_COMPAGNY_PSEUDONAME') }}, elle sera mise en
    ligne sur le Site Internet et les Applications Universelle iPhone/iPad et Android et sera signalée par un logo dans
    la liste de résultats.
</p>