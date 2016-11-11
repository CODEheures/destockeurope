<template>
    <div id="category-vertical-menu">
        <div class="ui active inverted dimmer" v-if="!isLoaded">
            <div class="ui large text loader">Loading</div>
        </div>
        <div :id="_uid" class="ui vertical accordion menu">
            <div class="item">
                <a class="title" v-on:click="emitCategoryChoice(0)">{{ allItem }}</a>
            </div>
            <div v-for="category in categories" class="item">
                <a class="title">
                    <i class="dropdown icon"></i>
                    {{ category['description'][actualLocale] }}
                </a>
                <div class="content">
                    <recursive-categories-lateral-accordion-menu
                            :categories="category.children"
                            :actual-locale="actualLocale"
                            :parent-id="category.id"
                            :all-item="allItem"
                    ></recursive-categories-lateral-accordion-menu>
                </div>
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
        ],
        data: () => {
            return {
                categories: [],
                isLoaded: false
            } ;
        },
        mounted () {
            this.getCategories();
            $('#'+this._uid).accordion();
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
                                function(response)  {
                                    this.categories = response.data;
                                    this.isLoaded = true;
                                },
                                function (response) {
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
