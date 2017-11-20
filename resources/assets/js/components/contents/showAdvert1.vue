<template>
    <div>
        <div :id="'modal1-'+_uid" class="ui modal">
            <i class="close icon"></i>
            <div class="header">
                {{ strings.contactLabel }}
            </div>
            <div class="content">
                <div :id="'form-'+_uid" class="ui form">
                    <div class="required field">
                        <label>{{ strings.formMessageLabel }}</label>
                        <textarea name="message" v-model="dataMessage" :maxlength="formMessageMaxValid"></textarea>
                    </div>
                    <div class="field">
                        <div class="two fields">
                            <div class="required field">
                                <label>{{ strings.formMessageNameLabel }}</label>
                                <input name="name" type="text" v-model="dataUserName">
                            </div>
                            <div class="required field">
                                <label>{{ strings.formMessageEmailLabel }}</label>
                                <input name="email" type="text" v-model="dataUserMail">
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <div class="two fields">
                            <div class="field">
                                <label>{{ strings.formMessagePhoneLabel }}</label>
                                <input name="phone" type="text" v-model="dataUserPhone">
                            </div>
                            <div class="field">
                                <label>{{ strings.formMessageCompagnyLabel }}</label>
                                <input name="compagnyName" type="text" v-model="dataUserCompagnyName">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="actions">
                <div style="float: left" v-if="dataOwnerUser.phone && dataOwnerUser.phone!=null && dataOwnerUser.phone!=''">
                    <button class="ui blue button" v-on:click="seePhone()" v-if="!phoneIsVisible" autofocus>
                        {{ strings.formSeePhoneLabel }}
                    </button>
                    <div class="ui large label" style="float: left" v-else>
                        <i class="phone icon"></i> {{ dataOwnerUser.phone }}
                    </div>
                </div>

                <div class="ui black deny button">
                    {{ strings.formMessageCancelLabel }}
                </div>
                <div :class="dataEnabledMessage == true ? 'ui positive right labeled icon button' : 'ui positive right labeled icon disabled button'">
                    {{ strings.formMessageSendLabel }}
                    <i class="send outline icon"></i>
                </div>
            </div>
        </div>
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
        <div :id="'modal3-'+_uid" class="ui modal">
            <i class="close icon"></i>
            <div class="header">
                {{ strings.reportLabel }}
            </div>
            <div class="content">
                <div :id="'reportform-'+_uid" class="ui form">
                    <div class="required field">
                        <label>{{ strings.formMessageLabel }}</label>
                        <textarea name="reportmessage" v-model="dataReportMessage" :maxlength="formMessageMaxValid"></textarea>
                    </div>
                    <div class="required field">
                        <label>{{ strings.formMessageEmailLabel }}</label>
                        <input name="reportemail" type="text" v-model="dataUserMail">
                    </div>
                </div>
            </div>
            <div class="actions">
                <div class="ui black deny button">
                    {{ strings.formMessageCancelLabel }}
                </div>
                <div :class="dataEnabledReportMessage == true ? 'ui positive right labeled icon button' : 'ui positive right labeled icon disabled button'">
                    {{ strings.formMessageSendLabel }}
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
                    <div class="header"><h3><span v-if="dataAdvert.isUrgent" class="ui red horizontal label">{{ strings.urgentLabel }}</span>{{ dataAdvert.title }}</h3></div>
                </div>
                <div class="sixteen wide tablet ten wide computer column">
                    <div class="row">
                        <div class="ui active inverted dimmer" v-if="!isLoaded">
                            <div class="ui large text loader">Loading</div>
                        </div>
                        <advert-by-id
                                :advert="dataAdvert"
                                :user-name="userName"
                                :is-user-owner="isUserOwner==1"
                        ></advert-by-id>
                    </div>
                </div>
                <div id="welcome-ads" class="computer only six wide column">
                    <div>
                        <div class="ui form">
                            <div class="field" v-if="!isUserOwner">
                                <button class="ui primary icon fluid button"
                                        v-on:click="openMessageBox">
                                    <i class="mail outline icon"></i>
                                    {{ strings.contactLabel }}
                                </button>
                            </div>
                            <div class="field" v-if="userName == '' || isUserOwner">
                                <div class="ui labeled button disabled-bookmark">
                                    <div class="ui yellow button">
                                        <i class="heart icon"></i> {{ strings.bookmarkInfo }}
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
                                    {{ strings.bookmarkLabel }}
                                </button>
                            </div>
                            <div class="field" v-if="userName != '' && !isUserOwner && dataIsUserBookmark">
                                <button class="ui basic yellow icon fluid button"
                                        v-on:click="unbookmarkMe()">
                                    <i class="heart icon"></i>
                                    {{ strings.unbookmarkLabel }}
                                </button>
                            </div>
                            <div class="field" v-if="isUserOwner">
                                <advert-manage-button
                                        :advert="dataAdvert"
                                        :with-see-action="false"
                                        @deleteAdvert="destroyMe"
                                ></advert-manage-button>
                            </div>
                            <div class="fb-share-button"
                                 :data-href="properties.routeFacebookSharer"
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
                <button class="ui primary icon fluid button"
                        v-on:click="openMessageBox">
                    <i class="mail outline icon"></i>
                    {{ strings.contactLabel }}
                </button>
            </div>
            <div class="mobile only tablet only sixteen wide center aligned column"  v-if="userName != '' && !isUserOwner && !dataIsUserBookmark">
                <button class="ui basic yellow icon fluid button"
                        v-on:click="bookmarkMe()">
                    <i class="empty heart icon"></i>
                    {{ strings.bookmarkLabel }}
                </button>
            </div>
            <div class="mobile only tablet only sixteen wide center aligned column"  v-if="userName != '' && !isUserOwner && dataIsUserBookmark">
                <button class="ui basic yellow icon fluid button"
                        v-on:click="unbookmarkMe()">
                    <i class="heart icon"></i>
                    {{ strings.unbookmarkLabel }}
                </button>
            </div>
            <div class="mobile only tablet only sixteen wide center aligned column"  v-if="isUserOwner">
                <advert-manage-button
                        :advert="dataAdvert"
                        :with-see-action="false"
                        @deleteAdvert="destroyMe"
                ></advert-manage-button>
            </div>
            <div class="sixteen wide right aligned column" v-if="!isUserOwner">
                <div class="ui divider"></div>
                <a href="#" v-on:click.prevent="report()"><i class="ban icon"></i>{{ strings.reportLabel }}</a>
            </div>
        </div>
    </div>
</template>

<script>
  import { DestockTools } from '../../destockTools'
  import Axios from 'axios'
  export default {
    props: [
      // vue routes
      'routeSendMail',
      'routeBookmarkAdd',
      'routeBookmarkRemove',
      'routeDeleteAdvert',
      'routeReportAdvert',
      'routeFacebookSharer',
      // vue vars
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
      'formCompagnyNameMaxValid'
    ],
    computed: {
      strings () {
        return this.$store.state.strings['showAdvert1']
      },
      properties () {
        return this.$store.state.properties['global']
      },
      dataAdvert () {
        return JSON.parse(this.advert)
      },
      dataOwnerUser () {
        return this.dataAdvert.user
      }
    },
    watch: {
      dataUserName () {
        this.testValidForm()
      },
      dataUserMail () {
        this.testValidForm()
        this.testValidReportForm()
      },
      dataMessage () {
        this.testValidForm()
      },
      dataReportMessage () {
        this.testValidReportForm()
      }
    },
    data () {
      return {
        typeMessage: '',
        message: '',
        sendMessage: false,
        breadcrumbItems: [],
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
      this.dataUserMail = this.userMail
      this.dataUserName = this.userName
      this.dataUserPhone = this.userPhone
      this.dataUserCompagnyName = this.userCompagnyName
      this.dataIsUserBookmark = this.isUserBookmark
      // BreadCrumbs
      this.setBreadCrumbItems()
      let that = this
      let messageForm = $('#form-' + this._uid)
      messageForm.form({
        fields: {
          email: {
            identifier: 'email',
            rules: [
              {
                type: 'email',
                prompt: that.strings.formValidationEmail
              }
            ]
          },
          name: {
            identifier: 'name',
            rules: [
              {
                type: 'minLength[' + that.formNameMinValid + ']',
                prompt: '{ruleValue} ' + that.strings.formPointingMinimumChars
              }
            ]
          },
          message: {
            identifier: 'message',
            rules: [
              {
                type: 'minLength[' + that.formMessageMinValid + ']',
                prompt: '{ruleValue} ' + that.strings.formPointingMinimumChars
              },
              {
                type: 'maxLength[' + that.formMessageMaxValid + ']',
                prompt: '{ruleValue} ' + that.strings.formPointingMaximumChars
              }
            ]
          },
          phone: {
            identifier: 'phone',
            rules: [
              {
                type: 'maxLength[' + that.formPhoneMaxValid + ']',
                prompt: '{ruleValue} ' + that.strings.formPointingMaximumChars
              }
            ]
          },
          compagnyName: {
            identifier: 'compagnyName',
            rules: [
              {
                type: 'maxLength[' + that.formCompagnyNameMaxValid + ']',
                prompt: '{ruleValue} ' + that.strings.formPointingMaximumChars
              }
            ]
          }
        },
        inline: true,
        on: 'change'
      })
      let messageReportForm = $('#reportform-' + this._uid)
      messageReportForm.form({
        fields: {
          reportemail: {
            identifier: 'reportemail',
            rules: [
              {
                type: 'email',
                prompt: that.strings.formValidationEmail
              }
            ]
          },
          reportmessage: {
            identifier: 'reportmessage',
            rules: [
              {
                type: 'minLength[' + that.formMessageMinValid + ']',
                prompt: '{ruleValue} ' + that.strings.formPointingMinimumChars
              },
              {
                type: 'maxLength[' + that.formMessageMaxValid + ']',
                prompt: '{ruleValue} ' + that.strings.formPointingMaximumChars
              }
            ]
          }
        },
        inline: true,
        on: 'change'
      })
    },
    methods: {
      setBreadCrumbItems () {
        let that = this
        let breadcrumb = this.dataAdvert.breadCrumb
        this.breadcrumbItems = []
        this.breadcrumbItems.push({
          name: this.strings.allLabel,
          value: 0
        })
        breadcrumb.forEach(function (element) {
          that.breadcrumbItems.push({
            name: element['description'][that.properties.actualLocale],
            value: element.id
          })
        })
      },
      openMessageBox () {
        let modalForm = $('#modal1-' + this._uid)
        let that = this
        modalForm.modal({
          closable: true,
          blurring: false,
          onApprove () {
            Axios.post(that.routeSendMail, {'id': that.dataAdvert.id, 'name': that.dataUserName, 'email': that.dataUserMail, 'phone': that.dataUserPhone, 'compagnyName': that.dataUserCompagnyName, 'message': that.dataMessage})
              .then(function (response) {
                that.$alertV({'message': that.strings.sendSuccessMessage, 'type': 'success'})
              })
              .catch(function (error) {
                if (error.response && error.response.status === 409) {
                  that.$alertV({'message': error.response.data, 'type': 'error'})
                }
                else {
                  that.$alertV({'message': that.strings.loadErrorMessage, 'type': 'error'})
                }
              })
          }
        }).modal('show')
      },
      report () {
        let modalForm = $('#modal3-' + this._uid)
        let that = this
        modalForm.modal({
          closable: true,
          blurring: false,
          onApprove () {
            Axios.post(that.routeReportAdvert, {'id': that.dataAdvert.id, 'email': that.dataUserMail, 'message': that.dataReportMessage})
              .then(function (response) {
                that.$alertV({'message': that.strings.sendSuccessReportMessage, 'type': 'success'})
              })
              .catch(function (error) {
                if (error.response && error.response.status === 409) {
                  that.$alertV({'message': error.response.data, 'type': 'error'})
                }
                else {
                  that.$alertV({'message': that.strings.loadErrorMessage, 'type': 'error'})
                }
              })
          }
        }).modal('show')
      },
      testValidForm () {
        this.dataEnabledMessage = $('#form-' + this._uid).form('is valid')
      },
      testValidReportForm () {
        this.dataEnabledReportMessage = $('#reportform-' + this._uid).form('is valid')
      },
      bookmarkMe () {
        let that = this
        Axios.get(this.routeBookmarkAdd)
          .then(function (response) {
            that.dataIsUserBookmark = true
            that.$alertV({'message': that.strings.bookmarkSuccess, 'type': 'success'})
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
      },
      unbookmarkMe () {
        let that = this
        Axios.get(this.routeBookmarkRemove)
          .then(function (response) {
            that.dataIsUserBookmark = false
            that.$alertV({'message': that.strings.unbookmarkSuccess, 'type': 'success'})
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
      },
      destroyMe () {
        let modalForm = $('#modal2-' + this._uid)
        let that = this
        modalForm.modal({
          closable: true,
          blurring: false,
          onApprove () {
            that.isLoaded = false
            Axios.delete(that.routeDeleteAdvert)
              .then(function (response) {
                DestockTools.goToUrl(response.data)
              })
              .catch(function (error) {
                if (error.response && error.response.status === 409) {
                  that.$alertV({'message': error.response.data, 'type': 'error'})
                }
                else {
                  that.$alertV({'message': that.strings.loadErrorMessage, 'type': 'error'})
                }
                that.isLoaded = true
              })
          }
        }).modal('show')
      },
      seePhone () {
        this.phoneIsVisible = true
      }
    }
  }
</script>
