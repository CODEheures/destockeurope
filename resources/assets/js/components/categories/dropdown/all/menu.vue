<template>
    <div>
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
            } ;
        },
        mounted () {
            this.strings = this.$store.state.strings['categories-dropdown-menu'];
            this.properties = this.$store.state.properties['global'];
            this.categories = this.$store.state.properties['categories-dropdown-menu']['datas'];
            this.$on('categoryChoice', function (event) {
                this.$parent.$emit('categoryChoice', {id: event.id});
            });

            let that = this;
            this.$watch('oldChoice', function () { that.setOldChoice() });
        },
        updated () {
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
            this.setOldChoice();
        },
        methods: {
            setOldChoice () {
                if(!isNaN(Number(this.oldChoice)) && Number(this.oldChoice)>0) {
                    $('#'+this._uid).dropdown('set selected', this.oldChoice)
                }
            }
        }
    }
</script>
