<template>
    <div>
        <div class="ui active inverted dimmer" v-if="!isLoaded">
            <div class="ui large text loader">Loading</div>
        </div>
        <div :id="_uid" class="ui floating dropdown" v-show="categories.length>0">
            <div class="text">{{ firstMenuName }}</div>
            <i class="dropdown icon"></i>
            <div class="menu">
                <div v-for="category in categories" class="item" :data-value="category.id">
                    <i class="dropdown icon" v-if="category.children.length>0"></i>
                    <span class="text">{{ category['description'][actualLocale] }}</span>
                    <recursive-categories-list-move-to
                            :categories="category.children"
                            :actual-locale="actualLocale">
                    </recursive-categories-list-move-to>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: {
            routeGetAvailableMoveToCategory: String,
            routeParam: Number,
            firstMenuName: String,
            actualLocale: String,
            flagRefresh: Boolean
        },
        data: () => {
            return {
                categories: [],
                isLoaded: false,
            } ;
        },
        mounted () {
            this.getCategories();
            this.$watch('flagRefresh', function () {
                this.getCategories();
            })
        },
        methods: {
            getCategories: function (withLoadIndicator) {
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                var route = this.routeGetAvailableMoveToCategory + '/' + this.routeParam;
                this.$http.get(route)
                        .then(
                                function (response) {
                                    this.categories = response.data;
                                    this.isLoaded = true;
                                },
                                function (response) {
                                    this.$parent.$emit('loadError');
                                    this.isLoaded = true;
                                }
                        );
            }
        },
        updated () {
            var that = this;
            let dropdown = $('#'+that._uid);
            dropdown.on('click', function () {
               $(this).closest('.accordion').css({'z-index':'2'});
            });
            dropdown.dropdown({
                        allowCategorySelection: true,
                        action: 'select',
                        onChange: function(value, text, $selectedItem) {
                            if(value != undefined && value != ''){
                                $(this).closest('.accordion').css({'z-index':''});
                                if(!that.routeParam){
                                    that.$parent.$emit('categoryChoice', {id: value});
                                } else {
                                    that.$parent.$emit('categoryChoice', {parentId: value, id: that.routeParam});
                                }
                            }
                        }
                    })
            ;
        }
    }
</script>
