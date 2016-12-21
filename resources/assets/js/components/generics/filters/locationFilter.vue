<template>
    <div :id="_uid" class="ui fluid search">
        <div class="ui fluid action input">
            <input id="filter_location" :class="wantSearch==true ? 'prompt' : 'prompt disabled'" type="text" :placeholder="placeHolder" v-on:autocompletechange="filterChange">
            <button class="ui icon button">
                <i class="search icon" v-if="wantSearch"></i>
                <i class="remove red icon" v-else
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
                let addressComponents = JSON.parse(sessionStorage.getItem('autoCompleteResult'));
                let parsed = this.parseAddressComponent(addressComponents);
                let event = {};
                for(let index in this.accurateList){
                    let key = this.accurateList[index];
                    if(this.accurateList[index] in parsed){
                        event[key] = parsed[key];
                        this.wantSearch=false;
                    } else {
                        event[key] = null;
                    }
                }
                if(!this.wantSearch){
                    //TODO set sessionstorage input val
                    sessionStorage.setItem('filterLocationInputVal', $('#filter_location').val());
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
                let previousVal = sessionStorage.getItem('filterLocationInputVal');
                if(previousVal && previousVal.length > 0){
                    $('#filter_location').val(previousVal);
                    this.wantSearch = false;
                }

            }
        }
    }
</script>
