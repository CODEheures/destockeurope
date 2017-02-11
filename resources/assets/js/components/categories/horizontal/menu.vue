<template>
    <div>
        <div class="ui active inverted dimmer" v-if="!isLoaded">
            <div class="ui large text loader">Loading</div>
        </div>
        <div class="ui menu">
            <a class="browse item" v-on:click="emitCategoryChoice(0)">
            {{ allItem }}
            </a>
            <template v-for="(category,index) in categories">
                    <template>
                        <a :id="'browse-'+index+'-'+_uid" class="browse item"
                           v-on:click="emitCategoryChoice(category.id)">
                            {{ category['description'][actualLocale] }}
                            <i class="dropdown icon"></i>
                        </a>
                        <recursive-categories-horizontal-menu
                                :categories="category.children"
                                :actual-locale="actualLocale"
                                :parent-id="category.id"
                                :all-item="allItem"
                                :old-choice="oldChoice"
                                :level="1"
                        ></recursive-categories-horizontal-menu>
                    </template>
            </template>
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
                isLoaded: false
            } ;
        },
        mounted () {
            this.getCategories();
            this.$on('categoryChoice', function (event) {
                this.$parent.$emit('categoryChoice', {id: event.id});
            });
        },
        updated() {
            this.setPopup();
        },
        methods: {
            getCategories: function (withLoadIndicator) {
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                let that = this;
                axios.get(this.routeCategory)
                    .then(function (response) {
                        that.categories = response.data;
                        that.isLoaded = true;
                    })
                    .catch(function (error) {
                        that.$parent.$emit('loadError');
                    });
            },
            emitCategoryChoice: function(value){
                this.$parent.$emit('categoryChoice', {id: value});
            },
            setPopup () {
                let that = this;
                (this.categories).forEach(function (elem, index) {
                    let $elem = $('#browse-'+index+'-'+that._uid);
                    $elem.popup({
                        inline: false,
                        hoverable: true,
                        exclusive: true,
                        position: 'bottom left',
                        lastResort: true,
                        delay: {
                            show: 300,
                            hide: 800
                        }
                    })
                    ;
                });
            }
        }
    }
</script>
