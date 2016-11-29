<template>
    <div  class="ui grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div :id="'modal-'+_uid" class="ui modal">
            <i class="close icon"></i>
            <div class="header">
                {{ contactLabel }}
            </div>
            <div class="content">
                <div :id="'form-'+_uid" class="ui form">
                    <div class="required field">
                        <label>{{ formMessageLabel }}</label>
                        <textarea name="message" v-model="dataMessage" :maxlength="formMessageMaxValid"></textarea>
                    </div>
                    <div class="field">
                        <div class="two fields">
                            <div class="required field">
                                <label>{{ formMessageNameLabel }}</label>
                                <input name="name" type="text" v-model="dataUserName">
                            </div>
                            <div class="required field">
                                <label>{{ formMessageEmailLabel }}</label>
                                <input name="email" type="text" v-model="dataUserMail">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="actions">
                <div class="ui black deny button">
                    {{ formMessageCancelLabel }}
                </div>
                <div :class="dataEnabledMessage == true ? 'ui positive right labeled icon button' : 'ui positive right labeled icon disabled button'">
                    {{ formMessageSendLabel }}
                    <i class="send outline icon"></i>
                </div>
            </div>
        </div>
        <div class="sixteen wide column">
            <div class="row">
                <breadcrumb
                        :items="breadcrumbItems"
                        :withAction="true">
                </breadcrumb>
            </div>
        </div>
        <div class="row">
            <div class="sixteen wide column">
                <div class="header"><h3><span v-if="isUrgent" class="ui red horizontal label">{{ urgentLabel }}</span>{{ title }}</h3></div>
            </div>
            <div class="sixteen wide tablet ten wide computer column">
                <div class="row">
                    <advert-by-id
                        :route-get-advert="routeGetAdvert"
                        :actual-locale="actualLocale"
                        :image-ratio="parseFloat(imageRatio)"
                        :total-quantity-label="totalQuantityLabel"
                        :lot-mini-quantity-label="lotMiniQuantityLabel"
                        :urgent-label="urgentLabel"
                        :price-info-label="priceInfoLabel"
                        :price-label="priceLabel"
                    ></advert-by-id>
                </div>
            </div>
            <div id="welcome-ads" class="computer only six wide column">
                <div>
                    <div class="ui form">
                        <div class="field" v-if="!isUserOwner">
                            <button class="ui basic teal icon fluid button"
                                    v-on:click="openMessageBox">
                                <i class="mail outline icon"></i>
                                {{ contactLabel }}
                            </button>
                        </div>
                        <div class="field" v-if="userName == ''">
                            <div class="ui labeled button">
                                <div class="ui red button">
                                    <i class="heart icon"></i> {{ bookmarkInfo }}
                                </div>
                                <a class="ui basic red left pointing label">
                                    {{ bookmarkCount }}
                                </a>
                            </div>
                        </div>
                        <div class="field" v-if="userName != '' && !isUserOwner && !dataIsUserBookmark">
                            <button class="ui basic pink icon fluid button">
                                <i class="empty heart icon"></i>
                                {{ bookmarkLabel }}
                            </button>
                        </div>
                        <div class="field" v-if="userName != '' && !isUserOwner && dataIsUserBookmark">
                            <button class="ui basic pink icon fluid button">
                                <i class="heart icon"></i>
                                {{ unbookmarkLabel }}
                            </button>
                        </div>
                        <div class="field" v-if="isUserOwner">
                            <button class="ui red icon button">
                                <i class="trash outline icon"></i>
                                {{ deleteLabel }}
                            </button>
                        </div>
                    </div>
                    <div class="sixteen right aligned column spaced-top-2">
                        <div class="ui small rectangle centered test ad" data-text="Small Rectangle"></div>
                        <div class="ui small rectangle centered test ad" data-text="Small Rectangle"></div>
                        <!--<div class="ui wide skyscraper test ad welcome-ads" data-text="Wide Skyscraper"></div>-->
                    </div>
                </div>
            </div>
        </div>
        <div class="mobile only tablet only sixteen wide center aligned column" v-if="!isUserOwner">
            <button class="ui basic teal icon fluid button"
                    v-on:click="openMessageBox">
                <i class="mail outline icon"></i>
                {{ contactLabel }}
            </button>
        </div>
        <div class="mobile only tablet only sixteen wide center aligned column"  v-if="userName == ''">
            <div class="ui labeled button">
                <div class="ui red button">
                    <i class="heart icon"></i> {{ bookmarkInfo }}
                </div>
                <a class="ui basic red left pointing label">
                    {{ bookmarkCount }}
                </a>
            </div>
        </div>
        <div class="mobile only tablet only sixteen wide center aligned column"  v-if="userName != '' && !isUserOwner && !dataIsUserBookmark">
            <button class="ui basic pink icon fluid button">
                <i class="empty heart icon"></i>
                {{ bookmarkLabel }}
            </button>
        </div>
        <div class="mobile only tablet only sixteen wide center aligned column"  v-if="userName != '' && !isUserOwner && dataIsUserBookmark">
            <button class="ui basic pink icon fluid button">
                <i class="heart icon"></i>
                {{ unbookmarkLabel }}
            </button>
        </div>
        <div class="mobile only tablet only sixteen wide center aligned column"  v-if="isUserOwner">
            <button class="ui red icon button">
                <i class="trash outline icon"></i>
                {{ deleteLabel }}
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            //vue routes
            'routeSendMail',
            //vue vars
            'userMail',
            'userName',
            'isUserOwner',
            'isUserBookmark',
            'bookmarkCount',
            'formNameMinValid',
            'formMessageMinValid',
            'formMessageMaxValid',
            //vue strings
            'loadErrorMessage',
            'sendSuccessMessage',
            'formValidationEmail',
            'formPointingMinimumChars',
            'formPointingMaximumChars',
            'contactLabel',
            'bookmarkInfo',
            'bookmarkLabel',
            'unbookmarkLabel',
            'deleteLabel',
            'formMessageLabel',
            'formMessageEmailLabel',
            'formMessageNameLabel',
            'formMessageSendLabel',
            'formMessageCancelLabel',
            //advertById component
            'routeGetAdvert',
            'routeHome',
            'actualLocale',
            'imageRatio',
            'totalQuantityLabel',
            'lotMiniQuantityLabel',
            'urgentLabel',
            'priceInfoLabel',
            'priceLabel'
        ],
        data: () => {
            return {
                typeMessage : '',
                message : '',
                sendMessage: false,
                breadcrumbItems: [],
                isUrgent: false,
                title: '',
                id: 0,
                dataUserName: '',
                dataUserMail: '',
                dataMessage: '',
                dataEnabledMessage: false,
                dataIsUserBookmark: false
            }
        },
        mounted () {
            this.dataUserMail = this.userMail;
            this.dataUserName = this.userName;
            this.dataIsUserBookmark = this.isUserBookmark;
            //Visibility for ADS
            let $elem = $('#welcome-ads').children('div');
            $elem.visibility({
                type   : 'fixed',
                offset : 112
            });

            //On load Error
            this.$on('loadError', function () {
                this.sendToast(this.loadErrorMessage, 'error');
            });
            this.$on('setBreadCrumb', function (chainedCategories) {
                this.setBreadCrumbItems(chainedCategories)
            });
            this.$on('setHeader', function (advert) {
                this.isUrgent = advert.isUrgent;
                this.title=  advert.title;
                this.id = advert.id;
            });
            this.$on('categoryChoice', function (category) {
                sessionStorage.setItem('goToCategory', category.id);
                window.location.href = this.routeHome;
            });
            let that = this;
            let messageForm = $('#form-'+this._uid);
            messageForm
                .form({
                    fields : {
                        email: {
                            identifier  : 'email',
                            rules: [
                                {
                                    type   : 'email',
                                    prompt : that.formValidationEmail
                                }
                            ]
                        },
                        name: {
                            identifier  : 'name',
                            rules: [
                                {
                                    type : 'minLength['+that.formNameMinValid+']',
                                    prompt: '{ruleValue} ' + that.formPointingMinimumChars
                                }
                            ]
                        },
                        message: {
                            identifier  : 'message',
                            rules: [
                                {
                                    type : 'minLength['+that.formMessageMinValid+']',
                                    prompt: '{ruleValue} ' + that.formPointingMinimumChars
                                },
                                {
                                    type : 'maxLength['+that.formMessageMaxValid+']',
                                    prompt: '{ruleValue} ' + that.formPointingMaximumChars
                                }
                            ]
                        }
                    },
                    inline : true,
                    on     : 'change'
                })
            ;
            this.$watch('dataUserName', function () {
                this.testValidForm();
            });
            this.$watch('dataUserMail', function () {
                this.testValidForm();
            });
            this.$watch('dataMessage', function () {
                this.testValidForm();
            })
        },
        methods: {
            sendToast: function(message,type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            },
            setBreadCrumbItems: function (chainedCategories) {
                this.breadcrumbItems = [];
                for(let index in chainedCategories){
                    this.breadcrumbItems.push({
                        name: chainedCategories[index]['description'][this.actualLocale],
                        value: chainedCategories[index].id
                    });
                }
            },
            openMessageBox: function () {
                let modalForm = $('#modal-'+this._uid);
                let that = this;
                modalForm.modal({
                    closable: true,
                    blurring: true,
                    onApprove: function () {
                        that.$http.post(that.routeSendMail, {'id': that.id, 'name': that.dataUserName, 'email': that.dataUserMail, 'message': that.dataMessage})
                            .then(
                                (response) => {
                                    that.sendToast(that.sendSuccessMessage, 'success');
                                },
                                (response) => {
                                    if (response.status == 409) {
                                        that.sendToast(response.body, 'error');
                                    }
                                    that.sendToast(that.loadErrorMessage, 'error');
                                }
                            );
                    }
                }).modal('show');
            },
            testValidForm: function () {
                this.dataEnabledMessage = $('#form-'+this._uid).form('is valid');
            }
        }
    }
</script>
