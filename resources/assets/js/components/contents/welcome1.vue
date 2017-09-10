<template>
    <div  class="ui grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
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
        <div class="tablet only computer only sixteen wide column">
            <div class="row">
                <categories-horizontal-menu></categories-horizontal-menu>
            </div>
        </div>
        <div class="sixteen wide column" v-if="masteradsIsActive=='1'">
            <masterads
                    :route-image-server = "masteradsRouteImageServer"
                    :is-active="masteradsIsActive"
                    :url-img="masteradsUrlImg"
                    :url-redirect="masteradsUrlRedirect"
                    :offset-y-main-container="masteradsOffsetYMainContainer"
                    :selector-main-container="'#ads-offset-y-'+_uid"
                    :width="masteradsWidth"
                    :ads-offset-y="-10"
            ></masterads>
        </div>
        <div class="row" :id="'ads-offset-y-'+_uid">
            <div class="sixteen wide column">
                <div class="row filters">
                    <advert-filter
                            :route-notifications-exist-in="routeNotificationsExistIn"
                            :route-notifications-add="routeNotificationsAdd"
                            :route-notifications-remove="routeNotificationsRemove"
                            :route-search="nextUrl"
                            :location-accurate-list="dataLocationAccurateList"
                    ></advert-filter>
                </div>
            </div>
            <div class="sixteen wide column">
                <h1 class="ui tiny blue header">{{ dataHeader }}</h1>
            </div>
            <div class="sixteen wide tablet thirteen wide computer column">
                <div class="row">
                    <adverts-by-list
                            :route-bookmark-add="routeBookmarkAdd"
                            :route-bookmark-remove="routeBookmarkRemove"
                            :ads-frequency="parseInt(adsFrenquency)"
                    ></adverts-by-list>
                </div>
                <div class="ui right aligned grid">
                    <div class="sixteen wide column pagination">
                        <pagination
                            :pages="paginate"
                            :route-get-list="''"
                            :fake-page-route="nextUrl"
                        ></pagination>
                    </div>
                </div>
            </div>
            <div class="computer only three wide column">
                <div class="ui centered grid">
                    <template v-if="dataHighlightAdverts.length > 1">
                        <div class="sixteen wide column" v-for="highLightAdvert in dataHighlightAdverts">
                            <advert-highlight
                                    :advert="highLightAdvert"
                            ></advert-highlight>
                        </div>
                    </template>

                    <template v-else>
                        <div class="sixteen wide column">
                            <advert-highlight v-if="dataHighlightAdverts.length == 1"
                                    :advert="dataHighlightAdverts[0]"
                            ></advert-highlight>
                            <advert-highlight
                                    :advert="dataFakeHighlightAdvert"
                            ></advert-highlight>
                        </div>
                    </template>
                    <div class="sixteen wide column">
                        <vertical-160x600
                            :centered="true"
                        ></vertical-160x600>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            //vue routes
            'routeBookmarkAdd',
            'routeBookmarkRemove',
            'routeNotificationsExistIn',
            'routeNotificationsAdd',
            'routeNotificationsRemove',
            'routeGetHighlight',
            //vue vars
            'masteradsRouteImageServer',
            'masteradsIsActive',
            'masteradsUrlImg',
            'masteradsUrlRedirect',
            'masteradsOffsetYMainContainer',
            'masteradsWidth',
            'filterLocationAccurateList',
            'adsFrenquency',
            'fakeHighlightAdvert',
        ],
        data: () => {
            return {
                strings: {},
                properties: {},
                typeMessage : '',
                message : '',
                sendMessage: false,
                isLoaded: true,
                breadcrumbItems: [],
                dataLocationAccurateList: [],
                dataFakeHighlightAdvert: {},
                dataHighlightAdverts: [],
                paginate: {},
                dataHeader: '',
                nextUrl: '',
            }
        },
        mounted () {
            this.strings = this.$store.state.strings['welcome1'];
            this.properties = this.$store.state.properties['global'];
            this.nextUrl = this.getHref();
            this.dataLocationAccurateList = JSON.parse(this.filterLocationAccurateList);
            this.dataFakeHighlightAdvert = JSON.parse(this.fakeHighlightAdvert);
            this.getHighLightAdvert();
            this.setHeader();

            let that = this;

            //pagination
            let paginate = this.$store.state.properties['adverts-by-list-item']['list']['adverts'];
            delete paginate.data;
            this.paginate = paginate;
            this.$on('changePage', function (url) {
                this.nextUrl = url;
                this.gotoNextUrl();
            });


            //On load Error
            this.$on('loadError', function () {
                this.sendToast(this.strings.loadErrorMessage, 'error');
            });


            //When choice a category
            this.$on('categoryChoice', function (event) {
                if(event.id != undefined && event.id >= 0) {
                    this.nextUrl = this.getNextUrl('minPrice', null);
                    this.nextUrl = this.getNextUrl('maxPrice', null);
                    this.nextUrl = this.getNextUrl('minQuantity', null);
                    this.nextUrl = this.getNextUrl('maxQuantity', null);
                    this.nextUrl = this.getNextUrl('categoryId', event.id);
                    this.gotoNextUrl();
                }
            });


            //When Update Filter
            this.$on('updateFilter', function (result) {
                Object.keys(result).forEach(function (key) {
                    console.log('key', key);
                    console.log('value', result[key]);
                    that.nextUrl = that.getNextUrl(key, result[key]);
                });
                this.gotoNextUrl();
            });


            //When clear Location
            this.$on('clearLocationResults', function () {
                this.dataLocationAccurateList.forEach(function(key){
                    that.nextUrl = that.getNextUrl(key, null);
                });
                this.nextUrl = this.getNextUrl('forLocation', null);
                this.gotoNextUrl();
            });


            //When search query results valid
            this.$on('refreshResults', function (query) {
                if(query != undefined && query.length >= this.properties.filterMinLengthSearch){
                    that.nextUrl = that.getNextUrl('resultsFor', query);
                    that.gotoNextUrl();
                }
            });
            this.$on('clearSearchResults', function () {
                this.nextUrl = this.getNextUrl('resultsFor', null);
                this.gotoNextUrl();
            });



            this.$on('sendToast', function (event) {
                this.sendToast(event.message, event.type);
            });
            this.$on('bookmarkSuccess', function () {
                this.sendToast(this.strings.bookmarkSuccess, 'success');
            });
            this.$on('unbookmarkSuccess', function () {
                this.sendToast(this.strings.unbookmarkSuccess, 'success');
            });
        },
        methods: {
            sendToast: function(message,type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            },
            getHighLightAdvert: function () {
                let that = this;
                axios.get(this.routeGetHighlight)
                    .then(function (response) {
                        that.dataHighlightAdverts = (response.data).adverts;
                    })
                    .catch(function (error) {
                        //that.sendToast(that.strings.loadErrorMessage, 'error');
                    });
            },
            setHeader: function () {
                this.dataHeader = this.strings.header;
                if(this.breadcrumbItems.length > 0){
                    this.dataHeader = this.dataHeader + ' ' + this.breadcrumbItems[this.breadcrumbItems.length -1].name;
                }
                if(DestockTools.findInUrl('forLocation')){
                    this.dataHeader = this.dataHeader + ' - ' + DestockTools.findInUrl('forLocation');
                }
            },
            getHref: function () {
                return window.location.href;
            },
            getNextUrl(paramName, paramValue) {
                let urlBase = this.nextUrl;
                let parsed = Parser.parse(urlBase, true);
                parsed.search=undefined;

                if(paramValue != null){
                    parsed.query[paramName] = paramValue.toString();
                } else if (paramName in parsed.query){
                    delete parsed.query[paramName]
                }

                'page' in parsed.query ? delete parsed.query['page'] : null;
                return Parser.format(parsed);
            },
            gotoNextUrl(forceLoad=false) {
                if(this.nextUrl !== window.location.href || forceLoad===true){
                    Pace.restart();
                    window.location.href = this.nextUrl;
                }
            }
        }
    }
</script>
