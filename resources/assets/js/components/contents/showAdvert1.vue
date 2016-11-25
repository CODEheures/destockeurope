<template>
    <div  class="ui grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div class="sixteen wide column">
            <div class="row">
                <breadcrumb
                        :items="breadcrumbItems"
                        :withAction="true">
                </breadcrumb>
            </div>
        </div>
        <div class="row">
            <div class="sixteen wide tablet ten wide computer column">
                <div class="row">
                    <advert-by-id
                        :route-get-advert="routeGetAdvert"
                        :actual-locale="actualLocale"
                        :total-quantity-label="totalQuantityLabel"
                        :lot-mini-quantity-label="lotMiniQuantityLabel"
                        :urgent-label="urgentLabel"
                        :price-info-label="priceInfoLabel"
                        :price-label="priceLabel"
                    ></advert-by-id>
                </div>
            </div>
            <div id="welcome-ads" class="computer only six wide column">
                <div>
                    <div class="sixteen right aligned column">
                        <div class="ui small rectangle centered test ad" data-text="Small Rectangle"></div>
                        <div class="ui small rectangle centered test ad" data-text="Small Rectangle"></div>
                        <!--<div class="ui wide skyscraper test ad welcome-ads" data-text="Wide Skyscraper"></div>-->
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
            //vue vars
            //vue strings
            'loadErrorMessage',
            //advertById component
            'routeGetAdvert',
            'routeHome',
            'actualLocale',
            'totalQuantityLabel',
            'lotMiniQuantityLabel',
            'urgentLabel',
            'priceInfoLabel',
            'priceLabel'
        ],
        data: () => {
            return {
                typeMessage : '',
                message : '',
                sendMessage: false,
                breadcrumbItems: [],
            }
        },
        mounted () {
            //Visibility for ADS
            var $elem = $('#welcome-ads').children('div');
            $elem.visibility({
                type   : 'fixed',
                offset : 112
            });

            //On load Error
            this.$on('loadError', function () {
                this.sendToast(this.loadErrorMessage, 'error');
            });

            this.$on('setBreadCrumb', function (chainedCategories) {
                this.setBreadCrumbItems(chainedCategories)
            });
            this.$on('categoryChoice', function (category) {
                sessionStorage.setItem('goToCategory', category.id);
                window.location.href = this.routeHome;
            })
        },
        methods: {
            sendToast: function(message,type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            },
            setBreadCrumbItems: function (chainedCategories) {
                this.breadcrumbItems = [];
                for(var index in chainedCategories){
                    this.breadcrumbItems.push({
                        name: chainedCategories[index]['description'][this.actualLocale],
                        value: chainedCategories[index].id
                    });
                }
            },
        }
    }
</script>
