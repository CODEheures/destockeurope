<script type="text/javascript">
    var geolocType = {{ $geolocType }};
    var zoomMap = {{ $zoomMap }};
    function initMap() {
        var map = new destock.destockMap(
                google,
                'map',
                zoomMap,
                'geoloc',
                geolocType,
                'mapInput',
                '{{ trans('strings.form_googlemap_marker') }}',
                '{{ trans('strings.form_googlemap_geoloc_fail') }}'
        );
        map.constructMap();
    }
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY') }}&libraries=places&language={{ \Illuminate\Support\Facades\App::getLocale() }}&callback=initMap">
</script>