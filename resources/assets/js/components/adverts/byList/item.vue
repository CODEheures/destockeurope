<template>
    <div class="item advert">
        <template  v-if="!advert.isUserOwner">
            <a :href="advert.url">
                <div class="ui grid">
                    <div class="six wide aligned mobile four wide tablet four wide computer column">
                        <div class="ui image">
                            <img class="ui top aligned medium bordered rounded image" :src="advert.thumb">
                            <div class="ui right blue corner label">
                                <i class="icon">{{ advert.pictures.length/2 }}</i>
                            </div>
                        </div>
                    </div>
                    <div class="twelve wide tablet only twelve wide computer only column">
                        <div class="ui grid">
                            <div class="ten wide left aligned column">
                                <div class="header"><h3>{{ advert.title }}</h3></div>
                                <p>
                                <span class="ui breadcrumb">
                                    <template v-for="(item,index) in advert.breadCrumb">
                                        <div class="active section">{{ item.description[actualLocale] }}</div>
                                        <i class="right angle icon divider" v-if="index != advert.breadCrumb.length-1"></i>
                                    </template>
                                </span>
                                </p>
                            </div>
                            <div class="six wide right aligned vertical middle aligned column">
                                <p class="price">
                                    <span class="ui small blue tag label">{{ advert.price }}</span><br/>
                                    <span :title="totalQuantityLabel"><i class="cubes icon"></i>{{ advert.totalQuantity }} </span>
                                    <span :title="lotMiniQuantityLabel"><i class="cube icon"></i>{{ advert.lotMiniQuantity }}</span>
                                    <span v-if="advert.isUrgent" class="ui red horizontal label">{{ urgentLabel }}</span>
                                </p>
                            </div>
                            <div class="sixteen wide column item-description">
                                <div class="description">
                                    <p>{{ advert.resume }}</p>
                                </div>
                            </div>
                            <div class="sixteen wide right aligned column geodate-computer">
                                <p>
                                    <i class="map signs icon"></i><span class="meta">{{ advert.geoloc }}</span>
                                    <i class="calendar icon"></i><span class="meta">{{ getMoment(advert.updated_at) }}</span>
                                    <i class="unhide icon"></i><span class="meta">{{ advert.views }}</span>
                                    <i class="yellow large heart icon" v-if="advert.isUserOwner"></i><span v-if="advert.isUserOwner">{{ advert.bookmarkCount }}</span>
                                    <i class="empty large heart yellow icon" v-on:click="bookmarkMe" :data-id="advert.id" v-if="!advert.isUserOwner && !advert.isUserBookmark"></i>
                                    <i class="large heart yellow icon" v-on:click="unbookmarkMe" :data-id="advert.id" v-if="!advert.isUserOwner && advert.isUserBookmark"></i>
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
                        <div class="header"><h3>{{ advert.title }}</h3></div>
                        <div class="ui grid">
                            <div class="sixteen wide mobile only right aligned column">
                                <p class="price">
                                    <span class="ui small blue tag label">{{ advert.price }}</span><br/>
                                    <span><i class="cubes icon" :title="totalQuantityLabel"></i>{{ advert.totalQuantity }} </span>
                                    <span><i class="cube icon" :title="lotMiniQuantityLabel"></i>{{ advert.lotMiniQuantity }}</span>
                                    <span v-if="advert.isUrgent" class="ui red horizontal label">{{ urgentLabel }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="sixteen wide mobile only column">
                        <div class="description">
                            <p>{{ advert.resume }}</p>
                        </div>
                    </div>
                    <div class="sixteen wide right aligned mobile only column geodate-mobile">
                        <p>
                            <i class="map signs icon"></i><span class="meta">{{ advert.geoloc }}</span>
                            <i class="calendar icon"></i><span class="meta">{{ getMoment(advert.updated_at) }}</span>
                            <i class="unhide icon"></i><span class="meta">{{ advert.views }}</span>
                            <i class="yellow large heart icon" v-if="advert.isUserOwner"></i><span v-if="advert.isUserOwner">{{ advert.bookmarkCount }}</span>
                            <i class="empty yellow large heart icon" v-on:click="bookmarkMe" :data-id="advert.id" v-if="!advert.isUserOwner && !advert.isUserBookmark"></i>
                            <i class="large heart yellow icon" v-on:click="unbookmarkMe" :data-id="advert.id" v-if="!advert.isUserOwner && advert.isUserBookmark"></i>
                        </p>
                    </div>
                </div>
            </a>
        </template>
        <template v-else>
            <div>
                <div class="ui grid">
                    <div class="six wide aligned mobile four wide tablet four wide computer column">
                        <div class="ui image">
                            <img class="ui top aligned medium bordered rounded image" :src="advert.thumb">
                            <div class="ui right blue corner label">
                                <template v-if="!advert.deleted_at">
                                    <i class="icon">{{ advert.pictures.length/2 }}</i>
                                </template>
                                <template v-else>
                                    <i class="icon">{{ advert.picturesWithTrashedCount/2 }}</i>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="twelve wide tablet only twelve wide computer only column">
                        <div class="ui grid">
                            <div class="ten wide left aligned column">
                                <div class="header"><h3>{{ advert.title }}</h3></div>
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
                                <div class="description">
                                    <template v-if="!advert.deleted_at">
                                        <a :href="advert.url" class="ui basic primary button">
                                            <i class="setting icon"></i>
                                            {{ manageAdvertLabel }}
                                        </a>
                                    </template>
                                    <template v-else>
                                        <a :href="advert.url" class="ui disabled button">
                                            <i class="power icon"></i>
                                            {{ renewAdvertLabel }}
                                        </a>
                                    </template>
                                    <div class="ui labeled button disabled-bookmark">
                                        <div class="ui yellow button">
                                            <i class="heart icon"></i> {{ bookmarkInfo }}
                                        </div>
                                        <a class="ui basic yellow left pointing label">
                                            {{ advert.bookmarkCount }}
                                        </a>
                                    </div>
                                    <div class="ui labeled button disabled-bookmark">
                                        <div class="ui olive button">
                                            <i class="unhide icon"></i> {{ viewsInfo }}
                                        </div>
                                        <a class="ui basic olive left pointing label">
                                            {{ advert.views }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="sixteen wide right aligned column geodate-computer">
                                <p>
                                    <i class="map signs icon"></i><span class="meta">{{ advert.geoloc }}</span>
                                    <i class="calendar icon"></i><span class="meta">{{ getMoment(advert.updated_at) }}</span>
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
                        <div class="header"><h3>{{ advert.title }}</h3></div>
                        <div class="sixteen wide centered column">
                            <template v-if="!advert.deleted_at">
                                <a :href="advert.url" class="ui basic primary button">
                                    <i class="setting icon"></i>
                                    {{ manageAdvertLabel }}
                                </a>
                            </template>
                            <template v-else>
                                <a :href="advert.url" class="ui disabled button">
                                    <i class="power icon"></i>
                                    {{ renewAdvertLabel }}
                                </a>
                            </template>
                        </div>
                    </div>
                    <div class="sixteen wide mobile only column">
                        <div class="description">
                            <div class="ui labeled button disabled-bookmark">
                                <div class="ui yellow button">
                                    <i class="heart icon"></i> {{ bookmarkInfo }}
                                </div>
                                <a class="ui basic yellow left pointing label">
                                    {{ advert.bookmarkCount }}
                                </a>
                            </div>
                            <div class="ui labeled button disabled-bookmark">
                                <div class="ui olive button">
                                    <i class="unhide icon"></i> {{ viewsInfo }}
                                </div>
                                <a class="ui basic olive left pointing label">
                                    {{ advert.views }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="sixteen wide right aligned mobile only column geodate-mobile">
                        <p>
                            <i class="map signs icon"></i><span class="meta">{{ advert.geoloc }}</span>
                            <i class="calendar icon"></i><span class="meta">{{ getMoment(advert.updated_at) }}</span>
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
            routeBookmarkAdd: String,
            routeBookmarkRemove: String,
            advert: Object,
            actualLocale: String,
            totalQuantityLabel: String,
            lotMiniQuantityLabel: String,
            urgentLabel: String,
            priceInfoLabel: String,
            manageAdvertLabel: String,
            renewAdvertLabel: String,
            bookmarkInfo: String,
            viewsInfo: String,
            noResultFoundHeader: String,
            noResultFoundMessage: String
        },
        data: () => {
            return {

            };
        },
        mounted () {

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
                this.$http.get(this.routeBookmarkAdd+'/'+this.advert.id)
                    .then(
                        function (response) {
                            that.dataIsUserBookmark = true;
                            that.$parent.$emit('bookmarkSuccess');
                            that.advert.isUserBookmark = true;
                        },
                        function (response) {
                            if (response.status == 409) {
                                that.$parent.$emit('sendToast', {'message': response.body, 'type': 'error'});
                            } else {
                                that.$parent.$emit('loadError')
                            }
                        }
                    );
            },
            unbookmarkMe: function (event) {
                event.preventDefault();
                event.stopPropagation();
                let that = this;
                this.$http.get(this.routeBookmarkRemove+'/'+this.advert.id)
                    .then(
                        function (response) {
                            that.dataIsUserBookmark = false;
                            that.$parent.$emit('unbookmarkSuccess');
                            that.advert.isUserBookmark = false;
                        },
                        function (response) {
                            if (response.status == 409) {
                                that.$parent.$emit('sendToast', {'message': response.body, 'type': 'error'});
                            } else {
                                that.$parent.$emit('loadError')
                            }
                        }
                    )
                ;
            }
        }
    }
</script>
