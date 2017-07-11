<template>
    <div>
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div class="ui grid">
            <div class="sixteen wide column">
                <h2 class="ui header">{{ contentHeader }}</h2>
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
                <form :id="'create_advert_form_'+_uid" class="ui form" :action="routeAdvertFormPost" method="post">
                    <input type="hidden" name="_token" :value="xCsrfToken"/>
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
                                {{ advertFormPreviewHeaderLabel }}
                            </h4>
                        </div>
                        <div class="sixteen wide mobile only six wide tablet only six wide computer only column">
                            <h4 class="ui horizontal divider header">
                                <i class="settings icon"></i>
                                {{ advertFormParamsHeaderLabel }}
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
                                                {{ advertExampleUrgentLabel }}
                                            </span>
                                    </div>
                                    <div :class="!isUrgent ? 'sixteen wide column' : 'fourteen wide column'">
                                        <h4 class="field">
                                            <input name="title" type="text" :placeholder="advertFormTitleLabel" v-model:value="title" :maxlength="formTitleMaxValid">
                                            <transition name="p-fade">
                                                <span class="ui red pointing basic label notransition" v-show="title.length<formTitleMinValid">{{ formTitleMinValid }}{{formPointingMinimumChars }}</span>
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
                                            :image-ratio="parseFloat(imageRatio)"
                                            :first-header="photoSwiperFirstHeader"
                                            :first-description="photoSwiperFirstDescription"
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
                                                                <i class="barcode icon"></i> {{ advertFormRefLabel }}
                                                            </td>
                                                            <td>
                                                                <div class="ui fluid input">
                                                                    <input name="manu_ref" type="text" :placeholder="advertFormRefLabel" v-model:value="manuRef">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="collapsing">
                                                                <i class="cubes icon"></i> {{ advertFormTotalQuantityLabel }}
                                                            </td>
                                                            <td>
                                                                <div class="ui fluid input">
                                                                    <input type="number" name="total_quantity" min="1" step="1" v-model="totalQuantity">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="collapsing">
                                                                <i class="cube icon"></i> {{ advertFormLotMiniQuantityLabel }}
                                                            </td>
                                                            <td>
                                                                <div class="ui fluid input">
                                                                    <input type="number" name="lot_mini_quantity" min="1" :max="totalQuantity" step="1" v-model="lotMiniQuantity">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="collapsing">
                                                                <i class="money icon"></i> {{ advertFormPriceLabel }}
                                                            </td>
                                                            <td>
                                                                <div v-show="!isNegociated" class="ui blue tag label">
                                                                    <div class="ui right labeled input">
                                                                        <template v-if="isNegociated==0">
                                                                            <input style="width: 90%"  name="price" type="number" :min="calcSubUnit" :step="calcSubUnit" v-model="price"/>
                                                                        </template>
                                                                        <template v-else>
                                                                            <input  name="" type="number" value="0" disabled/>
                                                                        </template>
                                                                        <div class="ui basic label">
                                                                            {{ currencySymbol }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div v-if="isNegociated" class="ui small blue tag label">{{ advertExampleIsNegociatedLabel }}</div>
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
                                                                <span class="ui red pointing basic label notransition" v-show="description.length<formDescriptionMinValid">{{ formDescriptionMinValid }}{{formPointingMinimumChars }}</span>
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
                                                :first-menu-name="listTypeFirstMenuName"
                                                :old-choice="'bid'"
                                                :is-disabled="true"
                                        ></type-radio-button>
                                    </div>
                                    <div :id="'params_accordion'+_uid" class="ui accordion">
                                        <div class="title">
                                            <i class="dropdown icon"></i>
                                            <span>
                                        <i class="sitemap icon"></i>
                                        {{ categoryFirstMenuName }}
                                    </span>
                                            <span>
                                        <template v-if="categoryId===undefined || categoryId===null || categoryId ==''">
                                            <i class="red alarm outline large icon" :data-content="categoryFieldRequired"></i>
                                        </template>
                                        <template v-else>
                                            <i class="green checkmark large icon"></i>
                                        </template>
                                    </span>
                                        </div>
                                        <div class="content">
                                            <div class="required field">
                                                <categories-dropdown-menu
                                                        :route-category="routeCategory"
                                                        :first-menu-name="categoryFirstMenuName"
                                                        :actual-locale="actualLocale"
                                                        :old-choice="oldCategoryId"
                                                        :with-all="false">
                                                </categories-dropdown-menu>
                                            </div>
                                        </div>
                                        <div class="title">
                                            <i class="dropdown icon"></i>
                                            <span>
                                        <i class="camera retro icon"></i>
                                        {{ advertFormPhotoSeparator }}
                                    </span>
                                            <span>
                                        <template v-if="thumbs.length===0">
                                        <i class="red alarm outline large icon" :data-content="photoFieldRequired"></i>
                                        </template>
                                        <template v-else>
                                            <i class="green checkmark large icon"></i>
                                        </template>
                                    </span>
                                        </div>
                                        <div class="content">
                                            <photo-uploader
                                                    :route-post-tempo-picture="routePostTempoPicture"
                                                    :route-get-list-tempo-thumbs="routeGetListTempoThumbs"
                                                    :route-get-tempo-thumb="routeGetTempoThumb"
                                                    :route-del-tempo-picture="routeDelTempoPicture"
                                                    :advert-form-photo-nb-free-picture="calcNbFreePictures()"
                                                    :max-files="parseInt(maxFiles)"
                                                    :is-delegation="isDelegation==1"
                                                    :old-main-picture="isEditAdvert ? dataAdvertEdit.mainPicturePicture : null"
                                                    nb-columns="two"
                                                    :advert-form-photo-btn-label="advertFormPhotoBtnLabel"
                                                    :advert-form-photo-btn-cancel="advertFormPhotoBtnCancel"
                                                    :advert-form-photo-label="advertFormPhotoLabel"
                                                    :advert-form-free-photo-help-header-singular="advertFormFreePhotoHelpHeaderSingular"
                                                    :advert-form-free-photo-help-header-plural="advertFormFreePhotoHelpHeaderPlural"
                                                    :advert-form-pay-photo-help-header-singular="advertFormPayPhotoHelpHeaderSingular"
                                                    :advert-form-pay-photo-help-header-plural="advertFormPayPhotoHelpHeaderPlural"
                                                    :advert-form-photo-help-content="advertFormPhotoHelpContent"
                                                    :advert-form-main-photo-label="advertFormMainPhotoLabel"
                                            ></photo-uploader>
                                        </div>
                                        <div class="title">
                                            <i class="dropdown icon"></i>
                                            <span>
                                        <i class="film icon"></i>
                                        {{ advertFormVideoSeparator }}
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
                                                    :format="'auto'"
                                                    :advert-form-video-btn-label="advertFormVideoSeparator"
                                                    :advert-form-video-label="advertFormVideoLabel"
                                                    :advert-form-video-btn-delete="advertFormVideoBtnDelete"
                                                    :advert-form-video-btn-cancel="advertFormVideoBtnCancel"
                                                    :waiting-message="waitingMessage"
                                                    :transcode-message="transcodeMessage"
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
                                                    <label><span class="ui red horizontal label">{{ advertExampleUrgentLabel }}</span>{{ advertFormUrgentLabel }}</label>
                                                </div>
                                            </div>
                                            <div class="field" v-if="isDelegation!=1">
                                                <div :id="'isNegociated'+_uid" class="ui checkbox">
                                                    <input type="checkbox" name="isNegociated">
                                                    <label> <span class="ui blue horizontal label">{{ advertExampleIsNegociatedLabel }}</span>{{ advertFormIsNegociatedLabel }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="title">
                                            <i class="dropdown icon"></i>
                                            <span>
                                        <i class="map signs icon"></i>
                                        {{ advertFormGooglemapLabel }}
                                    </span>
                                        </div>
                                        <div class="content">
                                            <div class="field">
                                                <googleMap
                                                        :lng="lng"
                                                        :lat="lat"
                                                        :geoloc="geoloc"
                                                        :geoloc-help-msg="geolocHelpMsg"
                                                        :geoloc-help-msg-two="geolocHelpMsgTwo"
                                                        :square="true"
                                                        :resize="flagMapResize"
                                                ></googleMap>
                                            </div>
                                        </div>
                                        <div class="title">
                                            <i class="dropdown icon"></i>
                                            <span>
                                        <i class="money icon"></i>
                                        {{ currenciesFirstMenuName }}
                                    </span>
                                        </div>
                                        <div class="content">
                                            <div class="field">
                                                <currencies-dropdown-2
                                                        :route-list-currencies="routeListCurrencies"
                                                        :first-menu-name="currenciesFirstMenuName"
                                                        :old-currency="oldCurrency">
                                                </currencies-dropdown-2>
                                            </div>
                                        </div>
                                        <div v-show="!isNegociated" class="title">
                                            <i class="dropdown icon"></i>
                                            <span>
                                        <i class="calculator icon"></i>
                                        {{ advertMarginHeader }}
                                    </span>
                                        </div>
                                        <div v-show="!isNegociated" class="content">
                                            <div class="field">
                                                <label>{{ advertFormTotalQuantityLabel }}</label>
                                                <input type="number" name="total_quantity" min="1" step="1" v-model="totalQuantity">
                                            </div>
                                            <div class="field">
                                                <label>{{ advertFormLotMiniQuantityLabel }}</label>
                                                <input type="number" name="lot_mini_quantity" min="1" :max="totalQuantity" step="1" v-model="lotMiniQuantity">
                                            </div>
                                            <div class="field">
                                                <label>{{ advertFormPriceLabel }}</label>
                                                <div class="ui right labeled input">
                                                    <template v-if="isNegociated==0">
                                                        <input style="width: 90%"  name="price" type="number" :min="calcSubUnit" :step="calcSubUnit" v-model="price"/>
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
                                                <label>{{ advertFormBuyingPriceLabel }}</label>
                                                <div class="ui input">
                                                    <template v-if="isNegociated==0">
                                                        <input  name="buyingPrice" type="number" :min="calcSubUnit" :step="calcSubUnit" v-model="buyingPrice"/>
                                                    </template>
                                                    <template v-else>
                                                        <input  name="" type="number" value="0" disabled/>
                                                    </template>
                                                </div>
                                                <margins-table v-if="isNegociated==0"
                                                               :advert="fakeAdvert"
                                                               :form-advert-price-coefficient-new-price-label="formAdvertPriceCoefficientNewPriceLabel"
                                                               :form-advert-price-coefficient-unit-margin-label="formAdvertPriceCoefficientUnitMarginLabel"
                                                               :form-advert-price-coefficient-lot-margin-label="formAdvertPriceCoefficientLotMarginLabel"
                                                               :form-advert-price-coefficient-total-margin-label="formAdvertPriceCoefficientTotalMarginLabel"
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
                                        {{ formValidationButtonLabel }}
                                    </h4>
                                    <div class="field">
                                        <button type="submit" :class="submitEnable ? 'ui blue labeled icon massive fluid button' : 'ui disabled labeled icon massive fluid button'" v-on:click="submitForm"><i class="save icon"></i>{{ formValidationButtonLabel }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sixteen wide mobile only column">
                            <h4 class="ui horizontal divider header">
                                <i class="unhide icon"></i>
                                {{ advertFormPreviewHeaderLabel }}
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
                                                {{ advertExampleUrgentLabel }}
                                            </span>
                                    </div>
                                    <div :class="!isUrgent ? 'sixteen wide column' : 'twelve wide column'">
                                        <h4 class="field">
                                            <input name="title" type="text" :placeholder="advertFormTitleLabel" v-model:value="title" :maxlength="formTitleMaxValid">
                                            <transition name="p-fade">
                                                <span class="ui red pointing basic label notransition" v-show="title.length<formTitleMinValid">{{ formTitleMinValid }}{{formPointingMinimumChars }}</span>
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
                                            :image-ratio="parseFloat(imageRatio)"
                                            :first-header="photoSwiperFirstHeader"
                                            :first-description="photoSwiperFirstDescription"
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
                                                                <i class="barcode icon"></i> {{ advertFormRefLabel }}
                                                            </td>
                                                            <td>
                                                                <div class="field">
                                                                    <input name="manu_ref" type="text" :placeholder="advertFormRefLabel" v-model:value="manuRef">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <i class="cubes icon"></i> {{ advertFormTotalQuantityLabel }}
                                                            </td>
                                                            <td>
                                                                <div class="field">
                                                                    <input type="number" name="total_quantity" min="1" step="1" v-model="totalQuantity">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <i class="cube icon"></i> {{ advertFormLotMiniQuantityLabel }}
                                                            </td>
                                                            <td>
                                                                <div class="field">
                                                                    <input type="number" name="lot_mini_quantity" min="1" :max="totalQuantity" step="1" v-model="lotMiniQuantity">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <i class="money icon"></i> {{ advertFormPriceLabel }}
                                                            </td>
                                                            <td>
                                                                <div v-show="!isNegociated" class="ui blue tag label">
                                                                    <div class="ui right labeled input">
                                                                        <template v-if="isNegociated==0">
                                                                            <input style="width: 90%"  name="price" type="number" :min="calcSubUnit" :step="calcSubUnit" v-model="price"/>
                                                                        </template>
                                                                        <template v-else>
                                                                            <input  name="" type="number" value="0" disabled/>
                                                                        </template>
                                                                        <div class="ui basic label">
                                                                            {{ currencySymbol }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div v-if="isNegociated" class="ui small blue tag label">{{ advertExampleIsNegociatedLabel }}</div>
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
                                                                <span class="ui red pointing basic label notransition" v-show="description.length<formDescriptionMinValid">{{ formDescriptionMinValid }}{{formPointingMinimumChars }}</span>
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
                                {{ formValidationButtonLabel }}
                            </h4>
                            <div class="field">
                                <button type="submit" :class="submitEnable ? 'ui blue labeled icon massive fluid button' : 'ui disabled labeled icon massive fluid button'" v-on:click="submitForm"><i class="save icon"></i>{{ formValidationButtonLabel }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            //vue routes
            'routeAdvertFormPost',
            'routeGetCost',
            'routePrices',
            //vue vars
            'old',
            'formTitleMinValid',
            'formTitleMaxValid',
            'formDescriptionMinValid',
            'formDescriptionMaxValid',
            'isDelegation',
            'editAdvert',
            //vue strings
            'contentHeader',
            'advertFormPreviewHeaderLabel',
            'advertFormParamsHeaderLabel',
            'advertFormTitleLabel',
            'advertFormRefLabel',
            'advertFormDescriptionLabel',
            'advertFormPriceLabel',
            'advertFormBuyingPriceLabel',
            'advertFormGooglemapLabel',
            'advertFormPhotoSeparator',
            'loadErrorMessage',
            'filesizeErrorMessage',
            'formValidationButtonLabel',
            'formPointingMinimumChars',
            'advertFormTotalQuantityLabel',
            'advertFormLotMiniQuantityLabel',
            'advertFormUrgentLabel',
            'advertExampleUrgentLabel',
            'advertFormIsNegociatedLabel',
            'advertExampleIsNegociatedLabel',
            'advertPrices',
            'advertMarginHeader',
            'categoryFieldRequired',
            'photoFieldRequired',
            //margins-table component
            'formAdvertPriceCoefficientNewPriceLabel',
            'formAdvertPriceCoefficientUnitMarginLabel',
            'formAdvertPriceCoefficientLotMarginLabel',
            'formAdvertPriceCoefficientTotalMarginLabel',
            //steps component
            'stepOneTitle',
            'stepTwoTitle',
            'stepThreeTitle',
            'stepThreeTitlePost',
            'stepOneDescription',
            'stepTwoDescription',
            'stepThreeDescription',
            //type-advert component
            'routeGetListType',
            'listTypeFirstMenuName',
            //category component
            'routeCategory',
            'categoryFirstMenuName',
            'actualLocale',
            'allLabel',
            'defaultBreadcrumb',
            //currencies-dropdown component
            'routeListCurrencies',
            'currenciesFirstMenuName',
            //geomap component
            'geolocHelpMsg',
            'geolocHelpMsgTwo',
            'geolocInitLat',
            'geolocInitLng',
            //Photo component
            'routePostTempoPicture',
            'routeGetListTempoThumbs',
            'routeGetTempoThumb',
            'routeGetTempoNormal',
            'routeDelTempoPicture',
            'advertFormPhotoNbFreePicture',
            'maxFiles',
            'advertFormPhotoBtnLabel',
            'advertFormPhotoBtnCancel',
            'advertFormPhotoLabel',
            'advertFormFreePhotoHelpHeaderSingular',
            'advertFormFreePhotoHelpHeaderPlural',
            'advertFormPayPhotoHelpHeaderSingular',
            'advertFormPayPhotoHelpHeaderPlural',
            'advertFormPhotoHelpContent',
            'advertFormMainPhotoLabel',
            //vimeo component
            'routeGetVideoPostTicket',
            'routeDelTempoVideo',
            'routeGetStatusVideo',
            'maxVideoFileSize',
            'sessionVideoId',
            'advertFormVideoSeparator',
            'advertFormVideoLabel',
            'advertFormVideoBtnDelete',
            'advertFormVideoBtnCancel',
            'waitingMessage',
            'transcodeMessage',
            //swiper component
            'imageRatio',
            'photoSwiperFirstHeader',
            'photoSwiperFirstDescription',
        ],
        data: () => {
            return {
                categoryId: '',
                listType: [],
                type: '',
                title: '',
                manuRef: '',
                description: '',
                price: '0',
                buyingPrice: '0',
                fakeAdvert: {
                    originalPrice: 0,
                    buyingPrice: 0,
                    priceSubUnit: 2,
                    totalQuantity: 0,
                    lotMiniQuantity:0,
                    currencySymbol: ''
                },
                totalQuantity: 1,
                lotMiniQuantity: 1,
                xCsrfToken: '',
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
                thumbs: [],
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
                    title: this.stepOneTitle,
                    description: this.stepOneDescription,
                    icon: 'write'
                },
                {
                    isActive : false,
                    isDisabled : this.isDelegation == 1,
                    isCompleted: this.isDelegation == 1,
                    title: this.stepTwoTitle,
                    description: this.stepTwoDescription,
                    icon: 'user'
                },
                {
                    isActive : false,
                    isDisabled : true,
                    isCompleted: this.isDelegation == 1,
                    title: this.stepThreeTitle,
                    description: this.stepThreeDescription,
                    routeDescription: this.isDelegation == 1 ? undefined : this.routePrices,
                    icon: 'payment'
                }
            ];
            this.setBreadCrumbItems(this.defaultBreadcrumb);
            this.description = this.advertFormDescriptionLabel;
            this.$on('typeChoice', function (event) {
                this.typeChoice(event.type);
            });
            this.$on('categoryChoice', function (event) {
                this.categoryChoice(event.id);
            });
            this.$on('currencyChoice', function (event) {
                this.currencyChoice(event.cur, event.subunit, event.symbol, event.initial);
            });
            this.$on('locationChange', function (event) {
                this.latLngChange(event);
            });
            this.$on('updateThumbs', function (event) {
                this.thumbs = event;
                this.setPictures();
                this.setSteps();
            });
            this.$on('vimeoStateChange', function (event) {
                this.hasVideo = event.hasVideo;
                this.videoId = event.videoId;
                this.setSteps();
            });
            this.$on('updateMainPicture', function (event) {
                this.mainPicture = event;
            });
            this.$on('loadError', function () {
                this.sendToast(this.loadErrorMessage, 'error');
            });
            this.$on('sendToast', function (event) {
                this.sendToast(event.message, event.type);
            });
            this.$on('fileSizeError', function () {
                this.sendToast(this.filesizeErrorMessage, 'error');
            });
            this.$on('videoUploadStatusChange', function(onUpload){
                this.submitEnable = !onUpload;
            });
            this.xCsrfToken = destockShareVar.csrfToken;
            this.$watch('categoryId', function () {
                this.setBreadCrumbItems();
            });
            this.$watch('isUrgent', function () {
                this.setSteps();
                if(this.isUrgent){
                    $('#isUrgent'+this._uid).checkbox('check');
                } else {

                    $('#isUrgent'+this._uid).checkbox('uncheck');
                }
            });
            this.$watch('mainPicture', function () {
                this.setPictures();
            });
            this.$watch('isNegociated', function () {
                if(this.isNegociated){
                    $('#isNegociated'+this._uid).checkbox('check');
                } else {

                    $('#isNegociated'+this._uid).checkbox('uncheck');
                }
            });
            this.$watch('subunit', function () {
               this.calcSubUnit = Math.pow(10,-(this.subunit));
               this.price = parseFloat(this.price).toFixed(this.subunit);
               this.fakeAdvert.priceSubUnit = parseInt(this.subunit);
            });
            this.$watch('currencySymbol', function () {
                this.fakeAdvert.currencySymbol = this.currencySymbol;
            });
            this.$watch('price', function () {
                this.fakeAdvert.originalPrice = parseFloat(this.price);
            });
            this.$watch('buyingPrice', function () {
                this.fakeAdvert.buyingPrice = parseFloat(this.buyingPrice);
            });
            this.$watch('totalQuantity', function () {
                this.fakeAdvert.totalQuantity = parseInt(this.totalQuantity);
            });
            this.$watch('lotMiniQuantity', function () {
                this.fakeAdvert.lotMiniQuantity = parseInt(this.lotMiniQuantity);
            });

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
                    this.lotMiniQuantity = this.dataAdvertEdit.lotMiniQuantity;
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
            currencyChoice: function (currency, subunit, symbol, initial) {
                if(this.oldCurrency == '' || initial==false){
                    this.currency = currency;
                    this.currencySymbol = symbol;
                    this.subunit = subunit;
                }
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

                    axios.get(this.routeGetCost+'/'+this.thumbs.length + '/'+ this.isUrgent, {params: params})
                        .then(function (response) {
                            that.cost = response.data;
                            (that.steps[2]).title = that.stepThreeTitle + '(' + (that.cost/100).toFixed(2) + that.stepThreeTitlePost + ')';
                        })
                        .catch(function (error) {
                            that.cost = 0;
                            that.sendToast(that.loadErrorMessage, 'error');
                        });
                } else {
                    this.cost = 0;
                    (this.steps[2]).title = this.stepThreeTitle;
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
                        axios.get(this.routeCategory+'/'+this.categoryId)
                            .then(function (response) {
                                let chainedCategories = response.data;
                                that.breadcrumbItems.push({
                                    name: that.allLabel,
                                    value: 0
                                });
                                chainedCategories.forEach(function (elem,index) {
                                    that.breadcrumbItems.push({
                                        name: elem['description'][that.actualLocale],
                                        value: elem.id
                                    });
                                });
                            })
                            .catch(function (error) {
                                that.breadcrumbItems.push({
                                    name: this.loadErrorMessage,
                                    value:''
                                });
                            });
                    }
                }
            },
            setPictures: function () {
                this.pictures=[];
                let that = this;
                this.thumbs.forEach(function (hashName) {
                    if(hashName == that.mainPicture){
                        that.pictures.unshift({isThumb: false, url:that.routeGetTempoNormal+"/"+hashName});
                        that.pictures.unshift({isThumb: true, url:that.routeGetTempoThumb+"/"+hashName});
                    } else {
                        that.pictures.push({isThumb: true, url:that.routeGetTempoThumb+"/"+hashName});
                        that.pictures.push({isThumb: false, url:that.routeGetTempoNormal+"/"+hashName});
                    }

                });
            },
            getMoment: function (dateTime) {
                moment.locale(this.actualLocale);
                return moment(dateTime).fromNow()
            },
            calcNbFreePictures () {
                let nbOriginalPictures = 'pictures' in this.dataAdvertEdit ? parseInt(this.dataAdvertEdit.pictures.length)/2 : 0;
                if(nbOriginalPictures > parseInt(this.advertFormPhotoNbFreePicture)){
                    return nbOriginalPictures;
                } else {
                    return parseInt(this.advertFormPhotoNbFreePicture);
                }
            },
            submitForm (event) {
                event.preventDefault();
                this.setStorage();
                $('#create_advert_form_'+this._uid).submit();
            },
            setStorage() {
                sessionStorage.setItem('category', this.categoryId);
                sessionStorage.setItem('title', this.title);
                sessionStorage.setItem('manuRef', this.manuRef);
                sessionStorage.setItem('description', this.description);
                sessionStorage.setItem('price', this.price);
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
                sessionStorage.getItem('category') != undefined ? this.categoryId = sessionStorage.getItem('category') : null;
                sessionStorage.getItem('category') != undefined? this.oldCategoryId = parseInt(sessionStorage.getItem('category')): null;
                sessionStorage.getItem('title') != undefined ? this.title = sessionStorage.getItem('title') : null;
                sessionStorage.getItem('manuRef') != undefined ? this.manuRef = sessionStorage.getItem('manuRef') : null;
                sessionStorage.getItem('description') != undefined ? this.description = sessionStorage.getItem('description') : null;
                sessionStorage.getItem('price') != undefined ? this.price = sessionStorage.getItem('price') : null;
                sessionStorage.getItem('totalQuantity') != undefined ? this.totalQuantity = sessionStorage.getItem('totalQuantity') : null;
                sessionStorage.getItem('lotMiniQuantity') != undefined ? this.lotMiniQuantity = sessionStorage.getItem('lotMiniQuantity') : null;
                sessionStorage.getItem('type') != undefined ? this.type = sessionStorage.getItem('type') : null;
                sessionStorage.getItem('type') != undefined ? this.oldType = sessionStorage.getItem('type') : null;
                sessionStorage.getItem('currency') != undefined ? this.currency= sessionStorage.getItem('currency') : null;
                sessionStorage.getItem('currency') != undefined ? this.oldCurrency= sessionStorage.getItem('currency') : null;
                sessionStorage.getItem('subunit') != undefined ? this.subunit= sessionStorage.getItem('subunit') : null;
                sessionStorage.getItem('subunit') != undefined ? this.oldSubunit= sessionStorage.getItem('subunit') : null;
                sessionStorage.getItem('lat') != undefined ? this.lat =  sessionStorage.getItem('lat') : null;
                sessionStorage.getItem('lng') != undefined ? this.lng =  sessionStorage.getItem('lng') : null;
                sessionStorage.getItem('isUrgent') != undefined ? this.isUrgent =  sessionStorage.getItem('isUrgent') == 'true' : null;
                sessionStorage.getItem('isNegociated') != undefined ? this.isNegociated =  sessionStorage.getItem('isNegociated') == 'true' : null;
            }
        }
    }
</script>
