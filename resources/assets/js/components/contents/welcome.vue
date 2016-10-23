<template>
    <div  class="ui grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div class="mobile only tablet only row">
            <div class="ui one column grid">
                <categories-dropdown-menu
                        :route-meta-category="routeMetaCategory"
                        :first-menu-name="categoriesDropdownMenuFirstMenuName"
                        :actual-locale="actualLocale"
                        :with-all="true">
                </categories-dropdown-menu>
            </div>
        </div>
        <div class="row">
            <div class="computer only four wide column">
                <categories-lateral-menu
                        :route-meta-category="routeMetaCategory"
                        :actual-locale="actualLocale">
                </categories-lateral-menu>
                <div id="welcome-skycrapper" class="ui wide skyscraper test ad" data-text="Wide Skyscraper"></div>
            </div>
            <div class="sixteen wide tablet twelve wide computer column">
                <div class="row filters">
                    <price-advert-filter
                            :filter-ribbon="filterRibbon"
                            :filter-price-title ="filterPriceTitle">
                    </price-advert-filter>
                </div>
                <div class="row">
                    <adverts-by-list
                            :route-get-adverts-list="routeGetAdvertsList"
                            :advert-title-label="advertTitleLabel"
                            :advert-description-label="advertDescriptionLabel"
                            :advert-price-label="advertPriceLabel"
                            :see-advert-link-label="seeAdvertLinkLabel">
                    </adverts-by-list>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'loadErrorMessage',
            'routeMetaCategory',
            'categoriesDropdownMenuFirstMenuName',
            'filterRibbon',
            'filterPriceTitle',
            'routeGetAdvertsList',
            'advertTitleLabel',
            'advertDescriptionLabel',
            'advertPriceLabel',
            'seeAdvertLinkLabel',
            'actualLocale'
        ],
        data: () => {
            return {
                typeMessage : '',
                message : '',
                sendMessage: false
            }
        },
        mounted () {
            this.$on('loadError', function () {
                this.sendToast(this.loadErrorMessage, 'error');
            });
        },
        methods: {
            sendToast: function(message,type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            }
        }
    }
</script>
