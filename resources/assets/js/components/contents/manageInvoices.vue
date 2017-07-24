<template>
    <div class="ui grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div :id="'modal-'+_uid" class="ui basic modal">
            <i class="close icon"></i>
            <div class="header">
                {{ strings.modalValidHeader }}
            </div>
            <div class="image content">
                <div class="image">
                    <i class="legal icon"></i>
                </div>
                <div class="description">
                    <p>{{ strings.modalValidDescriptionOne+refundAmount+strings.modalValidDescriptionTwo }}</p>
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
        <div class="sixteen wide column">
            <h2 class="ui header">{{ strings.contentHeader }}</h2>
        </div>
        <div class="row">
            <div class="sixteen wide column">
                <div class="row filters">
                    <invoice-filter
                            :update="update"
                            :filter="filter"
                            :route-search="dataRouteGetInvoicesList"
                            :flag-reset-search="dataFlagResetSearch"
                    ></invoice-filter>
                </div>
            </div>
            <div class="sixteen wide column">
                <div class="row">
                    <div class="ui active inverted dimmer" v-if="!isLoaded">
                        <div class="ui large text loader">Loading</div>
                    </div>
                    <invoices-by-list
                            :route-get-invoices-list="dataRouteGetInvoicesList"
                            :flag-force-reload="dataForceReload"
                    ></invoices-by-list>
                </div>
                <div class="ui right aligned grid">
                    <div class="sixteen wide column pagination">
                        <pagination
                                :pages="paginate"
                                :route-get-list="dataRouteGetInvoicesList"
                        ></pagination>
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
            'routeGetInvoicesList',
            //vue vars
            'clearStorage',
        ],
        data: () => {
            return {
                strings: {},
                properties: {},
                sendMessage: false,
                typeMessage: '',
                message: '',
                filter: {},
                paginate: {},
                dataRouteGetInvoicesList: '',
                dataFlagResetSearch: false,
                oldChoice: {},
                update: false,
                refundAmount: 0,
                dataForceReload: false,
                isLoaded: true
            };
        },
        mounted () {
            this.strings = this.$store.state.strings['manage-invoices'];
            this.properties = this.$store.state.properties['global'];
            let that = this;
            if(this.clearStorage){
                sessionStorage.clear();
            }
            //On load Error
            this.$on('loadError', function () {
                this.sendToast(this.strings.loadErrorMessage, 'error');
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
                    that.dataRouteGetInvoicesList = url;
                });
            });
            this.$on('refreshResults', function (query) {
                if(query != undefined && query.length >= this.properties.filterMinLengthSearch){
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
            this.$on('refund', function (refund) {
                this.refund(refund);
            })
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
                let urlBase = init ? this.routeGetInvoicesList : this.dataRouteGetInvoicesList;
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
                this.dataRouteGetInvoicesList = this.urlForFilter(true);
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
            refund(refund) {
                event.preventDefault();
                this.refundAmount = refund.amount;
                let that = this;
                $('#modal-'+this._uid).modal({
                    closable: false,
                    onApprove: function () {
                        that.isLoaded = false;
                        axios.get(refund.refundUrl)
                            .then(function (response) {
                                that.isLoaded = true;
                                that.dataForceReload=!that.dataForceReload;
                                that.sendToast(that.strings.invoiceRefundSuccess, 'success');
                            })
                            .catch(function (error) {
                                if (error.response && error.response.status == 409) {
                                    that.sendToast(error.response.data, 'error');
                                } else {
                                    that.sendToast(that.strings.loadErrorMessage, 'error');
                                }
                                that.isLoaded = true;
                            });
                    }
                }).modal('show');
            }
        }
    }
</script>
