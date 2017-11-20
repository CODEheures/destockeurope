<template>
    <div class="field">
        <div class="ui grid" v-show="videoOnUpload">
            <div class="sixteen wide column">
                <div :id="'progress-'+_uid" class="ui blue active progress">
                    <div class="bar">
                        <div class="progress"></div>
                    </div>
                    <div class="label"><a class="ui orange button" v-on:click="cancelUploadVideo()">{{ strings.videoBtnCancel }}</a></div>
                </div>
            </div>
        </div>
        <div class="ui grid" v-show="videoOnUpload">
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
        <div class="ui grid" v-show="onCloseTicket">
            <div class="sixteen wide center aligned column">
                {{ strings.waitingMessage }}
                <div class="ui massive active centered inline loader"></div>
            </div>
        </div>


        <div class="ui grid" v-show="videoId=='' && !(videoOnUpload || onCloseTicket)">
            <div class="sixteen wide column" v-if="!videoOnUpload">
                <div class="row">
                    <div class="ui icon big primary button" v-on:click="triggerClickInput()">
                        <i class="plus icon"></i>
                        {{ strings.videoBtnLabel }}
                    </div>
                </div>
                <div class="row">
                    <div class="ui top blue pointing basic label">
                        {{ strings.videoLabel }}
                    </div>
                </div>
                <input :id="formVideoFileInputName" type="file" :name="formVideoFileInputName" v-on:change="uploadVideo" style="display: none">
            </div>
        </div>


        <div :id="'vimeodiv-'+_uid" class="sixteen wide column" v-show="videoId!=''">
            <div :class="format != undefined && format == 'auto' ? 'ui basic segment' : 'ui basic compact segment'" v-if="videoId && !videoReady">
                <div class="ui inverted active dimmer" style="background-color: white;">
                    <div :class="format != undefined && format == 'auto' ? 'ui text loader': 'ui massive text loader'">{{ strings.transcodeMessage }}</div>
                </div>
                <iframe :id="'vimeo-iframe-'+_uid" :src="'https://player.vimeo.com/video/' + videoId" :width="iframeWidth" :height="iframeHeight" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
            </div>
            <iframe v-if="videoId && videoReady" :id="'vimeo-iframe-'+_uid" :src="'https://player.vimeo.com/video/' + videoId" :width="iframeWidth" :height="iframeHeight" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>
        <div class="sixteen wide column" v-if="videoId!=''">
            <a class="ui red button" v-on:click="delVideo()">{{ strings.videoBtnDelete }}</a>
        </div>

    </div>
</template>


<script>
  /**
   * Props
   *  - routeGetVideoPostTicket: String. The route to get a ticket for post video
   *  - routeDelTempoVideo: String. The route to del the actual upload video
   *  - routeGetStatusVideo: String. The route to get the status of upload video
   *  - maxVideoFileSize: Number. The maximum size of the video
   *  - sessionVideoId: String. The id of the actual upload video
   *  - format: String. The format for iframe: 'auto'
   *
   * Events:
   *  @vimeoStateChange: emit state of video: {hasVideo: Boolean, videoId: 'theVideoId'}
   *  @videoUploadStatusChange: emit true when upload on progress. Otherwise emit false
   */
  import Axios from 'axios'
  export default {
    props: {
      // vue routes
      routeGetVideoPostTicket: String,
      routeDelTempoVideo: String,
      routeGetStatusVideo: String,
      // vue vars
      maxVideoFileSize: Number,
      sessionVideoId: String,
      format: {
        type: String,
        required: false
      }
    },
    computed: {
      strings () {
        return this.$store.state.strings['vimeo-uploader']
      }
    },
    watch: {
      videoId () {
        let hasVideo = this.videoId !== undefined && this.videoId !== null && this.videoId !== ''
        this.$emit('vimeoStateChange', {'hasVideo': hasVideo, 'videoId': null})
        if (hasVideo) {
          this.timeout(0.01)
        }
      },
      videoOnUpload () {
        this.$emit('videoUploadStatusChange', this.videoOnUpload)
      }
    },
    data () {
      return {
        formVideoFileInputName: 'addvideo',
        videoInputEventTarget: null,
        videoBlob: undefined,
        fileToUpload: undefined,
        videoOnUpload: false,
        performUpload: 0,
        retry: 0,
        onCloseTicket: false,
        videoId: '',
        cancelToken: null,
        sourceCancelToken: null,
        videoReady: false,
        iframeWidth: 600,
        iframeHeight: 360
      }
    },
    mounted () {
      if (this.format !== undefined && this.format !== null && this.format === 'auto') {
        this.iframeWidth = 'auto'
        this.iframeHeight = 'auto'
      }
      this.videoId = this.sessionVideoId
    },
    methods: {
      triggerClickInput () {
        $('#' + this.formVideoFileInputName).click()
      },
      resetUploadVideoState () {
        this.videoBlob = undefined
        this.videoInputEventTarget.value = ''
        this.performUpload = 0
        $('#progress-' + this._uid).progress({
          percent: 0
        })
        this.videoOnUpload = false
      },
      uploadVideo (event) {
        // File to Post
        this.videoInputEventTarget = event.target
        this.fileToUpload = this.videoInputEventTarget.files[0]
        if (this.fileToUpload !== undefined && this.fileToUpload !== null) {
          if (this.fileToUpload.size > this.maxVideoFileSize) {
            this.videoInputEventTarget.value = ''
            this.$alertV({'message': this.strings.filesizeErrorMessage, 'type': 'error'})
          }
          else {
            this.videoBlob = new Blob([this.fileToUpload], {type: this.fileToUpload.type})
            // get ticket to set routes post
            this.getTicket()
          }
        }
      },
      getTicket () {
        // Get Ticket, return routes
        let that = this
        this.videoOnUpload = true
        Axios.put(this.routeGetVideoPostTicket, {'size': that.fileToUpload.size, 'type': that.fileToUpload.type})
          .then(function (response) {
            that.postVideo(response.data)
          })
          .catch(function (error) {
            that.resetUploadVideoState()
            if (error.response && error.response.status === 503) {
              that.$alertV({'message': error.response.data, 'type': 'error'})
            }
            else {
              that.$alertV({'message': that.strings.loadErrorMessage, 'type': 'error'})
            }
          })
      },
      postVideo (routes, offset) {
        if (offset === undefined) { offset = 0 }
        let that = this
        this.cancelToken = Axios.CancelToken
        this.sourceCancelToken = this.cancelToken.source()
        Axios.request({
          cancelToken: that.sourceCancelToken.token,
          url: routes.routePutVideo,
          method: 'put',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': that.fileToUpload.type,
            'Content-Range': 'bytes ' + offset + '-' + (that.fileToUpload.size - 1) + '/' + that.fileToUpload.size
          },
          data: that.videoBlob.slice(offset, that.fileToUpload.size - 1),
          validateStatus (status) {
            return status >= 200 && status < 400
          },
          onUploadProgress (progressEvent) {
            let perform = 100 * (offset + progressEvent.loaded) / progressEvent.total
            that.performUpload = ((offset + progressEvent.loaded) / (1024 * 1024)).toFixed(2) + 'Mb'
            $('#progress-' + that._uid).progress({
              percent: perform
            })
          }
        })
          .then(function (response) {
            if (response.status === 308) {
              this.retry = this.retry + 1
              if (this.retry < 10) {
                setTimeout(function () {
                  let calcOffset = that.extractPerformUpload(response.headers.range) + 1
                  this.postVideo(routes, calcOffset)
                }, this.retry * 1000)
              }
              else {
                that.resetUploadVideoState()
                that.$alertV({'message': that.strings.loadErrorMessage, 'type': 'error'})
              }
            }
            else {
              that.closeTicket(routes.routeCloseTicket, routes.completeVideoUpload)
            }
          })
          .catch(function () {
            that.resetUploadVideoState()
            that.$alertV({'message': that.strings.loadErrorMessage, 'type': 'error'})
          })
      },
      progressPostVideo (routeGetProgress) {
        let that = this
        Axios.request({
          url: routeGetProgress,
          method: 'put',
          headers: {
            'Content-Type': 'bytes */*'
          },
          validateStatus (status) {
            return status === 308
          }
        })
          .then(function (response) {
            let perform = that.extractPerformUpload(response.headers.range)
            $('#progress-' + this._uid).progress({
              total: that.fileToUpload.size,
              value: perform.toFixed(1)
            })
            if (perform >= that.fileToUpload.size) {
            }
          })
          .catch(function () {
          })
      },
      closeTicket (routeCloseTicket, routesCompleteVideoUpload) {
        let that = this
        this.onCloseTicket = true
        this.resetUploadVideoState()
        Axios.patch(routeCloseTicket, {'completeVideoUpload': routesCompleteVideoUpload})
          .then(function (response) {
            that.onCloseTicket = false
            let location = response.headers.location
            that.videoId = location.substr(8)
          })
          .catch(function () {
            that.onCloseTicket = false
            that.$alertV({'message': that.strings.loadErrorMessage, 'type': 'error'})
          })
      },
      extractPerformUpload (range) {
        let pos = range.lastIndexOf('-')
        return parseInt(range.substr(pos + 1))
      },
      cancelUploadVideo () {
        this.sourceCancelToken.cancel()
        this.resetUploadVideoState()
      },
      delVideo () {
        let that = this
        Axios.delete(this.routeDelTempoVideo + '/' + that.videoId)
          .then(function (response) {
            that.videoId = ''
            that.videoReady = false
          })
          .catch(function () {
            that.$alertV({'message': that.strings.loadErrorMessage, 'type': 'error'})
          })
      },
      timeout (seconds) {
        let counter = 0
        let that = this
        setTimeout(function () {
          counter++
          Axios.get(that.routeGetStatusVideo)
            .then(function (response) {
              if (response.data.status === 'available') {
                setTimeout(function () {
                  that.videoReady = true
                  that.$emit('vimeoStateChange', {'hasVideo': true, 'videoId': that.videoId})
                }, 2000)
              }
              else {
                that.timeout(10 + (Math.random() * 10))
              }
            })
            .catch(function () {
              if (counter < 4) {
                that.timeout(10 + (Math.random() * 10))
              }
            })
        }, seconds * 1000)
      }
    }
  }
</script>
