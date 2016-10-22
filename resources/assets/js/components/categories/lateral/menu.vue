<template>
    <div id="category-vertical-menu">
        <div class="ui active inverted dimmer" v-if="!isLoaded">
            <div class="ui large text loader">Loading</div>
        </div>
        <div class="ui vertical menu">
            <div v-for="metaCategory in metaCategories" class="item">
                {{ metaCategory['description'][actualLocale] }}
                <recursive-categories-vertical-menu
                        :parent-categories="metaCategory.categories"
                        :actual-locale="actualLocale"
                        :parent-id="parentId"
                ></recursive-categories-vertical-menu>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: [
            'routeMetaCategory',
            'actualLocale'
        ],
        data: () => {
            return {
                metaCategories: [],
                isLoaded: false,
                parentId: 0
            } ;
        },
        mounted () {
            this.getMetaCategories();
        },
        methods: {
            getMetaCategories: function (withLoadIndicator) {
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                this.$http.get(this.routeMetaCategory)
                        .then(
                                (response) => {
                                    this.metaCategories = response.data;
                                    this.isLoaded = true;
                                },
                                (response) => {
                                    this.$parent.$emit('loadError');
                                }
                        );
            }
        }
    }
</script>
