<template>
    <div id="category-dropdown-menu">
        <div class="ui active inverted dimmer" v-if="!isLoaded">
            <div class="ui large text loader">Loading</div>
        </div>
        <div class="ui floating dropdown button">
            <div class="text">{{ firstMenuName }}</div>
            <i class="dropdown icon"></i>
            <div class="menu">
                <div class="item" v-if="withAll">
                    {{ allItem }}
                </div>
                <div v-for="metaCategory in metaCategories" class="item" >
                    <i class="dropdown icon" v-if="metaCategory.categories.length>0"></i>
                    <span class="text">{{ metaCategory['description'][actualLocale] }}</span>
                    <recursive-categories-dropdown-menu
                            :parent-description="metaCategory['description'][actualLocale]"
                            :parent-categories="metaCategory.categories"
                            parent-type="metaCategory"
                            :actual-locale="actualLocale"
                            :parent-id="metaCategory.id"
                            :with-all="withAll"
                            :all-item="allItem"
                            :left="false">
                    </recursive-categories-dropdown-menu>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: {
            routeMetaCategory: String,
            firstMenuName: String,
            actualLocale: String,
            oldChoice: String,
            withAll: Boolean,
            allItem: String
        },
        data: () => {
            return {
                metaCategories: [],
                isLoaded: false,
                parentId: 0
            } ;
        },
        mounted () {
            this.getMetaCategories();
            this.$on('categoryChoice', function (event) {
                this.$parent.$emit('categoryChoice', {id: event.id});
            });
            this.$on('metaCategoryChoice', function (event) {
                this.$parent.$emit('metaCategoryChoice', {id: event.id});
            });
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
        },
        updated () {
            var that = this;
            if(that.oldChoice != ''){
                $('#category-dropdown-menu .ui.dropdown')
                        .dropdown('set selected',  that.oldChoice)
                        .dropdown({
                            onChange: function(value, text, $selectedItem) {
                                var type = value.split('_')[0];
                                var val = value.split('_')[1];
                                if(type == 'category') {
                                    that.$parent.$emit('categoryChoice', {id: val});
                                } else if(type=='metaCategory'){
                                    that.$parent.$emit('metaCategoryChoice', {id: val});
                                }
                            }
                        })
                ;
            } else {
                $('#category-dropdown-menu .ui.dropdown')
                        .dropdown({
                            onChange: function(value, text, $selectedItem) {
                                var type = value.split('_')[0];
                                var val = value.split('_')[1];
                                if(type == 'category') {
                                    that.$parent.$emit('categoryChoice', {id: val});
                                } else if(type=='metaCategory'){
                                    that.$parent.$emit('metaCategoryChoice', {id: val});
                                }
                            }
                        })
                ;
            }

        }
    }
</script>
