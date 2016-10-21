<template>
    <div id="category-dropdown-menu">
        <div class="ui dropdown button">
            <div class="ui active inverted dimmer" v-if="!isLoaded">
                <div class="ui large text loader">Loading</div>
            </div>
            <span class="text">{{ firstMenuName }}</span>
            <i class="dropdown icon"></i>
            <div class="menu">
                <div v-for="metaCategory in metaCategories" class="item">
                    <span class="text">{{ metaCategory.title }}</span>
                    <i class="dropdown icon"></i>
                    <div class="menu">
                        <div v-for="category in metaCategory.categories" class="item" :data-value="category.id">
                            {{ metaCategory.title}} {{ category.title }}
                        </div>
                    </div>
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
            'oldChoice'
        ],
        data: () => {
            return {
                metaCategories: [],
                isLoaded: false
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
