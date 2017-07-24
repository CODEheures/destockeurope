<template>
    <div>
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <h2 class="ui center aligned icon header"><i
                class="circular add user icon"></i> {{ strings.contentHeader }} </h2>
        <div class="ui centered grid">
            <div class="row">
                <form id="register-form" method="POST" :action="routeRegister" class="ui ten wide column form register">
                    <input type="hidden" name="_token" :value="xCsrfToken"/>
                    <div class="required field">
                        <div id="cguCheckBox" class="ui checkbox">
                            <input type="checkbox" name="cgu" autofocus>
                            <label>{{ dataCguText }} <a :href="dataCguHref" target="_blank">{{ dataCguA }}</a></label>
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
    export default {
        props: [
            //vue routes
            'routeRegister',
            'routeFacebookRegister',
            'routeTwitterRegister',
            'routeGoogleRegister',
            //vue vars
            'oldNameValue',
            'oldEmailValue',
            'captchaKey',
        ],
        data: () => {
            return {
                strings: {},
                properties: {},
                sendMessage: false,
                typeMessage: '',
                message: '',
                dataCguText: '',
                dataCguA: '',
                dataCguHref: '',
                isCguApprove: false,
                isNewsLetterApprove: false,
                xCsrfToken: ''
            };
        },
        mounted () {
            this.strings = this.$store.state.strings['user-account-register'];
            this.properties = this.$store.state.properties['global'];
            this.setDataCgu();
            this.xCsrfToken = destockShareVar.csrfToken;
        },
        updated () {
            let that = this;
            $('#cguCheckBox').checkbox({
                onChecked: function() {
                    that.isCguApprove = true;
                },
                onUnchecked: function() {
                    that.isCguApprove = false;
                }
            });
            $('#newsLetterCheckBox').checkbox({
                onChecked: function() {
                    that.isNewsLetterApprove = true;
                },
                onUnchecked: function() {
                    that.isNewsLetterApprove = false;
                }
            });
        },
        methods: {
            sendToast: function(message,type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            },
            setDataCgu () {
                let htmlObject = $('<p>'+this.strings.formCguCheckLabel+'</p>');
                this.dataCguText = htmlObject[0].firstChild.data;
                this.dataCguA = htmlObject[0].firstElementChild.innerHTML;
                this.dataCguHref = htmlObject[0].firstElementChild.href;
            }
        }
    }
</script>
