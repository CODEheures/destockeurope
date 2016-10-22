<template>
    <div :class="isLastLevel ? 'menu' : 'menu'">
        <div v-for="category in parentCategories" :class="(childsCategories(category)).length==0 ? 'item' : 'ui dropdown item lateral other'" v-if="category.parent_id==parentId">
            {{ category['description'][actualLocale] }}
            <i class="dropdown icon" v-if="(childsCategories(category)).length>0"></i>
            <recursive-categories-vertical-menu
                    v-if="(childsCategories(category)).length>0"
                    :parent-categories="childsCategories(category)"
                    :actual-locale="actualLocale"
                    :parent-id="category.id"
            ></recursive-categories-vertical-menu>
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
                isLastLevel: false
            } ;
        },
        mounted () {
            this.setIsLastLevel();
        },
        updated () {
            $('.ui.dropdown.lateral.other').dropdown();
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
            },
            setIsLastLevel: function () {
                var countLevels = 0;
                var levels = [];
                for(var category in this.parentCategories){
                    if(!_.includes(levels, this.parentCategories[category].parent_id)){
                        levels.push(this.parentCategories[category].parent_id)
                    }
                }

                this.isLastLevel = levels.length == 1;
                return null;
            }
        }
    }
</script>
