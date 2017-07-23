<template>
    <div>
        <div class="ui active inverted dimmer" v-if="!isLoaded">
            <div class="ui large text loader">Loading</div>
        </div>
        <div class="ui mini labeled right action input">
            <div class="ui blue label">
                {{ strings.firstMenuName }}
            </div>
            <div :id="_uid" class="ui mini floating dropdown" :class="isButton ? 'button' : ''">
                <div class="text">{{ strings.firstMenuName }}</div>
                <i class="dropdown icon"></i>
                <div class="menu">
                    <div class="item" data-value="0" :data-text="strings.allItem" v-if="withAll">
                        <span class="text">{{ strings.allItem }}</span>
                    </div>
                    <div v-for="category in categories" class="item" >
                        <i class="dropdown icon" v-if="category.children.length>0"></i>
                        <span class="text">{{ category['description'][properties.actualLocale] }}</span>
                        <recursive-categories-dropdown-menu
                                :parent-description="category['description'][properties.actualLocale]"
                                :categories="category.children"
                                :parent-id="category.id"
                                :with-all="withAll"
                                :all-item="strings.allItem"
                                :left="false">
                        </recursive-categories-dropdown-menu>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>


<script>
    export default {
        props: {
           oldChoice: {
                type: Number,
                required: false,
                default: 0
            },
            withAll: {
                type: Boolean,
                required: false,
                default: false
            },
            allowCategorySelection: {
                type: Boolean,
                required: false,
                default: false
            },
            isButton: {
                type: Boolean,
                required: false,
                default: true
            }
        },
        data: () => {
            return {
                strings: {},
                properties: {},
                categories: [],
                isLoaded: false,
            } ;
        },
        mounted () {
            this.strings = this.$store.state.strings['categories-dropdown-menu'];
            this.properties = this.$store.state.properties['global'];
            this.getCategories();
            this.$on('categoryChoice', function (event) {
                this.$parent.$emit('categoryChoice', {id: event.id});
            });
        },
        methods: {
            getCategories: function (withLoadIndicator) {
                let that = this;
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                axios.get(this.properties.routeCategory)
                    .then(function (response) {
                        that.categories = response.data;
                        that.isLoaded = true;
                    })
                    .catch(function (error) {
                        that.$parent.$emit('loadError');
                    });
            }
        },
        updated () {
            let that = this;
            let dropdown = $('#'+this._uid);
            dropdown.dropdown('set selected',  that.oldChoice.toString())
                 .dropdown({
                        allowCategorySelection: that.allowCategorySelection,
                        onChange: function(value, text, $selectedItem) {
                            if(value != undefined && value != '' && value != that.oldChoice){
                                that.$parent.$emit('categoryChoice', {id: value});
                            }
                        }
                    })
            ;

            this.$watch('oldChoice', function (categorieId) {
                dropdown.dropdown('set selected',  categorieId.toString())
            })
        }
    }
</script>
