<template>
    <div :class="left ? 'left menu' : 'menu'">
        <div class="item" :data-value="parentId" :data-text="parentDescription + ' > ' + allItem" v-if="withAll">
            <span class="text">{{ allItem }}</span>
        </div>
        <div v-for="category in categories" class="item" :data-value="category.id" :data-text="parentDescription + '<br /> > ' + category['description'][properties.actualLocale]" >
            <i :class="left ? 'right dropdown icon' : 'left dropdown icon'" v-if="category.children.length>0"></i>
            <span class="text">{{ category['description'][properties.actualLocale] }}</span>
            <recursive-categories-dropdown-menu
                    v-if="category.children.length>0"
                    :parent-description="parentDescription + '<br /> > ' + category['description'][properties.actualLocale]"
                    :categories="category.children"
                    :parent-id="category.id"
                    :with-all="withAll"
                    :all-item="allItem"
                    :left="!left">
            </recursive-categories-dropdown-menu>
        </div>
    </div>
</template>


<script>
    export default {
        props: {
            parentDescription: String,
            categories: Array,
            parentId: Number,
            withAll: Boolean,
            allItem: {
                type: String,
                required: false,
                default: ''
            },
            left: Boolean,
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
