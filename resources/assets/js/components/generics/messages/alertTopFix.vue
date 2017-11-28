<template></template>

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
        let body = $('body')
        alertBox.css({'top': '-' + (alertBox.height() + 10) + 'px'})
        alertBox.one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', () => {
          alertBox.remove()
          body.css({'margin-top': ''})
        })
        document.cookie = this.cookieName + this.name + '=closed; expires=' + this.validity + '; path=/'
      },
      openMe () {
        let template = this.getTemplate()
        let divAlert = document.createElement('div')
        divAlert.id = 'alert-top-fixed'
        divAlert.innerHTML = template
        document.body.insertBefore(divAlert, document.body.firstChild)

        let alertBox = $('#alert-top-fixed')
        alertBox.css({top: '-' + (alertBox.height() + 10) + 'px'})
        let body = $('body')

        let closeIcon = alertBox.find('i.ui.close.icon')
        closeIcon.on('click', () => { this.closeMe() })

        alertBox.css({transition: 'top 0.8s'})

        setTimeout(function () {
          body.css({marginTop: alertBox.height()})
          alertBox.css({'top': '0'})
        }, 800)
      },
      getTemplate () {
        return '<p>' + this.message + '<br /><span>' + this.subMessage +
          '</span><i class="ui close icon"></i></p>'
      }
    }
  }
</script>
