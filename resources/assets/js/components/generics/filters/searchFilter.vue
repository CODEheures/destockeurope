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
            minLengthSearch: Number,
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
                dataRouteSearch: '',
                wantSearch: true
            }
        },
        mounted () {
            let that = this;
            this.$watch('routeSearch', function () {
                this.urlForSearch(function (url) {
                    let elemSearch = $('#'+that._uid);
                    elemSearch
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
                                minCharacters : that.minLengthSearch,
                                onResultsOpen: function () {
                                    $(this).children('a.action').click(function (event) {
                                        event.preventDefault();
                                        elemSearch.search('hide results');
                                        let query = elemSearch.search('get value');
                                        that.$parent.$emit('refreshResults', query);
                                        that.wantSearch = false;
                                    })
                                }
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
                let elemSearch = $('#'+this._uid);
                elemSearch.search('set value','');
                elemSearch.search('clear cache');
                this.wantSearch = true;
                withEmit ? this.$parent.$emit('clearSearchResults') : null;
            },
            updateSearch() {
                let elemSearch = $('#'+this._uid);
                if(this.resultsFor != undefined){
                    elemSearch.search('set value',this.resultsFor);
                    this.wantSearch = false;
                }
            },
            readCookie: function read(name) {
                let match = document.cookie.match(new RegExp('(^|;\\s*)(' + name + ')=([^;]*)'));
                return (match ? decodeURIComponent(match[3]) : null);
            },
        }
    }
</script>
