<template>
    <div class="menu">
        <div v-for="category in parentCategories" class="item"  v-if="category.parent_id==parentId">
            <i class="dropdown icon" v-if="(childsCategories(category)).length>0"></i>
            <span class="text">{{ category['description'][actualLocale] }}</span>
            <recursive-categories-dropdown-menu
                    v-if="(childsCategories(category)).length>0"
                    :parent-categories="childsCategories(category)"
                    :actual-locale="actualLocale"
                    :parent-id="category.id">
            </recursive-categories-dropdown-menu>
        </div>
    </div>
</template>


<script>
    export default {
        props: {
            parentCategories: Array,
            parentId: Number,
            actualLocale: String
        },
        data: () => {
            return {

            } ;
        },
        mounted () {

        },
        methods: {
            childsCategories: function (category) {
                var listCategories=[];
                for(var cats in this.parentCategories){
                    if(this.parentCategories[cats].parent_id == category.id){
                        listCategories.push(this.parentCategories[cats]);
                    }
                }
                return listCategories;
            }
        }
    }
</script>
