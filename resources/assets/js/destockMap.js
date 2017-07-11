let DestockMap = function ()  {
    let _geocoder;
    let _elemMap;
    let _geoloc;
    let _input;
    let _zoomMap;
    let _geolocType;
    let _markerMsg;
    let _errorGeoCodeMsg;
    let _routeGetGeoByIp;
    let _position;
    let _marker;
    let _options;
    let _map;

    this.constructMap = function(idMap, zoomMap, idGeoLoc, geolocType, idMapInput, markerMsg, errorGeoCodeMsg,routeGetGeoByIp) {
        _geocoder = new google.maps.Geocoder;
        _elemMap = document.getElementById(idMap);
        _geoloc = document.getElementById(idGeoLoc);
        _input = document.getElementById(idMapInput);
        _zoomMap = zoomMap;
        _geolocType = geolocType;
        _markerMsg = markerMsg;
        _errorGeoCodeMsg = errorGeoCodeMsg;
        _routeGetGeoByIp = routeGetGeoByIp;
        if (_elemMap != undefined && _geoloc != undefined) {
            this.initLocation();
        }
    };

    this.initLocation = function() {
        if (sessionStorage.getItem('lat') != undefined && sessionStorage.getItem('lng') != undefined && sessionStorage.getItem('lat') != '' && sessionStorage.getItem('lng') != '') {
            let position = {
                coords: {
                    latitude: sessionStorage.getItem('lat'),
                    longitude: sessionStorage.getItem('lng')
                }
            };
            this.latLng(position);
        } else if (navigator.geolocation) {
            //_.bindAll(this, 'latLng', 'latLngByIp');

            let success = this.latLng.apply(this);
            let error = this.latLngByIp.apply(this);

            let successCallBack = function () { return success };
            let errorCallBack = function () { return error };

            navigator.geolocation.getCurrentPosition(successCallBack, errorCallBack, {
                enableHighAccuracy: true,
                timeout: 60 * 1000
            });
        } else {
            this.latLngByIp();
        }
    };
    this.latLngByIp= function() {
        let that = this;

        axios.get(_routeGetGeoByIp)
            .then(function (response)  {
                let loc = response.data.loc.split(',');
                let position = {
                    coords: {
                        latitude: loc[0],
                        longitude: loc[1]
                    }
                };
                that.latLng(position);
            })
            .catch(function (error)  {
                let position = {
                    coords: {
                        latitude: 0,
                        longitude: 0
                    }
                };
                that.latLng(position);
            });
    };
    this.latLng= function(position) {
        _position = position;
        let that = this;

        if (_position != undefined) {
            let myLatlng = new google.maps.LatLng(_position.coords.latitude, _position.coords.longitude);
            let mapOptions = {
                zoom: parseInt(_zoomMap),
                center: myLatlng,
                mapTypeControl: false,
                streetViewControl: false,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            let map = new google.maps.Map(_elemMap, mapOptions);
            _map = map;

            _marker = new google.maps.Marker({
                map: map,
                animation: google.maps.Animation.DROP,
                title: _markerMsg,
                draggable: true,
                position: myLatlng
            });

            this.geoCode();


            _marker.addListener('mousedown', function () {
                that.toggleBounce();
            });
            _marker.addListener('mouseup', function () {
                that.toggleBounce();
                that.geoCode();
            });

            let autocomplete = new google.maps.places.Autocomplete(_input);
            autocomplete.bindTo('bounds', map);
            autocomplete.addListener('place_changed', function () {
                let place = autocomplete.getPlace();
                if (place.geometry != undefined) {
                    map.setCenter(place.geometry.location);
                    sessionStorage.setItem('searchPlace', JSON.stringify(place.address_components));
                    _marker.setPosition(place.geometry.location);
                }
                that.geoCode();
            });
        }
    };
    this.toggleBounce= function() {
        if (_marker.getAnimation() !== null) {
            _marker.setAnimation(null);
        } else {
            _marker.setAnimation(google.maps.Animation.BOUNCE);
        }
    };
    this.geoCode= function() {
        let icon = $(_geoloc).find('i');
        let classIcon = $(icon).attr('class');
        $(icon).attr('class', 'notched circle loading icon');

        _geocoder.geocode({'location': _marker.getPosition().toJSON()}, function (results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                sessionStorage.setItem('geoloc', JSON.stringify(results));
                if (results[parseInt(_geolocType)]) {
                    let header = $(_geoloc).find('.header');
                    header.html(results[parseInt(_geolocType)].formatted_address);
                    _geoloc.dataset.lat = _marker.getPosition().lat();
                    _geoloc.dataset.lng = _marker.getPosition().lng();
                    _geoloc.dataset.geoloc = results[parseInt(_geolocType)].formatted_address;
                    $(icon).attr('class', classIcon);
                    if ("createEvent" in document) {
                        let evt = document.createEvent("HTMLEvents");
                        evt.initEvent("geochange", false, true);
                        _geoloc.dispatchEvent(evt);
                    } else {
                        _geoloc.fireEvent("ongeochange");
                    }
                } else {
                    _geoloc.innerHTML = _errorGeoCodeMsg;
                }
            } else {
                _geoloc.innerHTML = _errorGeoCodeMsg + ' ' + status;
            }
        });
    };
    this.initAutocomplete= function(idMapInput) {
        _input = document.getElementById(idMapInput);
        _options = {
            types: ['(regions)']
        };
        let autocomplete = new google.maps.places.Autocomplete(_input, _options);

        autocomplete.addListener('place_changed', function() {
            let place = autocomplete.getPlace();
            if(place.geometry != undefined) {
                sessionStorage.setItem('autoCompleteResult', JSON.stringify(place.address_components));
                if ("createEvent" in document) {
                    let evt = document.createEvent("HTMLEvents");
                    evt.initEvent("autocompletechange", false, true);
                    _input.dispatchEvent(evt);
                } else {
                    _input.fireEvent("autocompletechange");
                }
            }
        });
    };
    this.resize=function () {
        let center = _map.getCenter();
        google.maps.event.trigger(_elemMap, 'resize');
        _map.setCenter(center);
    }
};


module.exports = DestockMap;