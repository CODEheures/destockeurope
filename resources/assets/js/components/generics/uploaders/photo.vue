<template>
    <div>
        <div class="field" >
            <div class="ui icon info message">
                <i class="icon">{{ nbPicturesIndicator }}</i>
                <div class="content">
                    <div class="header">{{ helpHeaderIndicator }}</div>
                    <p v-if="!isDelegation">{{ helpUploadP }}<a :href="helpUploadAHref">{{ helpUploadA }}</a></p>
                </div>
            </div>
        </div>

        <div class="field">
            <div :class="'ui doubling ' + nbColumns + ' column grid'">
                <div class="column" v-for="(picture,index) in pictures">
                    <div :class="!isDelegation &&  index>=advertFormPhotoNbFreePicture ? 'ui pink segment' : 'ui segment'">
                        <a class="ui pink right ribbon label" v-if="!isDelegation && index>=advertFormPhotoNbFreePicture">{{ strings.payPhotoHelpHeaderSingular }}</a>
                        <div class="ui stackable grid">
                            <div class="four wide centered column">
                                <a href="#"><i class="large grey remove circle outline icon" :data-file="picture.hashName" v-on:click="delPhoto"></i></a>
                            </div>
                            <div class="twelve wide right aligned column">
                                <div :id="'slider1-'+_uid+'-'+index" class="ui slider checkbox">
                                    <input type="radio" name="mainThumb" :value="picture.hashName">
                                    <label>{{ strings.mainPhotoLabel }}</label>
                                </div>
                            </div>
                            <div class="sixteen wide column">
                                <img :src="picture.thumbUrl" class="ui rounded medium centered image" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column" v-show="onUpload">
                    <div :class="!isDelegation &&  pictures.length+1>advertFormPhotoNbFreePicture ? 'ui pink segment' : 'ui segment'">
                        <a class="ui pink right ribbon label" v-if="!isDelegation && pictures.length+1>advertFormPhotoNbFreePicture">{{ strings.payPhotoHelpHeaderSingular }}</a>
                        <div class="ui stackable grid">
                            <div class="four wide centered column">
                                <a href="#"><i class="large grey remove circle outline icon"></i></a>
                            </div>
                            <div class="twelve wide right aligned column">
                                <div :id="'fakeSlider1-'+_uid" class="ui slider checkbox">
                                    <input type="radio">
                                    <label>{{ strings.mainPhotoLabel }}</label>
                                </div>
                            </div>
                            <div class="sixteen wide column">
                                <div class="ui grid">
                                    <div class="sixteen wide column">
                                        <div :id="'progress-'+_uid" class="ui blue active progress" v-show="onUpload">
                                            <div class="bar">
                                                <div class="progress"></div>
                                            </div>
                                            <div class="label"><a class="ui orange button" v-on:click="cancelUploadPhoto()">{{ strings.photoBtnCancel }}</a></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ui grid" v-show="onUpload">
                                    <div class="sixteen wide center aligned column">
                                        <div class="ui statistic">
                                            <div class="value">
                                                {{ performUpload }}
                                            </div>
                                            <div class="label">
                                                Mb
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="field">
            <template v-if="pictures.length<maxFiles && !onUpload">
                <div class="ui grid">
                    <div class="sixteen wide column">
                        <div class="row">
                            <div class="ui icon big primary button" v-on:click="triggerClickInput()">
                                <i class="plus icon"></i>
                                {{ strings.photoBtnLabel }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="sixteen wide column">
                                <div class="ui top blue pointing basic label">
                                    {{ strings.photoLabel }}
                                </div>
                            </div>
                        </div>
                        <input :id="formPhotoFileInputName" type="file" :name="formPhotoFileInputName" v-on:change="uploadPhoto" style="display: none">
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>


<script>
  /**
   * Props
   *  - routePostPicture: String. The route for post picture
   *  - routeGetListPosts: String. The route to get list of posts pictures
   *  - routeDelPicture: String. The route to del picture of list of posts
   *  - advertFormPhotoNbFreePicture: Number. How many photos are free of cost
   *  - maxFiles: Number. How many maximum photos
   *  - maxVideoFileSize: Number. The maximum size of a photo
   *  - isDelegation: Boolean. Is user delegation?
   *  - oldMainPicture: String. The hash of mainPicture
   *  - nbColumns: String. Number of preview columns. Literral: "one", "two"...
   *
   * Events:
   *  @updatePictures: emit list of pictures when list change: ['haschcode1', 'haschcode2', ...]
   *  @updateMainPicture: emit main picture when change: 'haschcode1'
   */
  import Axios from 'axios'
  export default {
    props: {
      // vue routes
      routePostPicture: String,
      routeGetListPosts: String,
      routeDelPicture: String,
      // vue vars
      advertFormPhotoNbFreePicture: Number,
      maxFiles: Number,
      maxPhotoFileSize: Number,
      isDelegation: Boolean,
      oldMainPicture: {
        type: String,
        required: false,
        default: ''
      },
      nbColumns: {
        type: String,
        required: false,
        default: 'three'
      }
    },
    computed: {
      strings () {
        return this.$store.state.strings['photo-uploader']
      }
    },
    watch: {
      pictures () {
        this.setPicturesIndicators()
        this.setMainPicture()
        this.$emit('updatePictures', this.pictures)
      },
      mainPicture () {
        this.$emit('updateMainPicture', this.mainPicture)
      }
    },
    data () {
      return {
        filePhotoToPost: new FormData(),
        formPhotoFileInputName: 'addpicture',
        helpUploadP: '',
        helpUploadA: '',
        helpUploadAHref: '',
        pictures: [],
        nbPicturesIndicator: '',
        helpHeaderIndicator: '',
        mainPicture: '',
        onUpload: false,
        performUpload: 0,
        cancelToken: null,
        sourceCancelToken: null
      }
    },
    mounted () {
      this.setPicturesIndicators()
      this.helpUpload()
      this.getListPosts()
    },
    updated () {
      let that = this
      this.mainPicture = this.oldMainPicture
      this.setMainPicture()
      this.pictures.forEach(function (elem, index) {
        $('#slider1-' + that._uid + '-' + index).checkbox({
          onChange () {
            that.mainPicture = this.value
          }
        })
      })
      $('#fakeSlider1-' + that._uid).checkbox()
    },
    methods: {
      triggerClickInput () {
        $('#' + this.formPhotoFileInputName).click()
      },
      helpUpload () {
        let htmlObject = $('<p>' + this.strings.photoHelpContent + '</p>')
        this.helpUploadP = htmlObject[0].firstChild.data
        this.helpUploadA = htmlObject[0].firstElementChild.innerHTML
        this.helpUploadAHref = htmlObject[0].firstElementChild.href
      },
      uploadPhoto (event) {
        if (event.target.files[0] !== undefined && event.target.files[0] !== null) {
          let fileToPost = event.target.files[0]
          if (fileToPost.size > this.maxPhotoFileSize) {
            event.target.value = ''
            this.$alertV({'message': this.strings.filesizeErrorMessage, 'type': 'error'})
            return
          }
          this.filePhotoToPost.append(this.formPhotoFileInputName, fileToPost)
          let that = this
          this.onUpload = true
          this.cancelToken = Axios.CancelToken
          this.sourceCancelToken = this.cancelToken.source()
          Axios.post(this.routePostPicture, this.filePhotoToPost, {
            onUploadProgress (progressEvent) {
              let perform = 100 * progressEvent.loaded / progressEvent.total
              that.performUpload = ((progressEvent.loaded) / (1024 * 1024)).toFixed(2) + 'Mb'
              $('#progress-' + that._uid).progress({
                percent: perform
              })
            },
            cancelToken: that.sourceCancelToken.token
          })
            .then(function (response) {
              that.onUpload = false
              that.filePhotoToPost = new FormData()
              event.target.value = ''
              that.pictures = response.data
            })
            .catch(function (error) {
              that.onUpload = false
              that.filePhotoToPost = new FormData()
              event.target.value = ''
              if (error.response && error.response.status === 422) {
                let msg = error.response.data.errors.addpicture[0]
                that.$alertV({'message': msg, 'type': 'error'})
              }
              else if (error.response && error.response.status === 503) {
                that.$alertV({'message': error.response.data, 'type': 'error'})
              }
              else {
                that.$alertV({'message': that.strings.loadErrorMessage, 'type': 'error'})
              }
            })
        }
      },
      cancelUploadPhoto () {
        let that = this
        this.sourceCancelToken.cancel()
        that.onUpload = false
      },
      getListPosts (event) {
        let that = this
        Axios.get(this.routeGetListPosts)
          .then(function (response) {
            that.pictures = response.data
          })
          .catch(function () {
            that.$alertV({'message': that.strings.loadErrorMessage, 'type': 'error'})
          })
      },
      delPhoto (event) {
        event.preventDefault()
        let that = this
        Axios.delete(this.routeDelPicture + '/' + event.target.dataset.file)
          .then(function (response) {
            that.pictures = response.data
          })
          .catch(function () {
            that.$alertV({'message': that.strings.loadErrorMessage, 'type': 'error'})
          })
      },
      setPicturesIndicators () {
        let resultIndicator
        if (this.isDelegation) {
          resultIndicator = this.maxFiles - this.pictures.length
        }
        else {
          resultIndicator = this.advertFormPhotoNbFreePicture - this.pictures.length
        }
        if (resultIndicator >= 0) {
          this.nbPicturesIndicator = resultIndicator
          if (resultIndicator > 1) {
            this.helpHeaderIndicator = this.strings.freePhotoHelpHeaderPlural
          }
          else {
            this.helpHeaderIndicator = this.strings.freePhotoHelpHeaderSingular
          }
        }
        else {
          this.nbPicturesIndicator = -resultIndicator
          if (this.nbPicturesIndicator > 1) {
            this.helpHeaderIndicator = this.strings.payPhotoHelpHeaderPlural
          }
          else {
            this.helpHeaderIndicator = this.strings.payPhotoHelpHeaderSingular
          }
        }
      },
      setMainPicture () {
        let that = this
        if (this.pictures.length === 0) {
          this.mainPicture = ''
        }
        else if (this.pictures.length === 1 || this.pictures.indexOf(this.mainPicture) === -1) {
          let firstElem = $('#slider1-' + this._uid + '-0')
          this.mainPicture = firstElem.children('input').val()
          firstElem.checkbox('check')
        }
        else if (this.pictures.indexOf(this.mainPicture) >= 0) {
          this.pictures.forEach(function (elem, index) {
            if (elem === that.mainPicture) {
              $('#slider1-' + that._uid + '-' + index).checkbox('check')
            }
          })
        }
      }
    }
  }
</script>
