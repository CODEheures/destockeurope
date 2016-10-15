<template>
    <div  class="ui grid">
        <div class="mobile only tablet only row">
            <div class="ui one column grid">
                <categories-dropdown-menu
                        :load-error-message="loadErrorMessage"
                        :route-meta-category="routeMetaCategory"
                        :first-menu-name="categoriesDropdownMenuFirstMenuName">
                </categories-dropdown-menu>
            </div>
        </div>
        <div class="row">
            <div class="computer only four wide column">
                <categories-vertical-menu
                        :load-error-message="loadErrorMessage"
                        :route-meta-category="routeMetaCategory">
                </categories-vertical-menu>
            </div>
            <div class="sixteen wide tablet twelve wide computer column">
                <div class="row filters">
                    <advert-filter
                            :filter-ribbon="filterRibbon"
                            :filter-price-title ="filterPriceTitle">
                    </advert-filter>
                </div>
                <div class="row">
                    <adverts-by
                            :route-get-adverts-list="routeGetAdvertsList"
                            :loadErrorMessage="loadErrorMessage"
                            :advert-title-label="advertTitleLabel"
                            :advert-description-label="advertDescriptionLabel"
                            :advert-price-label="advertPriceLabel"
                            :see-advert-link-label="seeAdvertLinkLabel">
                    </adverts-by>
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
            'seeAdvertLinkLabel'
        ],
        data: () => {
            return {

            }
        },
        mounted () {

        },
        methods: {

        }
    }
</script>
