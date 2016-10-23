<template>
    <div id="category-vertical-menu">
        <div class="ui active inverted dimmer" v-if="!isLoaded">
            <div class="ui large text loader">Loading</div>
        </div>
        <div class="ui vertical menu">
            <a class="item">
                Tous
            </a>
            <div v-for="metaCategory in metaCategories" class="item">
                {{ metaCategory['description'][actualLocale] }}
                <recursive-categories-lateral-menu
                        :parent-categories="metaCategory.categories"
                        :actual-locale="actualLocale"
                        :parent-id="parentId"
                ></recursive-categories-lateral-menu>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: [
            'routeMetaCategory',
            'actualLocale'
        ],
        data: () => {
            return {
                metaCategories: [],
                isLoaded: false,
                parentId: 0
            } ;
        },
        mounted () {
            this.getMetaCategories();
        },
        methods: {
            getMetaCategories: function (withLoadIndicator) {
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                this.$http.get(this.routeMetaCategory)
                        .then(
                                (response) => {
                                    this.metaCategories = response.data;
                                    this.setChildsCategories();
                                    this.isLoaded = true;
                                },
                                (response) => {
                                    this.$parent.$emit('loadError');
                                }
                        );
            },
            setChildsCategories: function () {
                for(var index in this.metaCategories){
                    let metaCategory =  this.metaCategories[index];
                    for(var index2 in metaCategory.categories) {
                        let category = metaCategory.categories[index2];
                        category.children=[];
                        for(var index3 in metaCategory.categories){
                            if(metaCategory.categories[index3].parent_id==category.id){
                                category.children.push(metaCategory.categories[index3]);
                            }
                        }
                    }
                }
            }
        }
    }
</script>
