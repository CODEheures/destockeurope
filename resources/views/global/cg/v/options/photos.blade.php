<p>Prix de l'option:</p>
<ul>
@for($i = config('runtime.nbFreePictures')+1 ; $i <= config('runtime.nbMaxPictures'); $i++)
        <li>annonce avec {{ $i }} photos: {{  \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostPictures($i),'EUR',true) }} HT</li>
@endfor
</ul>
<p>Cette option permet :</p>
<ul>
    <li>d’ajouter {{ config('runtime.nbMaxPictures')-config('runtime.nbFreePictures') }} photographies supplémentaires
        dans une Annonce en plus des {{ config('runtime.nbFreePictures') }} photo(s) gratuite(s) et
        ainsi de présenter au maximum {{ config('runtime.nbMaxPictures') }} photographies dans une Annonce.
    </li>
</ul>
<p>
    Pour en bénéficier, il convient de la selectionner directement sur la page de création de l'Annonce en cliquant le
    bouton « +Ajouter une photo ».
</p>
<p>
    Si l'Annonce est validée par le service de modération de {{ env('LEGAL_COMPAGNY_PSEUDONAME') }}, elle sera mise en
    ligne sur le Site Internet et les Applications Universelle iPhone/iPad et Android et sera signalée par un logo dans
    la liste de résultats.
</p>
<p>
    L'Annonceur reconnait et accepte qu'en raison de l'ergonomie des solutions de {{ env('LEGAL_COMPAGNY_PSEUDONAME') }},
    si la (les) photo(s) de l’annonceur ne correspond(ent) pas à l’adaptions des applications
    de {{ env('LEGAL_COMPAGNY_PSEUDONAME') }}, {{ env('LEGAL_COMPAGNY_PSEUDONAME') }} ne pourra être tenu responsable du
    non fonctionnement de (des) photo(s) de l’annonceur.
</p>
<p>
    {{ env('LEGAL_COMPAGNY_PSEUDONAME') }} n’est aucunement responsable d’une photo dont le ou les caractères seraient
    interdites par les lois Française ou Européen. {{ env('LEGAL_COMPAGNY_PSEUDONAME') }} se verrai dans l’obligation
    d’en référé aux autorités compétentes, sans avoir à contacter l’annonceur.
</p>