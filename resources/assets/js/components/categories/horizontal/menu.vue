<template>
    <div class="horizontal-category-menu">
        <div class="ui active inverted dimmer" v-if="!isLoaded">
            <div class="ui large text loader">Loading</div>
        </div>
        <div class="ui blue inverted category menu">
            <a class="browse item" v-on:click="emitCategoryChoice(0)">
            {{ allItem }}
            </a>
            <template v-for="(category,index) in categories">
                <a :id="'browse-'+index+'-'+_uid" class="browse item"
                   v-on:click="emitCategoryChoice(category.id)">
                    {{ category['description'][actualLocale] }}
                    <i class="dropdown icon"></i>
                </a>
            </template>
        </div>
        <div :id="'popup-'+index+'-'+_uid" v-for="(category,index) in categories">
            <recursive-categories-horizontal-menu
                    :categories="category.children"
                    :actual-locale="actualLocale"
                    :parent-id="category.id"
                    :parent-description="category.description[actualLocale]"
                    :all-item="allItem"
                    :old-choice="oldChoice"
                    :level="1"
                    :max-level="countLevel(category)"
            ></recursive-categories-horizontal-menu>
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
            let fixedMenu = $('.horizontal-category-menu');
            fixedMenu.visibility({
                type   : 'fixed',
                offset : 66 // give some space from top of screen
            });
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
                    let $popup = $('#popup-'+index+'-'+that._uid + ' div.ui.fluid.popup');
                    $elem.popup({
                        inline: true,
                        target: $('.ui.category.menu'),
                        popup: $popup,
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
            },
            countLevel: function (category) {
                let level = 1;
                for(let i=0; i<category.children.length;i++) {
                    category.children[i].children.length > 0 ? level = 2: null;
                }
                return level;
            }
        }
    }
</script>
