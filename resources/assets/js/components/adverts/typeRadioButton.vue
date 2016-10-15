<template>
    <div id="advert-type-dropdown">
        <div class="inline fields" v-if="!hasError">
            <div class="ui active inverted dimmer" v-if="!isLoaded">
                <div class="ui large text loader">Loading</div>
            </div>
            <label class="text">{{ firstMenuName }}</label>
            <div v-for="(type,index) in listType" class="field" :data-value="index">
                <div class="ui radio checkbox">
                    <input type="radio" name="type" :value="index" :checked="oldChoice == index ? true : false">
                    <label>{{ type }}</label>
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
        }
    }
</script>
