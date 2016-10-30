<template>
    <div id="category-vertical-menu">
        <div class="ui active inverted dimmer" v-if="!isLoaded">
            <div class="ui large text loader">Loading</div>
        </div>
        <div class="ui vertical menu">
            <a class="item" v-on:click="emitCategoryChoice(0)">{{ allItem }}</a>
            <div v-for="category in categories" class="item">
                {{ category['description'][actualLocale] }}
                <recursive-categories-lateral-vertical-menu
                        :categories="category.children"
                        :actual-locale="actualLocale"
                        :parent-id="category.id"
                        :all-item="allItem"
                        :old-choice="oldChoice"
                ></recursive-categories-lateral-vertical-menu>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: [
            'routeCategory',
            'actualLocale',
            'allItem',
            'oldChoice'
        ],
        data: () => {
            return {
                categories: [],
                isLoaded: false,
                parentId: 0
            } ;
        },
        mounted () {
            this.getCategories();
            this.$on('categoryChoice', function (event) {
                this.$parent.$emit('categoryChoice', {id: event.id});
            });
        },
        methods: {
            getCategories: function (withLoadIndicator) {
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                this.$http.get(this.routeCategory)
                        .then(
                                (response) => {
                                    this.categories = response.data;
                                    this.isLoaded = true;
                                },
                                (response) => {
                                    this.$parent.$emit('loadError');
                                }
                        );
            },
            emitCategoryChoice: function(value){
                this.$parent.$emit('categoryChoice', {id: value});
            }
        }
    }
</script>
