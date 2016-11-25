<template>
    <div>
        <div class="row breadcrumb">
            <div class="ui breadcrumb">
                <template v-for="(item, index) in items">
                    <template v-if="index==items.length-1">
                        <div class="section active" :data-value="item.value">{{ item.name }}</div>
                    </template>
                    <template v-else>
                        <a class="section" :data-value="item.value" v-on:click="emitCategorieChoice(item.value)">{{ item.name }}</a>
                        <i class="right angle icon divider"></i>
                    </template>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            'items': Array,
            'withAction' : {
                type: Boolean,
                required: false,
                default: false
            }
        },
        data: () => {
            return {

            }
        },
        mounted () {

        },
        methods: {
            emitCategorieChoice: function (categoryId) {
                this.$parent.$emit('categoryChoice', {id: categoryId});
            }
        },
    }
</script>
