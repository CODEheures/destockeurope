<template>
    <div>
        <div v-for="category in parentCategories" class="accordion" v-if="category.parent_id==parentId">
            <div class="title">
                <i class="dropdown icon"></i>
                <i class="large blue minus square icon" v-on:click="delCategory" :data-id="category.id"></i>
                <span v-for="locale in availablesDatasLocalesList">
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
            <div class="content">
                <categories-updatable
                        :route-category="routeCategory"
                        :parent-categories="category.children"
                        :availables-locales-list="availablesLocalesList"
                        :meta-category-id="metaCategoryId"
                        :parent-id="category.id">
                </categories-updatable>
            </div>
        </div>
        <div class="ui blue segment">
            <i class="large blue add square icon"
               :data-value="categoryName"
               :data-meta-category-id="metaCategoryId"
               :data-parent-id="parentId"
               v-on:click="addCategory" >
            </i>
            <span v-for="locale in availablesDatasLocalesList">
                        <div class="ui mini labeled input">
                            <div class="ui label">{{ locale }}</div>
                            <input type="text" placeholder="Nouvelle sous-catégorie"
                                   :name="'newCategory_' + locale"
                                   :data-meta-category-id="metaCategoryId"
                                   :data-parent-id="parentId"
                                   :data-key="locale"
                                   v-on:keyup.enter="addCategory"
                                   v-model:value="categoryName[locale]"
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
            routeCategory: String,
            parentCategories: Array,
            availablesLocalesList: String,
            metaCategoryId: Number,
            parentId: Number,
        },
        data: () => {
            return {
                isLoaded: false,
                categoryName: [],
                oldUpdateMetaCategory: '',
                inProgressUpdateCategory: false,
                oldUpdateCategory: '',
                availablesDatasLocalesList: {},
                dataCategories: {},
                focused: {},
                blured: {},
            };
        },
        mounted () {
            this.availablesDatasLocalesList = JSON.parse(this.availablesLocalesList);
            this.isLoaded=true;
            this.$on('addError', function (message) {
                this.$parent.$emit('addError', message);
            });
            this.$on('delError', function (message) {
                this.$parent.$emit('delError', message);
            });
            this.$on('patchError', function (message) {
                this.$parent.$emit('patchError', message);
            });
            this.$on('patchSuccess', function (message) {
                this.$parent.$emit('patchSuccess', message);
            });
            this.$on('getMetaCategories', function (param) {
                this.$parent.$emit('getMetaCategories', param);
            });
            this.$watch('blured', function () {
                if(this.blured.id == this.focused.id && this.blured.locale == this.focused.locale && this.blured.value != this.focused.value) {
                    this.updateCategory();
                }
            });
        },
        methods: {
            addCategory: function (event) {
                if (this.categoryName != undefined) {
                    let isEmpty = true;
                    let postValue = {};
                    for (let category in this.categoryName) {
                        postValue[category] = this.categoryName[category];
                        if (this.categoryName[category] != '') {
                            isEmpty = false;
                        }
                    }

                    if (!isEmpty) {
                        this.isLoaded = false;
                        this.categoryName = [];
                        this.$http.post(this.routeCategory, {descriptions: postValue, metaCategoryId: event.target.dataset.metaCategoryId, parentId: event.target.dataset.parentId})
                                .then(
                                        (response) => {
                                            this.$parent.$emit('getMetaCategories');
                                        },
                                        (response) => {
                                            this.isLoaded = true;
                                            if (response.status == 409) {
                                                this.$parent.$emit('addError', response.body);
                                            } else {
                                                this.$parent.$emit('addError');
                                            }
                                        }
                                );
                    }
                }

            },
            delCategory: function (event) {
                if (event.target.dataset.id != undefined && event.target.dataset.id > 0) {
                    var that = this;
                    $('.ui.basic.modal').modal({
                        closable: false,
                        onApprove: function () {
                            that.isLoaded = false;
                            that.$http.delete(that.routeCategory + '/' + event.target.dataset.id, {})
                                    .then(
                                            (response) => {
                                                that.$parent.$emit('getMetaCategories');
                                            },
                                            (response) => {
                                                that.isLoaded = true;
                                                if (response.status == 409) {
                                                    that.$parent.$emit('delError', response.body);
                                                } else {
                                                    that.$parent.$emit('delError');
                                                }

                                            }
                                    );
                        }
                    }).modal('show');
                }
            },
            updateCategory: function (event) {
                let postValue = {};
                let key ='';
                let id = '';
                if(event == undefined) {
                    id = this.blured.id;
                    key = this.blured.locale;
                    postValue[key] = this.blured.value;
                } else if((event instanceof KeyboardEvent) && event.key=="Enter") {
                    id = event.target.dataset.id;
                    key = event.target.dataset.key;
                    postValue[key] = event.target.value;
                    this.focused.value = event.target.value;
                }
                if(postValue[key] != undefined && postValue[key] != ''){
                    this.$http.patch(this.routeCategory + '/' + id, {description: postValue})
                            .then(
                                    (response) => {
                                        this.$parent.$emit('getMetaCategories', false);
                                        this.$parent.$emit('patchSuccess');
                                    },
                                    (response) => {
                                        this.$parent.$emit('getMetaCategories', false);
                                        if (response.status == 409) {
                                            this.$parent.$emit('patchError', response.body);
                                        } else {
                                            this.$parent.$emit('patchError');
                                        }
                                    }
                            );
                } else {
                    this.$parent.$emit('getMetaCategories', false);
                    this.$parent.$emit('patchError');
                }
            }
        }
    }
</script>
