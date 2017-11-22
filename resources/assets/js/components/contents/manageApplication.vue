<template>
    <div class="ui one column grid">
        <div class="column">
            <h2 class="ui header">{{ strings.contentHeader }}</h2>
        </div>
        <div class="column">
            <div class="ui active inverted dimmer" v-if="!isLoaded">
                <div class="ui large text loader">Loading</div>
            </div>
            <div class="ui form">
                <h4 class="ui horizontal divider header"><i class="paint brush icon"></i> {{ strings.appearanceLabel }} </h4>
                <div class="field">
                    <type-radio-button
                            :route-get-list-type="routeGetListType"
                            :first-menu-name="strings.listTypeFirstMenuName"
                            :old-choice="oldType"
                            @typeChoice="typeChoice"
                    ></type-radio-button>
                </div>
                <h4 class="ui horizontal divider header"><i class="browser icon"></i> {{ strings.advertPreferencesLabel }} </h4>
                <div class="field">
                    <div class="three fields">
                        <div class="field">
                            <label>{{ strings.advertNbFreePicturesLabel }}</label>
                            <input type="number"
                                   name="nbFreePictures"
                                   min="1"
                                   :max="parameters.nbMaxPictures"
                                   v-model="parameters.nbFreePictures"
                                   v-on:keyup.enter="updateParameter"
                                   v-on:focus="focused={'name': 'nbFreePictures', 'value': parameters.nbFreePictures}"
                                   v-on:blur="testChanged(focused, {'name': 'nbFreePictures', 'value': parameters.nbFreePictures})">
                        </div>
                        <div class="field">
                            <label>{{ strings.advertNbMaxPicturesLabel }}</label>
                            <input type="number"
                                   name="nbMaxPictures"
                                   min="1"
                                   v-model="parameters.nbMaxPictures"
                                   v-on:keyup.enter="updateParameter"
                                   v-on:focus="focused={'name': 'nbMaxPictures', 'value': parameters.nbMaxPictures}"
                                   v-on:blur="testChanged(focused, {'name': 'nbMaxPictures', 'value': parameters.nbMaxPictures})">
                        </div>
                        <div class="field">
                            <label>{{ strings.advertResumeLenghtLabel }}</label>
                            <input type="number"
                                   name="advertResumeLenght"
                                   min="20"
                                   v-model="parameters.advertResumeLenght"
                                   v-on:keyup.enter="updateParameter"
                                   v-on:focus="focused={'name': 'advertResumeLenght', 'value': parameters.advertResumeLenght}"
                                   v-on:blur="testChanged(focused, {'name': 'advertResumeLenght', 'value': parameters.advertResumeLenght})">
                        </div>
                    </div>
                </div>
                <h4 class="ui horizontal divider header"><i class="euro icon"></i> {{ strings.costLabel }} </h4>
                <div class="field">
                    <div class="three fields">
                        <div class="field">
                            <label>{{ strings.advertUrgentCostLabel }}</label>
                            <input type="number"
                                   name="urgentCost"
                                   min="0"
                                   v-model="parameters.urgentCost"
                                   v-on:keyup.enter="updateParameter"
                                   v-on:focus="focused={'name': 'urgentCost', 'value': parameters.urgentCost}"
                                   v-on:blur="testChanged(focused, {'name': 'urgentCost', 'value': parameters.urgentCost})">
                        </div>
                        <div class="field">
                            <label>{{ strings.advertVideoCostLabel }}</label>
                            <input type="number"
                                   name="videoCost"
                                   min="0"
                                   v-model="parameters.videoCost"
                                   v-on:keyup.enter="updateParameter"
                                   v-on:focus="focused={'name': 'videoCost', 'value': parameters.videoCost}"
                                   v-on:blur="testChanged(focused, {'name': 'videoCost', 'value': parameters.videoCost})">
                        </div>
                        <div class="field">
                            <label>{{ strings.advertRenewCostLabel }}</label>
                            <input type="number"
                                   name="renewCost"
                                   min="0"
                                   v-model="parameters.renewCost"
                                   v-on:keyup.enter="updateParameter"
                                   v-on:focus="focused={'name': 'renewCost', 'value': parameters.renewCost}"
                                   v-on:blur="testChanged(focused, {'name': 'renewCost', 'value': parameters.renewCost})">
                        </div>
                    </div>
                    <div class="three fields">
                        <div class="field">
                            <label>{{ strings.advertEditCostLabel }}</label>
                            <input type="number"
                                   name="editCost"
                                   min="0"
                                   v-model="parameters.editCost"
                                   v-on:keyup.enter="updateParameter"
                                   v-on:focus="focused={'name': 'editCost', 'value': parameters.editCost}"
                                   v-on:blur="testChanged(focused, {'name': 'editCost', 'value': parameters.editCost})">
                        </div>
                        <div class="field">
                            <label>{{ strings.advertBackToTopCostLabel }}</label>
                            <input type="number"
                                   name="backToTopCost"
                                   min="0"
                                   v-model="parameters.backToTopCost"
                                   v-on:keyup.enter="updateParameter"
                                   v-on:focus="focused={'name': 'backToTopCost', 'value': parameters.backToTopCost}"
                                   v-on:blur="testChanged(focused, {'name': 'backToTopCost', 'value': parameters.backToTopCost})">
                        </div>
                        <div class="field">
                            <label>{{ strings.advertHighlightCostLabel }}</label>
                            <input type="number"
                                   name="highlightCost"
                                   min="0"
                                   v-model="parameters.highlightCost"
                                   v-on:keyup.enter="updateParameter"
                                   v-on:focus="focused={'name': 'highlightCost', 'value': parameters.highlightCost}"
                                   v-on:blur="testChanged(focused, {'name': 'highlightCost', 'value': parameters.highlightCost})">
                        </div>
                    </div>
                </div>
                <h4 class="ui horizontal divider header"><i class="search icon"></i> {{ strings.searchLabel }} </h4>
                <div class="field">
                    <div class="three fields">
                        <div class="field">
                            <label>{{ strings.minLengthSearchLabel }}</label>
                            <input type="number"
                                   name="minLengthSearch"
                                   min="1"
                                   v-model="parameters.minLengthSearch"
                                   v-on:keyup.enter="updateParameter"
                                   v-on:focus="focused={'name': 'minLengthSearch', 'value': parameters.minLengthSearch}"
                                   v-on:blur="testChanged(focused, {'name': 'minLengthSearch', 'value': parameters.minLengthSearch})">
                        </div>
                        <div class="field">
                            <label>{{ strings.maxNumberOfSearchResultsLabel }}</label>
                            <input type="number"
                                   name="maxNumberOfSearchResults"
                                   min="1"
                                   v-model="parameters.maxNumberOfSearchResults"
                                   v-on:keyup.enter="updateParameter"
                                   v-on:focus="focused={'name': 'maxNumberOfSearchResults', 'value': parameters.maxNumberOfSearchResults}"
                                   v-on:blur="testChanged(focused, {'name': 'maxNumberOfSearchResults', 'value': parameters.maxNumberOfSearchResults})">
                        </div>
                    </div>
                </div>
                <h4 class="ui horizontal divider header"><i class="announcement icon"></i> {{ strings.adsPreferencesLabel }} </h4>
                <div class="field">
                    <div class="three fields">
                        <div class="field">
                            <label>{{ strings.advertPerPageLabel }}</label>
                            <input type="number"
                                   name="advertsPerPage"
                                   min="4"
                                   v-model="parameters.advertsPerPage"
                                   v-on:keyup.enter="updateParameter"
                                   v-on:focus="focused={'name': 'advertsPerPage', 'value': parameters.advertsPerPage}"
                                   v-on:blur="testChanged(focused, {'name': 'advertsPerPage', 'value': parameters.advertsPerPage})">
                        </div>
                        <div class="field">
                            <label>{{ strings.adsFrequencyLabel }}</label>
                            <input type="number"
                                   name="adsFrequency"
                                   min="0"
                                   :max="parameters.advertPerPage"
                                   v-model="parameters.adsFrequency"
                                   v-on:keyup.enter="updateParameter"
                                   v-on:focus="focused={'name': 'adsFrequency', 'value': parameters.adsFrequency}"
                                   v-on:blur="testChanged(focused, {'name': 'adsFrequency', 'value': parameters.adsFrequency})">
                        </div>
                    </div>
                </div>
                <div class="field">
                    <div :id="'slider1-'+_uid" class="ui slider checkbox">
                        <input type="checkbox"
                               name="masterAds"
                               v-model="parameters.masterAds">
                        <label>{{ strings.masterAdsActivationLabel }}</label>
                    </div>
                </div>
                <div class="field">
                    <div class="three fields">
                        <div class="field">
                            <transition name="p-fade">
                                <div class="field" v-show="parameters.masterAds">
                                    <label>{{ strings.masterAdsUrlLabel }}</label>
                                    <input type="url" placeholder="http://ads.google.com/1d5f1d..."
                                           name="urlMasterAds"
                                           v-model="parameters.urlMasterAds"
                                           v-on:keyup.enter="updateParameter"
                                           v-on:focus="focused={'name': 'urlMasterAds', 'value': parameters.urlMasterAds}"
                                           v-on:blur="testChanged(focused, {'name': 'urlMasterAds', 'value': parameters.urlMasterAds})">
                                </div>
                            </transition>
                        </div>
                        <div class="field">
                            <transition name="p-fade">
                                <div class="field" v-show="parameters.masterAds">
                                    <label>{{ strings.masterAdsUrlLinkLabel }}</label>
                                    <input type="url" placeholder="http://ads.google.com/1d5f1d..."
                                           name="urlLinkMasterAds"
                                           v-model="parameters.urlLinkMasterAds"
                                           v-on:keyup.enter="updateParameter"
                                           v-on:focus="focused={'name': 'urlLinkMasterAds', 'value': parameters.urlLinkMasterAds}"
                                           v-on:blur="testChanged(focused, {'name': 'urlLinkMasterAds', 'value': parameters.urlLinkMasterAds})">
                                </div>
                            </transition>
                        </div>
                        <div class="field">
                            <transition name="p-fade">
                                <div class="field" v-show="parameters.masterAds">
                                    <label>{{ strings.masterAdsOffsetYLabel }} (px)</label>
                                    <input type="number"
                                           name="offsetYMasterAds"
                                           v-model="parameters.offsetYMasterAds"
                                           v-on:keyup.enter="updateParameter"
                                           v-on:focus="focused={'name': 'offsetYMasterAds', 'value': parameters.offsetYMasterAds}"
                                           v-on:blur="testChanged(focused, {'name': 'offsetYMasterAds', 'value': parameters.offsetYMasterAds})">
                                </div>
                            </transition>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
  /**
   * Props
   *  - routeParameters: String. The route to get/patch parameters
   *  - routeTestIsPicture: String. The route to test if url is a picture
   *  - routeGetListType: The route to get type of welcome page
   *
   * Events:
   *
   */
  import Axios from 'axios'
  export default {
    directives: {focus: focus},
    props: {
      routeParameters: String,
      routeTestIsPicture: String,
      routeGetListType: String
    },
    computed: {
      strings () {
        return this.$store.state.strings['manage-application']
      },
      properties () {
        return this.$store.state.properties['global']
      }
    },
    data () {
      return {
        isLoaded: false,
        sendMessage: false,
        typeMessage: '',
        message: '',
        focused: {},
        blured: {},
        parameters: [],
        oldType: '',
        isValidImage: false
      }
    },
    mounted () {
      this.getParameters()
      let that = this
      $('#slider1-' + this._uid).checkbox({
        onChecked () {
          that.parameters['masterAds'] = 1
          that.activeMasterAds(1)
        },
        onUnchecked () {
          that.parameters['masterAds'] = 0
          that.activeMasterAds(0)
        }
      })
    },
    methods: {
      getParameters () {
        this.isLoaded = false
        let that = this
        Axios.get(this.routeParameters)
          .then(function (response) {
            that.parameters = response.data
            that.oldType = that.parameters.welcomeType
            that.isLoaded = true
          })
          .catch(function () {
            that.$alertV({'message': that.strings.loadErrorMessage, 'type': 'error'})
          })
      },
      typeChoice (type) {
        this.blured.name = 'welcomeType'
        this.blured.value = type
        this.updateParameter()
      },
      activeMasterAds (flag) {
        this.blured.name = 'masterAds'
        this.blured.value = flag
        this.updateParameter()
      },
      updateParameter (event) {
        let patchValue = {}
        let name = ''
        if (event === undefined || event === null) {
          name = this.blured.name
          patchValue[name] = this.blured.value
        }
        else if ((event instanceof KeyboardEvent) && event.key === 'Enter') {
          name = event.target.name
          patchValue[name] = event.target.value
          this.focused.value = event.target.value
        }
        if (name !== undefined && name !== null && patchValue[name] !== undefined && patchValue[name] !== null) {
          if (name === 'urlMasterAds' && patchValue[name] !== '') {
            let that = this
            this.testValidImgUrl(patchValue[name], function () {
              that.updateRequest(patchValue)
            })
          }
          else {
            this.updateRequest(patchValue)
          }
        }
        else {
          this.getParameters()
          this.$alertV({'message': this.strings.patchErrorMessage, 'type': 'error'})
        }
      },
      updateRequest (patchValue) {
        let that = this
        Axios.patch(this.routeParameters, patchValue)
          .then(function (response) {
            that.getParameters()
            that.$alertV({'message': that.strings.patchSuccessMessage, 'type': 'success'})
          })
          .catch(function (error) {
            that.getParameters()
            if (error.response && error.response.status === 409) {
              that.$alertV({'message': error.response.data, 'type': 'error'})
            }
            else {
              that.$alertV({'message': that.strings.patchErrorMessage, 'type': 'error'})
            }
          })
      },
      testValidImgUrl (url, callback) {
        let that = this
        this.isLoaded = false
        Axios.post(this.routeTestIsPicture, {url: url})
          .then(function (response) {
            that.isLoaded = true
            if (response.data && response.data === true) {
              that.isValidImage = true
              callback()
            }
            else {
              that.isValidImage = false
              that.$alertV({'message': that.strings.invalidImageMessage, 'type': 'error'})
            }
          })
          .catch(function () {
            that.isLoaded = true
            that.isValidImage = false
            that.$alertV({'message': that.strings.invalidImageMessage, 'type': 'error'})
          })
      },
      testChanged ($in, $out) {
        if ($in.name === $out.name && $in.value !== $out.value) {
          this.blured = {name: $out.name, value: $out.value}
          this.updateParameter()
        }
      }
    }
  }
</script>
