<template>
    <span>
        <template  v-for="category in categories">
            <template v-if="category.children.length>0">
                <recursive-categories-select-menu
                        :parent-description="parentDescription + '>' + category['description'][properties.actualLocale]"
                        :categories="category.children"
                        :parent-id="category.id">
                </recursive-categories-select-menu>
            </template>
            <template v-else>
                {{ parentDescription }}
            </template>
        </template>
    </span>
</template>


<script>
    export default {
        props: {
            parentDescription: String,
            categories: Array,
            parentId: Number,
        },
        data: () => {
            return {
                properties: {}
            } ;
        },
        mounted () {
            this.properties = this.$store.state.properties['global'];
        },
        methods: {

        }
    }
</script>
