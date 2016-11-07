<template>
    <div>
        <div class="ui celled list">
            <div class="ui active inverted dimmer" v-if="!isLoaded">
                <div class="ui large text loader">Loading</div>
            </div>
            <a :href="routeGetAdvertsList+'/'+advert.id" v-for="advert in advertsList" class="item advert">
                <p class="date">
                    <i class="calendar icon"></i><span class="meta">{{ getMoment(advert.updated_at) }}</span>
                </p>
                <div class="ui image">
                    <div class="ui right blue corner label"><i class="icon">{{ advert.pictures.length/2 }}</i></div>
                    <img  class="ui top aligned small bordered rounded image" :src="routeGetThumb+'/'+advert.mainPicture+'/'+advert.id">
                </div>
                <div class="content">
                    <div class="header"><h3>{{ advert.title }}</h3></div>
                    <div class="description">
                        <p>
                            <span class="ui breadcrumb">
                                <template v-for="(item,index) in advert.breadCrumb">
                                    <div class="active section" >{{ item.description[actualLocale] }}</div>
                                    <i class="right angle icon divider" v-if="index != advert.breadCrumb.length-1"></i>
                                </template>
                            </span>
                        </p>
                        <p>
                            <i class="map signs icon"></i><span class="meta">{{ advert.geoloc }}</span>
                        </p>
                        <p>
                            <span class="ui large teal tag label">{{ advert.price }} {{ advert.currency }}</span>
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
            'advertTitleLabel',
            'advertDescriptionLabel',
            'advertPriceLabel',
            'seeAdvertLinkLabel',
            'actualLocale'
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
            }
        }
    }
</script>
