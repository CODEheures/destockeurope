<template>
    <div :id="_uid" class="ui fluid search filter">
        <div :class="!wantSearch ? 'ui fluid action left icon input' : 'ui fluid left icon input'">
            <i class="filter icon"></i>
            <input :class="wantSearch==true ? 'prompt' : 'prompt disabled'" type="text" :placeholder="placeHolder">
            <button class="ui red icon button" v-if="!wantSearch">
                <i class="remove icon"
                    v-on:click="resetSearch(true)">
                </i>
            </button>
        </div>

    </div>
</template>

<script>
    export default {
        props: {
            //vue routes
            routeSearch: String,
            //vue vars
            flagReset: {
                type: Boolean,
                default: false
            },
            resultsFor: {
                type: String
            },
            update: {
                type: Boolean
            },
            withXsrfToken: {
                type: Boolean,
                required: false,
                default: false
            },
            fields: {
                type: Object,
            },
            //vue strings
            placeHolder: String
        },
        data: () => {
            return {
                properties: {},
                dataRouteSearch: '',
                wantSearch: true,
                elemSearch: undefined
            }
        },
        mounted () {
            this.properties = this.$store.state.properties['global'];
            let that = this;
            this.elemSearch = $('#'+that._uid);
            this.observeElem(this.elemSearch[0]);
            this.$watch('routeSearch', function () {
                this.urlForSearch(function (url) {
                    that.elemSearch
                            .search({
                                apiSettings: {
                                    url: url.replace('query', '{query}'),
                                    beforeXHR: function(xhr) {
                                        // adjust XHR with additional headers
                                        if(that.withXsrfToken===true){
                                            xhr.setRequestHeader ('X-XSRF-TOKEN', that.readCookie('XSRF-TOKEN'));
                                        }
                                    }
                                },
                                //type: 'category',
                                fields: that.fields,
                                minCharacters : that.properties.filterMinLengthSearch
                            })
                    ;
                });
            });
            this.$watch('flagReset', function () {
                this.resetSearch(false);
            });
            this.$watch('update', function () {
                this.updateSearch();
            });
        },
        methods: {
            urlForSearch(callback) {
                let urlBase = this.routeSearch;
                let parsed = Parser.parse(urlBase, true);
                parsed.search=undefined;
                parsed.query.search="query";
                this.dataRouteSearch = Parser.format(parsed);
                callback(this.dataRouteSearch);
            },
            resetSearch(withEmit) {
                this.elemSearch.search('set value','');
                this.elemSearch.search('clear cache');
                this.wantSearch = true;
                withEmit ? this.$parent.$emit('clearSearchResults') : null;
            },
            updateSearch() {
                this.elemSearch = $('#'+this._uid);
                if(this.resultsFor != undefined){
                    this.elemSearch.search('set value',this.resultsFor);
                    this.wantSearch = false;
                } else {
                    this.resetSearch(false);
                }
            },
            readCookie: function read(name) {
                let match = document.cookie.match(new RegExp('(^|;\\s*)(' + name + ')=([^;]*)'));
                return (match ? decodeURIComponent(match[3]) : null);
            },
            observeElem: function (elem) {
                let MutationObserver    = window.MutationObserver || window.WebKitMutationObserver;
                let myObserver          = new MutationObserver (this.observeMutationHandler);
                let obsConfig           = { childList: true, characterData: false, attributes: false, subtree: true };

                myObserver.observe (elem, obsConfig);
            },
            observeMutationHandler: function (mutationRecords) {
                let that = this;
                mutationRecords.forEach ( function (mutation) {
                    let btnAction = $(mutation.target).find('a.action');
                    if(btnAction.length > 0){
                        btnAction.click(function (event) {
                            event.preventDefault();
                            that.elemSearch.search('hide results');
                            let query = that.elemSearch.search('get value');
                            that.$parent.$emit('refreshResults', query);
                            that.wantSearch = false;
                        });
                    }
                });
            }
        }
    }
</script>
