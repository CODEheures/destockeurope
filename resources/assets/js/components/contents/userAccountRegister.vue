<template>
    <div>
        <h2 class="ui center aligned icon header"><i
                class="circular add user icon"></i> {{ strings.contentHeader }} </h2>
        <div class="ui centered grid">
            <div class="row">
                <form id="register-form" method="POST" :action="routeRegister" class="ui ten wide column form register">
                    <input type="hidden" name="_token" :value="properties.csrfToken"/>
                    <div class="required field">
                        <div id="cguCheckBox" class="ui checkbox">
                            <input type="checkbox" name="cgu" autofocus>
                            <label>
                                {{ dataCguText }} <a :href="dataCguHref" target="_blank">{{ dataCguA }}</a>
                                {{ dataCguText2 }} <a :href="dataCguHref2" target="_blank">{{ dataCguA2 }}</a>
                            </label>
                        </div>
                    </div>
                    <div class="field">
                        <div id="newsLetterCheckBox" class="ui checkbox">
                            <input type="checkbox" name="subscribeNewsLetter">
                            <label>{{ strings.formNewsletterCheckLabel }}</label>
                        </div>
                    </div>
                    <div :class="isCguApprove ? 'fields' : 'disabled fields'">
                        <div class="eight wide required field">
                            <label>{{ strings.formNameLabel }}</label>
                            <input id="name" type="text" name="name" :value="oldNameValue">
                        </div>
                        <div class="eight wide required field">
                            <label>{{ strings.formEmailLabel }}</label>
                            <input id="email" type="email" name="email" :value="oldEmailValue">
                        </div>
                    </div>
                    <div :class="isCguApprove ? 'fields' : 'disabled fields'">
                        <div class="eight wide required field">
                            <label>{{ strings.formPasswordLabel }}</label>
                            <input id="password" type="password" name="password">

                        </div>
                        <div class="eight wide required field">
                            <label>{{ strings.formPasswordConfirmLabel }}</label>
                            <input id="password-confirm" type="password" name="password_confirmation">
                        </div>
                    </div>
                    <div class="fields spaced-top-2">
                        <div class="field">
                            <button
                                    :class="isCguApprove ? 'g-recaptcha ui primary button' : 'g-recaptcha ui primary disabled button'"
                                    :data-sitekey="captchaKey"
                                    data-callback="submitRegister">
                                {{ strings.formButtonLabel }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        <div class="ui horizontal divider">{{ strings.dividerLabel }} </div>
        <div class="ui centered grid">
            <div class="row social">
                <a :href="isNewsLetterApprove ? routeFacebookRegister + '?subscribeNewsLetter=true' : routeFacebookRegister" :class="isCguApprove ? 'ui facebook button' : 'ui facebook disabled button'"><i class="facebook icon"></i> Facebook</a>
                <a :href="isNewsLetterApprove ? routeTwitterRegister + '?subscribeNewsLetter=true' : routeTwitterRegister" :class="isCguApprove ? 'ui twitter button' : 'ui twitter disabled button'"><i class="twitter icon"></i> Twitter</a>
                <a :href="isNewsLetterApprove ? routeGoogleRegister + '?subscribeNewsLetter=true' : routeGoogleRegister" :class="isCguApprove ? 'ui google plus button' : 'ui google plus disabled button'"><i class="google plus icon"></i> Google Plus</a>
            </div>

        </div>
    </div>
</template>

<script>
  /**
   * Props
   *  - routeRegister: String. The route to registre a user
   *  - routeFacebookRegister: String. The route to registre a user by facebook
   *  - routeTwitterRegister: String. The route to registre a user by twitter
   *  - routeGoogleRegister: String. The route to registre a user by google
   *  - oldNameValue: String. The old input value of name
   *  - oldEmailValue: String. The old input value of email
   *  - captchaKey: String. The captcha key
   *
   * Events:
   *
   */
  export default {
    props: [
      'routeRegister',
      'routeFacebookRegister',
      'routeTwitterRegister',
      'routeGoogleRegister',
      'oldNameValue',
      'oldEmailValue',
      'captchaKey'
    ],
    computed: {
      strings () {
        return this.$store.state.strings['user-account-register']
      },
      properties () {
        return this.$store.state.properties['global']
      },
      dataInvoice () {
        return JSON.parse(this.invoice)
      },
      dataCguText () {
        let htmlObject = $('<p>' + this.strings.formCguCheckLabel + '</p>')
        return htmlObject[0].firstChild.data
      },
      dataCguA () {
        let htmlObject = $('<p>' + this.strings.formCguCheckLabel + '</p>')
        return htmlObject[0].firstElementChild.innerHTML
      },
      dataCguHref () {
        let htmlObject = $('<p>' + this.strings.formCguCheckLabel + '</p>')
        return htmlObject[0].firstElementChild.href
      },
      dataCguText2 () {
        let htmlObject = $('<p>' + this.strings.formCguCheckLabel2 + '</p>')
        return htmlObject[0].firstChild.data
      },
      dataCguA2 () {
        let htmlObject = $('<p>' + this.strings.formCguCheckLabel2 + '</p>')
        return htmlObject[0].firstElementChild.innerHTML
      },
      dataCguHref2 () {
        let htmlObject = $('<p>' + this.strings.formCguCheckLabel2 + '</p>')
        return htmlObject[0].firstElementChild.href
      }
    },
    data () {
      return {
        sendMessage: false,
        typeMessage: '',
        message: '',
        isCguApprove: false,
        isNewsLetterApprove: false
      }
    },
    mounted () {
      let that = this
      $('#cguCheckBox').checkbox({
        onChecked () {
          that.isCguApprove = true
        },
        onUnchecked () {
          that.isCguApprove = false
        }
      })
      $('#newsLetterCheckBox').checkbox({
        onChecked () {
          that.isNewsLetterApprove = true
        },
        onUnchecked () {
          that.isNewsLetterApprove = false
        }
      })
    }
  }
</script>
