<template>
    <select v-model="selected">
        <option v-if="withAll" value="0">{{ strings.allItem }}</option>
        <template v-for="group in optgroups">
            <optgroup :label="group['name']">
                <option v-if="withAll" :value="group['id']">{{ strings.allItem }}</option>
                <option v-for="option in options" v-if="option[0]['id']==group['id']" :value="option[option.length-1]['id']">
                    <template v-for="description ,index in option">
                        <template v-if="index == option.length-1">
                            {{ description['name'] }}
                        </template>
                    </template>
                </option>
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
        computed: {
            strings () {
                return this.$store.state.strings['categories-select-menu']
            },
            properties () {
                return this.$store.state.properties['global']
            },
            categories () {
                return this.$store.state.properties['categories-select-menu']['datas']
            },
            nextUrl () {
                return this.$store.state.properties['global']['routeHome']
            }
        },
        watch: {
            selected () {
                this.choiceCategory(this.selected)
            }
        },
        data () {
            return {
                options: [],
                optgroups: [],
                selected: this.oldChoice
            } ;
        },
        mounted () {
            this.getNativeSelectCategories(this.$store.state.properties['categories-select-menu']['datas']);
        },
        methods: {
            getNativeSelectCategories(categories) {
                for(let category in categories) {
                    this.optgroups.push({'id': categories[category]['id'], 'name': categories[category]['description'][this.properties.actualLocale]});
                    this.traverse(categories[category]);
                }
            },
            traverse(category, concat= []) {
                let localConcat = _.cloneDeep(concat);
                if('children' in category && category.children.length > 0) {
                    localConcat.push({'id': category['id'], 'name': category['description'][this.properties.actualLocale]});
                    for(let child in category.children) {
                        this.traverse(category.children[child], localConcat);
                    }
                } else {
                    localConcat.push({'id': category['id'], 'name': category['description'][this.properties.actualLocale]});
                    this.options.push(localConcat);
                }
            },
            getNextUrl(paramName, paramValue) {
                return DestockTools.getNextUrl(this.nextUrl, paramName, paramValue, true)
            },
            choiceCategory(value) {
                let that = this;
                if(value != undefined && value != ''){
                    if(!that.withRedirectionOnClick){
                        that.$emit('categoryChoice', value);
                    } else {
                        value != that.oldChoice ? document.location.href = that.getNextUrl('categoryId',value) : null;
                    }
                }
            }
        }
    }
</script>
