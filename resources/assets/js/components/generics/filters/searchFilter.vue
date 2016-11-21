<template>
    <div class="ui fluid search">
        <div class="ui fluid right icon input">
            <input class="prompt" type="text" placeholder="Search GitHub">
            <i class="search icon"></i>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            routeSearch: String
        },
        data: () => {
            return {
                dataRouteSearch: ''
            }
        },
        mounted () {

            var that = this;
            this.$watch('routeSearch', function () {
                this.urlForSearch(function (url) {
                    $('.ui.search')
                            .search({
                                apiSettings: {
//                            url: '//api.github.com/search/repositories?q={query}'
                                    url: url.replace('query', '{query}')
                                },
                                type: 'category',
                                fields: {
//                                results : 'items',
//                                title   : 'name',
//                                url     : 'html_url'
                                },
                                minCharacters : 3
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
            }
        }
    }
</script>
