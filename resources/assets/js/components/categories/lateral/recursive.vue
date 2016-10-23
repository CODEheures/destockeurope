<template>
    <div :class="isLastLevel ? 'menu' : 'menu'">
        <a class="item" :data-value="parentId">
            Tous
        </a>
        <template v-for="category in parentCategories" v-if="category.parent_id==parentId">
            <a class="item" v-if="category.children.length==0">
                {{ category['description'][actualLocale] }}
            </a>
            <div class="ui dropdown item lateral other" v-else>
                {{ category['description'][actualLocale] }}
                <i class="dropdown icon"></i>
                <recursive-categories-lateral-menu
                        :parent-categories="category.children"
                        :actual-locale="actualLocale"
                        :parent-id="category.id"
                ></recursive-categories-lateral-menu>
            </div>
        </template>
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
