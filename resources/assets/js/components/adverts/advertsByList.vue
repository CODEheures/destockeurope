<template>
    <div>
        <div class="ui celled list">
            <div class="ui active inverted dimmer" v-if="!isLoaded">
                <div class="ui large text loader">Loading</div>
            </div>
            <a :href="routeGetAdvertsList+'/'+advert.id" v-for="advert in advertsList" class="item advert">
                <div class="ui grid">
                    <div class="sixteen wide center aligned mobile four wide tablet four wide computer column">
                        <div class="ui image">
                            <div class="ui right blue corner label"><i class="icon">{{ advert.pictures.length/2 }}</i></div>
                            <img  class="ui top aligned medium bordered rounded image" :src="routeGetThumb+'/'+advert.mainPicture+'/'+advert.id">
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
                                    <span class="ui large blue tag label">{{ advert.price }} {{ advert.currency }}</span><br />
                                    <span><i class="cubes icon" :title="totalQuantityLabel"></i>{{ advert.totalQuantity }} </span>
                                    <span><i class="cube icon" :title="lotMiniQuantityLabel"></i>{{ advert.lotMiniQuantity }}</span>
                                    <span v-if="advert.isUrgent" class="ui red horizontal label">{{ urgentLabel }}</span>
                                </p>
                            </div>
                            <div class="sixteen wide column">
                                <div class="description">
                                    <p>{{ formatDescription(advert.description) }}</p>
                                </div>
                            </div>
                            <div class="sixteen wide right aligned column">
                                <p class="geodate">
                                    <i class="map signs icon"></i><span class="meta">{{ advert.geoloc }}</span>
                                    <i class="calendar icon"></i><span class="meta">{{ getMoment(advert.updated_at) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="sixteen wide mobile only column">
                        <span class="ui breadcrumb">
                                <template v-for="(item,index) in advert.breadCrumb">
                                    <div class="active section">{{ item.description[actualLocale] }}</div>
                                    <i class="right angle icon divider" v-if="index != advert.breadCrumb.length-1"></i>
                                </template>
                            </span>
                        <div class="header"><h3>{{ advert.title }}</h3></div>
                    </div>
                    <div class="sixteen wide mobile only right aligned column">
                        <p class="price">
                            <span class="ui large blue tag label">{{ advert.price }} {{ advert.currency }}</span><br />
                            <span><i class="cubes icon" :title="totalQuantityLabel"></i>{{ advert.totalQuantity }} </span>
                            <span><i class="cube icon" :title="lotMiniQuantityLabel"></i>{{ advert.lotMiniQuantity }}</span>
                            <span v-if="advert.isUrgent" class="ui red horizontal label">{{ urgentLabel }}</span>
                        </p>
                    </div>
                    <div class="sixteen wide mobile only column">
                        <div class="description">
                            <p>{{ formatDescription(advert.description) }}</p>
                        </div>
                    </div>
                    <div class="sixteen wide right aligned mobile only column">
                        <p class="geodate">
                            <i class="map signs icon"></i><span class="meta">{{ advert.geoloc }}</span>
                            <i class="calendar icon"></i><span class="meta">{{ getMoment(advert.updated_at) }}</span>
                        </p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'routeGetAdvertsList',
            'routeGetThumb',
            'actualLocale',
            'totalQuantityLabel',
            'lotMiniQuantityLabel',
            'urgentLabel',
        ],
        data: () => {
            return {
                advertsList: [],
                isLoaded: false
            };
        },
        mounted () {
            this.getAdvertsList();
        },
        methods: {
            getAdvertsList: function (withLoadIndicator) {
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                this.$http.get(this.routeGetAdvertsList)
                        .then(
                                function (response)  {
                                    this.advertsList = response.data;
                                    this.isLoaded = true;
                                },
                                function (response)  {
                                    this.$parent.$emit('loadError')
                                }
                        );
            },
            getMoment: function (dateTime) {
                moment.locale(this.actualLocale);
                return moment(dateTime).fromNow()
            },
            formatDescription (description) {
                let first = description.substr(0,1);
                let resume = description.substr(1,200);
                return first.toUpperCase()+resume+'... ';
            }
        }
    }
</script>
