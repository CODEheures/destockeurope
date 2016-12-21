<script type="text/javascript">
    function initAutocomplete() {
        let autocomplete = new destock.destockAutocomplete(
                google,
                'filter_location'
        );
        autocomplete.initAutocomplete();
    }
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY') }}&libraries=places&language={{ \Illuminate\Support\Facades\App::getLocale() }}&callback=initAutocomplete">
</script>