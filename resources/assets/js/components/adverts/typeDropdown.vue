<template>
    <div id="advert-type-dropdown">
        <div class="ui dropdown button" v-if="!hasError">
            <div class="ui active inverted dimmer" v-if="!isLoaded">
                <div class="ui large text loader">Loading</div>
            </div>
            <span class="text">{{ firstMenuName }}</span>
            <i class="dropdown icon"></i>
            <div class="menu">
                <div v-for="(type,index) in listType" class="item" :data-value="index">
                    <span class="text">{{ type }}</span>
                </div>
            </div>
        </div>
        <div class="ui message error" v-else>
            <p>{{ loadErrorMessage }}</p>
        </div>
    </div>
</template>


<script>
    export default {
        props: [
            'loadErrorMessage',
            'routeGetListType',
            'firstMenuName',
            'oldChoice'
            ],
        data: () => {
            return {
                listType: [],
                isLoaded: false,
                hasError: false
            } ;
        },
        mounted () {
            this.getListType();
        },
        methods: {
            getListType: function () {
                this.$http.get(this.routeGetListType)
                        .then(
                                (response) => {
                                    this.listType = response.data;
                                    this.isLoaded = true;
                                },
                                (response) => {
                                    this.hasError = true;
                                    this.isLoaded = true;
                                }
                        );
            }
        },
        updated () {
            var that = this;
            if(that.oldChoice != ''){
                $('#advert-type-dropdown .ui.dropdown')
                        .dropdown('set selected',  that.oldChoice)
                        .dropdown({
                            onChange: function(value, text, $selectedItem) {
                                that.$parent.$emit('typeChoice', {value: value});
                            }
                        })
                ;
            } else {
                $('#advert-type-dropdown .ui.dropdown')
                        .dropdown({
                            onChange: function(value, text, $selectedItem) {
                                that.$parent.$emit('typeChoice', {value: value});
                            }
                        })
                ;
            }

        }
    }
</script>
