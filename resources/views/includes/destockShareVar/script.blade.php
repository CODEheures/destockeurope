<script>
    window.destockShareVar={
        'start': {
            'mix': {
              'manifest': '{{ mix("js/manifest.js") }}',
              'vendor': '{{ mix("js/vendor.js") }}',
              'app': '{{ mix("js/app.js") }}'
            }
        },
        'otherScriptsToLoad': [],
        'vueJsDevTool' : '{{(bool)(env('APP_DEBUG'))}}',
        'firebase': {
            'config': {messagingSenderId: '{{ env('GOOGLE_FIREBASE_MESSAGINGSENDERID') }}'},
            'token': undefined
        }
    };
</script>