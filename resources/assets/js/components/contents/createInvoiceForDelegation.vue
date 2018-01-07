<template>
    <div class="ui grid">
        <div :id="'modal-'+_uid" class="ui basic modal">
            <i class="close icon"></i>
            <div class="header">
                {{ strings.modalValidHeader }}
            </div>
            <div class="image content">
                <div class="image">
                    <i class="legal icon"></i>
                </div>
                <div class="description">
                    <p>{{ strings.modalValidDescriptionOne+dataMarge+strings.modalValidDescriptionTwo }}</p>
                </div>
            </div>
            <div class="actions">
                <div class="two fluid ui inverted buttons">
                    <div class="ui cancel red basic inverted button">
                        <i class="remove icon"></i>
                        {{ strings.modalNo }}
                    </div>
                    <div class="ui ok green basic inverted button">
                        <i class="checkmark icon"></i>
                        {{ strings.modalYes }}
                    </div>
                </div>
            </div>
        </div>
        <div class="sixteen wide column">
            <h2 class="ui header">{{ strings.contentHeader }}</h2>
        </div>
        <div class="sixteen wide column">
            <div class="ui form">
                <div class="field">
                    <div class="two fields">
                        <div class="required field">
                            <label>{{ strings.customerLabel }}</label>
                            <input type="text" name="customer" :placeholder="strings.customerLabel" v-model:value="dataCustomer" />
                        </div>
                        <div class="required field">
                            <label>{{ strings.descriptionLabel }}</label>
                            <input type="text" name="description" :placeholder="strings.descriptionLabel" v-model:value="dataDescription" />
                        </div>
                    </div>
                </div>
                <div class="field">
                    <div class="three fields">
                        <div class="required field">
                            <label>{{ strings.dateLabel }}</label>
                            <input type="date" name="date" :placeholder="strings.dateLabel" v-model:value="dataDate" />
                        </div>
                        <div class="required field">
                            <label>{{ strings.margeLabel }}</label>
                            <number-input name="marge" :min="1" :decimal="2" v-model="dataMarge"></number-input>
                        </div>
                    </div>
                </div>
                <button v-on:click.stop.prevent="postInvoice" :class="isLoaded ? 'ui primary button' : 'ui loading button'" type="submit">{{ strings.buttonLabel }}</button>
            </div>
        </div>
        <div class="sixteen wide mobile only eight wide tablet only six wide computer only centered column" v-if="dataInvoiceUrl">
            <a :href="dataInvoiceUrl" target="_blank" class="ui massive fluid primary button">{{ strings.goToInvoiceLabel }}</a>
        </div>
    </div>
</template>


<script>
  /**
   * Props
   *  - routeGetInvoicesList: String. Route to get the list of invoices
   *
   * Events:
   *
   */
  import Axios from 'axios'
  export default {
    props: [
      'routePostCreate'
    ],
    computed: {
      strings () {
        return this.$store.state.strings['create-invoice-for-delegation']
      },
      properties () {
        return this.$store.state.properties['global']
      }
    },
    data () {
      return {
        isLoaded: true,
        dataCustomer: '',
        dataDescription: '',
        dataDate: '',
        dataMarge: 0,
        dataInvoiceUrl: undefined
      }
    },
    mounted () {

    },
    methods: {
      postInvoice () {
        let that = this
        $('#modal-' + this._uid).modal({
          closable: false,
          onApprove () {
            that.isLoaded = false
            Axios.post(that.routePostCreate, {
              'customer': that.dataCustomer,
              'description': that.dataDescription,
              'marge': that.dataMarge,
              'date': that.dataDate
            })
              .then(function (response) {
                that.isLoaded = true
                console.log(response.data.invoiceUrl)
                if ('invoiceUrl' in response.data && response.data.invoiceUrl) {
                  that.dataInvoiceUrl = response.data.invoiceUrl
                }
              })
              .catch(function (error) {
                if (error.response && error.response.status === 409) {
                  that.$alertV({'message': error.response.data, 'type': 'error'})
                }
                else if (error.response && error.response.status === 422) {
                  for (let item in error.response.data.errors) {
                    that.$alertV({'message': error.response.data.errors[item][0], 'type': 'error'})
                  }
                }
                else {
                  that.$alertV({'message': that.strings.loadErrorMessage, 'type': 'error'})
                }
                that.isLoaded = true
              })
          }
        }).modal('show')
      }
    }
  }
</script>
