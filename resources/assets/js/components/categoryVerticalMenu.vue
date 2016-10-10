<template>
    <div id="category-vertical-menu">
        <div class="ui vertical menu" v-if="!hasError">
            <div class="ui active inverted dimmer" v-if="!isLoaded">
                <div class="ui large text loader">Loading</div>
            </div>
            <div v-for="metaCategory in metaCategories" class="item">
                <div class="header">{{ metaCategory.title }}</div>
                <div class="menu">
                    <div v-for="category in metaCategory.categories" class="item">
                        {{ category.title }}
                    </div>
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
            'routeMetaCategory'],
        data: () => {
            return {
                metaCategories: [],
                isLoaded: false,
                hasError: false
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
                                    this.hasError = true;
                                    this.isLoaded = true;
                                }
                        );
            }
        }
    }
</script>
