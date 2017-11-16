<template>
    <div>
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div class="ui grid">
            <div class="sixteen wide column">
                <h2 class="ui header">{{ strings.contentHeader }}</h2>
            </div>
            <div class="sixteen wide mobile only sixteen wide tablet only column">
                <steps-light
                        :steps="steps">
                </steps-light>
            </div>
            <div class="sixteen wide computer only column">
                <steps
                        :steps="steps"
                ></steps>
            </div>

            <div class="sixteen wide column">
                <form :id="'create_advert_form_'+_uid" class="ui form" :action="routeAdvertFormPost" method="post" @keyup.enter.prevent.stop>
                    <input type="hidden" name="_token" :value="properties.csrfToken"/>
                    <input v-if="isEditAdvert" type="hidden" name="_method" value="PATCH">
                    <input type="hidden" name="category" :value="categoryId"/>
                    <input type="hidden" name="currency" :value="currency" />
                    <input type="hidden" name="lat" :value="lat" />
                    <input type="hidden" name="lng" :value="lng" />
                    <input type="hidden" name="geoloc" :value="geoloc" />
                    <input type="hidden" name="completegeoloc" :value="dataCompleteGeoloc" />
                    <input type="hidden" name="searchPlace" :value="searchPlace" />
                    <input type="hidden" name="main_picture" :value="mainPicture" />
                    <input type="hidden" name="is_urgent" :value="isUrgent ? 1 : 0" />
                    <input type="hidden" name="is_negociated" :value="isNegociated ? 1 : 0" />
                    <div class="ui error message"></div>

                    <div class="ui grid">
                        <div class="ten wide tablet only ten wide computer only column">
                            <h4 class="ui horizontal divider header">
                                <i class="unhide icon"></i>
                                {{ strings.formPreviewHeaderLabel }}
                            </h4>
                        </div>
                        <div class="sixteen wide mobile only six wide tablet only six wide computer only column">
                            <h4 class="ui horizontal divider header">
                                <i class="settings icon"></i>
                                {{ strings.formParamsHeaderLabel }}
                            </h4>
                        </div>
                        <div class="ten wide tablet only ten wide computer only column">
                            <div class="ui grid">
                                <div class="sixteen wide column">
                                    <div class="row">
                                        <breadcrumb
                                                :items="breadcrumbItems"
                                                :withAction="false"
                                        ></breadcrumb>
                                    </div>
                                </div>
                            </div>
                            <div class="sixteen wide column">
                                <div class="ui grid">
                                    <div v-if="isUrgent" class="two wide column">
                                            <span class="ui red horizontal label">
                                                {{ strings.exampleUrgentLabel }}
                                            </span>
                                    </div>
                                    <div :class="!isUrgent ? 'sixteen wide column' : 'fourteen wide column'">
                                        <h4 class="field">
                                            <input name="title" type="text" :placeholder="strings.formTitleLabel" v-model:value="title" :maxlength="formTitleMaxValid">
                                            <transition name="p-fade">
                                                <span class="ui red pointing basic label notransition" v-show="title.length<formTitleMinValid">{{ formTitleMinValid }}{{strings.formPointingMinimumChars }}</span>
                                            </transition>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="ui grid">
                                <div class="sixteen wide column">
                                    <swiper-gallerie
                                            :pictures="pictures"
                                            :main-picture="mainPicture"
                                            :video-id="videoId !== undefined && videoId !== null ? parseInt(videoId) : null"
                                            :lazy-load="false"
                                    ></swiper-gallerie>
                                </div>
                                <div class="sixteen wide column">
                                    <div class="ui grid">
                                        <div class="sixteen wide right aligned column geodate-computer">
                                            <p>
                                                <i class="map signs icon"></i><span class="meta">{{ geoloc }}</span>
                                                <i class="calendar icon"></i><span class="meta">{{ getMoment(Date.now()) }}</span>
                                                <i class="unhide icon"></i><span class="meta">0</span>
                                            </p>
                                        </div>
                                        <div class="sixteen wide column">
                                            <div class="ui segment">
                                                <div class="sixteen wide column">
                                                    <table id="table-advert-infos-1" class="ui very basic celled table advert-infos">
                                                        <tbody>
                                                        <tr>
                                                            <td class="collapsing">
                                                                <i class="barcode icon"></i> {{ strings.formRefLabel }}
                                                            </td>
                                                            <td>
                                                                <div class="ui fluid input">
                                                                    <input name="manu_ref" type="text" :placeholder="strings.formRefLabel" v-model:value="manuRef">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="collapsing">
                                                                <i class="cubes icon"></i> {{ strings.formTotalQuantityLabel }}
                                                            </td>
                                                            <td>
                                                                <div class="ui fluid input">
                                                                    <number-input name="total_quantity" :min="1" :decimal="0" v-model="totalQuantity" @blur="setMaxLotMini"></number-input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="collapsing">
                                                                <i class="cube icon"></i> {{ strings.formLotMiniQuantityLabel }}
                                                            </td>
                                                            <td>
                                                                <div class="ui fluid input">
                                                                    <number-input name="lot_mini_quantity" :min="1" :max="maxLotMini" :decimal="0" v-model="lotMiniQuantity"></number-input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="collapsing">
                                                                <i class="money icon"></i> {{ strings.formPriceLabel }}
                                                            </td>
                                                            <td>
                                                                <div v-show="!isNegociated" class="ui blue tag label">
                                                                    <div class="ui right labeled input">
                                                                        <template v-if="isNegociated==0">
                                                                            <number-input style="width: 90%"  name="price" :min="calcSubUnit" :decimal="subunit" v-model="price"></number-input>
                                                                        </template>
                                                                        <template v-else>
                                                                            <input  name="" type="number" value="0" disabled/>
                                                                        </template>
                                                                        <div class="ui basic label">
                                                                            {{ currencySymbol }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div v-if="isNegociated" class="ui small blue tag label">{{ strings.exampleIsNegociatedLabel }}</div>
                                                            </td>
                                                        </tr>
                                                        <tr v-if="!isNegociated && margins.globalDiscount > 0">
                                                            <td class="collapsing">
                                                                <i class="gift icon"></i> {{ strings.CompletePriceLabel }}
                                                            </td>
                                                            <td>
                                                                <discount-tag
                                                                        :advert="fakeAdvert"
                                                                        :margins="margins"
                                                                        :forSeller="true"
                                                                ></discount-tag>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="ui hidden divider"></div>
                                                <div class="sixteen wide column item-description">
                                                    <div class="description">
                                                        <div class="required field">
                                                            <textarea name="description" v-model="description" :maxlength="formDescriptionMaxValid"></textarea>
                                                            <transition name="p-fade">
                                                                <span class="ui red pointing basic label notransition" v-show="description.length<formDescriptionMinValid">{{ formDescriptionMinValid }}{{strings.formPointingMinimumChars }}</span>
                                                            </transition>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sixteen wide mobile only six wide tablet only six wide computer only column glass-box">
                            <div class="ui grid">
                                <div class="sixteen wide column">
                                    <div style="display:none">
                                        <type-radio-button
                                                :route-get-list-type="routeGetListType"
                                                :first-menu-name="strings.listTypeFirstMenuName"
                                                :old-choice="'bid'"
                                                :is-disabled="true"
                                                @typeChoice="typeChoice"
                                        ></type-radio-button>
                                    </div>
                                    <div :id="'params_accordion'+_uid" class="ui accordion">
                                        <div class="title">
                                            <i class="dropdown icon"></i>
                                            <span>
                                                <i class="sitemap icon"></i>
                                                {{ strings.categoryHeader }}
                                            </span>
                                            <span>
                                                <template v-if="categoryId===undefined || categoryId===null || categoryId ==''">
                                                    <i class="red alarm outline large icon" :data-content="strings.categoryFieldRequired"></i>
                                                </template>
                                                <template v-else>
                                                    <i class="green checkmark large icon"></i>
                                                </template>
                                            </span>
                                        </div>
                                        <div class="content">
                                            <div class="required field">
                                                <div class="ui grid">
                                                    <div class="sixteen wide mobile only column">
                                                        <categories-select-menu
                                                                :old-choice="oldCategoryId"
                                                                :with-all="false"
                                                                @categoryChoice="categoryChoice"
                                                        ></categories-select-menu>
                                                    </div>
                                                    <div class="sixteen wide tablet only siwteen wide computer only column">
                                                        <categories-dropdown-menu
                                                                :old-choice="oldCategoryId"
                                                                :with-all="false"
                                                                @categoryChoice="categoryChoice"
                                                        ></categories-dropdown-menu>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="title">
                                            <i class="dropdown icon"></i>
                                            <span>
                                        <i class="camera retro icon"></i>
                                        {{ strings.formPhotoSeparator }}
                                    </span>
                                            <span>
                                        <template v-if="pictures.length===0">
                                        <i class="red alarm outline large icon" :data-content="strings.photoFieldRequired"></i>
                                        </template>
                                        <template v-else>
                                            <i class="green checkmark large icon"></i>
                                        </template>
                                    </span>
                                        </div>
                                        <div class="content">
                                            <photo-uploader
                                                    :route-post-picture="routePostPicture"
                                                    :route-get-list-posts="routeGetListPosts"
                                                    :route-del-picture="routeDelPicture"
                                                    :advert-form-photo-nb-free-picture="calcNbFreePictures()"
                                                    :max-files="parseInt(maxFiles)"
                                                    :is-delegation="isDelegation==1"
                                                    :old-main-picture="isEditAdvert ? dataAdvertEdit.mainPicturePicture : null"
                                                    :is-edit-advert="isEditAdvert"
                                                    :edit-advert-id="isEditAdvert ? dataAdvertEdit.id : 0"
                                                    nb-columns="two"
                                                    @updatePictures="updatePictures"
                                                    @updateMainPicture="mainPicture = $event"
                                                    @sendToast="sendToast($event.message, $event.type)"
                                                    @fileSizeError="sendToast(strings.filesizeErrorMessage, 'error')"
                                                    @loadError="sendToast(strings.loadErrorMessage, 'error')"
                                            ></photo-uploader>
                                        </div>
                                        <div class="title">
                                            <i class="dropdown icon"></i>
                                            <span>
                                        <i class="film icon"></i>
                                        {{ strings.videoHeader }}
                                    </span>
                                        </div>
                                        <div class="content">
                                            <vimeo-uploader
                                                    :route-get-video-post-ticket="routeGetVideoPostTicket"
                                                    :route-del-tempo-video="routeDelTempoVideo"
                                                    :route-get-status-video="routeGetStatusVideo"
                                                    :max-video-file-size="parseInt(maxVideoFileSize)"
                                                    :session-video-id="sessionVideoId"
                                                    :is-edit-advert="isEditAdvert"
                                                    :edit-advert-id="isEditAdvert ? dataAdvertEdit.id : 0"
                                                    :format="'auto'"
                                                    @vimeoStateChange="vimeoStateChange"
                                                    @videoUploadStatusChange="submitEnable = !$event"
                                                    @fileSizeError="sendToast(strings.filesizeErrorMessage, 'error')"
                                                    @sendToast="sendToast($event.message, $event.type)"
                                                    @loadError="sendToast(strings.loadErrorMessage, 'error')"
                                            ></vimeo-uploader>
                                        </div>
                                        <div class="title">
                                            <i class="dropdown icon"></i>
                                            <span>
                                        <i class="checkmark box icon"></i>
                                        Options
                                    </span>
                                        </div>
                                        <div class="content">
                                            <div class="field">
                                                <div :id="'isUrgent'+_uid" class="ui checkbox">
                                                    <input type="checkbox" name="isUrgent">
                                                    <label><span class="ui red horizontal label">{{ strings.exampleUrgentLabel }}</span>{{ strings.formUrgentLabel }}</label>
                                                </div>
                                            </div>
                                            <div class="field" v-if="isDelegation!=1">
                                                <div :id="'isNegociated'+_uid" class="ui checkbox">
                                                    <input type="checkbox" name="isNegociated">
                                                    <label> <span class="ui blue horizontal label">{{ strings.exampleIsNegociatedLabel }}</span>{{ strings.formIsNegociatedLabel }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="title">
                                            <i class="dropdown icon"></i>
                                            <span>
                                        <i class="map signs icon"></i>
                                        {{ strings.formGooglemapLabel }}
                                    </span>
                                        </div>
                                        <div class="content">
                                            <div class="field">
                                                <googleMap
                                                        :lng="lng"
                                                        :lat="lat"
                                                        :geoloc="geoloc"
                                                        :resize="flagMapResize"
                                                        @locationChange="latLngChange"
                                                ></googleMap>
                                            </div>
                                        </div>
                                        <div class="title">
                                            <i class="dropdown icon"></i>
                                            <span>
                                        <i class="money icon"></i>
                                        {{ strings.currenciesHeader }}
                                    </span>
                                        </div>
                                        <div class="content">
                                            <div class="field">
                                                <currencies-dropdown-2
                                                        :old-currency="oldCurrency"
                                                        @currencyChoice="currencyChoice"
                                                ></currencies-dropdown-2>
                                            </div>
                                        </div>
                                        <div v-show="!isNegociated" class="title">
                                            <i class="dropdown icon"></i>
                                            <span>
                                        <i class="calculator icon"></i>
                                        {{ strings.marginHeader }}
                                    </span>
                                        </div>
                                        <div v-show="!isNegociated" class="content">
                                            <div class="field">
                                                <label>{{ strings.formTotalQuantityLabel }}</label>
                                               <number-input name="total_quantity" :min="1" :decimal="0" v-model="totalQuantity" @blur="setMaxLotMini"></number-input>
                                            </div>
                                            <div class="field">
                                                <label>{{ strings.formLotMiniQuantityLabel }}</label>
                                                <number-input name="lot_mini_quantity1" :min="1" :max="maxLotMini" :decimal="0" v-model="lotMiniQuantity"></number-input>
                                            </div>
                                            <div class="field">
                                                <label>{{ strings.formPriceLabel }}</label>
                                                <div class="ui right labeled input">
                                                    <template v-if="isNegociated==0">
                                                        <number-input style="width: 90%"  name="price" v-model="price" :decimal="subunit" :min="calcSubUnit"></number-input>
                                                    </template>
                                                    <template v-else>
                                                        <input  name="" type="number" value="0" disabled/>
                                                    </template>
                                                    <div class="ui basic label">
                                                        {{ currencySymbol }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <label>{{ strings.formDiscountLabel }}</label>
                                                <div class="ui right labeled input">
                                                    <number-input name="discount_on_total" :min="0" :max="100" :decimal="2" v-model="discountOnTotal"></number-input>
                                                    <div class="ui basic label">
                                                        %
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <label>{{ strings.formBuyingPriceLabel }}</label>
                                                <div class="ui right labeled input">
                                                    <template v-if="isNegociated==0">
                                                         <number-input name="buyingPrice" :min="0" :decimal="subunit" v-model="buyingPrice"></number-input>
                                                    </template>
                                                    <template v-else>
                                                        <input  name="" type="number" value="0" disabled/>
                                                    </template>
                                                    <div class="ui basic label">
                                                        {{ currencySymbol }}
                                                    </div>
                                                </div>
                                                <margins-table v-if="isNegociated==0"
                                                               :advert="fakeAdvert"
                                                               :with-valid-button="false"
                                                               :for-seller="true"
                                                ></margins-table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ui grid">
                                <div class="sixteen wide tablet only sixteen wide computer only column">
                                    <h4 class="ui horizontal divider header tablet only computer only">
                                        <i class="share icon"></i>
                                        {{ strings.validationButtonLabel }}
                                    </h4>
                                    <div class="field">
                                        <button type="submit" :class="submitEnable ? 'ui blue labeled icon massive fluid button' : 'ui disabled labeled icon massive fluid button'" v-on:click="submitForm"><i class="save icon"></i>{{ strings.validationButtonLabel }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sixteen wide mobile only column">
                            <h4 class="ui horizontal divider header">
                                <i class="unhide icon"></i>
                                {{ strings.formPreviewHeaderLabel }}
                            </h4>
                        </div>
                        <div class="sixteen wide mobile only column">
                            <div class="ui grid">
                                <div class="sixteen wide column">
                                    <div class="row">
                                        <breadcrumb
                                                :items="breadcrumbItems"
                                                :withAction="false"
                                        ></breadcrumb>
                                    </div>
                                </div>
                            </div>
                            <div class="sixteen wide column">
                                <div class="ui grid">
                                    <div v-if="isUrgent" class="four wide column">
                                            <span class="ui red horizontal label">
                                                {{ strings.exampleUrgentLabel }}
                                            </span>
                                    </div>
                                    <div :class="!isUrgent ? 'sixteen wide column' : 'twelve wide column'">
                                        <h4 class="field">
                                            <input name="title" type="text" :placeholder="strings.formTitleLabel" v-model:value="title" :maxlength="formTitleMaxValid">
                                            <transition name="p-fade">
                                                <span class="ui red pointing basic label notransition" v-show="title.length<formTitleMinValid">{{ formTitleMinValid }}{{strings.formPointingMinimumChars }}</span>
                                            </transition>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="ui grid">
                                <div class="sixteen wide column">
                                    <swiper-gallerie
                                            :pictures="pictures"
                                            :main-picture="mainPicture"
                                            :video-id="videoId !== undefined && videoId !== null ? parseInt(videoId) : null"
                                            :lazy-load="false"
                                    ></swiper-gallerie>
                                </div>
                                <div class="sixteen wide column">
                                    <div class="ui grid">
                                        <div class="sixteen wide right aligned column geodate-computer">
                                            <p>
                                                <i class="map signs icon"></i><span class="meta">{{ geoloc }}</span>
                                                <i class="calendar icon"></i><span class="meta">{{ getMoment(Date.now()) }}</span>
                                                <i class="unhide icon"></i><span class="meta">0</span>
                                            </p>
                                        </div>
                                        <div class="sixteen wide column">
                                            <div class="ui segment">
                                                <div class="sixteen wide column">
                                                    <table class="ui very basic unstackable table">
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <i class="barcode icon"></i> {{ strings.formRefLabel }}
                                                            </td>
                                                            <td>
                                                                <div class="field">
                                                                    <input name="manu_ref" type="text" :placeholder="strings.formRefLabel" v-model:value="manuRef">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <i class="cubes icon"></i> {{ strings.formTotalQuantityLabel }}
                                                            </td>
                                                            <td>
                                                                <div class="field">
                                                                    <number-input name="total_quantity" :min="1" :decimal="0" v-model="totalQuantity" @blur="setMaxLotMini"></number-input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <i class="cube icon"></i> {{ strings.formLotMiniQuantityLabel }}
                                                            </td>
                                                            <td>
                                                                <div class="field">
                                                                    <number-input name="lot_mini_quantity" :min="1" :max="maxLotMini" :decimal="0" v-model="lotMiniQuantity"></number-input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <i class="money icon"></i> {{ strings.formPriceLabel }}
                                                            </td>
                                                            <td>
                                                                <div v-show="!isNegociated" class="ui blue tag label">
                                                                    <div class="ui right labeled input">
                                                                        <template v-if="isNegociated==0">
                                                                            <number-input style="width: 90%"  name="price" :min="calcSubUnit" :decimal="subunit" v-model="price"></number-input>
                                                                        </template>
                                                                        <template v-else>
                                                                            <input  name="" type="number" value="0" disabled/>
                                                                        </template>
                                                                        <div class="ui basic label">
                                                                            {{ currencySymbol }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div v-if="isNegociated" class="ui small blue tag label">{{ strings.exampleIsNegociatedLabel }}</div>
                                                            </td>
                                                        </tr>
                                                        <tr v-if="!isNegociated && margins.globalDiscount > 0">
                                                            <td class="collapsing">
                                                                <i class="gift icon"></i> {{ strings.formDiscountLabel }}
                                                            </td>
                                                            <td>
                                                                <discount-tag
                                                                        :advert="fakeAdvert"
                                                                        :margins="margins"
                                                                        :forSeller="true"
                                                                ></discount-tag>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="ui hidden divider"></div>
                                                <div class="sixteen wide column item-description">
                                                    <div class="description">
                                                        <div class="required field">
                                                            <textarea name="description" v-model="description" :maxlength="formDescriptionMaxValid"></textarea>
                                                            <transition name="p-fade">
                                                                <span class="ui red pointing basic label notransition" v-show="description.length<formDescriptionMinValid">{{ formDescriptionMinValid }}{{strings.formPointingMinimumChars }}</span>
                                                            </transition>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="sixteen wide mobile only column">
                            <h4 class="ui horizontal divider header tablet only computer only">
                                <i class="share icon"></i>
                                {{ strings.validationButtonLabel }}
                            </h4>
                            <div class="field">
                                <button type="submit" :class="submitEnable ? 'ui blue labeled icon massive fluid button' : 'ui disabled labeled icon massive fluid button'" v-on:click="submitForm"><i class="save icon"></i>{{ strings.validationButtonLabel }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
  import moment from 'moment'
  import { DestockTools } from '../../destockTools'
  import Axios from 'axios'
    export default {
        props: [
            //vue routes
            'routeAdvertFormPost',
            'routeGetCost',
            'routePrices',
            'routeGetListType',
            'routePostPicture',
            'routeGetListPosts',
            'routeDelPicture',
            'routeGetVideoPostTicket',
            'routeDelTempoVideo',
            'routeGetStatusVideo',
            //vue vars
            'old',
            'formTitleMinValid',
            'formTitleMaxValid',
            'formDescriptionMinValid',
            'formDescriptionMaxValid',
            'isDelegation',
            'editAdvert',
            'geolocInitLat',
            'geolocInitLng',
            'advertFormPhotoNbFreePicture',
            'maxFiles',
            'maxVideoFileSize',
            'sessionVideoId',
        ],
        computed: {
            strings () {
                return this.$store.state.strings['createOrEditAdvert']
            },
            properties () {
                return this.$store.state.properties['global']
            }
        },
        watch: {
            categoryId () {
                this.setBreadCrumbItems()
            },
            isUrgent () {
                this.setSteps();
                if(this.isUrgent){
                    $('#isUrgent'+this._uid).checkbox('check');
                } else {

                    $('#isUrgent'+this._uid).checkbox('uncheck');
                }
            },
            isNegociated () {
                if(this.isNegociated){
                    $('#isNegociated'+this._uid).checkbox('check');
                } else {

                    $('#isNegociated'+this._uid).checkbox('uncheck');
                }
            },
            subunit () {
                this.calcSubUnit = Math.pow(10,-(this.subunit));
                this.setFakeAdvert();
            },
            currencySymbol () {
                this.setFakeAdvert()
            },
            price () {
                this.setFakeAdvert()
            },
            discountOnTotal () {
                this.setFakeAdvert()
            },
            buyingPrice () {
                this.setFakeAdvert()
            },
            totalQuantity () {
                this.setFakeAdvert()
            },
            lotMiniQuantity () {
                this.setFakeAdvert()
            }
        },
        data () {
            return {
                categoryId: '',
                listType: [],
                type: '',
                title: '',
                manuRef: '',
                description: '',
                price: 0,
                discountOnTotal: 0,
                buyingPrice: 0,
                fakeAdvert: {
                    originalPrice: 0,
                    buyingPrice: 0,
                    priceSubUnit: 2,
                    totalQuantity: 0,
                    lotMiniQuantity:0,
                    discount_on_total: 0,
                    currencySymbol: ''
                },
                margins: {
                    unitMargin: 0,
                    totalMargin: 0,
                    lotMiniMargin: 0,
                    unitSellerPrice: 0,
                    priceMargin: 0,
                    totalSellerPrice: 0,
                    totalSellerPriceWholePart: 0,
                    totalSellerPriceDecimalPart: 0,
                    totalPriceMargin: 0,
                    totalPriceMarginWholePart: 0,
                    totalPriceMarginDecimalPart: 0,
                    totalPriceByLot: 0,
                    totalPriceByLotMargin: 0,
                    globalDiscount: 0,
                    coefficientTotalIsOverMax: false
                },
                totalQuantity: 1,
                maxLotMini: 1,
                lotMiniQuantity: 1,
                oldCategoryId: 0,
                oldType: '',
                oldCurrency: '',
                sendMessage: false,
                typeMessage: '',
                message:'',
                currency:'',
                currencySymbol:'',
                subunit: 2,
                calcSubUnit: 0.01,
                lat: '',
                lng: '',
                geoloc: '',
                flagMapResize: false,
                dataCompleteGeoloc: '',
                searchPlace: '',
                pictures: [],
                mainPicture: '',
                steps: [],
                successFormSubmit: false,
                isUrgent: false,
                isNegociated: false,
                hasVideo: false,
                videoId: undefined,
                cost: 0,
                onSetSteps: false,
                submitEnable: true,
                breadcrumbItems: [],
                dataAdvertEdit: {},
                isEditAdvert: false
            };
        },
        mounted () {
            let that = this;
            this.steps = [
                {
                    isActive : true,
                    isDisabled : false,
                    isCompleted: false,
                    title: this.strings.stepOneTitle,
                    description: this.strings.stepOneDescription,
                    icon: 'write'
                },
                {
                    isActive : false,
                    isDisabled : this.isDelegation == 1,
                    isCompleted: this.isDelegation == 1,
                    title: this.strings.stepTwoTitle,
                    description: this.strings.stepTwoDescription,
                    icon: 'user'
                },
                {
                    isActive : false,
                    isDisabled : true,
                    isCompleted: this.isDelegation == 1,
                    title: this.strings.stepThreeTitle,
                    description: this.strings.stepThreeDescription,
                    routeDescription: this.isDelegation == 1 ? undefined : this.routePrices,
                    icon: 'payment'
                }
            ];
            this.setBreadCrumbItems(this.strings.defaultBreadcrumb);
            this.description = this.strings.formDescriptionLabel;


            //Inits
            if(this.editAdvert !== ''){
                this.dataAdvertEdit = JSON.parse(this.editAdvert);
                this.isEditAdvert = true;
                if(this.old == '0') {
                    this.categoryId = this.dataAdvertEdit.category_id;
                    this.title = this.dataAdvertEdit.title;
                    this.manuRef = this.dataAdvertEdit.manu_ref != null ? this.dataAdvertEdit.manu_ref : '';
                    this.description = this.dataAdvertEdit.description;
                    this.price = this.dataAdvertEdit.originalPrice/(Math.pow(10,this.dataAdvertEdit.priceSubUnit));
                    this.totalQuantity = this.dataAdvertEdit.totalQuantity;
                    this.maxLotMini = this.totalQuantity;
                    this.lotMiniQuantity = this.dataAdvertEdit.lotMiniQuantity;
                    this.discountOnTotal = (this.dataAdvertEdit.discount_on_total).toFixed(2);
                    this.type = this.dataAdvertEdit.type;
                    this.currency = this.dataAdvertEdit.currency;
                    this.currencySymbol = this.dataAdvertEdit.currencySymbol;
                    this.lat = this.dataAdvertEdit.latitude;
                    this.lng = this.dataAdvertEdit.longitude;
                    this.isUrgent = this.dataAdvertEdit.isUrgent;
                    this.isNegociated = this.dataAdvertEdit.isNegociated;
                    this.setStorage();
                }
            } else {
                if(this.old == '0'){
                    sessionStorage.clear();
                    this.lat = this.geolocInitLat;
                    this.lng = this.geolocInitLng;
                    sessionStorage.setItem('lat', this.lat);
                    sessionStorage.setItem('lng', this.lng);
                }
            }

            this.getStorage();
            let paramsAccordion = $('#params_accordion'+this._uid);
            paramsAccordion.accordion({
                exclusive: false,
                'onOpen': function () {
                    that.flagMapResize = !that.flagMapResize;
                }
            });
            paramsAccordion.accordion('open', 0);
            $('.red.alarm.outline.large.icon').popup();
        },
        updated () {
            let that = this;
            $('#isUrgent'+this._uid).checkbox({
                onChecked: function() {that.isUrgent = true;},
                onUnchecked: function() {that.isUrgent = false;}
            });
            $('#isNegociated'+this._uid).checkbox({
                onChecked: function() {that.isNegociated = true;},
                onUnchecked: function() {that.isNegociated = false;}
            });

        },
        methods: {
            typeChoice: function (type) {
                this.type = type;
            },
            categoryChoice: function (id) {
                this.categoryId = parseInt(id);
            },
            currencyChoice: function (event) {
                this.currency = event.cur;
                this.currencySymbol = event.symbol;
                this.subunit = event.subunit;
            },
            sendToast: function(message,type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            },
            latLngChange: function (event) {
                this.lat= event.lat;
                this.lng= event.lng;
                this.geoloc= event.geoloc;
                this.dataCompleteGeoloc = sessionStorage.getItem('geoloc');
                this.searchPlace = sessionStorage.getItem('searchPlace');
            },
            setSteps () {
                if(this.isDelegation!=1) {
                    if(this.isDelegation != 1) {
                        (this.steps[2]).isDisabled = false;
                    }
                    let that = this;

                    let params = {};
                    if(this.isEditAdvert){
                        params = {'isEditOf': this.dataAdvertEdit.id};
                    }

                    Axios.get(this.routeGetCost+'/'+this.pictures.length + '/'+ this.isUrgent, {params: params})
                        .then(function (response) {
                            that.cost = response.data;
                            (that.steps[2]).title = that.strings.stepThreeTitle + '(' + (that.cost/100).toFixed(2) + that.strings.stepThreeTitlePost + ')';
                        })
                        .catch(function (error) {
                            that.cost = 0;
                            that.sendToast(that.strings.loadErrorMessage, 'error');
                        });
                } else {
                    this.cost = 0;
                    (this.steps[2]).title = this.strings.stepThreeTitle;
                    (this.steps[2]).isDisabled = true;
                }
            },
            setBreadCrumbItems: function (initString) {
                this.breadcrumbItems = [];
                let that = this;
                if(initString !== undefined && initString !== null){
                    let words = initString.split(" ");
                    words.forEach(function (word) {
                        that.breadcrumbItems.push({
                            name: word,
                            value: 0
                        });
                    })
                } else {
                    if(this.categoryId != undefined && this.categoryId>0 ) {
                        Axios.get(this.properties.routeCategory+'/'+this.categoryId)
                            .then(function (response) {
                                let chainedCategories = response.data;
                                that.breadcrumbItems.push({
                                    name: that.strings.allLabel,
                                    value: 0
                                });
                                chainedCategories.forEach(function (elem,index) {
                                    that.breadcrumbItems.push({
                                        name: elem['description'][that.properties.actualLocale],
                                        value: elem.id
                                    });
                                });
                            })
                            .catch(function (error) {
                                that.breadcrumbItems.push({
                                    name: this.strings.loadErrorMessage,
                                    value:''
                                });
                            });
                    }
                }
            },
            getMoment: function (dateTime) {
                moment.locale(this.properties.actualLocale);
                return moment(dateTime).fromNow()
            },
            calcNbFreePictures () {
                let nbOriginalPictures = 'pictures' in this.dataAdvertEdit ? parseInt(this.dataAdvertEdit.pictures.length) : 0;
                if(nbOriginalPictures > parseInt(this.advertFormPhotoNbFreePicture)){
                    return nbOriginalPictures;
                } else {
                    return parseInt(this.advertFormPhotoNbFreePicture);
                }
            },
            setFakeAdvert () {
                Object.assign(this.fakeAdvert, {
                    originalPrice: Number((this.price*Math.pow(10,this.subunit)).toFixed(0)),
                    buyingPrice: Number((this.buyingPrice*Math.pow(10,this.subunit)).toFixed(0)),
                    priceSubUnit: this.subunit,
                    totalQuantity: this.totalQuantity,
                    lotMiniQuantity: this.lotMiniQuantity,
                    discount_on_total: this.discountOnTotal,
                    currencySymbol: this.currencySymbol
                });
                this.updateMargins();
            },
            updateMargins () {
                let calcMargins = DestockTools.calcMargins(this.fakeAdvert, true);
                Object.assign(this.margins, calcMargins);
            },
            submitForm (event) {
                event.preventDefault();
                DestockTools.paceRestart();
                this.setStorage();
                $('#create_advert_form_'+this._uid).submit();
            },
            setStorage() {
                sessionStorage.setItem('category', this.categoryId);
                sessionStorage.setItem('title', this.title);
                sessionStorage.setItem('manuRef', this.manuRef);
                sessionStorage.setItem('description', this.description);
                sessionStorage.setItem('price', this.price);
                sessionStorage.setItem('discountOnTotal', this.discountOnTotal);
                sessionStorage.setItem('totalQuantity', this.totalQuantity);
                sessionStorage.setItem('lotMiniQuantity', this.lotMiniQuantity);
                sessionStorage.setItem('type', this.type);
                sessionStorage.setItem('currency', this.currency);
                sessionStorage.setItem('subunit', this.subunit);
                sessionStorage.setItem('lat', this.lat);
                sessionStorage.setItem('lng', this.lng);
                sessionStorage.setItem('isUrgent', this.isUrgent);
                sessionStorage.setItem('isNegociated', this.isNegociated);
            },
            getStorage() {
                sessionStorage.getItem('successFormSubmit') != undefined ? this.successFormSubmit = sessionStorage.getItem('successFormSubmit') : null;
                sessionStorage.getItem('category') != undefined ? this.categoryId = Number(sessionStorage.getItem('category')) : null;
                sessionStorage.getItem('category') != undefined? this.oldCategoryId = Number(sessionStorage.getItem('category')): null;
                sessionStorage.getItem('title') != undefined ? this.title = sessionStorage.getItem('title') : null;
                sessionStorage.getItem('manuRef') != undefined ? this.manuRef = sessionStorage.getItem('manuRef') : null;
                sessionStorage.getItem('description') != undefined ? this.description = sessionStorage.getItem('description') : null;
                sessionStorage.getItem('price') != undefined ? this.price = Number(sessionStorage.getItem('price')) : null;
                sessionStorage.getItem('discountOnTotal') != undefined ? this.discountOnTotal = Number(sessionStorage.getItem('discountOnTotal')) : null;
                sessionStorage.getItem('totalQuantity') != undefined ? this.totalQuantity = Number(sessionStorage.getItem('totalQuantity')) : null;
                this.maxLotMini = this.totalQuantity;
                sessionStorage.getItem('lotMiniQuantity') != undefined ? this.lotMiniQuantity = Number(sessionStorage.getItem('lotMiniQuantity')) : null;
                sessionStorage.getItem('type') != undefined ? this.type = sessionStorage.getItem('type') : null;
                sessionStorage.getItem('type') != undefined ? this.oldType = sessionStorage.getItem('type') : null;
                sessionStorage.getItem('currency') != undefined ? this.currency= sessionStorage.getItem('currency') : null;
                sessionStorage.getItem('currency') != undefined ? this.oldCurrency= sessionStorage.getItem('currency') : null;
                sessionStorage.getItem('subunit') != undefined ? this.subunit= Number(sessionStorage.getItem('subunit')) : null;
                sessionStorage.getItem('lat') != undefined ? this.lat =  sessionStorage.getItem('lat') : null;
                sessionStorage.getItem('lng') != undefined ? this.lng =  sessionStorage.getItem('lng') : null;
                sessionStorage.getItem('isUrgent') != undefined ? this.isUrgent =  sessionStorage.getItem('isUrgent') == 'true' : null;
                sessionStorage.getItem('isNegociated') != undefined ? this.isNegociated =  sessionStorage.getItem('isNegociated') == 'true' : null;
            },
            setMaxLotMini () {
                this.maxLotMini = this.totalQuantity;
            },
            updatePictures (pictures) {
                this.pictures = pictures;
                this.setSteps();
            },
            vimeoStateChange (event) {
                this.hasVideo = event.hasVideo;
                this.videoId = event.videoId;
                this.setSteps();
            }
        }
    }
</script>
