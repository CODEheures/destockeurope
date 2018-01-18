<template>
    <div>
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
                    <input type="hidden" name="type" value="bid"/>
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
                                                    :max-photo-file-size="parseInt(maxPhotoFileSize)"
                                                    :is-delegation="isDelegation==1"
                                                    :old-main-picture="isEditAdvert ? dataAdvertEdit.mainPicturePicture : null"
                                                    :is-edit-advert="isEditAdvert"
                                                    :edit-advert-id="isEditAdvert ? dataAdvertEdit.id : 0"
                                                    nb-columns="two"
                                                    @updatePictures="updatePictures"
                                                    @updateMainPicture="mainPicture = $event"
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
  /**
   * Props
   *  - routeAdvertFormPost: String. The route to post advert
   *  - routeGetCost: String. The route to get cost of advert
   *  - routePrices: String. The route to list of prices
   *  - routeGetListType: String. The route to list of advert types
   *  - routePostPicture: String. The route to post a picture
   *  - routeGetListPosts: String. The route to get the list of pictures
   *  - routeDelPicture: String. The route to del a post picture
   *  - routeGetVideoPostTicket: String. The route to get a vimeo ticket
   *  - routeDelTempoVideo: String. The route for del tempo video
   *  - routeGetStatusVideo: String. The route to get status of upload video
   *  - old: String. JSON of the old inputs values
   *  - formTitleMinValid: String. Minimun title length to valid form
   *  - formDescriptionMaxValid: String. Maximum title length to valid form
   *  - formDescriptionMinValid: String. Minimun description length to valid form
   *  - formDescriptionMaxValid: String. Maximum description length to valid form
   *  - isDelegation: String. Boolean status if the advert is a delegation
   *  - editAdvert: String. Boolean status if the advert is on edit
   *  - geolocInitLat: String. Latitude
   *  - geolocInitLng: String. Longitude
   *  - advertFormPhotoNbFreePicture: String. The number of free pictures
   *  - maxFiles: String. The max number of photo files
   *  - maxPhotoFileSize: String. The maximum photo file size
   *  - maxVideoFileSize: String. The maximum video file size
   *  - sessionVideoId: String. The Id of the video
   *
   * Events:
   *
   */
  import moment from 'moment'
  import { DestockTools } from '../../destockTools'
  import Axios from 'axios'
  export default {
    props: [
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
      'maxPhotoFileSize',
      'maxVideoFileSize',
      'sessionVideoId'
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
        this.oldCategoryId = this.categoryId
        this.setBreadCrumbItems()
      },
      isUrgent () {
        this.setSteps()
        if (this.isUrgent) {
          $('#isUrgent' + this._uid).checkbox('check')
        }
        else {
          $('#isUrgent' + this._uid).checkbox('uncheck')
        }
      },
      isNegociated () {
        if (this.isNegociated) {
          $('#isNegociated' + this._uid).checkbox('check')
        }
        else {
          $('#isNegociated' + this._uid).checkbox('uncheck')
        }
      },
      subunit () {
        this.calcSubUnit = Math.pow(10, -(this.subunit))
        this.setFakeAdvert()
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
        type: 'bid',
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
          lotMiniQuantity: 0,
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
        message: '',
        currency: '',
        currencySymbol: '',
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
        dataAdvertEdit: {},
        isEditAdvert: false
      }
    },
    mounted () {
      let that = this
      this.steps = [
        {
          isActive: true,
          isDisabled: false,
          isCompleted: false,
          title: this.strings.stepOneTitle,
          description: this.strings.stepOneDescription,
          icon: 'write'
        },
        {
          isActive: false,
          isDisabled: this.isDelegation === '1',
          isCompleted: this.isDelegation === '1',
          title: this.strings.stepTwoTitle,
          description: this.strings.stepTwoDescription,
          icon: 'user'
        },
        {
          isActive: false,
          isDisabled: true,
          isCompleted: this.isDelegation === '1',
          title: this.strings.stepThreeTitle,
          description: this.strings.stepThreeDescription,
          routeDescription: this.isDelegation === '1' ? undefined : this.routePrices,
          icon: 'payment'
        }
      ]
      this.setBreadCrumbItems(this.strings.defaultBreadcrumb)
      this.description = this.strings.formDescriptionLabel
      // Inits
      if (this.editAdvert !== '') {
        this.dataAdvertEdit = JSON.parse(this.editAdvert)
        this.isEditAdvert = true
        if (this.old === '0') {
          this.categoryId = this.dataAdvertEdit.category_id
          this.title = this.dataAdvertEdit.title
          this.manuRef = this.dataAdvertEdit.manu_ref !== undefined && this.dataAdvertEdit.manu_ref !== null ? this.dataAdvertEdit.manu_ref : ''
          this.description = this.dataAdvertEdit.description
          this.price = this.dataAdvertEdit.originalPrice / (Math.pow(10, this.dataAdvertEdit.priceSubUnit))
          this.totalQuantity = this.dataAdvertEdit.totalQuantity
          this.maxLotMini = this.totalQuantity
          this.lotMiniQuantity = this.dataAdvertEdit.lotMiniQuantity
          this.discountOnTotal = this.dataAdvertEdit.discount_on_total.toFixed(2)
          this.type = this.dataAdvertEdit.type
          this.currency = this.dataAdvertEdit.currency
          this.currencySymbol = this.dataAdvertEdit.currencySymbol
          this.lat = this.dataAdvertEdit.latitude
          this.lng = this.dataAdvertEdit.longitude
          this.isUrgent = this.dataAdvertEdit.isUrgent
          this.isNegociated = this.dataAdvertEdit.isNegociated
          this.setStorage()
        }
      }
      else {
        if (this.old === '0') {
          sessionStorage.clear()
          this.lat = this.geolocInitLat
          this.lng = this.geolocInitLng
          sessionStorage.setItem('lat', this.lat)
          sessionStorage.setItem('lng', this.lng)
        }
      }
      this.getStorage()
      let paramsAccordion = $('#params_accordion' + this._uid)
      paramsAccordion.accordion({
        exclusive: false,
        onOpen () {
          that.flagMapResize = !that.flagMapResize
        }
      })
      paramsAccordion.accordion('open', 0)
      $('.red.alarm.outline.large.icon').popup()
    },
    updated () {
      let that = this
      $('#isUrgent' + this._uid).checkbox({
        onChecked () { that.isUrgent = true },
        onUnchecked () { that.isUrgent = false }
      })
      $('#isNegociated' + this._uid).checkbox({
        onChecked () { that.isNegociated = true },
        onUnchecked () { that.isNegociated = false }
      })
    },
    methods: {
      typeChoice (type) {
        this.type = type
      },
      categoryChoice (id) {
        this.categoryId = parseInt(id)
      },
      currencyChoice (event) {
        this.currency = event.cur
        this.currencySymbol = event.symbol
        this.subunit = event.subunit
      },
      latLngChange (event) {
        this.lat = event.lat
        this.lng = event.lng
        this.geoloc = event.geoloc
        this.dataCompleteGeoloc = sessionStorage.getItem('geoloc')
        this.searchPlace = sessionStorage.getItem('searchPlace')
      },
      setSteps () {
        if (this.isDelegation !== '1') {
          if (this.isDelegation !== '1') {
            (this.steps[2]).isDisabled = false
          }
          let that = this
          let params = {}
          if (this.isEditAdvert) {
            params = {'isEditOf': this.dataAdvertEdit.id}
          }
          Axios.get(this.routeGetCost + '/' + this.pictures.length + '/' + this.isUrgent, {params: params})
            .then(function (response) {
              that.cost = response.data
              that.steps[2].title = that.strings.stepThreeTitle + '(' + (that.cost / 100).toFixed(2) + that.strings.stepThreeTitlePost + ')'
            })
            .catch(function () {
              that.cost = 0
              that.$alertV({'message': that.strings.loadErrorMessage, 'type': 'error'})
            })
        }
        else {
          this.cost = 0
          this.steps[2].title = this.strings.stepThreeTitle
          this.steps[2].isDisabled = true
        }
      },
      setBreadCrumbItems (initString) {
        let breadcrumbItems = []
        if (initString !== undefined && initString !== null) {
          let words = initString.split(' ')
          words.forEach(function (word) {
            breadcrumbItems.push({
              name: word,
              value: 0
            })
          })
          this.$store.commit('setProperties', {name: 'breadcrumbItems', properties: breadcrumbItems})
        }
        else {
          DestockTools.setBreadCrumbItems(this.$store, this.categoryId, this.strings.allLabel)
        }
      },
      getMoment (dateTime) {
        moment.locale(this.properties.actualLocale)
        return moment(dateTime).fromNow()
      },
      calcNbFreePictures () {
        let nbOriginalPictures = 'pictures' in this.dataAdvertEdit ? parseInt(this.dataAdvertEdit.pictures.length) : 0
        if (nbOriginalPictures > parseInt(this.advertFormPhotoNbFreePicture)) {
          return nbOriginalPictures
        }
        else {
          return parseInt(this.advertFormPhotoNbFreePicture)
        }
      },
      setFakeAdvert () {
        Object.assign(this.fakeAdvert, {
          originalPrice: Number((this.price * Math.pow(10, this.subunit)).toFixed(0)),
          buyingPrice: Number((this.buyingPrice * Math.pow(10, this.subunit)).toFixed(0)),
          priceSubUnit: this.subunit,
          totalQuantity: this.totalQuantity,
          lotMiniQuantity: this.lotMiniQuantity,
          discount_on_total: this.discountOnTotal,
          currencySymbol: this.currencySymbol
        })
        this.updateMargins()
      },
      updateMargins () {
        let calcMargins = DestockTools.calcMargins(this.fakeAdvert, true)
        Object.assign(this.margins, calcMargins)
      },
      submitForm (event) {
        event.preventDefault()
        DestockTools.paceRestart()
        this.setStorage()
        $('#create_advert_form_' + this._uid).submit()
      },
      setStorage () {
        sessionStorage.setItem('category', this.categoryId)
        sessionStorage.setItem('title', this.title)
        sessionStorage.setItem('manuRef', this.manuRef)
        sessionStorage.setItem('description', this.description)
        sessionStorage.setItem('price', this.price)
        sessionStorage.setItem('discountOnTotal', this.discountOnTotal)
        sessionStorage.setItem('totalQuantity', this.totalQuantity)
        sessionStorage.setItem('lotMiniQuantity', this.lotMiniQuantity)
        sessionStorage.setItem('type', this.type)
        sessionStorage.setItem('currency', this.currency)
        sessionStorage.setItem('subunit', this.subunit)
        sessionStorage.setItem('lat', this.lat)
        sessionStorage.setItem('lng', this.lng)
        sessionStorage.setItem('isUrgent', this.isUrgent)
        sessionStorage.setItem('isNegociated', this.isNegociated)
      },
      getStorage () {
        if (sessionStorage.getItem('successFormSubmit') !== undefined && sessionStorage.getItem('successFormSubmit') !== null) { this.successFormSubmit = sessionStorage.getItem('successFormSubmit') }
        if (sessionStorage.getItem('category') !== undefined && sessionStorage.getItem('category') !== null) { this.categoryId = Number(sessionStorage.getItem('category')) }
        if (sessionStorage.getItem('category') !== undefined && sessionStorage.getItem('category') !== null) { this.oldCategoryId = Number(sessionStorage.getItem('category')) }
        if (sessionStorage.getItem('title') !== undefined && sessionStorage.getItem('title') !== null) { this.title = sessionStorage.getItem('title') }
        if (sessionStorage.getItem('manuRef') !== undefined && sessionStorage.getItem('manuRef') !== null) { this.manuRef = sessionStorage.getItem('manuRef') }
        if (sessionStorage.getItem('description') !== undefined && sessionStorage.getItem('description') !== null) { this.description = sessionStorage.getItem('description') }
        if (sessionStorage.getItem('price') !== undefined && sessionStorage.getItem('price') !== null) { this.price = Number(sessionStorage.getItem('price')) }
        if (sessionStorage.getItem('discountOnTotal') !== undefined && sessionStorage.getItem('discountOnTotal') !== null) { this.discountOnTotal = Number(sessionStorage.getItem('discountOnTotal')) }
        if (sessionStorage.getItem('totalQuantity') !== undefined && sessionStorage.getItem('totalQuantity') !== null) { this.totalQuantity = Number(sessionStorage.getItem('totalQuantity')) }
        this.maxLotMini = this.totalQuantity
        if (sessionStorage.getItem('lotMiniQuantity') !== undefined && sessionStorage.getItem('lotMiniQuantity') !== null) { this.lotMiniQuantity = Number(sessionStorage.getItem('lotMiniQuantity')) }
        if (sessionStorage.getItem('type') !== undefined && sessionStorage.getItem('type') !== null) { this.type = sessionStorage.getItem('type') }
        if (sessionStorage.getItem('type') !== undefined && sessionStorage.getItem('type') !== null) { this.oldType = sessionStorage.getItem('type') }
        if (sessionStorage.getItem('currency') !== undefined && sessionStorage.getItem('currency') !== null) { this.currency = sessionStorage.getItem('currency') }
        if (sessionStorage.getItem('currency') !== undefined && sessionStorage.getItem('currency') !== null) { this.oldCurrency = sessionStorage.getItem('currency') }
        if (sessionStorage.getItem('subunit') !== undefined && sessionStorage.getItem('subunit') !== null) { this.subunit = Number(sessionStorage.getItem('subunit')) }
        if (sessionStorage.getItem('lat') !== undefined && sessionStorage.getItem('lat') !== null) { this.lat = sessionStorage.getItem('lat') }
        if (sessionStorage.getItem('lng') !== undefined && sessionStorage.getItem('lng') !== null) { this.lng = sessionStorage.getItem('lng') }
        if (sessionStorage.getItem('isUrgent') !== undefined && sessionStorage.getItem('isUrgent') !== null) { this.isUrgent = sessionStorage.getItem('isUrgent') === 'true' }
        if (sessionStorage.getItem('isNegociated') !== undefined && sessionStorage.getItem('isNegociated') !== null) { this.isNegociated = sessionStorage.getItem('isNegociated') === 'true' }
      },
      setMaxLotMini () {
        this.maxLotMini = this.totalQuantity
      },
      updatePictures (pictures) {
        this.pictures = pictures
        this.setSteps()
      },
      vimeoStateChange (event) {
        this.hasVideo = event.hasVideo
        this.videoId = event.videoId
        this.setSteps()
      }
    }
  }
</script>
