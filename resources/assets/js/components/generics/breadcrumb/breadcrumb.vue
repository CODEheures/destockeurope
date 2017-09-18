<template>
    <div style="display: inline-block">
        <div class="ui grid breadcrumb">
            <div class="sixteen wide tablet sixteen wide computer only column">
                <div class="ui big breadcrumb">
                    <template v-for="(item, index) in items">
                        <template v-if="withAction">
                            <a :href="getNextUrl('categoryId',item.value)" class="section" :data-value="item.value">{{ item.name }}</a>
                        </template>
                        <template v-else>
                            <a :href="getNextUrl('categoryId',item.value)" class="section" :data-value="item.value" v-on:click.stop.prevent="">{{ item.name }}</a>
                        </template>
                        <template v-if="index!=items.length-1">
                            <i class="right angle icon divider"></i>
                        </template>
                    </template>
                </div>
            </div>
            <div class="sixteen wide mobile only column">
                <div class="ui breadcrumb">
                    <template v-for="(item, index) in items">
                        <template v-if="withAction">
                            <a :href="getNextUrl('categoryId',item.value)" class="section" :data-value="item.value">{{ item.name }}</a>
                        </template>
                        <template v-else>
                            <a :href="getNextUrl('categoryId',item.value)" class="section" :data-value="item.value" v-on:click.stop.prevent="">{{ item.name }}</a>
                        </template>
                        <template v-if="index!=items.length-1">
                            <i class="right angle icon divider"></i>
                        </template>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            'items': Array,
            'withAction' : {
                type: Boolean,
                required: false,
                default: false
            }
        },
        data: () => {
            return {
                nextUrl: ""
            }
        },
        mounted () {
            this.nextUrl = this.$store.state.properties['global']['routeHome'];
        },
        methods: {
            emitCategorieChoice: function (categoryId) {
                this.$parent.$emit('categoryChoice', {id: categoryId});
            },
            getNextUrl(paramName, paramValue) {
                let urlBase = this.nextUrl;
                let parsed = Parser.parse(urlBase, true);
                parsed.search=undefined;

                if(paramValue != null){
                    parsed.query[paramName] = paramValue.toString();
                } else if (paramName in parsed.query){
                    delete parsed.query[paramName]
                }

                'page' in parsed.query ? delete parsed.query['page'] : null;
                return Parser.format(parsed);
            },
        },
    }
</script>
