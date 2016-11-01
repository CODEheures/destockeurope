<script type="text/javascript">
    function initMap() {
        var map = new destock.destockMap(
                google,
                'map',
                'geoloc',
                '{{ $ip }}',
                '{{ trans('strings.form_googlemap_marker') }}',
                '{{ trans('strings.form_googlemap_geoloc_fail') }}'
        );
        map.constructMap();
    }
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY') }}&language={{ \Illuminate\Support\Facades\App::getLocale() }}&callback=initMap">
</script>