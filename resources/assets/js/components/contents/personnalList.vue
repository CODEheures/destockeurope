<template>
    <div  class="ui one column grid">
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
        <div class="column">
            <h2 class="ui header">{{ contentHeader }}</h2>
        </div>
        <div class="row">
            <div v-if="isDelegation" class="sixteen wide column">
                <div class="row filters">
                    <advert-simple-search-filter
                            :route-search="nextUrl"
                    ></advert-simple-search-filter>
                </div>
            </div>
            <div :class="isDelegation ? 'sixteen wide column' : 'sixteen wide tablet twelve wide computer column'">
                <div class="row">
                    <adverts-by-list
                            :route-bookmark-add="routeBookmarkAdd"
                            :route-bookmark-remove="routeBookmarkRemove"
                            :ads-frequency="parseInt(adsFrequency)"
                            :can-get-delegations="canGetDelegations==true"
                            :is-personnal-list="isPersonnalList==true"
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
            <div v-if="!isDelegation" id="welcome-ads" class="computer only four wide column">
                <div>
                    <div class="sixteen right aligned column">
                        <vertical-160x600></vertical-160x600>
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
            //vue vars
            'clearStorage',
            'reloadAdvertOnUnbookmarkSuccess',
            'adsFrequency',
            'canGetDelegations',
            'isPersonnalList',
            'isDelegation',
            //vue strings
            'contentHeader',
        ],
        data: () => {
            return {
                strings: {},
                properties: {},
                typeMessage : '',
                message : '',
                sendMessage: false,
                paginate: {},
                nextUrl: '',
            }
        },
        mounted () {
            this.strings = this.$store.state.strings['personnal-list'];
            this.properties = this.$store.state.properties['global'];
            this.nextUrl = this.getHref();

            let that = this;
            if(this.clearStorage){
                sessionStorage.clear();
            }

            //On load Error or Update success
            this.$on('loadError', function () {
                this.sendToast(this.strings.loadErrorMessage, 'error');
            });
            this.$on('updateSuccess', function () {
                this.sendToast(this.strings.updateSuccessMessage, 'success');
            });


            //pagination
            let paginate = this.$store.state.properties['adverts-by-list-item']['list']['adverts'];
            delete paginate.data;
            this.paginate = paginate;
            this.$on('changePage', function (url) {
                this.nextUrl = url;
                this.gotoNextUrl();
            });



            //When Update Filter
            this.$on('updateFilter', function (result) {
                Object.keys(result).forEach(function (key) {
                    that.nextUrl = that.getNextUrl(key, result[key]);
                });
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

            //Bookmarks
            this.$on('unbookmarkSuccess', function () {
                this.nextUrl = this.getNextUrl('page', null);
                this.gotoNextUrl(true);
            });

            this.$on('sendToast', function (event) {
                this.sendToast(event.message, event.type);
            });
            this.$on('deleteAdvert', function (event) {
                this.destroyMe(event.url);
            })
        },
        methods: {
            sendToast: function(message,type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            },
            destroyMe: function (url) {
                let modalForm = $('#modal2-' + this._uid);
                let that = this;
                modalForm.modal({
                    closable: true,
                    blurring: true,
                    onApprove: function () {
                        Pace.restart();
                        axios.delete(url)
                            .then(function (response) {
                                that.gotoNextUrl(true);
                            })
                            .catch(function (error) {
                                if (error.response && error.response.status == 409) {
                                    that.sendToast(error.response.data, 'error');
                                } else {
                                    that.sendToast(that.strings.loadErrorMessage, 'error');
                                }
                            });
                    }
                }).modal('show');
            },
            getHref: function () {
                return window.location.href;
            },
            getNextUrl(paramName, paramValue) {
                return DestockTools.getNextUrl(this.nextUrl, paramName, paramValue, true)
            },
            gotoNextUrl(forceLoad=false) {
                if(this.nextUrl !== window.location.href || forceLoad===true){
                    DestockTools.goToUrl(this.nextUrl);
                }
            }
        }
    }
</script>
