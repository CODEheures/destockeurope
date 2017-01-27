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
            <div class="ui doubling three column grid">
                <div class="column" v-for="(thumb,index) in thumbs">
                    <div :class="!isDelegation &&  index>=advertFormPhotoNbFreePicture ? 'ui pink segment' : 'ui segment'">
                        <a class="ui pink right ribbon label" v-if="!isDelegation && index>=advertFormPhotoNbFreePicture">{{ advertFormPayPhotoHelpHeaderSingular }}</a>
                        <div class="ui stackable grid">
                            <div class="four wide centered column">
                                <a href="#"><i class="large grey remove circle outline icon" :data-file="thumb" v-on:click="delPhoto"></i></a>
                            </div>
                            <div class="twelve wide right aligned column">
                                <div :id="'slider1-'+_uid+'-'+index" class="ui slider checkbox">
                                    <input type="radio" name="mainThumb" :value="thumb">
                                    <label>{{ advertFormMainPhotoLabel }}</label>
                                </div>
                            </div>
                            <div class="sixteen wide column">
                                <img :src="routeGetTempoThumb+'/'+thumb" class="ui rounded medium centered image" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="field">
            <template v-if="thumbs.length<maxFiles">
                <div class="ui grid">
                    <div class="sixteen wide column">
                        <div class="row">
                            <div class="ui icon big primary button" v-on:click="triggerClickInput()">
                                <i class="plus icon"></i>
                                {{ advertFormPhotoBtnLabel }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="sixteen wide column">
                                <div class="ui top blue pointing basic label">
                                    {{ advertFormPhotoLabel }}
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
            routePostTempoPicture: String,
            routeGetListTempoThumbs: String,
            routeGetTempoThumb: String,
            routeDelTempoPicture: String,
            //vue vars
            advertFormPhotoNbFreePicture: Number,
            maxFiles: Number,
            isDelegation: Boolean,
            //vue strings
            advertFormPhotoBtnLabel: String,
            advertFormPhotoLabel: String,
            advertFormFreePhotoHelpHeaderSingular: String,
            advertFormFreePhotoHelpHeaderPlural: String,
            advertFormPayPhotoHelpHeaderSingular: String,
            advertFormPayPhotoHelpHeaderPlural: String,
            advertFormPhotoHelpContent: String,
            advertFormMainPhotoLabel: String
        },
        data: () => {
            return {
                filePhotoToPost: new FormData(),
                formPhotoFileInputName: 'addpicture',
                helpUploadP: '',
                helpUploadA: '',
                helpUploadAHref: '',
                thumbs: [],
                nbPicturesIndicator: '',
                helpHeaderIndicator: '',
                mainPicture: '',
            };
        },
        mounted () {
            this.setPicturesIndicators();
            this.helpUpload();
            this.getListThumbs();
            this.$watch('thumbs', function () {
                this.setPicturesIndicators();
                this.setMainPicture();
                this.$parent.$emit('updateThumbs', this.thumbs);
            });
            this.$watch('mainPicture', function () {
                this.$parent.$emit('updateMainPicture', this.mainPicture);
            });
        },
        updated () {
            let that = this;
            for(let index in this.thumbs){
                $('#slider1-'+this._uid+'-'+index).checkbox({
                    onChange: function () {
                        console.log('on change slider Main Picture');
                        that.mainPicture = this.value;
                    }
                });
            }
        },
        methods: {
            triggerClickInput: function () {
                $('#'+this.formPhotoFileInputName).click()
            },
            helpUpload: function () {
                let htmlObject = $('<p>'+this.advertFormPhotoHelpContent+'</p>');
                this.helpUploadP = htmlObject[0].firstChild.data;
                this.helpUploadA = htmlObject[0].firstElementChild.innerHTML;
                this.helpUploadAHref = htmlObject[0].firstElementChild.href;
            },
            uploadPhoto: function (event) {
                if(event.target.files[0] != undefined){
                    this.filePhotoToPost.append(this.formPhotoFileInputName, event.target.files[0]);
                    let that = this;
                    this.$http.post(this.routePostTempoPicture, this.filePhotoToPost)
                        .then(
                            function (response) {
                                that.filePhotoToPost.delete(that.formPhotoFileInputName);
                                event.target.value="";
                                that.thumbs = response.body;
                            },
                            function (response) {
                                that.filePhotoToPost.delete(that.formPhotoFileInputName);
                                event.target.value="";
                                if (response.status == 422) {
                                    let msg = response.body.addpicture[0];
                                    that.$parent.$emit('sendToast', {'message': msg, 'type':'error'});
                                } else if(response.status == 413) {
                                    that.$parent.$emit('fileSizeError');
                                } else {
                                    that.$parent.$emit('loadError');
                                }
                            }
                        );
                }
            },
            getListThumbs: function (event) {
                let that = this;
                this.$http.get(this.routeGetListTempoThumbs)
                    .then(
                        function (response) {
                            that.thumbs = response.body;
                        },
                        function (response) {
                            that.$parent.$emit('loadError');
                        }
                    );
            },
            delPhoto: function (event) {
                event.preventDefault();
                let that=this;
                this.$http.delete(this.routeDelTempoPicture+'/'+event.target.dataset.file)
                    .then(
                        function (response) {
                            that.thumbs = response.body;
                        },
                        function (response) {
                            that.$parent.$emit('loadError');
                        }
                    );
            },
            setPicturesIndicators () {
                let resultIndicator;
                if(this.isDelegation==1){
                    resultIndicator =  this.maxFiles - this.thumbs.length;
                } else {
                    resultIndicator =  this.advertFormPhotoNbFreePicture - this.thumbs.length;
                }
                if(resultIndicator>=0){
                    this.nbPicturesIndicator = resultIndicator;
                    if(resultIndicator>1){
                        this.helpHeaderIndicator = this.advertFormFreePhotoHelpHeaderPlural;
                    } else {
                        this.helpHeaderIndicator = this.advertFormFreePhotoHelpHeaderSingular;
                    }
                } else {
                    this.nbPicturesIndicator = -resultIndicator;
                    if(this.nbPicturesIndicator>1){
                        this.helpHeaderIndicator = this.advertFormPayPhotoHelpHeaderPlural;
                    } else {
                        this.helpHeaderIndicator = this.advertFormPayPhotoHelpHeaderSingular;
                    }
                }
            },
            setMainPicture() {
                if(this.thumbs.length == 0){
                    this.mainPicture ='';
                } else if(this.thumbs.length == 1 || this.thumbs.indexOf(this.mainPicture)==-1){
                    let firstElem = $('#slider1-'+this._uid+'-0');
                    this.mainPicture=firstElem.children('input').val();
                    firstElem.checkbox('check');
                }
            },
        }
    }
</script>
