<template>
    <div class="item advert">
        <template  v-if="!isPersonnalList">
            <a :href="advert.url">
                <div class="ui grid ripple-me">
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
                                            <i class="icon">{{ advert.pictures.length }}</i>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <img class="ui top aligned medium bordered rounded image" :src="getThumbUrl(advert)">
                                        <div class="ui right blue corner label">
                                            <i class="icon">{{ advert.pictures.length }}</i>
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
                                                    <div class="active section">{{ item.description[properties.actualLocale] }}</div>
                                                    <i class="right angle icon divider" v-if="index != advert.breadCrumb.length-1"></i>
                                                </template>
                                            </span>
                                        </p>
                                    </div>
                                    <div class="five wide right aligned vertical top aligned column">
                                        <p class="infos">
                                            <span :title="strings.totalQuantityLabel"><i class="cubes icon"></i>{{ advert.totalQuantity }} </span>
                                            <span :title="strings.lotMiniQuantityLabel"><i class="cube icon"></i>{{ advert.lotMiniQuantity }}</span><br />
                                            <span v-if="advert.isUrgent" class="ui red horizontal label">{{ strings.urgentLabel }}</span>
                                        </p>
                                    </div>
                                    <div class="sixteen wide column item-description">
                                        <div class="description">
                                            <p class="resume">{{ advert.resume }}</p>
                                        </div>
                                    </div>
                                    <div class="four wide column">
                                        <span :class="advert.isNegociated ? 'ui big blue left floated left pointing label price negociated' : 'ui big yellow left floated left pointing label price'">{{ advert.isNegociated ? strings.isNegociatedLabel + '(' + advert.currencySymbol + ')' : advert.price_margin }}</span>
                                    </div>
                                    <div class="twelve wide right aligned column geodate-computer">
                                        <p>
                                            <i class="green big protect icon" :title="strings.trustedProviderLabel" v-if="advert.is_delegation"></i>
                                            <i class="yellow big heart icon" v-if="advert.isUserOwner"></i><span v-if="advert.isUserOwner">{{ advert.bookmarkCount }}</span>
                                            <i class="empty big heart yellow icon" v-on:click.prevent.stop="bookmarkMe" data-stop-ripple :data-id="dataAdvert.id" v-if="!dataAdvert.isUserOwner && !dataAdvert.isUserBookmark"></i>
                                            <i class="big heart yellow icon" v-on:click.prevent.stop="unbookmarkMe" data-stop-ripple :data-id="dataAdvert.id" v-if="!dataAdvert.isUserOwner && dataAdvert.isUserBookmark"></i>
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
                            <div class="six wide mobile only column">
                                <div class="ui image">
                                    <template v-if="advert.video_id">
                                        <div class="ui top aligned small bordered rounded image">
                                            <div style="width:100%; height:100%; background-color: rgba(0,0,0,0.1); position: absolute; z-index: 2"></div>
                                            <iframe :id="'vimeo-iframe-'+_uid" :src="'https://player.vimeo.com/video/' + advert.video_id" width="100%" height="100%" frameborder="0"></iframe>
                                        </div>
                                        <div class="ui mini right blue corner label">
                                            <i class="icon">{{ advert.pictures.length }}</i>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <img class="ui top aligned small bordered rounded image" :src="getThumbUrl(advert)">
                                        <div class="ui mini right blue corner label">
                                            <i class="icon">{{ advert.pictures.length }}</i>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div class="ten wide mobile only column" style="padding-left : 0; display: flex; justify-content: space-between; flex-direction: column;">
                                <div class="sixteen wide column">
                                    <div class="header"><h4>{{ advert.title }}</h4></div>
                                    <p class="infos" style="text-align: right;">
                                        <span v-if="advert.isUrgent" class="ui mini red horizontal label">{{ strings.urgentLabel }}</span>
                                    </p>
                                </div>
                                <div class="sixteen wide bottom aligned column">
                                    <p class="infos" style="text-align: right;">
                                        <span><i class="small cubes icon" :title="strings.totalQuantityLabel"></i>{{ advert.totalQuantity }} </span>
                                        <span><i class="small cube icon" :title="strings.lotMiniQuantityLabel"></i>{{ advert.lotMiniQuantity }}</span>
                                        <span :class="advert.isNegociated ? 'ui tiny blue left pointing label price negociated' : 'ui small yellow left pointing label price'">{{ advert.isNegociated ? strings.isNegociatedLabel + '(' + advert.currencySymbol + ')' : advert.price_margin }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </template>
        <template  v-if="isPersonnalList && canGetDelegations && advert.is_delegation">
            <div class="ui blue padded segment delegation">
                <div class="ui grid">
                    <div class="sixteen wide column refs">
                        <div class="ui segment">
                            <div class="ui grid">
                                <div class="sixteen wide mobile six wide tablet six wide computer column">
                                    <p>{{ strings.refLabel }}: {{ advert.manu_ref }}</p>
                                </div>
                                <div class="sixteen wide mobile ten wide tablet ten wide computer right aligned column">
                                    <p>{{ strings.providerLabel }}: {{ advert.user.compagnyName}} ({{ advert.user.email}} - {{ advert.user.phone}})</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="six wide aligned mobile four wide tablet four wide computer column">
                        <div class="ui image">
                            <template v-if="advert.video_id">
                                <div class="ui top aligned medium bordered rounded image">
                                    <div style="width:100%; height:100%; background-color: rgba(0,0,0,0.1); position: absolute; z-index: 2"></div>
                                    <iframe :id="'vimeo-iframe-'+_uid" :src="'https://player.vimeo.com/video/' + advert.video_id" width="100%" height="100%" frameborder="0"></iframe>
                                </div>
                                <div class="ui right blue corner label">
                                    <i class="icon">{{ advert.pictures.length }}</i>
                                </div>
                            </template>
                            <template v-else>
                                <img class="ui top aligned medium bordered rounded image" :src="getThumbUrl(advert)">
                                <div class="ui right blue corner label">
                                    <i class="icon">{{ advert.pictures.length }}</i>
                                </div>
                            </template>
                            <template v-if="!advert.deleted_at">
                                <advert-manage-button
                                        :advert="advert"
                                        :with-delegation-action="false"
                                        @deleteAdvert="$emit('deleteAdvert', $event)"
                                ></advert-manage-button>
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
                                        <div class="active section"> {{ item.description[properties.actualLocale] }}</div>
                                        <i class="right angle icon divider" v-if="index != advert.breadCrumb.length-1"></i>
                                    </template>
                                </span>
                                </p>
                            </div>
                            <div class="six wide right aligned vertical middle aligned column">
                                <p class="infos">
                                    <span :class="advert.isNegociated ? 'ui big blue left pointing label price negociated' : 'ui big yellow left pointing label price'">{{ advert.isNegociated ? strings.isNegociatedLabel + '(' + advert.currencySymbol + ')' : advert.price_margin }}</span><br/>
                                    <span :title="strings.totalQuantityLabel"><i class="cubes icon"></i>{{ advert.totalQuantity }} </span>
                                    <span :title="strings.lotMiniQuantityLabel"><i class="cube icon"></i>{{ advert.lotMiniQuantity }}</span>
                                    <span v-if="advert.isUrgent" class="ui red horizontal label">{{ strings.urgentLabel }}</span>
                                </p>
                            </div>
                            <div class="sixteen wide column item-description" v-if="!advert.deleted_at">
                                <div class="ui form">
                                    <quantities-input-field
                                            :advert="advert"
                                            @updateSuccess="$emit('updateSuccess')"
                                            @loadError="$emit('loadError')"
                                    ></quantities-input-field>
                                </div>
                            </div>
                            <div class="sixteen wide column item-description">
                                <div class="ui form">
                                    <margin-input-field
                                            :advert="advert"
                                            @updateSuccess="$emit('updateSuccess')"
                                            @loadError="$emit('loadError')"
                                    ></margin-input-field>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ten wide mobile only column">
                    <span class="ui mini breadcrumb">
                        <template v-for="(item,index) in advert.breadCrumb">
                            <div class="active section">{{ item.description[properties.actualLocale] }}</div>
                            <i class="right angle icon divider" v-if="index != advert.breadCrumb.length-1"></i>
                        </template>
                    </span>
                        <div class="header"><h4>{{ advert.title }}</h4></div>
                        <div class="ui grid">
                            <div class="sixteen wide mobile only right aligned column">
                                <p class="infos">
                                    <span class="ui big blue left pointing label">{{ advert.isNegociated ? strings.isNegociatedLabel + '(' + advert.currencySymbol + ')' : advert.price_margin }}</span><br/>
                                    <span><i class="cubes icon" :title="strings.totalQuantityLabel"></i>{{ advert.totalQuantity }} </span>
                                    <span><i class="cube icon" :title="strings.lotMiniQuantityLabel"></i>{{ advert.lotMiniQuantity }}</span>
                                    <span v-if="advert.isUrgent" class="ui red horizontal label">{{ strings.urgentLabel }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="sixteen wide mobile only column" v-if="!advert.deleted_at">
                        <div class="ui form">
                            <quantities-input-field
                                    :advert="advert"
                                    @updateSuccess="$emit('updateSuccess')"
                                    @loadError="$emit('loadError')"
                            ></quantities-input-field>
                        </div>
                    </div>
                    <div class="sixteen wide mobile only column">
                        <div class="ui form">
                            <margin-input-field
                                    :advert="advert"
                                    @updateSuccess="$emit('updateSuccess')"
                                    @loadError="$emit('loadError')"
                            ></margin-input-field>
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
                                    <i class="icon">{{ advert.pictures.length }}</i>
                                </div>
                            </template>
                            <template v-else>
                                <img class="ui top aligned medium bordered rounded image" :src="getThumbUrl(advert)">
                                <div class="ui right blue corner label">
                                    <i class="icon">{{ advert.pictures.length }}</i>
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
                                        <div class="active section">{{ item.description[properties.actualLocale] }}</div>
                                        <i class="right angle icon divider" v-if="index != advert.breadCrumb.length-1"></i>
                                    </template>
                                </span>
                                </p>
                            </div>
                            <div class="sixteen wide column item-description">
                                <div class="description" v-if="advert.isValid">
                                    <template v-if="!advert.deleted_at">
                                        <advert-manage-button
                                            :advert="advert"
                                            @deleteAdvert="$emit('deleteAdvert', $event)"
                                        ></advert-manage-button>
                                    </template>
                                    <template v-else>
                                        <a :href="advert.renewUrl" class="ui primary button">
                                            <i class="power icon"></i>
                                            {{ strings.renewAdvertLabel }}
                                        </a>
                                    </template>
                                    <div class="ui labeled button disabled-bookmark">
                                        <div class="ui yellow button">
                                            <i class="heart icon"></i> {{ strings.bookmarkInfo }}
                                        </div>
                                        <a class="ui basic yellow left left pointing label">
                                            {{ advert.bookmarkCount }}
                                        </a>
                                    </div>
                                    <div class="ui labeled button disabled-bookmark">
                                        <div class="ui olive button">
                                            <i class="unhide icon"></i> {{ strings.viewsInfo }}
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
                                                {{ strings.validationOnProgressLabel }}
                                            </div>
                                            <div v-on:click="destroyMe()" :class="'ui red button destroy-'+_uid">
                                                <i class="trash icon"></i>
                                                {{ strings.deleteAdvertLabel }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="sixteen wide column item-description" v-if="!advert.deleted_at">
                                <div class="ui form">
                                    <quantities-input-field
                                            :advert="advert"
                                            @updateSuccess="$emit('updateSuccess')"
                                            @loadError="$emit('loadError')"
                                    ></quantities-input-field>
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
                                <div class="active section">{{ item.description[properties.actualLocale] }}</div>
                                <i class="right angle icon divider" v-if="index != advert.breadCrumb.length-1"></i>
                            </template>
                        </span>
                        <div class="header"><h4>{{ advert.title }}</h4></div>
                        <div class="sixteen wide centered column" v-if="advert.isValid">
                            <template v-if="!advert.deleted_at">
                                <advert-manage-button
                                        :advert="advert"
                                        @deleteAdvert="$emit('deleteAdvert', $event)"
                                ></advert-manage-button>
                            </template>
                            <template v-else>
                                <a :href="advert.renewUrl" class="ui primary button">
                                    <i class="power icon"></i>
                                    {{ strings.renewAdvertLabel }}
                                </a>
                            </template>
                        </div>
                    </div>
                    <div class="sixteen wide mobile only column">
                        <div class="description" v-if="advert.isValid">
                            <div class="ui labeled button disabled-bookmark">
                                <div class="ui yellow button">
                                    <i class="heart icon"></i> {{ strings.bookmarkInfo }}
                                </div>
                                <a class="ui basic yellow left left pointing label">
                                    {{ advert.bookmarkCount }}
                                </a>
                            </div>
                            <div class="ui labeled button disabled-bookmark">
                                <div class="ui olive button">
                                    <i class="unhide icon"></i> {{ strings.viewsInfo }}
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
                                        {{ strings.validationOnProgressLabel }}
                                    </div>
                                    <div v-on:click="destroyMe()" :class="'ui red button destroy-'+_uid">
                                        <i class="trash icon"></i>
                                        {{ strings.deleteAdvertLabel }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sixteen wide mobile only column" v-if="!advert.deleted_at">
                        <div class="ui form">
                            <quantities-input-field
                                    :advert="advert"
                                    @updateSuccess="$emit('updateSuccess')"
                                    @loadError="$emit('loadError')"
                            ></quantities-input-field>
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
    import _ from 'lodash'

    export default {
        props: {
            routeBookmarkAdd: String,
            routeBookmarkRemove: String,
            advert: Object,
            canGetDelegations: {
                type: Boolean,
                default: false,
                required: false
            },
            isPersonnalList: {
                type: Boolean,
                default: false,
                required: false
            }
        },
        computed: {
            strings () {
                return this.$store.state.strings['adverts-by-list-item']
            },
            properties () {
                return this.$store.state.properties['global']
            }
        },
        watch: {
            advert () {
                this.dataAdvert = _.cloneDeep(this.advert)
            }
        },
        data () {
            return {
                dataAdvert: _.cloneDeep(this.advert)
            }
        },
        methods: {
            getMoment: function (dateTime) {
                moment.locale(this.properties.actualLocale);
                return moment(dateTime).fromNow();
            },
            bookmarkMe: function (event) {
                event.preventDefault();
                event.stopPropagation();
                let that = this;
                axios.get(this.routeBookmarkAdd+'/'+this.advert.id)
                    .then(function (response) {
                        that.dataIsUserBookmark = true;
                        that.$emit('bookmarkSuccess');
                        that.dataAdvert.isUserBookmark = true;
                    })
                    .catch(function (error) {
                        if (error.response && error.response.status == 409) {
                            that.$emit('sendToast', {'message': error.response.data, 'type': 'error'});
                        } else {
                            that.$emit('loadError')
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
                        that.$emit('unbookmarkSuccess');
                        that.dataAdvert.isUserBookmark = false;
                    })
                    .catch(function (error) {
                        if (error.response && error.response.status == 409) {
                            that.$emit('sendToast', {'message': error.response.data, 'type': 'error'});
                        } else {
                            that.$emit('loadError')
                        }
                    });
            },
            destroyMe: function () {
                $(".ui.red.button.destroy-" + this._uid).addClass('loading disabled');
                this.$emit('deleteAdvert', {'url': this.advert.destroyUrl});
            },
            getThumbUrl(advert) {
                let picture = [];
                if('pictures' in advert) {
                    picture = advert.pictures.filter(function (elem) {
                        return elem.hashName === advert.mainPicture;
                    });
                }

                return picture.length >= 1 ? picture[0].thumbUrl : '';
            }
        }
    }
</script>
