<template>
    <div class="ui blue colored segment">
        <div :id="'filter-accordion-'+_uid" class="ui fluid accordion filter">
            <div class="active title">
                <span class="ui blue ribbon label"><i class="dropdown icon"></i><span class="close">{{ strings.ribbonClose }}</span><span class="open">{{ strings.ribbonOpen }}</span></span>
            </div>
            <div class="active content">
                <div class="ui grid">
                    <div class="column">
                        <search-filter
                                :route-search="routeSearch"
                                :place-holder="strings.placeHolder"
                                :results-for="dataResultsFor"
                                :update="dataUpdate"
                                :flag-reset="flagResetSearch"
                                :with-xsrf-token="true"
                                :fields="{title: 'email', description : 'created_at'}"
                        ></search-filter>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: {
            //vue routes
            //vue vars
            update: {
                type: Boolean
            },
            filter: {
                type: Object
            },
            //search component
            routeSearch: {
                type: String
            },
            flagResetSearch: {
                type: Boolean
            },
        },
        data: () => {
            return {
                strings: {},
                properties: {},
                dataResultsFor: '',
                dataUpdate: false
            };
        },
        mounted () {
            this.strings = this.$store.state.strings['user-filter'];
            this.properties = this.$store.state.properties['global'];
            this.$watch('update', function () {
                this.setSearchFilter();
                this.dataUpdate = !this.dataUpdate;
            });
            this.$on('refreshResults', function (query) {
                this.$parent.$emit('refreshResults', query);
            });
            this.$on('clearSearchResults', function () {
                this.$parent.$emit('clearSearchResults');
            });
            let that = this;
            let accordionElement = $('#filter-accordion-'+this._uid);
            if($(window).width()<768){
                accordionElement.accordion('close',0);
            } else {
                accordionElement.accordion();
            }
        },
        methods: {
            setSearchFilter: function () {
                this.dataResultsFor = this.filter.resultsFor;
            }
        }
    }
</script>
