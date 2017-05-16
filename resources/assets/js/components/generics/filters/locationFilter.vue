<template>
    <div :id="_uid" class="ui fluid search filter">
        <div :class="!wantSearch ? 'ui fluid action left icon input' : 'ui fluid left icon input'">
            <i class="marker icon"></i>
            <input id="filter_location" :class="wantSearch==true ? 'prompt' : 'prompt disabled'" type="text" :placeholder="placeHolder" v-on:autocompletechange="filterChange">
            <button class="ui red icon button" v-if="!wantSearch">
                <i class="remove icon"
                    v-on:click="resetSearch(true)">
                </i>
            </button>
        </div>
        <!--<input id="filter_location" class="" type="text">-->
    </div>
</template>

<script>
    export default {
        props: {
            //vue routes
            //vue vars
            accurateList: {
                type: Array
            },
            update: {
                type: Boolean
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
            this.$watch('update', function () {
                this.updateSearch();
            });
        },
        methods: {
            filterChange () {
                let that = this;
                let addressComponents = JSON.parse(sessionStorage.getItem('autoCompleteResult'));
                let parsed = this.parseAddressComponent(addressComponents);
                let event = {};
                (this.accurateList).forEach(function (elem,index) {
                    if(elem in parsed){
                        event[elem] = parsed[elem];
                        that.wantSearch=false;
                    } else {
                        event[elem] = null;
                    }
                });
                if(!this.wantSearch){
                    sessionStorage.setItem('filterLocationInputVal', JSON.stringify($('#filter_location').val()));
                    this.$parent.$emit('locationUpdate', event);
                }
            },
            parseAddressComponent (addressComponents) {
                let parsed = {};
                for(let index in addressComponents){
                    parsed[(addressComponents[index]).types[0]] = (addressComponents[index]).short_name;
                }
                return parsed;
            },
            resetSearch(withEmit) {
                $('#filter_location').val('');
                sessionStorage.removeItem('filterLocationInputVal');
                this.wantSearch = true;
                withEmit ? this.$parent.$emit('clearLocationResults') : null;
            },
            updateSearch() {
                let previousVal = JSON.parse(sessionStorage.getItem('filterLocationInputVal'));
                if(previousVal && previousVal.length > 0){
                    $('#filter_location').val(previousVal);
                    this.wantSearch = false;
                } else {
                    this.resetSearch(false);
                }
            }
        }
    }
</script>
