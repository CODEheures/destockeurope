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
        <div class="sixteen wide column">
            <h2 class="ui header">{{ strings.contentHeader }}</h2>
        </div>
        <div class="row">
            <div class="sixteen wide column">
                <div class="row filters">
                    <user-filter
                            :update="update"
                            :filter="filter"
                            :route-search="dataRouteGetUsersList"
                            :flag-reset-search="dataFlagResetSearch"
                            @clearSearchResults="clearSearchResults"
                            @refreshResults="refreshResults"
                    ></user-filter>
                </div>
            </div>
            <div class="sixteen wide column">
                <div class="row">
                    <users-by-list
                            :route-get-users-list="dataRouteGetUsersList"
                            :flag-force-reload="dataForceReload"
                            @deleteUser="deleteUser($event)"
                            @paginate="paginate=$event"
                            @loadError="sendToast(strings.loadErrorMessage, 'error')"
                            @sendToast="sendToast(event.message, event.type)"
                    ></users-by-list>
                </div>
                <div class="ui right aligned grid">
                    <div class="sixteen wide column pagination">
                        <pagination
                                :pages="paginate"
                                :route-get-list="dataRouteGetUsersList"
                                @changePage="changePage"
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
            'routeGetUsersList',
        ],
        data () {
            return {
                sendMessage: false,
                typeMessage: '',
                message: '',
                filter: {},
                paginate: {},
                dataRouteGetUsersList: '',
                dataFlagResetSearch: false,
                oldChoice: {},
                update: false,
                dataForceReload: false
            };
        },
        computed: {
            strings () {
                return this.$store.state.strings['manage-users']
            },
            properties () {
                return this.$store.state.properties['global']
            }
        },
        mounted () {
            sessionStorage.clear();
            this.updateResults();
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
            deleteUser(url) {
                let that = this;
                $('#modal-'+this._uid).modal({
                    closable: false,
                    onApprove: function () {
                        that.isLoaded = false;
                        axios.delete(url)
                            .then(function (response) {
                                that.isLoaded = true;
                                that.dataForceReload=!that.dataForceReload;
                                that.sendToast(that.strings.deleteUserSuccess, 'success');
                            })
                            .catch(function (error) {
                                if (error.response && error.response.status == 409) {
                                    that.sendToast(error.response.data, 'error');
                                } else {
                                    that.sendToast(that.strings.loadErrorMessage, 'error');
                                }
                                that.isLoaded = false;
                            });
                    }
                }).modal('show');
            },
            clearSearchResults () {
                let haveClearAction = this.clearInputSearch();
                if(haveClearAction){
                    this.updateResults(true);
                }
            },
            refreshResults (query) {
                if(query !== undefined && query.length >= this.properties.filterMinLengthSearch){
                    this.filter.resultsFor = query;
                    this.updateResults(true);
                }
            },
            changePage (url) {
                let that = this
                $('html, body').animate({
                    scrollTop: 0
                }, 600, function () {
                    that.dataRouteGetUsersList = url;
                });
            }
        }
    }
</script>
