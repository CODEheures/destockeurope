<script type="text/javascript">
    {!! 'let geolocType =  ' . $geolocType .';'!!}
    {!! 'let zoomMap =  ' . $zoomMap .';'!!}
    function initMap() {
        let destockMap = new DestockMap();
        destockMap.constructMap(
                'map',
                zoomMap,
                'geoloc',
                geolocType,
                'mapInput',
                '{{ trans('strings.form_googlemap_marker') }}',
                '{{ trans('strings.form_googlemap_geoloc_fail') }}'
        );
    }
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY') }}&libraries=places&language={{ \Illuminate\Support\Facades\App::getLocale() }}&callback=initMap">
</script>