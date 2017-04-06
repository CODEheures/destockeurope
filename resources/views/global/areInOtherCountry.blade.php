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
                            <h1>Vous êtes à l'étranger</h1>
                        </div>
                        {{--<div class="ui segment">--}}
                            {{--<h1>Conditions Générales d'utilisation</h1>--}}
                            {{--<div class="cgv">--}}
                                {{--<h2 class="fa"><i class="fa fa-square-o"></i> Article 1: Contrat de vente</h2>--}}
                                {{--<ol>--}}
                                    {{--<li>--}}
                                        {{--Le prestataire DestockEurope (DestockEurope SAS) désigne la personne physique qui propose ses services au--}}
                                        {{--client contre rémunérations.<br/>--}}
                                        {{--Le client désigne la personne physique et morale qui bénéficie des services du prestataire.--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--Le client faisant appel aux services de DestockEurope reconnaît avoir pris connaissance et accepter--}}
                                        {{--sans réserve les conditions générales de vente suivantes, ainsi que les mises en garde concernant les--}}
                                        {{--lois de la propriété intellectuelle. L'acceptation du devis ou l'achat direct de crédit(s) horaire--}}
                                        {{--sont soumis à l'acceptation des présentes conditions.--}}
                                    {{--</li>--}}
                                {{--</ol>--}}
                                {{--<h2 class="fa"><i class="fa fa-square-o"></i> Article 2: Types de prestations</h2>--}}
                                {{--<ol>--}}
                                    {{--<li>--}}
                                        {{--Les prestations standards:<br/>--}}
                                        {{--Ce sont toutes les prestations listées dans le document des prestations standards (disponible à--}}
                                        {{--l'adresse: <a--}}
                                                {{--href="#">https://destockeurope.com</a>).--}}
                                        {{--Si votre besoin correspond à l'un des éléments de cette liste, aucun devis n'est établi et DestockEurope--}}
                                        {{--réalise la prestation sur simple demande par mail en débitant le crédit horaire de votre compte au--}}
                                        {{--maximum de la valeur indiquée sur cette liste et au prorata du nombre de prestations demandées.--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--Les prestations sur devis: <br/>--}}
                                        {{--Si votre besoin ne correspond à aucune des prestations standards, CODEheure établi un devis. Ce devis mentionnera:--}}
                                        {{--<ul>--}}
                                            {{--<li>--}}
                                                {{--La désignation de la prestation et son tarif.--}}
                                            {{--</li>--}}
                                            {{--<li>--}}
                                                {{--Un document de référence joint détaillant la prestation, les achats effectués par DestockEurope au nom du client, les achats--}}
                                                {{--que le client doit faire lui-même, les délais, les technologies choisies, et tout autre information jugées utile--}}
                                            {{--</li>--}}
                                        {{--</ul>--}}
                                    {{--</li>--}}
                                {{--</ol>--}}
                                {{--<h2 class="fa"><i class="fa fa-square-o"></i> Article 3: Fonctionnement des comptes clients</h2>--}}
                                {{--<ol>--}}
                                    {{--<li>--}}
                                        {{--Tous les clients possedent un compte personnel sur CODEheure.fr.--}}
                                        {{--<ul>--}}
                                            {{--<li>--}}
                                                {{--Chaque compte indique le solde au crédit du client dont l'unité est en heures (h) et dont la--}}
                                                {{--décimale la plus fine est le centième d'heure (0,01h).--}}
                                            {{--</li>--}}
                                            {{--<li>--}}
                                                {{--Chaque compte indique aussi la liste des achats logiciels externes (thèmes, plugins, modules--}}
                                                {{--etc...)--}}
                                                {{--ou les achats de services (location de serveur, nom de domaine etc...) ou la sous-traitance--}}
                                                {{--(graphiste etc...) payé sur devis par le client à DestockEurope--}}
                                            {{--</li>--}}
                                        {{--</ul>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--Le solde client peut évoluer à la hausse par 2 moyens:--}}
                                        {{--<ul>--}}
                                            {{--<li>--}}
                                                {{--Un achat volontaire d'heures à l'unité ou par lot. Cet achat est directement à faire en ligne,--}}
                                                {{--à partir d'un lien nommé "Recharger mon compte" fournit dans le compte client à la rubrique--}}
                                                {{--"Mon suivi".--}}
                                            {{--</li>--}}
                                            {{--<li>La signature d'un devis mentionnant un crédit d'heures</li>--}}
                                        {{--</ul>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--Le solde client peut évoluer à la baisse de 2 manières:--}}
                                        {{--<ul>--}}
                                            {{--<li>--}}
                                                {{--DestockEurope utilise le crédit pour réaliser la liste des prestations indiquées dans le devis. Dans ce cas, DestockEurope--}}
                                                {{--ne débitera jamais plus que les heures indiquées dans le devis. Si un solde positif advient à l’émission de la facture--}}
                                                {{--finale le client possèdera alors un solde qu'il pourra utiliser ultèrieurement pour d'autres projets ou prestations standards.--}}
                                            {{--</li>--}}
                                            {{--<li>--}}
                                                {{--DestockEurope utilise le credit pour répondre à une prestation standard. Dans ce cas, DestockEurope--}}
                                                {{--ne débitera jamais plus qu'indiqué sur la grille des prestations standards (voir 2.1).--}}
                                            {{--</li>--}}
                                        {{--</ul>--}}
                                    {{--</li>--}}
                                {{--</ol>--}}
                                {{--<h2 class="fa"><i class="fa fa-square-o"></i> Article 4: Cahier des charges</h2>--}}
                                {{--<ol>--}}
                                    {{--<li>--}}
                                        {{--Si le client fournit un cahier des charges pour la prestation de service, le devis sera effectué en--}}
                                        {{--fonction--}}
                                        {{--de celui-ci. Le prestataire se conforme au cahier des charges pour la réalisation de la prestation.--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--Si aucun cahier des charges n'est fourni avant le début de la commande, ou si il ne comporte pas assez--}}
                                        {{--d'éléments explicites pour sa réalisation, la prestation est laissée à la libre interprétation du--}}
                                        {{--prestataire--}}
                                        {{--DestockEurope.--}}
                                        {{--Si toutefois dans ce cas de figure, le client en refuse l'interprétation et la conception, la prestation--}}
                                        {{--effectuée sera due par le client et toute nouvelle prestation fera l'objet d'un nouveau devis.--}}
                                    {{--</li>--}}
                                {{--</ol>--}}
                                {{--<h2 class="fa"><i class="fa fa-square-o"></i> Article 5: &Eacute;volution de la liste des prestations standards</h2>--}}
                                {{--<ol>--}}
                                    {{--<li>--}}
                                        {{--La liste des prestations standards (disponible à l'adresse: <a--}}
                                                {{--href="#">https://destockeurope.com</a>)--}}
                                        {{--est destinée à améliorer la transparence entre le prestataire et le client. Elle favorise la--}}
                                        {{--compréhension--}}
                                        {{--des investissements effectués par le client sur son site ou son application et permet d'éviter de longs--}}
                                        {{--échanges de devis entre prestataire et client, améliorant fortement la réactivité du prestataire dans la--}}
                                        {{--réponse au besoin du client. De ce fait cette liste est appelée à évoluer.--}}
                                        {{--Les clients seront prévenus des éventuelles évolutions par mail et seront invités à venir consulter cette nouvelle--}}
                                        {{--liste. <strong>Si toutefois, une quelconque évolution de celle-ci ne convient pas, alors le client peut--}}
                                            {{--demander--}}
                                            {{--sans condition le remboursement intégral du solde de son compte au prix d'achat de--}}
                                            {{--celui-ci.</strong>--}}
                                    {{--</li>--}}
                                {{--</ol>--}}
                                {{--<h2 class="fa"><i class="fa fa-square-o"></i> Article 6: Tarifs et prestations</h2>--}}
                                {{--<ol>--}}
                                    {{--<li>--}}
                                        {{--DestockEurope se réserve le droit de modifier les prix de ses tarifs de crédit d'heure à l'unité ou par lot--}}
                                        {{--disponible en achat direct par un lien nommé "Recharger mon compte" dans la rubrique "Mon suivi" de--}}
                                        {{--l'espace client. Un prix obtenu à un jour donné par un client donné ne peut être exigé un autre jour--}}
                                        {{--et/ou par un client différent.--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--DestockEurope se réserve le droit de modifier les prix des crédits d'heures émis dans ses devis,--}}
                                        {{--en fonction du projet de chaque client. Un prix effectué pour un crédit d'heures sur un précédent devis--}}
                                        {{--aux mêmes clients ou à un client différent ne peut être exigé.--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--Les prix stipulés sur le devis sont valables un mois à partir de la date d'émission de celui-ci. Ceux-ci--}}
                                        {{--restent fermes et non révisables à la commande si celle-ci intervient durant ce délai.--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--Toute prestation non standard ne figurant pas sur la commande fera l'objet d'un nouveau devis.--}}
                                    {{--</li>--}}
                                {{--</ol>--}}
                                {{--<h2 class="fa"><i class="fa fa-square-o"></i> Article 7: Délai de paiement</h2>--}}
                                {{--<ol>--}}
                                    {{--<li>--}}
                                        {{--Sauf modification du délai de livraison en commun accord entre les deux parties, le solde du règlement--}}
                                        {{--de la facture doit se faire, au plus tard, 30 jours à compter de la date figurant sur ladite facture.--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--Tout retard de paiement entrainera, à titre de pénalité de retard, l'application d'un intérêt égal à--}}
                                        {{--celui appliqué par la Banque Centrale Européenne à son opération de refinancement la plus récente,--}}
                                        {{--majoré de 10 points de pourcentage. (Soit 10,25 % (0,25 + 10) depuis le 13 novembre 2013). Une indemnité--}}
                                        {{--forfaitaire de 40 € est due au créancier pour frais de recouvrement, à l'occasion de tout retard de--}}
                                        {{--paiement (L. 441-6, I, 12).--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--Le paiement sera effectué par chèque ou virement bancaire à l'adresse énoncée sur la facture.--}}
                                    {{--</li>--}}
                                {{--</ol>--}}
                                {{--<h2 class="fa"><i class="fa fa-square-o"></i> Article 8: Délai de livraison</h2>--}}
                                {{--<ol>--}}
                                    {{--<li>--}}
                                        {{--Les délais de réalisations dépendant de l’efficacité des organismes sollicités ainsi que de la bonne volonté--}}
                                        {{--du client ne sont donnés qu’à titre indicatif. Aucune pénalité de retard ne peut être exigée par le client.--}}
                                    {{--</li>--}}
                                {{--</ol>--}}
                                {{--<h2 class="fa"><i class="fa fa-square-o"></i> Article 9: Modification de commande</h2>--}}
                                {{--<ol>--}}
                                    {{--<li>--}}
                                        {{--Si le client souhaite une modification de sa commande, celui-ci s'engage à indiquer de façon claire et--}}
                                        {{--précise par email ou courrier les modifications qu'il souhaite pour sa commande.--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--Prise en charge de la modification: un nouveau devis est emis et le fonctionnement est le même que pour--}}
                                        {{--un devis initial.--}}
                                    {{--</li>--}}
                                {{--</ol>--}}
                                {{--<h2 class="fa"><i class="fa fa-square-o"></i> Article 10: Propriété intellectuelle</h2>--}}
                                {{--<ol>--}}
                                    {{--<li>--}}
                                        {{--Toutes créations et réalisations demeure la propriété entière et exclusive du prestataire DestockEurope--}}
                                        {{--tant que la totalité des factures émises par le prestataire n'ont pas été honorées. Ceci inclut les--}}
                                        {{--avenants au contrat, les retards de paiement et autres frais induits par ladite commande.--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--Le client devra s'acquitter du règlement de la facture de la commande pour devenir propriétaire des--}}
                                        {{--droits de diffusions cédés par le prestataire et pour la durée illimitée.--}}
                                    {{--</li>--}}
                                {{--</ol>--}}
                                {{--<h2 class="fa"><i class="fa fa-square-o"></i> Article 11: Cessions de droits</h2>--}}
                                {{--<ol>--}}
                                    {{--<li>--}}
                                        {{--Selon l'article L.121-1 à L.121-9 du Code français de la propriété intellectuelle, il est défini que le--}}
                                        {{--droit moral d'une création (comprenant droit de divulgation, droit au respect de l'œuvre et droit au--}}
                                        {{--retrait) est attaché à son créateur de manière perpétuelle et imprescriptible.--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--Ainsi ne sont cédés au diffuseur que les droits patrimoniaux correspondant à l'intitulé défini par le--}}
                                        {{--champ «cession de droits de représentation et de reproduction» et énoncés sur le présent contrat.--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--Selon l'article L.121-1: Toute représentation ou reproduction intégrale ou partielle faite sans le--}}
                                        {{--consentement de l'auteur ou de ses ayants droit ou ayants cause est illicite. Il en est de même pour la--}}
                                        {{--traduction, l'adaptation ou la transformation, l'arrangement ou la reproduction par un art ou un procédé--}}
                                        {{--quelconque.--}}
                                    {{--</li>--}}
                                {{--</ol>--}}
                                {{--<h2 class="fa"><i class="fa fa-square-o"></i> Article 12: Mentions commerciales et droit de publicité</h2>--}}
                                {{--<ol>--}}
                                    {{--<li>--}}
                                        {{--Le prestataire se réserve le droit d'ajouter un lien commercial DestockEurope sur les sites web créés.--}}
                                    {{--</li>--}}
                                {{--</ol>--}}
                                {{--<h2 class="fa"><i class="fa fa-square-o"></i> Article 13: Litiges</h2>--}}
                                {{--<ol>--}}
                                    {{--<li>--}}
                                        {{--La responsabilité du prestataire ne pourra être mise en cause si la non-exécution de la--}}
                                        {{--prestation découle d'un cas de force majeure.--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--Tout litige relatif à l'interprétation et à l'exécution des présentes conditions générales de vente est--}}
                                        {{--soumis au droit français. &Agrave; défaut de conciliation amiable, le litige pourra être porté devant un--}}
                                        {{--tribunal compétent.--}}
                                    {{--</li>--}}
                                {{--</ol>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection