exports.destockMap= function (google, idMap, zoomMap, idGeoLoc, geolocType, idMapInput, ip, markerMsg, errorGeoCodeMsg) {
    this.geocoder = new google.maps.Geocoder;
    this.elemMap = document.getElementById(idMap);
    this.geoloc = document.getElementById(idGeoLoc);
    this.input = document.getElementById(idMapInput);
    var that = this;

    this.constructMap= function () {
        if(this.elemMap != undefined && this.geoloc != undefined && ip != undefined){
            this.getInitLocation();
        }
    };
    this.setMap = function () {
        if(this.position != undefined){
            this.myLatlng = new google.maps.LatLng(this.position.coords.latitude, this.position.coords.longitude);
            this.mapOptions = {
                zoom: parseInt(zoomMap),
                center: this.myLatlng,
                mapTypeControl: false,
                streetViewControl: false,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            this.map = new google.maps.Map(this.elemMap, this.mapOptions);
            this.marker = new google.maps.Marker({
                map: this.map,
                animation: google.maps.Animation.DROP,
                title: markerMsg,
                draggable: true,
                position: this.myLatlng
            });

            that.setGeoCode();
            this.marker.addListener('mousedown', function() {
                that.toggleBounce();
            });
            this.marker.addListener('mouseup', function() {
                that.toggleBounce();
                that.setGeoCode();
            });

            let autocomplete = new google.maps.places.Autocomplete(this.input);
            autocomplete.bindTo('bounds', this.map);
            autocomplete.addListener('place_changed', function() {
                let place = autocomplete.getPlace();
                if(place.geometry != undefined) {
                    that.map.setCenter(place.geometry.location);
                    that.marker.setPosition(place.geometry.location);
                }
                that.setGeoCode();
            });
        }
    };
    this.getInitLocation= function () {
        if(sessionStorage.getItem('lat')!=undefined && sessionStorage.getItem('lng')!=undefined && sessionStorage.getItem('lat')!='' && sessionStorage.getItem('lng')!=''){
            let position =  {
                coords: {
                    latitude: sessionStorage.getItem('lat'),
                    longitude: sessionStorage.getItem('lng')
                }
            };
            that.setMyLatLng(position);
        } else if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(this.setMyLatLng, this.setMyLatLngByIp, {enableHighAccuracy:true, timeout:60*1000});
        } else {
            that.setMyLatLngByIp();
        }
    };
    this.setMyLatLngByIp= function () {
        $.getJSON("http://ip-api.com/json/"+ip, function(data) {
            let position =  {
                coords: {
                    latitude: data.lat,
                    longitude: data.lon
                }
            };
            that.setMyLatLng(position);
        });
    };
    this.setMyLatLng= function (position) {
        that.position = position;
        that.setMap();
    };
    this.toggleBounce= function () {
        if (this.marker.getAnimation() !== null) {
            this.marker.setAnimation(null);
        } else {
            this.marker.setAnimation(google.maps.Animation.BOUNCE);
        }
    };
    this.setGeoCode= function () {
        let icon = $(that.geoloc).find('i');
        let classIcon = $(icon).attr('class');
        $(icon).attr('class', 'notched circle loading icon');
        this.geocoder.geocode({'location': this.marker.getPosition().toJSON()}, function(results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                sessionStorage.setItem('geoloc', JSON.stringify(results));
                if (results[parseInt(geolocType)]) {
                    let header = $(that.geoloc).find('.header');
                    header.html(results[parseInt(geolocType)].formatted_address);
                    that.geoloc.dataset.lat = that.marker.getPosition().lat();
                    that.geoloc.dataset.lng = that.marker.getPosition().lng();
                    that.geoloc.dataset.geoloc = results[parseInt(geolocType)].formatted_address;
                    $(icon).attr('class', classIcon);
                    if ("createEvent" in document) {
                        let evt = document.createEvent("HTMLEvents");
                        evt.initEvent("geochange", false, true);
                        that.geoloc.dispatchEvent(evt);
                    } else {
                        that.geoloc.fireEvent("ongeochange");
                    }
                } else {
                    that.geoloc.innerHTML = errorGeoCodeMsg;
                }
            } else {
                that.geoloc.innerHTML = errorGeoCodeMsg + ' ' + status;
            }
        });
    };
};