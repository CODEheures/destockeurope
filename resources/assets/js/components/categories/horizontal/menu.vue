<template>
    <div class="horizontal-category-menu">
        <div class="ui blue inverted category menu">
            <a class="browse item" v-on:click="emitCategoryChoice(0)">
            {{ strings.allItem }}
            </a>
            <template v-for="(category,index) in categories">
                <a :id="'browse-'+index+'-'+_uid" class="browse item"
                   v-on:click="emitCategoryChoice(category.id)">
                    {{ category['description'][properties.actualLocale] }}
                    <i class="dropdown icon"></i>
                </a>
            </template>
        </div>
        <div :id="'popup-'+index+'-'+_uid" v-for="(category,index) in categories">
            <recursive-categories-horizontal-menu
                    :categories="category.children"
                    :parent-id="category.id"
                    :parent-description="category.description[properties.actualLocale]"
                    :all-item="strings.allItem"
                    :level="1"
                    :max-level="countLevel(category)"
            ></recursive-categories-horizontal-menu>
        </div>
    </div>
</template>


<script>
    export default {
        props: {

        },
        data: () => {
            return {
                strings: {},
                properties: {},
                categories: []
            } ;
        },
        mounted () {
            this.strings = this.$store.state.strings['categories-horizontal-menu'];
            this.properties = this.$store.state.properties['global'];
            this.categories = this.$store.state.properties['categories-horizontal-menu']['datas'];
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
