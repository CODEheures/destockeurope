<p>Prix du renouvellement d'une annonce : {{  \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsRenew(true),'EUR',true) }} HT</p>
<p>
    Le renouvellement d'une annonce prolonge celle-ci pour une durée de {{ env('ADVERT_LIFE_TIME') }} jours.
    Votre annonce est automatiquement et GRATUITEMENT remise en tête de liste avec cette option.
</p>
