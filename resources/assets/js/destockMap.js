import axios from 'axios'

class DestockMap {
  constructor () {
    // eslint-disable-next-line new-parens
    this._geocoder = new window.google.maps.Geocoder
    this._elemMap = window.paramsMap.idMap !== undefined ? document.getElementById(window.paramsMap.idMap) : undefined
    this._geoloc = window.paramsMap.idGeoLoc !== undefined ? document.getElementById(window.paramsMap.idGeoLoc) : undefined
    this._input = window.paramsMap.idMapInput !== undefined ? document.getElementById(window.paramsMap.idMapInput) : undefined
    this._zoomMap = window.paramsMap.zoomMap !== undefined ? (window.paramsMap.zoomMap) : undefined
    this._geolocType = window.paramsMap.geolocType !== undefined ? parseInt(window.paramsMap.geolocType) : undefined
    this._markerMsg = window.paramsMap.markerMsg
    this._errorGeoCodeMsg = window.paramsMap.errorGeoCodeMsg
    this._routeGetGeoByIp = window.paramsMap.routeGetGeoByIp
    this._position = undefined
    this._marker = undefined
    this._options = undefined
    this._map = undefined
    if (this._elemMap !== undefined && this._geoloc !== undefined) {
      this.initLocation()
    }
  }

  initLocation () {
    if (sessionStorage.getItem('lat') !== undefined && sessionStorage.getItem('lng') !== undefined && sessionStorage.getItem('lat') !== '' && sessionStorage.getItem('lng') !== '') {
      let position = {
        coords: {
          latitude: sessionStorage.getItem('lat'),
          longitude: sessionStorage.getItem('lng')
        }
      }
      this.latLng(position)
    }
    else if (navigator.geolocation) {
      let success = this.latLng.apply(this)
      let error = this.latLngByIp.apply(this)

      let successCallBack = function () { return success }
      let errorCallBack = function () { return error }

      navigator.geolocation.getCurrentPosition(successCallBack, errorCallBack, {
        enableHighAccuracy: true,
        timeout: 60 * 1000
      })
    }
    else {
      this.latLngByIp()
    }
  }

  latLngByIp () {
    let that = this
    axios.get(this._routeGetGeoByIp)
      .then(function (response) {
        let loc = response.data.loc.split(',')
        let position = {
          coords: {
            latitude: loc[0],
            longitude: loc[1]
          }
        }
        that.latLng(position)
      })
      .catch(function () {
        let position = {
          coords: {
            latitude: 0,
            longitude: 0
          }
        }
        that.latLng(position)
      })
  }

  latLng (position) {
    this._position = position

    if (this._position !== undefined) {
      let myLatlng = new window.google.maps.LatLng(this._position.coords.latitude, this._position.coords.longitude)
      let mapOptions = {
        zoom: parseInt(this._zoomMap),
        center: myLatlng,
        mapTypeControl: false,
        streetViewControl: false,
        mapTypeId: window.google.maps.MapTypeId.ROADMAP
      }
      let map = new window.google.maps.Map(this._elemMap, mapOptions)
      this._map = map
      this._map.addListener('resize', function () { })

      this._marker = new window.google.maps.Marker({
        map: map,
        animation: window.google.maps.Animation.DROP,
        title: this._markerMsg,
        draggable: true,
        position: myLatlng
      })

      this.geoCode()

      this._marker.addListener('mousedown', () => {
        this.toggleBounce()
      })
      this._marker.addListener('mouseup', () => {
        this.toggleBounce()
        this.geoCode()
      })

      let autocomplete = new window.google.maps.places.Autocomplete(this._input)
      autocomplete.bindTo('bounds', map)
      autocomplete.addListener('place_changed', () => {
        let place = autocomplete.getPlace()
        if (place.geometry !== undefined) {
          map.setCenter(place.geometry.location)
          sessionStorage.setItem('searchPlace', JSON.stringify(place.address_components))
          this._marker.setPosition(place.geometry.location)
        }
        this.geoCode()
      })
    }
  }

  toggleBounce () {
    if (this._marker.getAnimation() !== null) {
      this._marker.setAnimation(null)
    }
    else {
      this._marker.setAnimation(window.google.maps.Animation.BOUNCE)
    }
  }

  geoCode () {
    let icon = $(this._geoloc).find('i')
    let classIcon = $(icon).attr('class')
    $(icon).attr('class', 'notched circle loading icon')

    this._geocoder.geocode({'location': this._marker.getPosition().toJSON()}, (results, status) => {
      if (status === window.google.maps.GeocoderStatus.OK) {
        sessionStorage.setItem('geoloc', JSON.stringify(results))
        if (results[parseInt(this._geolocType)]) {
          let header = $(this._geoloc).find('.header')
          header.html(results[parseInt(this._geolocType)].formatted_address)
          this._geoloc.dataset.lat = this._marker.getPosition().lat()
          this._geoloc.dataset.lng = this._marker.getPosition().lng()
          this._geoloc.dataset.geoloc = results[parseInt(this._geolocType)].formatted_address
          $(icon).attr('class', classIcon)
          if ('createEvent' in document) {
            let evt = document.createEvent('HTMLEvents')
            evt.initEvent('geochange', false, true)
            this._geoloc.dispatchEvent(evt)
          }
          else {
            this._geoloc.fireEvent('ongeochange')
          }
        }
        else {
          this._geoloc.innerHTML = this._errorGeoCodeMsg
        }
      }
      else {
        this._geoloc.innerHTML = this._errorGeoCodeMsg + ' ' + status
      }
    })
  }

  initAutocomplete (idMapInput) {
    this._input = document.getElementById(idMapInput)
    this._options = {
      types: ['(regions)']
    }
    let autocomplete = new window.google.maps.places.Autocomplete(this._input, this._options)

    autocomplete.addListener('place_changed', () => {
      let place = autocomplete.getPlace()
      if (place.geometry !== undefined) {
        sessionStorage.setItem('autoCompleteResult', JSON.stringify(place.address_components))
        if ('createEvent' in document) {
          let evt = document.createEvent('HTMLEvents')
          evt.initEvent('autocompletechange', false, true)
          this._input.dispatchEvent(evt)
        }
        else {
          this._input.fireEvent('autocompletechange')
        }
      }
    })
  }

  resize () {
    let center = this._map.getCenter()
    window.google.maps.event.trigger(this._elemMap, 'resize')
    this._map.setCenter(center)
  }
}

export { DestockMap }
