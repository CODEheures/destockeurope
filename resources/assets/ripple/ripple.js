;(function($, document, Math){
  $.ripple = function(selector, options) {

    var self = this;

    self.touchstart = {x: 0, y: 0, t: 0};
    self.touchend = {x: 0, y: 0, t: 0};

    var _log = self.log = function() {
      if(self.defaults.debug && console && console.log) {
        console.log.apply(console, arguments);
      }
    };

    self.selector = selector;
    self.defaults = {
      debug: false,
      on: 'mousedown',

      opacity: 0.4,
      color: "auto",
      multi: false,

      duration: 0.7,
      rate: function(pxPerSecond) {
        return pxPerSecond;
      },

      easing: 'linear'
    };

    self.defaults = $.extend({}, self.defaults, options);

    var Trigger = function(e) {

      var $this = $(this);
      var $ripple;
      var settings;

      $this.addClass('has-ripple');

      // This instances settings
      settings = $.extend({}, self.defaults, $this.data());

      // Create the ripple element
      if ( settings.multi || (!settings.multi && $this.find(".ripple").length === 0) ) {
        $ripple = $("<span></span>").addClass("ripple");
        $ripple.appendTo($this);

        _log('Create: Ripple');

        // Set ripple size
        if (!$ripple.height() && !$ripple.width()) {
          var size = Math.max($this.outerWidth(), $this.outerHeight());
          $ripple.css({
            height: size,
            width: size
          });
          _log('Set: Ripple size');
        }

        // Give the user the ability to change the rate of the animation
        // based on element width
        if(settings.rate && typeof settings.rate == "function") {

          // rate = pixels per second
          var rate = Math.round( $ripple.width() / settings.duration );

          // new amount of pixels per second
          var filteredRate = settings.rate(rate);

          // Determine the new duration for the animation
          var newDuration = ( $ripple.width() / filteredRate);

          // Set the new duration if it has not changed
          if(settings.duration.toFixed(2) !== newDuration.toFixed(2)) {
            _log('Update: Ripple Duration', {
              from: settings.duration,
              to: newDuration
            });
            settings.duration = newDuration;
          }
        }

        // Set the color and opacity
        var color = (settings.color == "auto") ? $this.css('color') : settings.color;
        var css = {
          animationDuration: (settings.duration).toString() + 's',
          animationTimingFunction: settings.easing,
          background: color,
          opacity: settings.opacity
        };

        _log('Set: Ripple CSS', css);
        $ripple.css(css);
      }

      // Ensure we always have the ripple element
      if(!settings.multi) {
        _log('Set: Ripple Element');
        $ripple = $this.find(".ripple");
      }

      // Kill animation
      _log('Destroy: Ripple Animation');
      $ripple.removeClass("ripple-animate");


      // Retrieve coordinates
      var x = e.pageX - $this.offset().left - $ripple.width() / 2;
      var y = e.pageY - $this.offset().top - $ripple.height() / 2;

      /**
       * We want to delete the ripple elements if we allow multiple so we dont sacrifice any page
       * performance. We don't do this on single ripples because once it has rendered, we only
       * need to trigger paints thereafter.
       */
      if(settings.multi) {
        _log('Set: Ripple animationend event');
        $ripple.one('animationend webkitAnimationEnd oanimationend MSAnimationEnd', function() {
          _log('Note: Ripple animation ended');
          _log('Destroy: Ripple');
          $(this).remove();
        });
      }

      // Set position and animate
      _log('Set: Ripple location');
      _log('Set: Ripple animation');
      $ripple.css({
        top: y + 'px',
        left: x + 'px'
      }).addClass("ripple-animate");
    };

    $(document).on(self.defaults.on, self.selector, Trigger);

    $(document).on('touchstart', self.selector, function (event) {
      self.touchstart.x = event.changedTouches[0].pageX
      self.touchstart.y = event.changedTouches[0].pageY
      self.touchstart.t = event.timeStamp
    })
    $(document).on('touchend', self.selector, function (event) {
      self.touchend.x = event.changedTouches[0].pageX
      self.touchend.y = event.changedTouches[0].pageY
      self.touchend.t = event.timeStamp

      let deltaTime = self.touchend.t - self.touchstart.t
      let deltaSpace = (self.touchend.x - self.touchstart.x)*(self.touchend.x - self.touchstart.x) + (self.touchend.y - self.touchstart.y)*(self.touchend.y - self.touchstart.y)
      deltaSpace = Math.sqrt(deltaSpace)

      if(deltaTime < 500 && deltaSpace < 80) {
        Trigger ()
      }
    })

  };
})(jQuery, document, Math);