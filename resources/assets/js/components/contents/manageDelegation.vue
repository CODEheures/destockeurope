<template>
    <div  class="ui one column grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div :id="'modal2-'+_uid" class="ui basic modal">
            <i class="close icon"></i>
            <div class="header">
                {{ strings.modalValidHeader }}
            </div>
            <div class="image content">
                <div class="image">
                    <i class="legal icon"></i>
                </div>
                <div class="description">
                    <p>{{ strings.modalValidDescription }}</p>
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
        <div class="column">
            <h2 class="ui header">{{ contentHeader }}</h2>
        </div>
        <div class="row">
            <div class="sixteen wide column">
                <div class="row">
                    <div class="ui active inverted dimmer" v-if="!isLoaded">
                        <div class="ui large text loader">Loading</div>
                    </div>
                    <div class="ui segment">
                        <div class="ui celled list">
                            <div class="ui active inverted dimmer" v-if="!isLoaded">
                                <div class="ui large text loader">Loading</div>
                            </div>
                            <adverts-by-list-item v-if="dataAdvert"
                                :route-bookmark-add="''"
                                :route-bookmark-remove="''"
                                :advert="dataAdvert"
                                :can-get-delegations="canGetDelegations==1"
                                :is-personnal-list="isPersonnalList==1"
                                @deleteAdvert="destroyMe($event.url)"
                                @updateSuccess="sendToast(strings.updateSuccessMessage, 'success')"
                                @loadError="sendToast(strings.loadErrorMessage, 'error')"
                                @sendToast="sendToast($event.message, $event.type)"
                            ></adverts-by-list-item>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import Axios from 'axios'
  export default {
    props: [
      // vue routes
      'advert',
      // vue vars
      'canGetDelegations',
      'isPersonnalList',
      'isDelegation',
      // vue strings
      'contentHeader'
    ],
    computed: {
      strings () {
        return this.$store.state.strings['manage-delegation']
      },
      properties () {
        return this.$store.state.properties['global']
      }
    },
    data () {
      return {
        typeMessage: '',
        message: '',
        sendMessage: false,
        dataAdvert: JSON.parse(this.advert),
        isLoaded: true
      }
    },
    methods: {
      sendToast (message, type) {
        this.typeMessage = type
        this.message = message
        this.sendMessage = !this.sendMessage
      },
      destroyMe (url) {
        let modalForm = $('#modal2-' + this._uid)
        let that = this
        modalForm.modal({
          closable: true,
          blurring: false,
          onApprove () {
            that.isLoaded = false
            Axios.delete(url)
              .then(function (response) {
                that.flagForceReload = !that.flagForceReload
                that.isLoaded = true
              })
              .catch(function (error) {
                if (error.response && error.response.status === 409) {
                  that.sendToast(error.response.data, 'error')
                }
                else {
                  that.sendToast(that.strings.loadErrorMessage, 'error')
                }
                that.isLoaded = true
              })
          }
        }).modal('show')
      }
    }
  }
</script>
