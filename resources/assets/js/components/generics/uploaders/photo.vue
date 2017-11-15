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
    export default {
        props: {
            //vue routes
            routePostPicture: String,
            routeGetListPosts: String,
            routeDelPicture: String,
            //vue vars
            advertFormPhotoNbFreePicture: Number,
            maxFiles: Number,
            isDelegation: Boolean,
            oldMainPicture: {
                type: String,
                required: false,
                default: ''
            },
            nbColumns: {
                type: String,
                required: false,
                default: "three"
            }
        },
        computed: {
            strings () {
                return this.$store.state.strings['photo-uploader']
            }
        },
        watch: {
            pictures () {
                this.setPicturesIndicators();
                this.setMainPicture();
                this.$emit('updatePictures', this.pictures);
            },
            mainPicture () {
                this.$emit('updateMainPicture', this.mainPicture);
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
                sourceCancelToken: null,
            };
        },
        mounted () {
            this.setPicturesIndicators();
            this.helpUpload();
            this.getListPosts();
        },
        updated () {
            let that = this;
            this.mainPicture = this.oldMainPicture;
            this.setMainPicture();
            (this.pictures).forEach(function (elem,index) {
                $('#slider1-'+that._uid+'-'+index).checkbox({
                    onChange: function () {
                        that.mainPicture = this.value;
                    }
                });
            });
            $('#fakeSlider1-'+that._uid).checkbox();
        },
        methods: {
            triggerClickInput: function () {
                $('#'+this.formPhotoFileInputName).click()
            },
            helpUpload: function () {
                let htmlObject = $('<p>'+this.strings.photoHelpContent+'</p>');
                this.helpUploadP = htmlObject[0].firstChild.data;
                this.helpUploadA = htmlObject[0].firstElementChild.innerHTML;
                this.helpUploadAHref = htmlObject[0].firstElementChild.href;
            },
            uploadPhoto: function (event) {
                if(event.target.files[0] != undefined){
                    this.filePhotoToPost.append(this.formPhotoFileInputName, event.target.files[0]);
                    let that = this;
                    this.onUpload = true;
                    this.cancelToken = axios.CancelToken;
                    this.sourceCancelToken = this.cancelToken.source();
                    axios.post(this.routePostPicture, this.filePhotoToPost, {
                        onUploadProgress: function (progressEvent) {
                            let perform = 100*(progressEvent.loaded)/progressEvent.total;
                            that.performUpload = ((progressEvent.loaded)/(1024*1024)).toFixed(2)+'Mb';
                            $('#progress-'+that._uid).progress({
                                percent: perform
                            });
                        },
                        cancelToken: that.sourceCancelToken.token,
                    })
                        .then(function (response) {
                            that.onUpload = false;
                            that.filePhotoToPost = new FormData();
                            event.target.value="";
                            that.pictures = response.data;
                        })
                        .catch(function (error) {
                            that.onUpload = false;
                            that.filePhotoToPost = new FormData();
                            event.target.value="";
                            if (error.response && error.response.status == 422) {
                                let msg = error.response.data.errors.addpicture[0];
                                that.$emit('sendToast', {'message': msg, 'type':'error'});
                            } else if(error.response && error.response.status == 413) {
                                that.$emit('fileSizeError');
                            }  else if(error.response && error.response.status == 503) {
                                that.$emit('sendToast', {'message': error.response.data, 'type':'error'});
                            }else {
                                that.$emit('loadError');
                            }
                        });
                }
            },
            cancelUploadPhoto: function () {
                let that = this;
                this.sourceCancelToken.cancel();
                that.onUpload = false;
            },
            getListPosts: function (event) {
                let that = this;
                axios.get(this.routeGetListPosts)
                    .then(function (response) {
                        that.pictures = response.data;
                    })
                    .catch(function (error) {
                        that.$emit('loadError');
                    });
            },
            delPhoto: function (event) {
                event.preventDefault();
                let that=this;
                axios.delete(this.routeDelPicture + '/' + event.target.dataset.file)
                    .then(function (response) {
                        that.pictures = response.data;
                    })
                    .catch(function (error) {
                        that.$emit('loadError');
                    });
            },
            setPicturesIndicators () {
                let resultIndicator;
                if(this.isDelegation==1){
                    resultIndicator =  this.maxFiles - this.pictures.length;
                } else {
                    resultIndicator =  this.advertFormPhotoNbFreePicture - this.pictures.length;
                }
                if(resultIndicator>=0){
                    this.nbPicturesIndicator = resultIndicator;
                    if(resultIndicator>1){
                        this.helpHeaderIndicator = this.strings.freePhotoHelpHeaderPlural;
                    } else {
                        this.helpHeaderIndicator = this.strings.freePhotoHelpHeaderSingular;
                    }
                } else {
                    this.nbPicturesIndicator = -resultIndicator;
                    if(this.nbPicturesIndicator>1){
                        this.helpHeaderIndicator = this.strings.payPhotoHelpHeaderPlural;
                    } else {
                        this.helpHeaderIndicator = this.strings.payPhotoHelpHeaderSingular;
                    }
                }
            },
            setMainPicture() {
                let that = this;
                if(this.pictures.length == 0){
                    this.mainPicture ='';
                } else if(this.pictures.length == 1 || this.pictures.indexOf(this.mainPicture)==-1){
                    let firstElem = $('#slider1-'+this._uid+'-0');
                    this.mainPicture=firstElem.children('input').val();
                    firstElem.checkbox('check');
                } else if(this.pictures.indexOf(this.mainPicture)>=0) {
                    (this.pictures).forEach(function (elem,index) {
                        if(elem==that.mainPicture){
                            $('#slider1-'+that._uid+'-'+index).checkbox('check');
                        }
                    });
                }
            },
        }
    }
</script>
