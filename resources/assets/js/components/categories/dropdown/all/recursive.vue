<template>
    <div :class="left ? 'left menu' : 'menu'">
        <div class="item" :data-value="parentId" :data-text="parentDescription + ' > ' + allItem" v-if="withAll">
            <span class="text">{{ allItem }}</span>
        </div>
        <div v-for="category in categories" class="item" :data-value="category.id" :data-text="parentDescription + ' > ' + category['description'][actualLocale]" >
            <i :class="left ? 'right dropdown icon' : 'left dropdown icon'" v-if="category.children.length>0"></i>
            <span class="text">{{ category['description'][actualLocale] }}</span>
            <recursive-categories-dropdown-menu
                    v-if="category.children.length>0"
                    :parent-description="parentDescription + ' > ' + category['description'][actualLocale]"
                    :categories="category.children"
                    :actual-locale="actualLocale"
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
            actualLocale: String,
            withAll: Boolean,
            left: Boolean,
            allItem: String,
            parentType: String
        },
        data: () => {
            return {

            } ;
        },
        mounted () {

        },
        methods: {

        }
    }
</script>
