<script>
    paceOptions = {
        document: false,
        eventLag: false,
        elements : {
            selectors: ['#paceforcing']
        },
        ajax: {
            ignoreURLs: [/^((?!destockeurope).)*$/]
        }
    };
</script>
<script src="{{ mix("js/pace.min.js") }}"></script>
<script>
    window.onbeforeunload = function () {
        let paceForcing = document.getElementById('paceforcing');
        paceForcing !== null ? paceForcing.remove() : null;
        Pace.restart();
    }
</script>
<link rel="stylesheet" href="{{ mix("css/pace-theme.css") }}">