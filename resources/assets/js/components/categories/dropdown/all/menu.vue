<template>
    <div>
        <div class="ui active inverted dimmer" v-if="!isLoaded">
            <div class="ui large text loader">Loading</div>
        </div>
        <div class="ui mini labeled right action input">
            <div class="ui blue label" v-on:click="setOldChoice">
                {{ strings.firstMenuName }}
            </div>
            <div :id="_uid" class="ui mini floating dropdown" :class="isButton ? 'button' : ''">
                <div class="default text">{{ strings.firstMenuName }}</div>
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
                countCategories: 0,
                isLoaded: false,
                isReady: false,
            } ;
        },
        mounted () {
            this.strings = this.$store.state.strings['categories-dropdown-menu'];
            this.properties = this.$store.state.properties['global'];
            this.getCategories();
            this.$on('categoryChoice', function (event) {
                this.$parent.$emit('categoryChoice', {id: event.id});
            });
            let that = this;

            $('#'+this._uid).dropdown({
                    allowCategorySelection: that.allowCategorySelection,
                    onChange: function(value, text, $selectedItem) {
                        if(value != undefined && value != ''){
                            that.$parent.$emit('categoryChoice', {id: value});
                        }
                    }
                })
            ;

            this.setReady();
            this.$watch('isReady', function () { that.setOldChoice() });
            this.$watch('oldChoice', function () { that.setOldChoice() });
        },
        methods: {
            getCategories: function (withLoadIndicator) {
                let that = this;
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                axios.get(this.properties.routeCategoryWithCount)
                    .then(function (response) {
                        that.categories = response.data.tree;
                        that.countCategories = response.data.count;
                        that.isLoaded = true;
                    })
                    .catch(function (error) {
                        that.$parent.$emit('loadError');
                    });
            },
            setReady () {
                let that = this;
                this.$watch('isLoaded', function () {
                    let testLoadedInterval = setInterval(function () {
                        if($('#'+that._uid).find('.item').length === that.countCategories) {
                            that.isReady = true;
                            clearInterval(testLoadedInterval);
                        }
                    }, 200);
                });
            },
            setOldChoice () {
                if(!isNaN(Number(this.oldChoice)) && Number(this.oldChoice)>0) {
                    $('#'+this._uid).dropdown('set selected', this.oldChoice)
                }
            }
        }
    }
</script>
