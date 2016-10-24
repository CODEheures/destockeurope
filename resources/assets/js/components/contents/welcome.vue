<template>
    <div  class="ui grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div class="mobile only tablet only row">
            <div class="ui one column grid">
                <categories-dropdown-menu
                        :route-meta-category="routeMetaCategory"
                        :first-menu-name="categoriesDropdownMenuFirstMenuName"
                        :all-item="categoriesAllItem"
                        :actual-locale="actualLocale"
                        :with-all="true">
                </categories-dropdown-menu>
            </div>
        </div>
        <div class="row">
            <div class="computer only sixteen wide column">
                <breadcrumb
                    :items="breadcrumbItems">
                </breadcrumb>
            </div>
            <div class="computer only four wide column">
                <categories-lateral-menu
                        :route-meta-category="routeMetaCategory"
                        :all-item="categoriesAllItem"
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
            'routeCategoryInfo',
            'categoriesDropdownMenuFirstMenuName',
            'filterRibbon',
            'filterPriceTitle',
            'routeGetAdvertsList',
            'advertTitleLabel',
            'advertDescriptionLabel',
            'advertPriceLabel',
            'seeAdvertLinkLabel',
            'actualLocale',
            'categoriesAllItem',
            'menuHome'
        ],
        data: () => {
            return {
                typeMessage : '',
                message : '',
                sendMessage: false,
                breadcrumbItems: [],
                filter: {}
            }
        },
        mounted () {
            this.$on('loadError', function () {
                this.sendToast(this.loadErrorMessage, 'error');
            });
            this.$on('categoryChoice', function (event) {
                if(event.id != undefined && event.id > 0) {
                    this.setBreadCrumbItems(event.id, false);
                    this.filter['type'] = 'category';
                    this.filter['id'] = event.id;
                } else {
                    this.breadcrumbItems= [];
                    this.filter={};
                }
            });
            this.$on('metaCategoryChoice', function (event) {
                if(event.id != undefined && event.id > 0) {
                    this.setBreadCrumbItems(event.id, true);
                    this.filter['type']='metaCategory';
                    this.filter['id']=event.id;
                } else {
                    this.breadcrumbItems= [];
                    this.filter={};
                }
            });
        },
        methods: {
            sendToast: function(message,type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            },
            setBreadCrumbItems: function (categoryId, isMetaCategorie) {
                if(isMetaCategorie){
                    this.$http.get(this.routeMetaCategory +'/'+ categoryId)
                            .then(
                                    function (response) {
                                        var metaCategory = response.data;
                                        this.breadcrumbItems = [];
                                        this.breadcrumbItems.push({
                                            name: metaCategory['description'][this.actualLocale],
                                            value: metaCategory.id
                                        });
                                    },
                                    function (response) {
                                        this.breadcrumbItems = [];
                                        this.breadcrumbItems.push({
                                            name: this.loadErrorMessage,
                                            value:''
                                        });
                                        //this.$parent.$emit('loadError');
                                    }
                            );
                } else {
                    this.$http.get(this.routeCategoryInfo+'/'+categoryId)
                            .then(
                                    function (response) {
                                        var chainedCategories = response.data;
                                        this.breadcrumbItems = [];
                                        for(var index in chainedCategories){
                                            this.breadcrumbItems.push({
                                                name: chainedCategories[index]['description'][this.actualLocale],
                                                value: chainedCategories[index].id
                                            });
                                        }
                                    },
                                    function (response) {
                                        this.breadcrumbItems = [];
                                        this.breadcrumbItems.push({
                                            name: this.loadErrorMessage,
                                            value:''
                                        });
                                        //this.$parent.$emit('loadError');
                                    }
                            );
                }

            }
        }
    }
</script>
