<template>
    <div>
        <div :id="_uid" class="ui floating dropdown" v-show="category.availableMoveTo.length>0">
            <div class="text">{{ strings.firstMenuName }}</div>
            <i class="dropdown icon"></i>
            <div class="menu">
                <div v-for="category in category.availableMoveTo" class="item" :data-value="category.id">
                    <i class="dropdown icon" v-if="category.children.length>0"></i>
                    <span class="text">{{ category['description'][properties.actualLocale] }}</span>
                    <recursive-categories-list-move-to
                            :categories="category.children"
                    ></recursive-categories-list-move-to>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: {
            category: Object,
        },
        data: () => {
            return {
                strings: {},
                properties: {},
            } ;
        },
        mounted () {
            this.strings = this.$store.state.strings['categories-list-move-to'];
            this.properties = this.$store.state.properties['global'];
        },
        methods: {

        },
        updated () {
            let that = this;
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
                                that.$parent.$emit('categoryChoice', {parentId: value, id: that.category.id});
                            }
                        }
                    })
            ;
        }
    }
</script>
