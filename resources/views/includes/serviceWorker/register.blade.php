<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js').then(function(reg) {
            if(reg.installing) {} else if(reg.waiting) {} else if(reg.active) {}
        });
    } else {}
</script>