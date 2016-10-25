<template>
    <div class="menu">
        <a class="item" :data-value="parentId" v-on:click="emitCategoryChoice(parentId)">{{ allItem }}</a>
        <template v-for="category in categories">
            <a class="item" :data-value="category.id" v-on:click="emitCategoryChoice(category.id)" v-if="category.children.length==0">{{ category['description'][actualLocale] }}</a>
            <div class="ui dropdown item lateral other" :data-value="category.id" v-else>
                {{ category['description'][actualLocale] }}
                <i class="dropdown icon"></i>
                <recursive-categories-lateral-menu
                        :categories="category.children"
                        :actual-locale="actualLocale"
                        :parent-id="category.id"
                        :all-item="allItem"
                ></recursive-categories-lateral-menu>
            </div>
        </template>
    </div>
</template>


<script>
    export default {
        props: {
            categories: Array,
            actualLocale: String,
            allItem: String,
            parentId: Number
        },
        data: () => {
            return {

            } ;
        },
        mounted () {
            this.$on('categoryChoice', function (event) {
                this.$parent.$emit('categoryChoice', {id: event.id});
            });
            var that = this;
            $('.ui.dropdown.lateral.other').dropdown({
                onChange: function(value, text, $selectedItem) {
                    that.$parent.$emit('categoryChoice', {id: value});
                }
            });
        },
        methods: {
            emitCategoryChoice: function (value) {
                this.$parent.$emit('categoryChoice', {id: value});
            }
        }
    }
</script>
