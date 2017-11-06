<script type="text/javascript">
    var tarteaucitronForceLanguage = '{{ \Illuminate\Support\Facades\App::getLocale() }}'; /* supported: fr, en, de, es, it, pt, pl, ru */
</script>
<script type="text/javascript" src="/tarteaucitron/tarteaucitron.js"></script>

<script type="text/javascript">
    tarteaucitron.init({
        "hashtag": "#tarteaucitron", /* Ouverture automatique du panel avec le hashtag */
        "highPrivacy": false, /* désactiver le consentement implicite (en naviguant) ? */
        "orientation": "bottom", /* le bandeau doit être en haut (top) ou en bas (bottom) ? */
        "adblocker": false, /* Afficher un message si un adblocker est détecté */
        "showAlertSmall": false, /* afficher le petit bandeau en bas à droite ? */
        "cookieslist": true, /* Afficher la liste des cookies installés ? */
        "removeCredit": false /* supprimer le lien vers la source ? */
    });

    window.fbAsyncInit = function() {
    FB.init({
        appId      : '{{ env('FACEBOOK_CLIENT_ID') }}',
        xfbml      : true,
        version    : 'v2.8'
    });
    };

    (tarteaucitron.job = tarteaucitron.job || []).push('facebook');

    @if(env('APP_ADSENSE')=='true')
        (tarteaucitron.job = tarteaucitron.job || []).push('adsense');
    @endif


    @if(env('APP_ANALYTICS') && (is_null(config('runtime.stopAnalytics')) || config('runtime.stopAnalytics')==false))
        tarteaucitron.user.analyticsUa = 'UA-98711623-1';
        tarteaucitron.user.analyticsMore = function () { /* add here your optionnal ga.push() */ };
        (tarteaucitron.job = tarteaucitron.job || []).push('analytics');
    @endif

</script>