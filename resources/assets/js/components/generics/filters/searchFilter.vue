<template>
    <div :id="_uid" class="ui fluid search">
        <div class="ui fluid action input">
            <input :class="wantSearch==true ? 'prompt' : 'prompt disabled'" type="text" :placeholder="placeHolder">
            <button class="ui icon button">
                <i class="search icon" v-if="wantSearch"></i>
                <i class="remove red icon" v-else
                    v-on:click="emitResetSearch">
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
            var that = this;
            this.$watch('routeSearch', function () {
                this.urlForSearch(function (url) {
                    let elemSearch = $('#'+that._uid);
                    elemSearch
                            .search({
                                apiSettings: {
                                    url: url.replace('query', '{query}')
                                },
                                type: 'category',
                                fields: {
                                    description : 'resume',
                                    image: 'thumb'
                                },
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
            emitResetSearch() {
                let elemSearch = $('#'+this._uid);
                elemSearch.search('set value','');
                elemSearch.search('clear cache');
                this.wantSearch = true;
                this.$parent.$emit('clearSearchResults');
            }
        }
    }
</script>
