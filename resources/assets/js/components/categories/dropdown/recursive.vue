<template>
    <div :class="left ? 'left menu' : 'menu'">
        <div class="item" :data-value="parentId" v-if="withAll">
            <span class="text">Tous</span>
        </div>
        <div v-for="category in parentCategories" class="item" :data-value="category.id"  v-if="category.parent_id==parentId">
            <i :class="left ? 'right dropdown icon' : 'left dropdown icon'" v-if="category.children.length>0"></i>
            <span class="text">{{ category['description'][actualLocale] }}</span>
            <recursive-categories-dropdown-menu
                    v-if="category.children.length>0"
                    :parent-categories="category.children"
                    :actual-locale="actualLocale"
                    :parent-id="category.id"
                    :with-all="withAll"
                    :level="level+1"
                    :left="!left">
            </recursive-categories-dropdown-menu>
        </div>
    </div>
</template>


<script>
    export default {
        props: {
            parentCategories: Array,
            parentId: Number,
            actualLocale: String,
            withAll: Boolean,
            left: Boolean
        },
        data: () => {
            return {

            } ;
        },
        mounted () {
            console.log(this.left);
        },
        methods: {

        }
    }
</script>
