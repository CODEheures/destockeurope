<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js?v=18').then(function(reg) {
            if(reg.installing) {} else if(reg.waiting) {} else if(reg.active) {}
        });
    } else {}
</script>