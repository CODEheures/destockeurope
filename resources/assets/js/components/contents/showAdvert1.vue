<template>
    <div>
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div :id="'modal1-'+_uid" class="ui modal">
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
                    <div class="field">
                        <div class="two fields">
                            <div class="field">
                                <label>{{ formMessagePhoneLabel }}</label>
                                <input name="phone" type="text" v-model="dataUserPhone">
                            </div>
                            <div class="field">
                                <label>{{ formMessageCompagnyLabel }}</label>
                                <input name="compagnyName" type="text" v-model="dataUserCompagnyName">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="actions">
                <div style="float: left" v-if="dataOwnerUser.phone && dataOwnerUser.phone!=null && dataOwnerUser.phone!=''">
                    <button class="ui blue button" v-on:click="seePhone()" v-if="!phoneIsVisible" autofocus>
                        {{ formSeePhoneLabel }}
                    </button>
                    <div class="ui large label" style="float: left" v-else>
                        <i class="phone icon"></i> {{ dataOwnerUser.phone }}
                    </div>
                </div>

                <div class="ui black deny button">
                    {{ formMessageCancelLabel }}
                </div>
                <div :class="dataEnabledMessage == true ? 'ui positive right labeled icon button' : 'ui positive right labeled icon disabled button'">
                    {{ formMessageSendLabel }}
                    <i class="send outline icon"></i>
                </div>
            </div>
        </div>
        <div :id="'modal2-'+_uid" class="ui basic modal">
            <i class="close icon"></i>
            <div class="header">
                {{ modalValidHeader }}
            </div>
            <div class="image content">
                <div class="image">
                    <i class="legal icon"></i>
                </div>
                <div class="description">
                    <p>{{ modalValidDescription }}</p>
                </div>
            </div>
            <div class="actions">
                <div class="two fluid ui inverted buttons">
                    <div class="ui cancel red basic inverted button">
                        <i class="remove icon"></i>
                        {{ modalNo }}
                    </div>
                    <div class="ui ok green basic inverted button">
                        <i class="checkmark icon"></i>
                        {{ modalYes }}
                    </div>
                </div>
            </div>
        </div>
        <div :id="'modal3-'+_uid" class="ui modal">
            <i class="close icon"></i>
            <div class="header">
                {{ reportLabel }}
            </div>
            <div class="content">
                <div :id="'reportform-'+_uid" class="ui form">
                    <div class="required field">
                        <label>{{ formMessageLabel }}</label>
                        <textarea name="reportmessage" v-model="dataReportMessage" :maxlength="formMessageMaxValid"></textarea>
                    </div>
                    <div class="required field">
                        <label>{{ formMessageEmailLabel }}</label>
                        <input name="reportemail" type="text" v-model="dataUserMail">
                    </div>
                </div>
            </div>
            <div class="actions">
                <div class="ui black deny button">
                    {{ formMessageCancelLabel }}
                </div>
                <div :class="dataEnabledReportMessage == true ? 'ui positive right labeled icon button' : 'ui positive right labeled icon disabled button'">
                    {{ formMessageSendLabel }}
                    <i class="send outline icon"></i>
                </div>
            </div>
        </div>
        <div class="ui grid">
            <div class="sixteen wide column">
                <div class="row">
                    <breadcrumb
                            :items="breadcrumbItems"
                            :withAction="true"
                    ></breadcrumb>
                </div>
            </div>
            <div class="row">
                <div class="sixteen wide column">
                    <div class="header"><h3><span v-if="dataAdvert.isUrgent" class="ui red horizontal label">{{ urgentLabel }}</span>{{ dataAdvert.title }}</h3></div>
                </div>
                <div class="sixteen wide tablet ten wide computer column">
                    <div class="row">
                        <div class="ui active inverted dimmer" v-if="!isLoaded">
                            <div class="ui large text loader">Loading</div>
                        </div>
                        <advert-by-id
                                :advert="dataAdvert"
                                :actual-locale="actualLocale"
                                :image-ratio="parseFloat(imageRatio)"
                                :user-name="userName"
                                :is-user-owner="isUserOwner==1"
                                :total-quantity-label="totalQuantityLabel"
                                :lot-mini-quantity-label="lotMiniQuantityLabel"
                                :urgent-label="urgentLabel"
                                :is-negociated-label="isNegociatedLabel"
                                :price-info-label="priceInfoLabel"
                                :price-label="priceLabel"
                                :ref-label="refLabel"
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
                            <div class="field" v-if="userName == '' || isUserOwner">
                                <div class="ui labeled button disabled-bookmark">
                                    <div class="ui yellow button">
                                        <i class="heart icon"></i> {{ bookmarkInfo }}
                                    </div>
                                    <a class="ui basic yellow left pointing label">
                                        {{ dataAdvert.bookmarkCount }}
                                    </a>
                                </div>
                            </div>
                            <div class="field" v-if="userName != '' && !isUserOwner && !dataIsUserBookmark">
                                <button class="ui basic yellow icon fluid button"
                                    v-on:click="bookmarkMe()">
                                    <i class="empty heart icon"></i>
                                    {{ bookmarkLabel }}
                                </button>
                            </div>
                            <div class="field" v-if="userName != '' && !isUserOwner && dataIsUserBookmark">
                                <button class="ui basic yellow icon fluid button"
                                        v-on:click="unbookmarkMe()">
                                    <i class="heart icon"></i>
                                    {{ unbookmarkLabel }}
                                </button>
                            </div>
                            <div class="field" v-if="isUserOwner">
                                <advert-manage-button
                                        :advert="dataAdvert"
                                        :with-see-action="false"
                                        :manage-advert-label="manageAdvertLabel"
                                        :edit-advert-label="editAdvertLabel"
                                        :see-advert-label="seeAdvertLabel"
                                        :delete-advert-label="deleteAdvertLabel"
                                        :back-to-top-label="backToTopAdvertLabel"
                                        :highlight-label="highlightAdvertLabel"
                                        :renew-advert-label="renewAdvertLabel"
                                        :see-advert-popup-label="seeAdvertPopupLabel"
                                        :edit-advert-popup-label="editAdvertPopupLabel"
                                        :delete-advert-popup-label="deleteAdvertPopupLabel"
                                        :back-to-top-popup-label="backToTopPopupLabel"
                                        :highlight-popup-label="highlightPopupLabel"
                                        :renew-advert-popup-label="renewAdvertPopupLabel"
                                ></advert-manage-button>
                            </div>
                            <div class="fb-share-button"
                                 :data-href="advert.url"
                                 data-layout="button"
                                 data-size="large"
                            ></div>
                        </div>
                        <div class="sixteen right aligned column spaced-top-2">
                            <vertical-160x600></vertical-160x600>
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
            <div class="mobile only tablet only sixteen wide center aligned column"  v-if="userName != '' && !isUserOwner && !dataIsUserBookmark">
                <button class="ui basic yellow icon fluid button"
                        v-on:click="bookmarkMe()">
                    <i class="empty heart icon"></i>
                    {{ bookmarkLabel }}
                </button>
            </div>
            <div class="mobile only tablet only sixteen wide center aligned column"  v-if="userName != '' && !isUserOwner && dataIsUserBookmark">
                <button class="ui basic yellow icon fluid button"
                        v-on:click="unbookmarkMe()">
                    <i class="heart icon"></i>
                    {{ unbookmarkLabel }}
                </button>
            </div>
            <div class="mobile only tablet only sixteen wide center aligned column"  v-if="isUserOwner">
                <advert-manage-button
                        :advert="dataAdvert"
                        :with-see-action="false"
                        :manage-advert-label="manageAdvertLabel"
                        :see-advert-label="seeAdvertLabel"
                        :edit-advert-label="editAdvertLabel"
                        :delete-advert-label="deleteAdvertLabel"
                        :back-to-top-label="backToTopAdvertLabel"
                        :highlight-label="highlightAdvertLabel"
                        :renew-advert-label="renewAdvertLabel"
                        :see-advert-popup-label="seeAdvertPopupLabel"
                        :edit-advert-popup-label="editAdvertPopupLabel"
                        :delete-advert-popup-label="deleteAdvertPopupLabel"
                        :back-to-top-popup-label="backToTopPopupLabel"
                        :highlight-popup-label="highlightPopupLabel"
                        :renew-advert-popup-label="renewAdvertPopupLabel"
                ></advert-manage-button>
            </div>
            <div class="sixteen wide right aligned column" v-if="!isUserOwner">
                <div class="ui divider"></div>
                <a href="#" v-on:click.prevent="report()"><i class="ban icon"></i>{{ reportLabel }}</a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            //vue routes
            'routeSendMail',
            'routeBookmarkAdd',
            'routeBookmarkRemove',
            'routeDeleteAdvert',
            'routeReportAdvert',
            //vue vars
            'advert',
            'userMail',
            'userName',
            'userPhone',
            'userCompagnyName',
            'isUserOwner',
            'isUserBookmark',
            'formNameMinValid',
            'formMessageMinValid',
            'formMessageMaxValid',
            'formPhoneMaxValid',
            'formCompagnyNameMaxValid',
            //vue strings
            'loadErrorMessage',
            'sendSuccessMessage',
            'sendSuccessReportMessage',
            'formValidationEmail',
            'formPointingMinimumChars',
            'formPointingMaximumChars',
            'contactLabel',
            'reportLabel',
            'bookmarkInfo',
            'bookmarkLabel',
            'unbookmarkLabel',
            'manageAdvertLabel',
            'seeAdvertLabel',
            'editAdvertLabel',
            'deleteAdvertLabel',
            'backToTopAdvertLabel',
            'highlightAdvertLabel',
            'renewAdvertLabel',
            'seeAdvertPopupLabel',
            'editAdvertPopupLabel',
            'deleteAdvertPopupLabel',
            'backToTopPopupLabel',
            'highlightPopupLabel',
            'renewAdvertPopupLabel',
            'formMessageLabel',
            'formMessageEmailLabel',
            'formMessageNameLabel',
            'formMessagePhoneLabel',
            'formMessageCompagnyLabel',
            'formSeePhoneLabel',
            'formMessageSendLabel',
            'formMessageCancelLabel',
            'bookmarkSuccess',
            'unbookmarkSuccess',
            'modalValidHeader',
            'modalValidDescription',
            'modalNo',
            'modalYes',
            'allLabel',
            //advertById component
            'routeHome',
            'actualLocale',
            'imageRatio',
            'totalQuantityLabel',
            'lotMiniQuantityLabel',
            'urgentLabel',
            'isNegociatedLabel',
            'priceInfoLabel',
            'priceLabel',
            'refLabel'
        ],
        data: () => {
            return {
                typeMessage : '',
                message : '',
                sendMessage: false,
                breadcrumbItems: [],
                dataAdvert: {},
                dataOwnerUser: {},
                dataUserName: '',
                dataUserMail: '',
                dataUserPhone: '',
                dataUserCompagnyName: '',
                dataMessage: '',
                dataReportMessage: '',
                dataEnabledMessage: false,
                dataEnabledReportMessage: false,
                dataIsUserBookmark: false,
                phoneIsVisible: false,
                isLoaded: true
            }
        },
        mounted () {
            this.dataAdvert= JSON.parse(this.advert);
            this.dataOwnerUser = this.dataAdvert.user;
            this.dataUserMail = this.userMail;
            this.dataUserName = this.userName;
            this.dataUserPhone = this.userPhone;
            this.dataUserCompagnyName = this.userCompagnyName;
            this.dataIsUserBookmark = this.isUserBookmark;
            //BreadCrumbs
            this.setBreadCrumbItems();

            //On load Error
            this.$on('loadError', function () {
                this.sendToast(this.loadErrorMessage, 'error');
            });
            this.$on('categoryChoice', function (category) {
                sessionStorage.setItem('goToCategory', category.id);
                window.location.assign(this.routeHome);
            });
            let that = this;
            let messageForm = $('#form-'+this._uid);
            messageForm.form({
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
                        },
                        phone: {
                            identifier  : 'phone',
                            rules: [
                                {
                                    type : 'maxLength['+that.formPhoneMaxValid+']',
                                    prompt: '{ruleValue} ' + that.formPointingMaximumChars
                                }
                            ]
                        },
                        compagnyName: {
                            identifier  : 'compagnyName',
                            rules: [
                                {
                                    type : 'maxLength['+that.formCompagnyNameMaxValid+']',
                                    prompt: '{ruleValue} ' + that.formPointingMaximumChars
                                }
                            ]
                        }
                    },
                    inline : true,
                    on: 'change'
                })
            ;
            let messageReportForm = $('#reportform-'+this._uid);
            messageReportForm.form({
                fields : {
                    reportemail: {
                        identifier  : 'reportemail',
                        rules: [
                            {
                                type   : 'email',
                                prompt : that.formValidationEmail
                            }
                        ]
                    },
                    reportmessage: {
                        identifier  : 'reportmessage',
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
                this.testValidReportForm();
            });
            this.$watch('dataMessage', function () {
                this.testValidForm();
            });
            this.$watch('dataReportMessage', function () {
                this.testValidReportForm();
            });
            this.$on('deleteAdvert', function (event) {
                this.destroyMe();
            })
        },
        methods: {
            sendToast: function(message,type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            },
            setBreadCrumbItems: function () {
                let that = this;
                let breadcrumb = this.dataAdvert.breadCrumb;
                let lastBread = {
                    description: [],
                    id: 0
                };
                lastBread.description[this.actualLocale] = this.dataAdvert.title;
                breadcrumb.push(lastBread);
                this.breadcrumbItems = [];
                this.breadcrumbItems.push({
                    name: this.allLabel,
                    value: 0
                });
                breadcrumb.forEach(function (element){
                    that.breadcrumbItems.push({
                        name: element['description'][that.actualLocale],
                        value: element.id
                    });
                });
            },
            openMessageBox: function () {
                let modalForm = $('#modal1-'+this._uid);
                let that = this;
                modalForm.modal({
                    closable: true,
                    blurring: true,
                    onApprove: function () {
                        axios.post(that.routeSendMail, {'id': that.dataAdvert.id, 'name': that.dataUserName, 'email': that.dataUserMail, 'phone': that.dataUserPhone, 'compagnyName': that.dataUserCompagnyName, 'message': that.dataMessage})
                            .then(function (response) {
                                that.sendToast(that.sendSuccessMessage, 'success');
                            })
                            .catch(function (error) {
                                if (error.response && error.response.status == 409) {
                                    that.sendToast(error.response.data, 'error');
                                } else {
                                    that.sendToast(that.loadErrorMessage, 'error');
                                }
                            });
                    }
                }).modal('show');
            },
            report: function () {
                let modalForm = $('#modal3-'+this._uid);
                let that = this;
                modalForm.modal({
                    closable: true,
                    blurring: true,
                    onApprove: function () {
                        axios.post(that.routeReportAdvert, {'id': that.dataAdvert.id, 'email': that.dataUserMail, 'message': that.dataReportMessage})
                            .then(function (response) {
                                that.sendToast(that.sendSuccessReportMessage, 'success');
                            })
                            .catch(function (error) {
                                if (error.response && error.response.status == 409) {
                                    that.sendToast(error.response.data, 'error');
                                } else {
                                    that.sendToast(that.loadErrorMessage, 'error');
                                }
                            });
                    }
                }).modal('show');
            },
            testValidForm: function () {
                this.dataEnabledMessage = $('#form-'+this._uid).form('is valid');
            },
            testValidReportForm: function () {
                this.dataEnabledReportMessage = $('#reportform-'+this._uid).form('is valid');
            },
            bookmarkMe: function () {
                let that = this;
                axios.get(this.routeBookmarkAdd)
                    .then(function (response)  {
                        that.dataIsUserBookmark = true;
                        that.sendToast(that.bookmarkSuccess, 'success');
                    })
                    .catch(function (error)  {
                        if (error.response && error.response.status == 409) {
                            that.sendToast(error.response.data, 'error');
                        } else {
                            that.sendToast(that.loadErrorMessage, 'error');
                        }
                        that.isLoaded = false;
                    });
            },
            unbookmarkMe: function () {
                let that = this;
                axios.get(this.routeBookmarkRemove)
                    .then(function (response)  {
                        that.dataIsUserBookmark = false;
                        that.sendToast(that.unbookmarkSuccess, 'success');
                    })
                    .catch(function (error)  {
                        if (error.response && error.response.status == 409) {
                            that.sendToast(error.response.data, 'error');
                        } else {
                            that.sendToast(that.loadErrorMessage, 'error');
                        }
                        that.isLoaded = false;
                    });
            },
            destroyMe: function () {
                let modalForm = $('#modal2-'+this._uid);
                let that = this;
                modalForm.modal({
                    closable: true,
                    blurring: true,
                    onApprove: function () {
                        that.isLoaded = false;
                        axios.delete(that.routeDeleteAdvert)
                            .then(function (response) {
                                window.location.assign(response.data);
                            })
                            .catch(function (error) {
                                if (error.response && error.response.status == 409) {
                                    that.sendToast(error.response.data, 'error');
                                } else {
                                    that.sendToast(that.loadErrorMessage, 'error');
                                }
                                that.isLoaded = true;
                            });
                    }
                }).modal('show');
            },
            seePhone: function() {
                this.phoneIsVisible = true;
            }
        }
    }
</script>
