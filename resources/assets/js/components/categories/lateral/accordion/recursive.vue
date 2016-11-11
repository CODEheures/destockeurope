<template>
    <div>
        <div class="item">
            <a class="title" :data-value="parentId" v-on:click="emitCategoryChoice(parentId)">
                {{ allItem }}
            </a>
        </div>
        <template v-for="(category,index) in categories">
            <div class="item" v-if="category.children.length==0">
                <a class="title" :data-value="category.id" v-on:click="emitCategoryChoice(category.id)">
                    {{ category['description'][actualLocale] }}
                </a>
            </div>
            <div :id="_uid+'-'+index" class="ui dropdown lateral item" v-else>
                <i class="dropdown icon"></i>
                {{ category['description'][actualLocale] }}
                <div class="menu">
                    <recursive-categories-lateral-accordion-menu
                            :categories="category.children"
                            :actual-locale="actualLocale"
                            :parent-id="category.id"
                            :all-item="allItem"
                    ></recursive-categories-lateral-accordion-menu>
                </div>
            </div>
        </template>
    </div>
</template>


<script>
    export default {
        props: {
            categories: Array,
            actualLocale: String,
            parentId: Number,
            allItem: String
        },
        data: () => {
            return {

            } ;
        },
        mounted () {
            this.$on('categoryChoice', function (event) {
                this.$parent.$emit('categoryChoice', {id: event.id});
            });
            for(var index in this.categories){
                $('#'+this._uid+'-'+index).dropdown();
            }
        },
        methods: {
            emitCategoryChoice: function (value) {
                this.$parent.$emit('categoryChoice', {id: value});
            }
        }
    }
</script>
