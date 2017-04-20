<p>Prix du renouvellement d'une annonce : {{  \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsRenew(true),'EUR',true) }} HT</p>
<p>Le renouvellement d'une annonce duplique l'annonce en cours avec toutes ses options et publie cette duplication pour
    une durée de {{ env('ADVERT_LIFE_TIME') }} jours à compter de la date de fin de publication de l'annonce dupliquée</p>
