<h3 class="ui header">Règles générales</h3>
<p>
    Le bénéfice de toute Commande (Frais d'Insertion, Option(s) Payante(s)), est personnel à l'Annonceur qui l'a effectuée
    et ne peut être cédé, transféré sans l'accord de {{ env('LEGAL_COMPAGNY_PSEUDONAME') }}. Aucun remboursement n'est
    possible après le début d'exécution de toute commande passée et validé par {{ env('LEGAL_COMPAGNY_PSEUDONAME') }}.
</p>

<h3 class="ui header">Moment de la commande, paiement et facturation</h3>
<p>Le paiement se fait par paypal ou par carte bancaire au moment du dépot de l'annonce</p>
<p>Les tarifs sont indiqués en euros et sont exprimés HT (hors taxes)</p>
<p>Les tarifs sont communiqués à l'Annonceur sur simple demande et sont disponibles dans les présentes
   <a href="{{ route('cgv') }}">Conditions Générales de Vente</a>.
</p>
<p>
    Le tarif des frais d'insertion, de renouvellement et de chaque Option payante est celui en vigueur au jour de la
    Commande par l'Annonceur.
    {{ env('LEGAL_COMPAGNY_PSEUDONAME') }} se réserve la possibilité de modifier ses tarifs à tout moment.
</p>
<p>Si l'Annonce est refusée par le service modération de {{ env('LEGAL_COMPAGNY_PSEUDONAME') }}, en raison de sa
    non-conformité aux règles de diffusion, le paiement par carte bancaire ou paypal sera annulé et l'Annonceur non
    débité. L'Annonceur en sera informé par email.</p>
<p>Si l'Annonce est publiée par le service modération de {{ env('LEGAL_COMPAGNY_PSEUDONAME') }}, le paiement sera
    capturé et l'annonce sera mise en ligne sur le Site Internet.</p>
<p>
    Aucun remboursement n'est possible après exécution de la prestation soit validation par le service modération de
    {{ env('LEGAL_COMPAGNY_PSEUDONAME') }}.
</p>
<p>
    Le montant de la facture, exprimé en euro hors taxes, sera majoré de celui de la TVA et/ou de toute autre taxe à la
    charge de l'Annonceur, au taux en vigueur à la date de parution.
</p>
<p>
    Une facture est automatiquement envoyée par mail à l'annonceur après validation de l'annonce par le service
    modération de {{ env('LEGAL_COMPAGNY_PSEUDONAME') }}.
</p>
