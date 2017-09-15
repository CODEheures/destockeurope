<script>
    window.destockShareVar={
        'vueJsDevTool' : '{{(bool)(env('APP_DEBUG'))}}',
        'firebase': {
            'config': {messagingSenderId: '{{ env('GOOGLE_FIREBASE_MESSAGINGSENDERID') }}'},
            'token': undefined
        }
    };
</script>