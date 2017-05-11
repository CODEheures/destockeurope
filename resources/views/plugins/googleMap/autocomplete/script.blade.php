<script type="text/javascript">
    function initAutocomplete() {
        window.destockMap = new DestockMap();
        window.destockMap.initAutocomplete('filter_location');
    }
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY') }}&libraries=places&language={{ \Illuminate\Support\Facades\App::getLocale() }}&callback=initAutocomplete">
</script>