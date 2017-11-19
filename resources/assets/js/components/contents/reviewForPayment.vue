<template>
    <div class="ui one column grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div :id="'modal-'+_uid" class="ui modal">
            <i class="close icon"></i>
        </div>
        <div class="column">
            <h2 class="ui header">{{ strings.contentHeader }}</h2>
        </div>
        <div class="mobile only tablet only column">
            <steps-light
                    :steps="steps">
            </steps-light>
        </div>
        <div class="computer only column">
            <steps
                    :steps="steps">
            </steps>
        </div>
        <div class="column">
            <div class="ui active inverted dimmer" v-if="!isLoaded">
                <div class="ui large text loader">Loading</div>
            </div>
            <template>
                <table class="ui compact unstackable table">
                    <thead>
                        <tr>
                            <th>{{ strings.tableHeaderOptionName }}</th>
                            <th>{{ strings.tableHeaderOptionQuantity }}</th>
                            <th>{{ strings.tableHeaderOptionCost }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="option in dataInvoice.options">
                            <td>
                                <h5 class="ui center aligned header">{{ option.name }}</h5>
                            </td>
                            <td class="single line">
                                {{ option.quantity }}
                            </td>
                            <td class="single line">
                                {{ (option.cost/100).toFixed(2) }}€
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="ui grid">
                    <div class="right aligned column">
                        <table style="float: right" class="ui very basic collapsing celled compact unstackable table">
                            <tbody>
                                <tr>
                                    <td class="four wide double">{{ strings.tableTotalExclVat }}</td>
                                    <td class="four wide double right aligned">{{ (dataInvoice.cost/100).toFixed(2) }}€</td>
                                </tr>
                                <tr>
                                    <td class="four wide double">{{ strings.tableTotalVat }}</td>
                                    <td class="four wide double right aligned">{{ (tva/100).toFixed(2) }}€</td>
                                </tr>
                                <tr>
                                    <td class="four wide double">{{ strings.tableTotalInclVat }}</td>
                                    <td class="four wide double right aligned">{{ ((dataInvoice.cost + tva)/100).toFixed(2) }}€</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="ui grid">
                    <div class="column">
                        <div class="ui compact right floated segment">
                            <div id="cgvSlider" class="ui toggle checkbox">
                                <input type="checkbox" name="cgv">
                                <label>{{ dataCgvText }} <a :href="dataCgvHref" target="_blank">{{ dataCgvA }}</a></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ui icon top attached info message">
                    <i class="lock icon"></i>
                    <div class="content">
                        <div class="header">{{ strings.lockInfoHeader }}</div>
                        <p>{{ strings.lockInfoContent }}</p>
                    </div>
                </div>
                <div :class="isCgvApprove ? 'ui attached fluid segment':'ui disabled attached fluid segment'">
                    <div class="ui grid">
                        <transition name="slide-fade">
                            <div v-if="nonce != '' && nonce.length > 0 && method=='card'" class="one wide tablet only one wide computer only column"></div>
                        </transition>
                        <div class="sixteen wide mobile eight wide tablet six wide computer centered column">
                            <form accept-charset="UTF-8" autocomplete="off" action="#"  id="payment-form" method="post" class="ui form" >
                                <input type="hidden" name="_token" :value="properties.csrfToken"/>
                                <div :class="isCgvApprove ? 'sixteen wide field' : 'sixteen wide disabled field'">
                                    <label>{{ strings.paymentCardNumberLabel }} {{ dataCardNiceType }}</label>
                                    <div class="ui labeled input braintree">
                                        <div class="ui label">
                                            <i class="payment icon"></i>
                                        </div>
                                        <div id="card-number" class="form-card-control"></div>
                                    </div>
                                </div>
                                <div class="two fields">
                                    <div :class="isCgvApprove ? 'ten wide field' : 'disabled ten wide field'">
                                        <label>{{ strings.paymentCardExpirationLabel }}</label>
                                        <div class="ui labeled input braintree">
                                            <div class="ui label">
                                                <i class="calendar icon"></i>
                                            </div>
                                            <div id="expiration-date" class="form-card-control"></div>
                                        </div>
                                    </div>
                                    <div :class="isCgvApprove ? 'six wide field' : 'disabled six wide field'">
                                        <label>{{ dataCVCLabel }}</label>
                                        <div class="ui labeled input braintree">
                                            <div class="ui label">
                                                <i class="lock icon"></i>
                                            </div>
                                            <div id="cvv" class="form-card-control"></div>
                                        </div>
                                    </div>
                                </div>
                                <button id="valid-card" type="submit" :class="isCgvApprove ? 'ui primary right labeled icon button':'ui disabled primary right labeled icon button'">
                                    <i class="right arrow icon"></i>
                                    {{ strings.payment_btn_check_card }}
                                </button>
                            </form>
                        </div>
                        <transition name="slide-fade">
                            <div v-if="nonce != '' && nonce.length > 0 && method=='card'" class="sixteen wide mobile only center aligned column">
                                <i class="big circular check green icon"></i>
                            </div>
                        </transition>
                        <transition name="slide-fade">
                            <div v-if="nonce != '' && nonce.length > 0 && method=='card'" class="one wide tablet only one wide computer only column">
                                <i class="big circular check green icon" style="position: absolute; right: 10px;"></i>
                            </div>
                        </transition>

                        <div class="sixteen wide column">
                            <div class="ui horizontal divider">
                                {{ strings.dividerChoiceLabel }}
                            </div>
                        </div>

                        <div class="sixteen wide column">
                            <div class="ui grid">
                                <transition name="slide-fade">
                                    <div v-if="nonce != '' && nonce.length > 0 && method=='paypal'" class="one wide tablet only one wide computer only column"></div>
                                </transition>
                                <div class="sixteen wide mobile eight wide tablet six wide computer centered column">
                                    <div id="paypal-button"></div>
                                </div>
                                <transition name="slide-fade">
                                    <div v-if="nonce != '' && nonce.length > 0 && method=='paypal'" class="sixteen wide mobile only center aligned column">
                                        <i class="big circular check green icon"></i>
                                    </div>
                                </transition>
                                <transition name="slide-fade">
                                    <div v-if="nonce != '' && nonce.length > 0 && method=='paypal'" class="one wide tablet only one wide computer only column">
                                        <i class="big circular check green icon" style="position: absolute; right: 10px;"></i>
                                    </div>
                                </transition>
                            </div>
                        </div>



                        <div class="sixteen wide right aligned column">
                            <transition name="slide-fade">
                            <button id="validate-order-btn" v-if="nonce != '' && nonce.length > 0" class="ui blue button" v-on:click="sendNonce()">
                                {{ strings.payment_btn_valid }}
                            </button>
                            </transition>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
  import { DestockTools } from '../../destockTools'
  import Axios from 'axios'
  export default {
    props: [
      // vue routes
      'routePostNonce',
      'routePrices',
      // vue vars
      'invoice',
      'mode',
      'clientToken'
    ],
    computed: {
      strings () {
        return this.$store.state.strings['review-for-payment']
      },
      properties () {
        return this.$store.state.properties['global']
      },
      dataInvoice () {
        return JSON.parse(this.invoice)
      }
    },
    data () {
      return {
        isLoaded: true,
        sendMessage: false,
        typeMessage: '',
        message: '',
        steps: [],
        tva: 0,
        dataCgvText: '',
        dataCgvA: '',
        dataCgvHref: '',
        isCgvApprove: false,
        nonce: '',
        method: '',
        dataCVCLabel: '',
        dataCardNiceType: '',
        deviceData: null
      }
    },
    mounted () {
      this.steps = [
        {
          isActive: false,
          isDisabled: false,
          isCompleted: true,
          title: this.strings.stepOneTitle,
          description: this.strings.stepOneDescription,
          icon: 'write'
        },
        {
          isActive: false,
          isDisabled: false,
          isCompleted: true,
          title: this.strings.stepTwoTitle,
          description: this.strings.stepTwoDescription,
          icon: 'user'
        },
        {
          isActive: true,
          isDisabled: false,
          isCompleted: false,
          title: this.strings.stepThreeTitle,
          description: this.strings.stepThreeDescription,
          routeDescription: this.routePrices,
          icon: 'payment'
        }
      ]
      this.dataCVCLabel = this.strings.paymentCardCvcLabel
      this.setSteps()
      this.calcTVA()
      this.setDataCgv()
      this.braintreeCreate()
    },
    updated () {
      let that = this
      $('#cgvSlider').checkbox({
        onChecked () {
          that.isCgvApprove = true
        },
        onUnchecked () {
          that.isCgvApprove = false
        }
      })
    },
    methods: {
      sendToast (message, type) {
        this.typeMessage = type
        this.message = message
        this.sendMessage = !this.sendMessage
      },
      setSteps () {
        (this.steps[2]).title = this.strings.stepThreeTitle + '(' + (this.dataInvoice.cost / 100).toFixed(2) + this.strings.stepThreeTitlePost + ')'
      },
      setDataCgv () {
        let htmlObject = $('<p>' + this.strings.toggleCgvLabel + '</p>')
        this.dataCgvText = htmlObject[0].firstChild.data
        this.dataCgvA = htmlObject[0].firstElementChild.innerHTML
        this.dataCgvHref = htmlObject[0].firstElementChild.href
      },
      calcTVA (synchrone = false) {
        if (!synchrone) {
          this.tva = 0
          if (this.dataInvoice.tvaSubject) {
            for (let index in this.dataInvoice.options) {
              this.tva = this.tva + (this.dataInvoice.options[index].tvaVal)
            }
          }
        }
        else {
          let tva = 0
          let dataInvoice = JSON.parse(this.invoice)
          if (dataInvoice.tvaSubject) {
            for (let index in dataInvoice.options) {
              tva = tva + (dataInvoice.options[index].tvaVal)
            }
          }
          return tva
        }
      },
      braintreeCreate () {
        let that = this
        let threeDSecure
        let form = document.querySelector('#payment-form')
        let submit = document.querySelector('#valid-card')
        window.braintree.client.create({
          authorization: that.clientToken
        }, function (clientErr, clientInstance) {
          // Stop if there was a problem creating the client.
          // This could happen if there is a network error or if the authorization
          // is invalid.
          if (clientErr) {
            console.error('Error creating client:', clientErr)
            return
          }
          // Recalc Price on synchrone for ensure
          let price = ((JSON.parse(that.invoice).cost + that.tva) / 100).toFixed(2)
          // Create dataCollector component.
          window.braintree.dataCollector.create({client: clientInstance, paypal: true}, function (err, dataCollectorInstance) {
            if (err) {
              // Handle error
              return
            }
            // At this point, you should access the dataCollectorInstance.deviceData value and provide it
            // to your server, e.g. by injecting it into your form as a hidden input.
            that.deviceData = dataCollectorInstance.deviceData
          })
          // Create a PayPal Checkout component.
          window.braintree.paypalCheckout.create({client: clientInstance}, function (paypalCheckoutErr, paypalCheckoutInstance) {
            // Stop if there was a problem creating PayPal Checkout.
            // This could happen if there was a network error or if it's incorrectly
            // configured.
            if (paypalCheckoutErr) {
              console.error('Error creating PayPal Checkout:', paypalCheckoutErr)
              return
            }
            // Set up PayPal with the checkout.js library
            window.paypal.Button.render({
              env: that.mode, // or 'sandbox'
              style: {label: 'pay', size: 'responsive', shape: 'rect', color: 'blue'},
              payment () {
                return paypalCheckoutInstance.createPayment({
                  // Your PayPal options here. For available options, see
                  // http://braintree.github.io/braintree-web/current/PayPalCheckout.html#createPayment
                  flow: 'vault',
                  billingAgreementDescription: that.strings.billingAgreementDescription + price + '€',
                  enableShippingAddress: false
                })
              },
              onAuthorize (data, actions) {
                return paypalCheckoutInstance.tokenizePayment(data)
                  .then(function (payload) {
                    that.nonce = payload.nonce
                    that.method = 'paypal'
                  })
              },
              onCancel (data) {
                console.log('checkout.js payment cancelled', JSON.stringify(data, 0, 2))
                that.nonce = ''
                that.method = ''
              },
              onError (err) {
                console.error('checkout.js error', err)
                that.nonce = ''
                that.method = ''
              }
            }, '#paypal-button').then(function () {
              // The PayPal button will be rendered in an html element with the id
              // `paypal-button`. This function will be called when the PayPal button
              // is set up and ready to be used.
            })
          })
          // Create 3DS component
          window.braintree.threeDSecure.create({client: clientInstance}, function (threeDSecureErr, threeDSecureInstance) {
            if (threeDSecureErr) {
              return
            }
            threeDSecure = threeDSecureInstance
          })
          // Create Hosted Fields Component
          window.braintree.hostedFields.create({
            client: clientInstance,
            styles: {
              // Styling a specific field
              'input': {
                'line-height': '1.2em',
                'height': '2em'
              },
              '.number': {
                'font-family': 'monospace'
              },
              // Styling element state
              ':focus': {
                'color': 'blue'
              },
              '.valid': {
                'color': 'green'
              },
              '.invalid': {
                'color': 'red'
              }
            },
            fields: {
              number: {
                selector: '#card-number',
                placeholder: '4111 1111 1111 1111'
              },
              cvv: {
                selector: '#cvv',
                placeholder: '123'
              },
              expirationDate: {
                selector: '#expiration-date',
                placeholder: '10 / 19'
              }
            }
          }, function (hostedFieldsErr, hostedFieldsInstance) {
            if (hostedFieldsErr) {
              console.error(hostedFieldsErr)
              return
            }
            hostedFieldsInstance.on('cardTypeChange', function (event) {
              // This event is fired whenever a change in card type is detected.
              // It will only ever be fired from the number field.
              if (event.cards.length === 1) {
                that.dataCardNiceType = event.cards[0].niceType
                that.dataCVCLabel = event.cards[0].code.name
                let placeHolder = ''
                for (let i = 1; i <= event.cards[0].code.size; i++) {
                  placeHolder = placeHolder + i.toString()
                }
                hostedFieldsInstance.setAttribute({
                  field: 'cvv',
                  attribute: 'placeholder',
                  value: placeHolder
                })
              }
              else {
                that.dataCardNiceType = ''
                that.dataCVCLabel = that.strings.paymentCardCvcLabel
                hostedFieldsInstance.setAttribute({
                  field: 'cvv',
                  attribute: 'placeholder',
                  value: '123'
                })
              }
            })
            form.addEventListener('submit', function (event) {
              event.preventDefault()
              let state = hostedFieldsInstance.getState()
              let formValid = Object.keys(state.fields).every(function (key) {
                return state.fields[key].isValid
              })
              if (formValid) {
                $(submit).addClass('loading disabled')
                that.nonce = ''
                that.method = ''
                // Tokenize Hosted Fields
                hostedFieldsInstance.tokenize(function (tokenizeErr, payload) {
                  if (tokenizeErr) {
                    $(submit).removeClass('loading disabled')
                    console.error(tokenizeErr)
                    alert(that.strings.errorInPaymentProcess)
                    return
                  }
                  if (threeDSecure !== undefined) {
                    let my3DSContainer = document.createElement('div')
                    let modal3DSContainer = document.querySelector('#modal-' + that._uid)
                    let removeFrame = function () {
                      // Remove UI that you added in addFrame.
                      modal3DSContainer.removeChild(my3DSContainer)
                      $(modal3DSContainer).modal({closable: false}).modal('hide')
                      $(submit).removeClass('loading disabled')
                    }
                    // eslint-disable-next-line handle-callback-err
                    let addFrame = function (err, iframe) {
                      // Set up your UI and add the iframe.
                      my3DSContainer.appendChild(iframe)
                      modal3DSContainer.appendChild(my3DSContainer)
                      $(modal3DSContainer).modal({
                        closable: false,
                        onHide () {
                          threeDSecure.cancelVerifyCard(removeFrame())
                        }
                      }).modal('show')
                    }
                    threeDSecure.verifyCard({
                      amount: price,
                      nonce: payload.nonce,
                      addFrame: addFrame,
                      removeFrame: removeFrame
                    }, function (err, verification) {
                      $(submit).removeClass('loading disabled')
                      if (err) {
                        that.nonce = ''
                        that.method = ''
                        alert(that.strings.cardInvalid)
                        return
                      }
                      that.nonce = verification.nonce
                      that.method = 'card'
                    })
                  }
                  else {
                    $(submit).removeClass('loading disabled')
                    that.nonce = payload.nonce
                    that.method = 'card'
                  }
                })
              }
              else {
                // Let the customer know their fields are invalid
                alert(that.strings.formInvalid)
              }
            }, false)
          })
        })
      },
      sendNonce () {
        let that = this
        if (this.nonce !== '' && this.nonce.length > 0) {
          $('#validate-order-btn').addClass('loading disabled')
          Axios.post(this.routePostNonce, {'nonce': this.nonce, 'deviceData': this.deviceData})
            .then(function (response) {
              DestockTools.goToUrl(response.data)
              $('#validate-order-btn').removeClass('loading')
            })
            .catch(function () {
              $('#validate-order-btn').removeClass('loading disabled')
              alert(that.strings.errorInPaymentProcess)
            })
        }
      }
    }
  }
</script>
