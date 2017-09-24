<template>
    <select>
        <option>{{ strings.allItem }}</option>
        <template v-for="category in categories">
            <optgroup :label="category['description'][properties.actualLocale]">
                <option v-if="withAll">{{ strings.allItem }}</option>
                <template v-if="category.children.length>0" v-for="child in category.children">
                    <recursive-categories-select-menu
                            :parent-description="child['description'][properties.actualLocale]"
                            :categories="child.children"
                            :parent-id="child.id">
                    </recursive-categories-select-menu>
                </template>
            </optgroup>
        </template>
    </select>
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
            },
            withRedirectionOnClick: {
               type: Boolean,
               required: false,
               default: false
            }
        },
        data: () => {
            return {
                strings: {},
                properties: {},
                categories: [],
                nextUrl: ""
            } ;
        },
        mounted () {
            this.strings = this.$store.state.strings['categories-select-menu'];
            this.properties = this.$store.state.properties['global'];
            this.categories = this.$store.state.properties['categories-select-menu']['datas'];
            this.nextUrl = this.$store.state.properties['global']['routeHome'];


//            let that = this;
//            this.$watch('oldChoice', function () { that.setOldChoice() });
        },
        updated () {
//            let that = this;

//            $('#'+this._uid).dropdown({
//                allowCategorySelection: that.allowCategorySelection,
//                onChange: function(value, text, $selectedItem) {
//                    if(value != undefined && value != ''){
//                        if(!that.withRedirectionOnClick){
//                            that.$parent.$emit('categoryChoice', {id: value});
//                        } else {
//                            console.log([that.oldChoice, value]);
//                            value != that.oldChoice ? document.location.href = that.getNextUrl('categoryId',value) : null;
//                        }
//                    }
//                }
//            })
//            ;
//            this.setOldChoice();
        },
        methods: {
//            setOldChoice () {
//                if(!isNaN(Number(this.oldChoice)) && Number(this.oldChoice)>0) {
//                    $('#'+this._uid).dropdown('set selected', this.oldChoice)
//                }
//            },
            getNextUrl(paramName, paramValue) {
                return DestockTools.getNextUrl(this.nextUrl, paramName, paramValue, true)
            },
        }
    }
</script>
