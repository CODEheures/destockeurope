<template>
    <div style="display: inline-block">
        <div class="ui grid breadcrumb">
            <div class="sixteen wide tablet sixteen wide computer only column">
                <div class="ui big breadcrumb">
                    <template v-for="(item, index) in items">
                        <template v-if="index==items.length-1">
                            <!--<div class="section active" :data-value="item.value">{{ item.name }}</div>-->
                            <a class="section" :data-value="item.value" v-on:click="emitCategorieChoice(item.value)">{{ item.name }}</a>
                        </template>
                        <template v-else>
                            <template v-if="withAction">
                                <a class="section" :data-value="item.value" v-on:click="emitCategorieChoice(item.value)">{{ item.name }}</a>
                            </template>
                            <template v-else>
                                <a class="section" :data-value="item.value" v-on:click.preventdefault="">{{ item.name }}</a>
                            </template>
                            <i class="right angle icon divider"></i>
                        </template>
                    </template>
                </div>
            </div>
            <div class="sixteen wide mobile only column">
                <div class="ui breadcrumb">
                    <template v-for="(item, index) in items">
                        <template v-if="index==items.length-1">
                            <!--<div class="section active" :data-value="item.value">{{ item.name }}</div>-->
                            <a class="section" :data-value="item.value" v-on:click="emitCategorieChoice(item.value)">{{ item.name }}</a>
                        </template>
                        <template v-else>
                            <template v-if="withAction">
                                <a class="section" :data-value="item.value" v-on:click="emitCategorieChoice(item.value)">{{ item.name }}</a>
                            </template>
                            <template v-else>
                                <a class="section" :data-value="item.value" v-on:click.preventdefault="">{{ item.name }}</a>
                            </template>
                            <i class="right angle icon divider"></i>
                        </template>
                    </template>
                </div>
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
