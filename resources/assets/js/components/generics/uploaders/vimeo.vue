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
  import Axios from 'axios'
    export default {
        props: {
            //vue routes
            routeGetVideoPostTicket : String,
            routeDelTempoVideo: String,
            routeGetStatusVideo: String,
            //vue vars
            maxVideoFileSize: Number,
            sessionVideoId: String,
            format: {
                type: String,
                required: false,
            }
        },
        computed: {
            strings () {
                return this.$store.state.strings['vimeo-uploader']
            }
        },
        watch: {
            videoId () {
                let that = this
                let hasVideo = this.videoId != undefined && this.videoId != null && this.videoId != '';
                this.$emit('vimeoStateChange', {'hasVideo': hasVideo, 'videoId': null});
                if(hasVideo){
                    let counter = 0;
                    function timeout(seconds) {
                        setTimeout(function () {
                            counter++;
                            Axios.get(that.routeGetStatusVideo)
                                .then(function (response) {
                                    if(response.data.status=='available'){
                                        setTimeout(function () {
                                            that.videoReady = true;
                                            that.$emit('vimeoStateChange', {'hasVideo': hasVideo, 'videoId': that.videoId});
                                        },2000)
                                    } else {
                                        timeout(10+(Math.random()*10));
                                    }
                                })
                                .catch(function (error) {
                                    if(counter<4){
                                        timeout(10+(Math.random()*10));
                                    }
                                });
                        }, seconds*1000);
                    }
                    timeout(0.01);
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
            };
        },
        mounted () {
            if(this.format != undefined && this.format=="auto"){
                this.iframeWidth= 'auto';
                this.iframeHeight= 'auto';
            }
            this.videoId = this.sessionVideoId;
        },
        methods: {
            triggerClickInput: function () {
                $('#'+this.formVideoFileInputName).click()
            },
            resetUploadVideoState: function () {
                this.videoBlob = undefined;
                this.videoInputEventTarget.value="";
                this.performUpload = 0;
                $('#progress-'+this._uid).progress({
                    percent: 0
                });
                this.videoOnUpload = false;
            },
            uploadVideo: function (event) {
                //File to Post
                this.videoInputEventTarget = event.target;
                this.fileToUpload = this.videoInputEventTarget.files[0];
                if(this.fileToUpload != undefined){
                    this.videoBlob= new Blob([this.fileToUpload], {type: this.fileToUpload.type});
                    if (this.fileToUpload.size > this.maxVideoFileSize) {
                        this.videoInputEventTarget.value="";
                        this.videoBlob = undefined;
                        this.$emit('fileSizeError');
                    } else {
                        //get ticket to set routes post
                        this.getTicket();
                    }
                }
            },
            getTicket: function () {
                //Get Ticket, return routes
                let that = this;
                this.videoOnUpload = true;
                Axios.put(this.routeGetVideoPostTicket, {'size': that.fileToUpload.size,'type': that.fileToUpload.type})
                    .then(function (response) {
                        that.postVideo(response.data);
                    })
                    .catch(function (error) {
                        that.resetUploadVideoState();
                        if(error.response && error.response.status == 503) {
                            that.$emit('sendToast', {'message': error.response.data, 'type':'error'});
                        } else {
                            that.$emit('loadError');
                        }
                    });
            },
            postVideo: function(routes, offset) {
                offset==undefined ? offset = 0 : null;
                let that = this;
                this.cancelToken = Axios.CancelToken;
                this.sourceCancelToken = this.cancelToken.source();
                Axios.request({
                    cancelToken: that.sourceCancelToken.token,
                    url: routes.routePutVideo,
                    method: 'put',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': that.fileToUpload.type,
                        'Content-Range': 'bytes ' + offset + '-' + (that.fileToUpload.size-1) + '/' + that.fileToUpload.size,
                    },
                    data: that.videoBlob.slice(offset,that.fileToUpload.size-1),
                    validateStatus: function (status) {
                        return status >= 200 && status < 400;
                    },
                    onUploadProgress: function (progressEvent) {
                        let perform = 100*(offset+progressEvent.loaded)/progressEvent.total;
                        that.performUpload = ((offset+progressEvent.loaded)/(1024*1024)).toFixed(2)+'Mb';
                        $('#progress-'+that._uid).progress({
                            percent: perform
                        });
                    },
                })
                    .then(function (response) {
                        if(response.status==308){
                            this.retry = this.retry + 1;
                            let passRetry = this.retry;
                            if(this.retry < 10) {
                                setTimeout(function () {
                                    let calc_offset = that.extractPerformUpload(response.headers.range)+1;
                                    //console.log('retry upload n°' + passRetry + ' at ' + calc_offset + ' bytes');
                                    this.postVideo(routes,calc_offset);
                                },this.retry*1000)
                            } else {
                                that.resetUploadVideoState();
                                that.$emit('loadError');
                            }
                        } else {
                            that.closeTicket(routes.routeCloseTicket, routes.completeVideoUpload);
                        }
                    })
                    .catch(function (error) {
                        that.resetUploadVideoState();
                        that.$emit('loadError');
                    });
            },
            progressPostVideo: function (routeGetProgress) {
                let that = this;
                Axios.request({
                    url: routeGetProgress,
                    method: 'put',
                    headers: {
                        'Content-Type': 'bytes */*'
                    },
                    validateStatus: function (status) {
                        return status == 308; // default
                    }
                })
                    .then(function (response) {
                        let perform = that.extractPerformUpload(response.headers.range);
                        $('#progress-'+this._uid).progress({
                            total: that.fileToUpload.size,
                            value: perform.toFixed(1)
                        });
                        if(perform>=that.fileToUpload.size){

                        }
                    })
                    .catch(function (error) {
                        //console.log('error get Progress');
                    });
            },
            closeTicket: function (routeCloseTicket, routesCompleteVideoUpload) {
                let that = this;
                this.onCloseTicket = true;
                this.resetUploadVideoState();
                Axios.patch(routeCloseTicket, {'completeVideoUpload': routesCompleteVideoUpload})
                    .then(function (response) {
                        that.onCloseTicket = false;
                        let location = response.headers.location;
                        that.videoId = location.substr(8);
                    })
                    .catch(function (error) {
                        that.onCloseTicket = false;
                        that.$emit('loadError');
                    });
            },
            extractPerformUpload: function (range) {
                let pos = range.lastIndexOf('-');
                return parseInt(range.substr(pos+1));
            },
            cancelUploadVideo: function () {
                let that = this;
                this.sourceCancelToken.cancel();
                that.resetUploadVideoState();
            },
            delVideo: function () {
                let that = this;
                Axios.delete(this.routeDelTempoVideo + '/' + that.videoId)
                    .then(function (response) {
                        that.videoId = '';
                        that.videoReady = false;
                    })
                    .catch(function (error) {
                        that.$emit('loadError');
                    });
            },
        }
    }
</script>
