<template>
    <div>
        <div id="alert-top-fixed" v-show="!isClosed">
            <p>{{ message }}<br /><span>{{ subMessage }}</span><i class="ui close icon" @click="closeMe"></i></p>
        </div>
    </div>
</template>

<script>
  /**
   * Global $alertV
   * Use: this.$alertV({message: 'my message', type: 'error'})
   * type: see types of semantic-ui message class (error, success, warning, info...)
   *
   * Props
   *
   * Events:
   *
   */
  export default {
    props: {
      message: String,
      subMessage: {
        type: String,
        required: false,
        default: ''
      },
      validity: String,
      name: String
    },
    data () {
      return {
        isClosed: true,
        cookieName: 'alertTopFix-'
      }
    },
    mounted () {
      console.log(this.readCookie())
      let alertTopFixedState = this.readCookie()
      if ((alertTopFixedState === undefined || alertTopFixedState === null) && ((new Date(this.validity)).valueOf() >= Date.now())) {
        this.openMe()
      }
    },
    methods: {
      readCookie () {
        let match = document.cookie.match(new RegExp('(^|;\\s*)(' + this.cookieName + this.name + ')=([^;]*)'))
        return (match ? decodeURIComponent(match[3]) : null)
      },
      closeMe () {
        let alertBox = $('#alert-top-fixed')
        alertBox.css({
          'top': '-40px'
        })
        alertBox.one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', () => { this.isClosed = true })
        document.cookie = this.cookieName + this.name + '=closed; expires=' + this.validity + '; path=/'
      },
      openMe () {
        let alertBox = $('#alert-top-fixed')
        this.isClosed = false
        setTimeout(function () {
          alertBox.css({
            'top': '0'
          })
        }, 800)
      }
    }
  }
</script>
