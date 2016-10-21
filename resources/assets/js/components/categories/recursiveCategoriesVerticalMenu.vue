<template>
    <div class="menu">
        <div v-for="category in parentCategories" class="item" v-if="category.parent_id==parentId">
            <div v-for="(description, locale) in category.description" class="item" v-if="locale==actualLocale">
                <div class="header">{{ description }}</div>
                <recursive-categories-vertical-menu
                        :parent-categories="childsCategories(category)"
                        :actual-locale="actualLocale"
                        :parent-id="category.id">
                </recursive-categories-vertical-menu>
            </div>
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
