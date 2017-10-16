<script>
    paceOptions = {
        document: false,
        eventLag: false,
        ajax: {
            ignoreURLs: [/^((?!destockeurope).)*$/]
        }
    };
</script>
<script src="{{ mix("js/pace.min.js") }}"></script>
<link rel="stylesheet" href="{{ mix("css/pace-theme.css") }}">