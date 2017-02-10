<template>
    <div class="menu">
        <a class="item" :data-value="parentId" v-on:click="emitCategoryChoice(parentId)">{{ allItem }}</a>
        <template v-for="(category,index) in categories">
            <a class="item" :data-value="category.id" v-on:click="emitCategoryChoice(category.id)" v-if="category.children.length==0">{{ category['description'][actualLocale] }}</a>
            <div :id="_uid+'-'+index" class="ui left pointing dropdown link item" :data-value="category.id" v-else>
                <i class="dropdown icon"></i>
                {{ category['description'][actualLocale] }}
                <recursive-categories-lateral-vertical-menu
                        :categories="category.children"
                        :actual-locale="actualLocale"
                        :parent-id="category.id"
                        :all-item="allItem"
                ></recursive-categories-lateral-vertical-menu>
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
            let that = this;
            this.$on('categoryChoice', function (event) {
                this.$parent.$emit('categoryChoice', {id: event.id});
            });
            (this.categories).forEach(function (elem, index) {
                $('#'+that._uid+'-'+index).dropdown();
            });
        },
        methods: {
            emitCategoryChoice: function (value) {
                this.$parent.$emit('categoryChoice', {id: value});
            }
        }
    }
</script>
