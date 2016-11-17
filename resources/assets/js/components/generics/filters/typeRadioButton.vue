<template>
    <div id="advert-type-dropdown">
        <div class="inline fields">
            <div class="ui active inverted dimmer" v-if="!isLoaded">
                <div class="ui large text loader">Loading</div>
            </div>
            <label class="text">{{ firstMenuName }}</label>
            <div v-for="(type,index) in listType" class="field" :data-value="index">
                <div :id="'radio-'+index+'-'+_uid" class="ui radio checkbox">
                    <input type="radio" name="type" :value="index" :checked="oldChoice == index ? true : false">
                    <label>{{ type }}</label>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: [
            'routeGetListType',
            'firstMenuName',
            'oldChoice'
            ],
        data: () => {
            return {
                listType: [],
                isLoaded: false
            } ;
        },
        mounted () {
            this.getListType();
        },
        updated () {
            var that = this;
            for(let index in this.listType){
                $('#radio-'+index+'-'+that._uid).checkbox({
                    onChange: function () {
                        that.$parent.$emit('typeChoice', {'type': this.value})
                    }
                })
            }

        },
        methods: {
            getListType: function () {
                this.$http.get(this.routeGetListType)
                        .then(
                                function (response) {
                                    this.listType = response.data;
                                    this.isLoaded = true;
                                },
                                function (response) {
                                    this.$parent.$emit('loadError');
                                }
                        );
            }
        }
    }
</script>
