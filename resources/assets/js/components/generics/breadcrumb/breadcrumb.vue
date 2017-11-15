<template>
    <div style="display: inline-block">
        <div class="ui grid breadcrumb">
            <div class="sixteen wide tablet sixteen wide computer only column">
                <div class="ui big breadcrumb">
                    <template v-for="(item, index) in items">
                        <template v-if="withAction">
                            <a :href="getNextUrl('categoryId',item.value)" class="section" :data-value="item.value" @click.stop="">{{ item.name }}</a>
                        </template>
                        <template v-else>
                            <a :href="getNextUrl('categoryId',item.value)" class="section" :data-value="item.value" @click.stop.prevent="">{{ item.name }}</a>
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
                            <a :href="getNextUrl('categoryId',item.value)" class="section" :data-value="item.value" @click.stop="">{{ item.name }}</a>
                        </template>
                        <template v-else>
                            <a :href="getNextUrl('categoryId',item.value)" class="section" :data-value="item.value" @click.stop.prevent="">{{ item.name }}</a>
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
        computed: {
            nextUrl () {
                return this.$store.state.properties['global']['routeHome']
            }
        },
        methods: {
            getNextUrl(paramName, paramValue) {
                return DestockTools.getNextUrl(this.nextUrl, paramName, paramValue, true)
            },
        },
    }
</script>
