<template>
    <div :class="level==1 ? 'ui flowing basic admission popup' : 'ui link list'">
        <template v-if="level==1">
            <div :class="'ui ' + numberToWord(categories.length) +' column relaxed equal height divided grid'">
                <template v-for="(category,index) in categories">
                    <div class="column">
                        <h4 class="ui header">{{ category['description'][actualLocale] }}</h4>
                        <recursive-categories-horizontal-menu
                                :categories="category.children"
                                :actual-locale="actualLocale"
                                :parent-id="category.id"
                                :all-item="allItem"
                                :level="level+1"
                        ></recursive-categories-horizontal-menu>
                    </div>
                </template>
            </div>
        </template>
        <template v-if="level>1">
            <a class="item" :data-value="parentId" v-on:click="emitCategoryChoice(parentId)">{{ allItem }}</a>
            <template v-for="(category,index) in categories">
                <a class="item" :data-value="category.id" v-on:click="emitCategoryChoice(category.id)" v-if="category.children.length==0">{{ category['description'][actualLocale] }}</a>
            </template>
        </template>
    </div>
</template>


<script>
    export default {
        props: {
            categories: Array,
            actualLocale: String,
            allItem: String,
            parentId: Number,
            level: Number
        },
        data: () => {
            return {

            } ;
        },
        mounted () {
            let that=this;
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
            },
            numberToWord: function (num) {
                var a = ['', 'one','two','three','four', 'five','six','seven','eight','nine','ten','eleven','twelve','thirteen','fourteen','fifteen','sixteen'];
                return a[num];
            }
        }
    }
</script>
