<template>
    <div class="ui one column grid">
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
            <h2 class="ui header">{{ strings.contentHeader }}</h2>
        </div>
        <div class="column">
            <div class="ui divided list">
                <div class="ui active inverted dimmer" v-if="!isLoaded">
                    <div class="ui large text loader">Loading</div>
                </div>
                <form>
                    <div v-for="(advert,index) in advertsList" class="ui basic segment">
                        <div class="ui grid">
                            <div class="ten wide tablet only ten wide computer only column">
                                <h4 class="ui horizontal divider header">
                                    <i class="unhide icon"></i>
                                    {{ strings.advertFormPreviewHeaderLabel }}
                                </h4>
                            </div>
                            <div class="sixteen wide mobile only six wide tablet only six wide computer only column">
                                <h4 class="ui horizontal divider header">
                                    <i class="settings icon"></i>
                                    {{ strings.advertFormParamsHeaderLabel }}
                                </h4>
                            </div>
                            <div class="ten wide tablet only ten wide computer only column">
                                <div class="row">
                                    <breadcrumb
                                            :staticItems="setBreadCrumbItems(advert)"
                                    ></breadcrumb>
                                </div>
                                <div class="sixteen wide column">
                                    <div class="header"><h3><span v-if="advert.isUrgent" class="ui red horizontal label">{{ strings.urgentLabel }}</span>{{ advert.title }}</h3></div>
                                </div>
                                <advert-by-id
                                        :advert="advert"
                                        user-name=""
                                        :is-user-owner="true"
                                        :is-static="false"
                                ></advert-by-id>
                            </div>
                            <div class="sixteen wide mobile only six wide tablet only six wide computer only column glass-box">
                                <div class="ui grid">
                                    <div class="ui orange right ribbon label" v-show="advert.isEditOf != null">{{ strings.segmentEditLabel }}</div>
                                    <div class="sixteen wide column">
                                        <div class="ui cards" :title="advert.user.isSupplier ? strings.trustedProviderLabel :''">
                                            <div class="card">
                                                <div class="content">
                                                    <div class="header">
                                                        <i class="green big shield icon" v-if="advert.user.isSupplier"></i>
                                                        {{ advert.user.compagnyName }}
                                                    </div>
                                                </div>
                                                <div class="content">
                                                    <div class="meta">
                                                        {{ advert.user.name }} / {{ advert.user.email }}
                                                    </div>
                                                    <div class="description">
                                                        {{ getFormattedAddress(advert.user.geoloc) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sixteen wide column">
                                        <div class="ui form">
                                            <div class="field" v-if="advert.user.isSupplier">
                                                <quantities-input-field
                                                        v-model="advertsList[index]"
                                                        :with-valid-button="false"
                                                        :only-mini-lot="true"
                                                ></quantities-input-field>
                                                <margin-input-field
                                                        v-model="advertsList[index]"
                                                        :with-valid-button="false"
                                                ></margin-input-field>
                                            </div>
                                            <div class="field">
                                                <div class="grouped fields">
                                                    <div class="field">
                                                        <div :id="'slider1-'+_uid+'-'+advert.id" class="ui slider checkbox">
                                                            <input type="radio" :name="advert.id" value="1">
                                                            <label>{{ strings.toggleApproveLabel }}</label>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <div :id="'slider2-'+_uid+'-'+advert.id" class="ui slider checkbox">
                                                            <input type="radio" :name="advert.id" value="0">
                                                            <label>{{ strings.toggleDisapproveLabel }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="field" v-if="((advert.id) in approveList) && ((approveList[advert.id]).isApprove=='0')">
                                                <label>{{ strings.textDisapproveReason }}</label>
                                                <textarea rows="2" v-model="(approveList[advert.id]).disapproveReason"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="ui primary button" :class="action ? '': 'disabled'" @click.prevent="approveAll">
                        {{ strings.formValidationButtonLabel }}
                    </button>
                </form>

            </div>
        </div>
        <div class="column">
            <div class="ui right aligned grid">
                <div class="sixteen wide column pagination">
                    <pagination
                      :pages="paginate"
                      :fake-page-route="nextUrl"
                      :per-page-option="true"
                      @changePage="changePage"
                    ></pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  /**
   * Props
   *  - routeGetAdvertsList: String. The route to get adverts
   *  - routeAdvertApprove: String. The route to put approves status
   *
   * Events:
   *
   */
  import Axios from 'axios'
  export default {
    props: [
      'routeGetAdvertsList',
      'routeAdvertApprove',
    ],
    computed: {
      strings () {
        return this.$store.state.strings['approve-advert-form']
      },
      properties () {
        return this.$store.state.properties['global']
      }
    },
    data () {
      return {
        advertsList: [],
        isLoaded: false,
        action: false,
        approveList: {},
        sendMessage: false,
        typeMessage: '',
        message: '',
        paginate: {},
        nextUrl: this.routeGetAdvertsList
      }
    },
    mounted () {
      this.getAdvertsList()
    },
    updated () {
      let that = this
      for (let index in this.advertsList) {
        $('#slider1-' + this._uid + '-' + this.advertsList[index]['id']).checkbox({
          onChange () {
            that.action = true
            if (!(this.name in that.approveList)) {
              that.$set(that.approveList, this.name, {'isApprove': this.value, 'disapproveReason': ''})
            }
            else {
              that.approveList[this.name].isApprove = this.value
            }
          }
        })
        $('#slider2-' + this._uid + '-' + this.advertsList[index]['id']).checkbox({
          onChange () {
            that.action = true
            if (!(this.name in that.approveList)) {
              that.$set(that.approveList, this.name, {'isApprove': this.value, 'disapproveReason': ''})
            }
            else {
              that.approveList[this.name].isApprove = this.value
            }
          }
        })
      }
    },
    methods: {
      getAdvertsList () {
        this.isLoaded = false
        let that = this
        this.approveList = {}
        this.advertsList = {}
        Axios.get(this.nextUrl)
          .then(function (response) {
            that.advertsList = response.data.adverts
            that.paginate = response.data.paginate
            that.isLoaded = true
          })
          .catch(function () {
            that.$alertV({'message': that.strings.loadErrorMessage, 'type': 'error'})
          })
      },
      approveAll (event) {
        let that = this
        for (let index in this.approveList) {
          this.approveList[index]['priceCoefficient'] = this.advertsList[index]['price_coefficient']
          this.approveList[index]['priceCoefficientTotal'] = this.advertsList[index]['price_coefficient_total']
          this.approveList[index]['lotMiniQuantity'] = this.advertsList[index]['lotMiniQuantity']
        }
        $('#modal-' + this._uid).modal({
          closable: false,
          onApprove () {
            that.isLoaded = false
            Axios.post(that.routeAdvertApprove, that.approveList)
              .then(function (response) {
                that.getAdvertsList()
                that.$alertV({'message': that.strings.advertApproveSuccess, 'type': 'success'})
              })
              .catch(function (error) {
                if (error.response && error.response.status === 409) {
                  that.$alertV({'message': error.response.data, 'type': 'error'})
                }
                else {
                  that.$alertV({'message': that.strings.loadErrorMessage, 'type': 'error'})
                }
                that.isLoaded = false
              })
          }
        }).modal('show')
      },
      getFormattedAddress (geoloc) {
        let parsed = JSON.parse(geoloc)
        if (parsed && parsed.length > 0 && 'formatted_address' in parsed[0]) {
          return (JSON.parse(geoloc)[0]['formatted_address'])
        }
      },
      setBreadCrumbItems (advert) {
        let that = this
        let breadcrumbItems = []
        advert.breadCrumb.forEach(function (element) {
          breadcrumbItems.push({
            name: element['description'][that.properties.actualLocale],
            value: element.id
          })
        })
        return breadcrumbItems
      },
      changePage (url) {
        let that = this
        $('html, body').animate({
          scrollTop: 0
        }, 600, function () {
          that.nextUrl = url
          that.getAdvertsList()
        })
      }
    }
  }
</script>
