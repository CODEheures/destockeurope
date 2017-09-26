<script>
    window.addEventListener("beforeunload", function (event) {
        let paceForcing = document.getElementById('paceforcing');
        paceForcing !== null ? paceForcing.remove() : null;
        Pace.restart();
        return null;
    });
</script>