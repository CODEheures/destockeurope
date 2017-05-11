<template>
    <div class="item advert">
        <template  v-if="!isPersonnalList">
            <a :href="advert.url">
                <div class="ui grid">
                    <div class="sixteen wide tablet only sixteen wide computer only column">
                        <div class="ui vertical middle aligned grid">
                            <div class="four wide tablet four wide computer column">
                                <div class="ui image">
                                    <template v-if="advert.video_id">
                                        <div class="ui top aligned medium bordered rounded image">
                                            <div style="width:100%; height:100%; background-color: rgba(0,0,0,0.1); position: absolute; z-index: 2"></div>
                                            <iframe :id="'vimeo-iframe-'+_uid" :src="'https://player.vimeo.com/video/' + advert.video_id" width="100%" height="100%" frameborder="0"></iframe>
                                        </div>
                                        <div class="ui right blue corner label">
                                            <i class="icon">{{ advert.pictures.length/2 }}</i>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <img class="ui top aligned medium bordered rounded image" :src="advert.thumb">
                                        <div class="ui right blue corner label">
                                            <i class="icon">{{ advert.pictures.length/2 }}</i>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div class="twelve wide tablet only twelve wide computer only column">
                                <div class="ui grid">
                                    <div class="eleven wide left aligned column">
                                        <div class="header"><h4>{{ advert.title }}</h4></div>
                                        <p>
                                            <span class="ui breadcrumb">
                                                <template v-for="(item,index) in advert.breadCrumb">
                                                    <div class="active section">{{ item.description[actualLocale] }}</div>
                                                    <i class="right angle icon divider" v-if="index != advert.breadCrumb.length-1"></i>
                                                </template>
                                            </span>
                                        </p>
                                    </div>
                                    <div class="five wide right aligned vertical top aligned column">
                                        <p class="infos">
                                            <span :title="totalQuantityLabel"><i class="cubes icon"></i>{{ advert.totalQuantity }} </span>
                                            <span :title="lotMiniQuantityLabel"><i class="cube icon"></i>{{ advert.lotMiniQuantity }}</span><br />
                                            <span v-if="advert.isUrgent" class="ui red horizontal label">{{ urgentLabel }}</span>
                                        </p>
                                    </div>
                                    <div class="sixteen wide column item-description">
                                        <div class="description">
                                            <p class="resume">{{ advert.resume }}</p>
                                        </div>
                                    </div>
                                    <div class="four wide column">
                                        <span :class="advert.isNegociated ? 'ui big blue left floated left pointing label price negociated' : 'ui big yellow left floated left pointing label price'">{{ advert.isNegociated ? isNegociatedLabel + '(' + advert.currencySymbol + ')' : advert.price_margin }}</span>
                                    </div>
                                    <div class="twelve wide right aligned column geodate-computer">
                                        <p>
                                            <i class="yellow big heart icon" v-if="advert.isUserOwner"></i><span v-if="advert.isUserOwner">{{ advert.bookmarkCount }}</span>
                                            <i class="empty big heart yellow icon" v-on:click="bookmarkMe" :data-id="advert.id" v-if="!advert.isUserOwner && !advert.isUserBookmark"></i>
                                            <i class="big heart yellow icon" v-on:click="unbookmarkMe" :data-id="advert.id" v-if="!advert.isUserOwner && advert.isUserBookmark"></i>
                                            <br /><i class="map signs icon"></i><span class="meta">{{ advert.geoloc }}</span>
                                            <i class="calendar icon"></i><span class="meta">{{ getMoment(advert.online_at) }}</span>
                                            <i class="unhide icon"></i><span class="meta">{{ advert.views }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sixteen wide mobile only ptpb2 column">
                        <div class="ui grid">
                            <div class="sixteen wide mobile only column">
                                <div class="header"><h4>{{ advert.title }}</h4></div>
                                <span class="ui mini breadcrumb">
                                    <template v-for="(item,index) in advert.breadCrumb">
                                        <div class="active section">{{ item.description[actualLocale] }}</div>
                                        <i class="right angle icon divider" v-if="index != advert.breadCrumb.length-1"></i>
                                    </template>
                                </span>
                            </div>
                            <div class="ten wide mobile only column">
                                <div class="ui image">
                                    <template v-if="advert.video_id">
                                        <div class="ui top aligned medium bordered rounded image">
                                            <div style="width:100%; height:100%; background-color: rgba(0,0,0,0.1); position: absolute; z-index: 2"></div>
                                            <iframe :id="'vimeo-iframe-'+_uid" :src="'https://player.vimeo.com/video/' + advert.video_id" width="100%" height="100%" frameborder="0"></iframe>
                                        </div>
                                        <div class="ui right blue corner label">
                                            <i class="icon">{{ advert.pictures.length/2 }}</i>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <img class="ui top aligned medium bordered rounded image" :src="advert.thumb">
                                        <div class="ui right blue corner label">
                                            <i class="icon">{{ advert.pictures.length/2 }}</i>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div class="six wide mobile only column">
                                <p class="infos">
                                    <span v-if="advert.isUrgent" class="ui red horizontal label">{{ urgentLabel }}</span>
                                </p>
                                <p class="infos">
                                    <span><i class="cubes icon" :title="totalQuantityLabel"></i>{{ advert.totalQuantity }} </span>
                                </p>
                                <p class="infos">
                                    <span><i class="cube icon" :title="lotMiniQuantityLabel"></i>{{ advert.lotMiniQuantity }}</span>
                                </p>
                                <p>
                                    <span :class="advert.isNegociated ? 'ui blue left pointing label price negociated' : 'ui yellow left pointing label price'">{{ advert.isNegociated ? isNegociatedLabel + '(' + advert.currencySymbol + ')' : advert.price_margin }}</span>
                                </p>
                            </div>
                            <div class="sixteen wide right aligned mobile only column geodate-mobile">
                                <p>
                                    <i class="yellow big heart icon" v-if="advert.isUserOwner"></i><span v-if="advert.isUserOwner">{{ advert.bookmarkCount }}</span>
                                    <i class="empty big heart yellow icon" v-on:click="bookmarkMe" :data-id="advert.id" v-if="!advert.isUserOwner && !advert.isUserBookmark"></i>
                                    <i class="big heart yellow icon" v-on:click="unbookmarkMe" :data-id="advert.id" v-if="!advert.isUserOwner && advert.isUserBookmark"></i>
                                    <br /><i class="map signs icon"></i><span class="meta">{{ advert.geoloc }}</span>
                                    <br /><i class="calendar icon"></i><span class="meta">{{ getMoment(advert.online_at) }}</span>
                                    <i class="unhide icon"></i><span class="meta">{{ advert.views }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </template>
        <template  v-if="isPersonnalList && isAdminUser && advert.is_delegation">
            <div>
                <div class="ui grid">
                    <div class="six wide aligned mobile four wide tablet four wide computer column">
                        <div class="ui image">
                            <template v-if="advert.video_id">
                                <div class="ui top aligned medium bordered rounded image">
                                    <div style="width:100%; height:100%; background-color: rgba(0,0,0,0.1); position: absolute; z-index: 2"></div>
                                    <iframe :id="'vimeo-iframe-'+_uid" :src="'https://player.vimeo.com/video/' + advert.video_id" width="100%" height="100%" frameborder="0"></iframe>
                                </div>
                                <div class="ui right blue corner label">
                                    <i class="icon">{{ advert.pictures.length/2 }}</i>
                                </div>
                            </template>
                            <template v-else>
                                <img class="ui top aligned medium bordered rounded image" :src="advert.thumb">
                                <div class="ui right blue corner label">
                                    <i class="icon">{{ advert.pictures.length/2 }}</i>
                                </div>
                            </template>
                            <a :href="advert.url" class="ui primary button">{{ seeAdvertLabel }}</a>
                        </div>
                    </div>
                    <div class="twelve wide tablet only twelve wide computer only column">
                        <div class="ui grid">
                            <div class="ten wide left aligned column">
                                <div class="header"><h4>{{ advert.title }}</h4></div>
                                <p>
                                <span class="ui breadcrumb">
                                    <template v-for="(item,index) in advert.breadCrumb">
                                        <div class="active section"> {{ item.description[actualLocale] }}</div>
                                        <i class="right angle icon divider" v-if="index != advert.breadCrumb.length-1"></i>
                                    </template>
                                </span>
                                </p>
                            </div>
                            <div class="six wide right aligned vertical middle aligned column">
                                <p class="infos">
                                    <span :class="advert.isNegociated ? 'ui big blue left pointing label price negociated' : 'ui big yellow left pointing label price'">{{ advert.isNegociated ? isNegociatedLabel + '(' + advert.currencySymbol + ')' : advert.price }}</span><br/>
                                    <span :title="totalQuantityLabel"><i class="cubes icon"></i>{{ advert.totalQuantity }} </span>
                                    <span :title="lotMiniQuantityLabel"><i class="cube icon"></i>{{ advert.lotMiniQuantity }}</span>
                                    <span v-if="advert.isUrgent" class="ui red horizontal label">{{ urgentLabel }}</span>
                                </p>
                            </div>
                            <div class="sixteen wide column item-description">
                                <div class="ui form">
                                    <div class="two fields">
                                        <margin-input-field
                                                :advert="advert"
                                                :form-advert-price-coefficient-label="formAdvertPriceCoefficientLabel"
                                                :form-advert-price-coefficient-new-price-label="formAdvertPriceCoefficientNewPriceLabel"
                                                :form-advert-price-coefficient-unit-margin-label="formAdvertPriceCoefficientUnitMarginLabel"
                                                :form-advert-price-coefficient-total-margin-label="formAdvertPriceCoefficientTotalMarginLabel"
                                        ></margin-input-field>
                                        <div class="field">
                                            <label style="opacity: 0">1</label>
                                            <div v-on:click="updateCoefficient()" class="ui primary button">
                                                <i class="percent icon"></i>
                                                {{ formAdvertPriceCoefficientUpdateLabel }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ten wide mobile only column">
                    <span class="ui mini breadcrumb">
                        <template v-for="(item,index) in advert.breadCrumb">
                            <div class="active section">{{ item.description[actualLocale] }}</div>
                            <i class="right angle icon divider" v-if="index != advert.breadCrumb.length-1"></i>
                        </template>
                    </span>
                        <div class="header"><h4>{{ advert.title }}</h4></div>
                        <div class="ui grid">
                            <div class="sixteen wide mobile only right aligned column">
                                <p class="infos">
                                    <span class="ui big blue left pointing label">{{ advert.isNegociated ? isNegociatedLabel + '(' + advert.currencySymbol + ')' : advert.price }}</span><br/>
                                    <span><i class="cubes icon" :title="totalQuantityLabel"></i>{{ advert.totalQuantity }} </span>
                                    <span><i class="cube icon" :title="lotMiniQuantityLabel"></i>{{ advert.lotMiniQuantity }}</span>
                                    <span v-if="advert.isUrgent" class="ui red horizontal label">{{ urgentLabel }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="sixteen wide mobile only column">
                        <div class="ui form">
                            <margin-input-field
                                    :advert="advert"
                                    :form-advert-price-coefficient-label="formAdvertPriceCoefficientLabel"
                                    :form-advert-price-coefficient-new-price-label="formAdvertPriceCoefficientNewPriceLabel"
                                    :form-advert-price-coefficient-unit-margin-label="formAdvertPriceCoefficientUnitMarginLabel"
                                    :form-advert-price-coefficient-total-margin-label="formAdvertPriceCoefficientTotalMarginLabel"
                            ></margin-input-field>
                            <div class="field">
                                <div v-on:click="updateCoefficient()" class="ui primary button">
                                    <i class="trash icon"></i>
                                    {{ formAdvertPriceCoefficientUpdateLabel }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template v-if="isPersonnalList && advert.isUserOwner">
            <div>
                <div class="ui grid">
                    <div class="six wide aligned mobile four wide tablet four wide computer column">
                        <div class="ui image">
                            <template v-if="advert.video_id">
                                <div class="ui top aligned medium bordered rounded image">
                                    <div style="width:100%; height:100%; background-color: rgba(0,0,0,0.1); position: absolute; z-index: 2"></div>
                                    <iframe :id="'vimeo-iframe-'+_uid" :src="'https://player.vimeo.com/video/' + advert.video_id" width="100%" height="100%" frameborder="0"></iframe>
                                </div>
                                <div class="ui right blue corner label">
                                    <i class="icon">{{ advert.pictures.length/2 }}</i>
                                </div>
                            </template>
                            <template v-else>
                                <img class="ui top aligned medium bordered rounded image" :src="advert.thumb">
                                <div class="ui right blue corner label">
                                    <i class="icon">{{ advert.pictures.length/2 }}</i>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="twelve wide tablet only twelve wide computer only column">
                        <div class="ui grid">
                            <div class="ten wide left aligned column">
                                <div class="header"><h4>{{ advert.title }}</h4></div>
                                <p>
                                <span class="ui breadcrumb">
                                    <template v-for="(item,index) in advert.breadCrumb">
                                        <div class="active section">{{ item.description[actualLocale] }}</div>
                                        <i class="right angle icon divider" v-if="index != advert.breadCrumb.length-1"></i>
                                    </template>
                                </span>
                                </p>
                            </div>
                            <div class="sixteen wide column item-description">
                                <div class="description" v-if="advert.isValid">
                                    <template v-if="!advert.deleted_at">
                                        <div :id="'manage-btn-1-'+_uid" class="ui labeled icon top right pointing dropdown button">
                                            <i class="wrench icon"></i>
                                            <span class="text">{{ manageAdvertLabel }}</span>
                                            <div class="menu">
                                                <div class="item" v-on:click="seeMe()">{{ seeAdvertLabel }}</div>
                                                <div class="item" v-on:click="destroyMe()">{{ deleteAdvertLabel }}</div>
                                                <div class="item" v-on:click="backToTopMe()">{{ backToTopLabel }}</div>
                                                <div class="item" v-if="advert.isEligibleForHighlight" v-on:click="highlightMe()">{{ highlightLabel }}</div>
                                                <div class="item" v-if="advert.isEligibleForRenew" v-on:click="renewMe()">{{ renewAdvertLabel }}</div>
                                            </div>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <a :href="advert.renewUrl" class="ui primary button">
                                            <i class="power icon"></i>
                                            {{ renewAdvertLabel }}
                                        </a>
                                    </template>
                                    <div class="ui labeled button disabled-bookmark">
                                        <div class="ui yellow button">
                                            <i class="heart icon"></i> {{ bookmarkInfo }}
                                        </div>
                                        <a class="ui basic yellow left left pointing label">
                                            {{ advert.bookmarkCount }}
                                        </a>
                                    </div>
                                    <div class="ui labeled button disabled-bookmark">
                                        <div class="ui olive button">
                                            <i class="unhide icon"></i> {{ viewsInfo }}
                                        </div>
                                        <a class="ui basic olive left left pointing label">
                                            {{ advert.views }}
                                        </a>
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="ui icon message">
                                        <i class="notched circle loading icon"></i>
                                        <div class="content">
                                            <div class="header">
                                                {{ validationOnProgressLabel }}
                                            </div>
                                            <div v-on:click="destroyMe()" class="ui red button">
                                                <i class="trash icon"></i>
                                                {{ deleteAdvertLabel }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="sixteen wide right aligned column geodate-computer">
                                <p v-if="advert.isValid">
                                    <i class="map signs icon"></i><span class="meta">{{ advert.geoloc }}</span>
                                    <i class="calendar icon"></i><span class="meta">{{ getMoment(advert.online_at) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="ten wide mobile only column">
                        <span class="ui mini breadcrumb">
                            <template v-for="(item,index) in advert.breadCrumb">
                                <div class="active section">{{ item.description[actualLocale] }}</div>
                                <i class="right angle icon divider" v-if="index != advert.breadCrumb.length-1"></i>
                            </template>
                        </span>
                        <div class="header"><h4>{{ advert.title }}</h4></div>
                        <div class="sixteen wide centered column" v-if="advert.isValid">
                            <template v-if="!advert.deleted_at">
                                <div :id="'manage-btn-2-'+_uid" class="ui labeled icon top right pointing dropdown button">
                                    <i class="wrench icon"></i>
                                    <span class="text">{{ manageAdvertLabel }}</span>
                                    <div class="menu">
                                        <div class="item" v-on:click="seeMe()">{{ seeAdvertLabel }}</div>
                                        <div class="item" v-on:click="destroyMe()">{{ deleteAdvertLabel }}</div>
                                        <div class="item" v-on:click="backToTopMe()">{{ backToTopLabel }}</div>
                                        <div class="item" v-if="advert.isEligibleForHighlight" v-on:click="highlightMe()">{{ highlightLabel }}</div>
                                        <div class="item" v-if="advert.isEligibleForRenew" v-on:click="renewMe()">{{ renewAdvertLabel }}</div>
                                    </div>
                                </div>
                            </template>
                            <template v-else>
                                <a :href="advert.renewUrl" class="ui primary button">
                                    <i class="power icon"></i>
                                    {{ renewAdvertLabel }}
                                </a>
                            </template>
                        </div>
                    </div>
                    <div class="sixteen wide mobile only column">
                        <div class="description" v-if="advert.isValid">
                            <div class="ui labeled button disabled-bookmark">
                                <div class="ui yellow button">
                                    <i class="heart icon"></i> {{ bookmarkInfo }}
                                </div>
                                <a class="ui basic yellow left left pointing label">
                                    {{ advert.bookmarkCount }}
                                </a>
                            </div>
                            <div class="ui labeled button disabled-bookmark">
                                <div class="ui olive button">
                                    <i class="unhide icon"></i> {{ viewsInfo }}
                                </div>
                                <a class="ui basic olive left left pointing label">
                                    {{ advert.views }}
                                </a>
                            </div>
                        </div>
                        <div v-else>
                            <div class="ui icon message">
                                <i class="notched circle loading icon"></i>
                                <div class="content">
                                    <div class="header">
                                        {{ validationOnProgressLabel }}
                                    </div>
                                    <div v-on:click="destroyMe()" class="ui red button">
                                        <i class="trash icon"></i>
                                        {{ deleteAdvertLabel }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sixteen wide right aligned mobile only column geodate-mobile">
                        <p v-if="advert.isValid">
                            <i class="map signs icon"></i><span class="meta">{{ advert.geoloc }}</span>
                            <i class="calendar icon"></i><span class="meta">{{ getMoment(advert.online_at) }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    export default {
        props: {
            routeUpdatePriceCoefficient: {
                type: String,
                default: '',
                required: false
            },
            routeBookmarkAdd: String,
            routeBookmarkRemove: String,
            advert: Object,
            isAdminUser: {
                type: Boolean,
                default: false,
                required: false
            },
            isPersonnalList: {
                type: Boolean,
                default: false,
                required: false
            },
            actualLocale: String,
            totalQuantityLabel: String,
            lotMiniQuantityLabel: String,
            urgentLabel: String,
            isNegociatedLabel: String,
            priceInfoLabel: String,
            manageAdvertLabel: String,
            renewAdvertLabel: String,
            backToTopLabel: String,
            highlightLabel: String,
            deleteAdvertLabel: String,
            seeAdvertLabel: String,
            validationOnProgressLabel: String,
            bookmarkInfo: String,
            viewsInfo: String,
            //margin input field
            formAdvertPriceCoefficientLabel: String,
            formAdvertPriceCoefficientNewPriceLabel: String,
            formAdvertPriceCoefficientUnitMarginLabel: String,
            formAdvertPriceCoefficientTotalMarginLabel: String,
            formAdvertPriceCoefficientUpdateLabel: String
        },
        data: () => {
            return {

            };
        },
        mounted () {
            $('#manage-btn-1-'+this._uid).dropdown();
            $('#manage-btn-2-'+this._uid).dropdown();
        },
        methods: {
            getMoment: function (dateTime) {
                moment.locale(this.actualLocale);
                return moment(dateTime).fromNow();
            },
            bookmarkMe: function (event) {
                event.preventDefault();
                event.stopPropagation();
                let that = this;
                axios.get(this.routeBookmarkAdd+'/'+this.advert.id)
                    .then(function (response) {
                        that.dataIsUserBookmark = true;
                        that.$parent.$emit('bookmarkSuccess');
                        that.advert.isUserBookmark = true;
                    })
                    .catch(function (error) {
                        if (error.response && error.response.status == 409) {
                            that.$parent.$emit('sendToast', {'message': error.response.data, 'type': 'error'});
                        } else {
                            that.$parent.$emit('loadError')
                        }
                    });
            },
            unbookmarkMe: function (event) {
                event.preventDefault();
                event.stopPropagation();
                let that = this;
                axios.get(this.routeBookmarkRemove+'/'+this.advert.id)
                    .then(function (response) {
                        that.dataIsUserBookmark = false;
                        that.$parent.$emit('unbookmarkSuccess');
                        that.advert.isUserBookmark = false;
                    })
                    .catch(function (error) {
                        if (error.response && error.response.status == 409) {
                            that.$parent.$emit('sendToast', {'message': error.response.data, 'type': 'error'});
                        } else {
                            that.$parent.$emit('loadError')
                        }
                    });
            },
            destroyMe: function () {
                this.$parent.$emit('deleteAdvert', {'url': this.advert.destroyUrl});
            },
            renewMe: function () {
                window.location.assign(this.advert.renewUrl);
            },
            backToTopMe: function () {
                window.location.assign(this.advert.backToTopUrl);
            },
            highlightMe: function () {
                window.location.assign(this.advert.highlightUrl);
            },
            seeMe: function () {
                window.location.assign(this.advert.url);
            },
            updateCoefficient: function () {
                let that = this;
                axios.patch(this.routeUpdatePriceCoefficient + '/' + this.advert.id + '/' + (this.advert.price_coefficient*100).toFixed(0))
                    .then(function (response) {
                        that.$parent.$emit('updateSuccess')
                    })
                    .catch(function (error) {
                        that.$parent.$emit('loadError')
                    });
            }
        }
    }
</script>
