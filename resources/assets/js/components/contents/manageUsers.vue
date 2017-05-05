<template>
    <div class="ui grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div class="sixteen wide column">
            <h2 class="ui header">{{ contentHeader }}</h2>
        </div>
        <div class="row">
            <div class="sixteen wide column">
                <div class="row filters">
                    <user-filter
                            :filter-ribbon-open="filterRibbonOpen"
                            :filter-ribbon-close="filterRibbonClose"
                            :update="update"
                            :filter="filter"
                            :route-search="dataRouteGetUsersList"
                            :min-length-search="parseInt(filterMinLengthSearch)"
                            :flag-reset-search="dataFlagResetSearch"
                            :search-place-holder="filterSearchPlaceHolder"
                    ></user-filter>
                </div>
            </div>
            <div class="sixteen wide column">
                <div class="row">
                    <users-by-list
                            :route-get-users-list="dataRouteGetUsersList"
                            :no-result-found-header="noResultFoundHeader"
                            :no-result-found-message="noResultFoundMessage"
                            :actual-locale="actualLocale"
                            :role-user-label="roleUserLabel"
                            :list-header-usermail="listHeaderUsermail"
                            :list-header-name="listHeaderName"
                            :list-header-compagny="listHeaderCompagny"
                            :list-header-register-date="listHeaderRegisterDate"
                            :list-header-vat-number="listHeaderVatNumber"
                            :list-vat-verification-number-label="listVatVerificationNumberLabel"
                            :list-header-address="listHeaderAddress"
                    ></users-by-list>
                </div>
                <div class="ui right aligned grid">
                    <div class="sixteen wide column pagination">
                        <pagination
                                :pages="paginate"
                                :route-get-list="dataRouteGetUsersList"
                                :page-label="pageLabel"
                                :page-previous-label="pagePreviousLabel"
                                :page-next-label="pageNextLabel">
                        </pagination>
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
            'clearStorage',
            //vue strings
            'contentHeader',
            'loadErrorMessage',
            //filter invoice component
            'filterMinLengthSearch',
            'filterRibbonOpen',
            'filterRibbonClose',
            'filterSearchPlaceHolder',
            //invoiceByList component
            'routeGetUsersList',
            'noResultFoundHeader',
            'noResultFoundMessage',
            'actualLocale',
            'roleUserLabel',
            'listHeaderUsermail',
            'listHeaderName',
            'listHeaderCompagny',
            'listHeaderRegisterDate',
            'listHeaderVatNumber',
            'listVatVerificationNumberLabel',
            'listHeaderAddress',
            //paginate component
            'pageLabel',
            'pagePreviousLabel',
            'pageNextLabel'
        ],
        data: () => {
            return {
                sendMessage: false,
                typeMessage: '',
                message: '',
                filter: {},
                paginate: {},
                dataRouteGetUsersList: '',
                dataFlagResetSearch: false,
                oldChoice: {},
                update: false
            };
        },
        mounted () {
            let that = this;
            if(this.clearStorage){
                sessionStorage.clear();
            }
            //On load Error
            this.$on('loadError', function () {
                this.sendToast(this.loadErrorMessage, 'error');
            });
            this.$on('sendToast', function (event) {
                this.sendToast(event.message, event.type);
            });

            //on reconstruit le filtre
            this.initFilterBySessionStorage();
            this.updateResults();

            this.$on('paginate', function (result) {
                this.paginate=result;
            });
            this.$on('updateFilter', function (result) {
                this.updateFilter(result);
            });
            this.$on('changePage', function (url) {
                $('html, body').animate({
                    scrollTop: 0
                }, 600, function () {
                    that.dataRouteGetUsersList = url;
                });
            });
            this.$on('refreshResults', function (query) {
                if(query != undefined && query.length >= this.filterMinLengthSearch){
                    this.filter.resultsFor = query;
                    this.updateResults(true);
                }
            });
            this.$on('clearSearchResults', function () {
                let haveClearAction = this.clearInputSearch();
                if(haveClearAction){
                    this.updateResults(true);
                }
            });
        },
        updated () {

        },
        methods: {
            sendToast: function (message, type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            },
            urlForFilter(init=false) {
                let urlBase = init ? this.routeGetUsersList : this.dataRouteGetUsersList;
                let parsed = Parser.parse(urlBase, true);
                parsed.search=undefined;
                parsed.query={};

                //reset sessionStorageFilter
                sessionStorage.removeItem('filter');
                sessionStorage.setItem('filter', JSON.stringify(this.filter));

                for(let elem in this.filter){
                    if(this.filter[elem] != null){
                        parsed.query[elem]=(this.filter[elem]).toString();
                    }
                }
                return Parser.format(parsed);
            },
            clearInputSearch() {
                if('resultsFor' in this.filter) {
                    delete this.filter.resultsFor;
                    this.dataFlagResetSearch = !this.dataFlagResetSearch;
                    return true;
                } else {
                    return false;
                }
            },
            updateResults(){
                this.update = !this.update;
                this.dataRouteGetUsersList = this.urlForFilter(true);
            },
            updateFilter(result){
                let oldFilter= _.cloneDeep(this.filter);
                for(let elem in result){
                    if(result[elem] == null){
                        if(elem in this.filter){
                            delete this.filter[elem];
                        }
                    } else {
                        this.filter[elem] = result[elem];
                    }
                }
                if(!_.isEqual(oldFilter, this.filter)){
                    this.updateResults();
                }
            },
            initFilterBySessionStorage: function () {
                if(sessionStorage.getItem('filter') != null){
                    this.filter = JSON.parse(sessionStorage.getItem('filter'));
                }
            },
        }
    }
</script>
