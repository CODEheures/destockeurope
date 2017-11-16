<template>
    <div :id="_uid" class="ui fluid search filter">
        <div :class="!wantSearch ? 'ui fluid action left icon input' : 'ui fluid left icon input'">
            <i class="marker icon"></i>
            <input id="filter_location" :class="wantSearch==true ? 'prompt' : 'prompt disabled'" type="text" :placeholder="strings.placeHolder" v-on:autocompletechange="filterChange" :style="withNullBorderRadiusBottom ?  'border-bottom-left-radius: 0; border-bottom-right-radius: 0;':''">
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
  import { DestockTools } from '../../../destockTools'
    export default {
        props: {
            //vue routes
            //vue vars
            accurateList: {
                type: Array
            },
            withNullBorderRadiusBottom: {
                type: Boolean,
                default: false,
                required: false
            }
        },
        computed: {
            strings () {
                return this.$store.state.strings['location-filter']
            }
        },
        data () {
            return {
                wantSearch: true
            }
        },
        mounted () {
            this.updateSearch();
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
                event['forLocation']=$('#filter_location').val();
                if(!this.wantSearch){
                    this.$emit('locationUpdate', event);
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
                this.wantSearch = true;
                withEmit ? this.$emit('clearLocationResults') : null;
            },
            updateSearch() {
                let previousVal = DestockTools.findInUrl('forLocation');
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
