<script type="text/javascript">
    window.paramsMap = {
      idMap: 'map',
      zoomMap: '{!! $zoomMap !!}',
      idGeoLoc: 'geoloc',
      geolocType: '{!! $geolocType !!}',
      idMapInput: 'mapInput',
      markerMsg: '{{ trans('strings.form_googlemap_marker') }}',
      errorGeoCodeMsg: '{{ trans('strings.form_googlemap_geoloc_fail') }}',
      routeGetGeoByIp: '{{ route(config('codeheuresUtils.geoIp.routes.geoByIp.name')) }}'
    }

</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY') }}&libraries=places&language={{ \Illuminate\Support\Facades\App::getLocale() }}&callback=initMap">
</script>