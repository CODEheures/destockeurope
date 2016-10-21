<template>
    <div id="category-vertical-menu">
        <div class="ui vertical menu">
            <div class="ui active inverted dimmer" v-if="!isLoaded">
                <div class="ui large text loader">Loading</div>
            </div>
            <div v-for="metaCategory in metaCategories">
                <div v-for="(description, locale) in metaCategory.description" class="item" v-if="locale==actualLocale">
                    <div class="header">{{ description }}</div>
                    <recursive-categories-vertical-menu
                        :parent-categories="metaCategory.categories"
                        :actual-locale="actualLocale"
                        :parent-id="parentId">
                    </recursive-categories-vertical-menu>
                </div>
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
