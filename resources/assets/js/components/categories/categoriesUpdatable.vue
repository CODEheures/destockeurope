<template>
    <div>
        <div v-for="(category,index) in categories" class="accordion">
            <div class="title flex">
                <div>
                    <i class="dropdown icon"></i>
                    <i :class="'large ' + colors[colorNumber%colors.length] + ' minus square icon'" v-on:click="delCategory" :data-id="category.id" v-if="category.canBeDeleted"></i>
                    <i class="big ban icon" v-else></i>
                    <span v-for="locale in properties.availableLocalesList">
                    <div class="ui mini labeled input">
                        <div class="ui label">{{ locale }}</div>
                        <input type="text"
                               :name="category.id + '_' + locale"
                               :data-id="category.id"
                               :data-key="locale"
                               v-model="category['description'][locale]"
                               v-on:keyup.enter="updateCategory"
                               v-on:focus="focused={'id': category.id, 'locale': locale, 'value': category['description'][locale]}"
                               v-on:blur="blured={'id': category.id, 'locale': locale, 'value': category['description'][locale]}"
                        />
                    </div>
                </span>
                </div>
                <div class="change-category">
                    <span>
                        <categories-list-move-to
                                :category="category"
                        ></categories-list-move-to>
                    </span>
                    <span class="drag-category" :data-value="category.id" v-if="categories.length>1">
                        <template v-if="index==0">
                            <i class="large toggle down icon" :data-value="category.id" v-on:click="shiftDown"></i>
                        </template>
                        <template v-if="index==categories.length-1">
                            <i class="large toggle up icon" :data-value="category.id" v-on:click="shiftUp"></i>
                        </template>
                        <template v-if="index!=0 && index!=categories.length-1">
                            <i class="large toggle down icon" :data-value="category.id" v-on:click="shiftDown"></i>
                            <i class="large toggle up icon" :data-value="category.id" v-on:click="shiftUp"></i>
                        </template>
                    </span>
                </div>

            </div>
            <div class="content">
                <categories-updatable
                        :categories="category.children"
                        :parent-id="category.id"
                        :color-number="colorNumber+1">
                </categories-updatable>
            </div>
        </div>
        <div :class="'ui ' + colors[colorNumber%colors.length] + ' segment'">
            <i :class="'large ' + colors[colorNumber%colors.length] + ' add square icon'"
               :data-parent-id="parentId"
               v-on:click="addCategory">
            </i>
            <span v-for="locale in properties.availableLocalesList">
                        <div class="ui mini labeled input">
                            <div class="ui label">{{ locale }}</div>
                            <input type="text" placeholder="Nouvelle sous-catégorie"
                                   :name="'newCategory-' + _uid + '_' + locale"
                                   :data-parent-id="parentId"
                                   :data-key="locale"
                                   v-on:keyup.enter="addCategory"
                                   v-model="categoryName[locale]"
                                   v-on:focus="focused={}"
                                   v-on:blur="blured={}"
                            />
                        </div>
                    </span>
        </div>
    </div>
</template>


<script>
    export default {
        props: {
            categories: Array,
            parentId: Number,
            colorNumber: {
                type: Number,
                required: false,
                default: 0
            }
        },
        data: () => {
            return {
                properties: {},
                isLoaded: false,
                categoryName: {},
                focused: {},
                blured: {},
                colors: [
                        'violet',
                        'purple',
                        'pink',
                        'brown',
                        'grey',
                        'black',
                        'red',
                        'orange',
                        'yellow',
                        'olive',
                        'green',
                        'teal',
                        'blue',
                ]
            };
        },
        mounted () {
            this.properties = this.$store.state.properties['global'];
            this.$on('categoryChoice', function (event) {
                this.$parent.$emit('categoryChoice', event);
            });
            this.$on('getCategories', function (withLoadIndicator) {
                this.$parent.$emit('getCategories', withLoadIndicator);
            });
            this.$on('addCategory', function (postValue) {
                this.$parent.$emit('addCategory', postValue);
            });
            this.$on('delCategory', function (id) {
                this.$parent.$emit('delCategory', id);
            });
            this.$on('updateCategory', function (postValue) {
                this.$parent.$emit('updateCategory', postValue);
            });
            this.$on('shiftDown', function (event) {
                this.$parent.$emit('shiftDown', event);
            });
            this.$on('shiftUp', function (event) {
                this.$parent.$emit('shiftUp', event);
            });
            this.$on('patchError', function (message) {
                this.$parent.$emit('patchError', message);
            });
            this.$watch('blured', function () {
                if (this.blured.id == this.focused.id && this.blured.locale == this.focused.locale && this.blured.value != this.focused.value) {
                    this.updateCategory();
                }
            });
        },
        methods: {
            addCategory: function (event) {
                if (this.categoryName != undefined) {
                    let isEmpty = true;
                    let postValue = {};
                    postValue['descriptions'] = {};
                    postValue['parentId'] = this.parentId;
                    for (let category in this.categoryName) {
                        postValue['descriptions'][category] = this.categoryName[category];
                        if (this.categoryName[category] != '') {
                            isEmpty = false;
                        }
                    }
                    if (!isEmpty) {
                        this.isLoaded = false;
                        this.categoryName = {};
                        this.$parent.$emit('addCategory', postValue);
                    }
                }

            },
            delCategory: function (event) {
                if (event.target.dataset.id != undefined && event.target.dataset.id > 0) {
                    this.$parent.$emit('delCategory', event.target.dataset.id);
                }
            },
            updateCategory: function (event) {
                let postValue = {};
                let key = '';
                let id = '';
                if (event == undefined) {
                    id = this.blured.id;
                    key = this.blured.locale;
                    postValue[key] = this.blured.value;
                } else if ((event instanceof KeyboardEvent) && event.key == "Enter") {
                    id = event.target.dataset.id;
                    key = event.target.dataset.key;
                    postValue[key] = event.target.value;
                    this.focused.value = event.target.value;
                }
                if (postValue[key] != undefined && postValue[key] != '') {
                    this.$parent.$emit('updateCategory', {postValue: postValue, key: key, id: id});
                } else {
                    this.$parent.$emit('getCategories', false);
                    this.$parent.$emit('patchError');
                }
            },
            shiftDown: function (event) {
                this.$parent.$emit('shiftDown', event);
            },
            shiftUp: function (event) {
                this.$parent.$emit('shiftUp', event);
            }
        }
    }
</script>
