<template>
    <div id="category-dropdown-menu">
        <div class="ui active inverted dimmer" v-if="!isLoaded">
            <div class="ui large text loader">Loading</div>
        </div>
        <div class="ui dropdown button">
            <input type="hidden" name="categorie">
            <div class="text">{{ firstMenuName }}</div>
            <i class="dropdown icon"></i>
            <div class="menu">
                <div v-for="metaCategory in metaCategories" class="item" >
                    <i class="dropdown icon" v-if="metaCategory.categories.length>0"></i>
                    <span class="text">{{ metaCategory['description'][actualLocale] }}</span>
                    <recursive-categories-dropdown-menu
                            :parent-categories="metaCategory.categories"
                            :actual-locale="actualLocale"
                            :parent-id="parentId">
                    </recursive-categories-dropdown-menu>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: [
            'routeMetaCategory',
            'firstMenuName',
            'actualLocale',
            'oldChoice'
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
                                    this.isLoaded = true;
                                },
                                (response) => {
                                    this.$parent.$emit('loadError');
                                }
                        );
            }
        },
        updated () {
            var that = this;
            if(that.oldChoice != ''){
                $('#category-dropdown-menu .ui.dropdown')
                        .dropdown('set selected',  that.oldChoice)
                        .dropdown({
                            onChange: function(value, text, $selectedItem) {
                                that.$parent.$emit('categoryChoice', {id: value});
                            }
                        })
                ;
            } else {
                $('#category-dropdown-menu .ui.dropdown')
                        .dropdown({
                            onChange: function(value, text, $selectedItem) {
                                that.$parent.$emit('categoryChoice', {id: value});
                            }
                        })
                ;
            }

        }
    }
</script>
