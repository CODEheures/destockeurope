<template>
    <div class="ui one column grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div class="ui basic modal">
            <i class="close icon"></i>
            <div class="header">
                {{ modalDelHeader }}
            </div>
            <div class="image content">
                <div class="image">
                    <i class="trash icon"></i>
                </div>
                <div class="description">
                    <p>{{ modalDelDescription }}</p>
                </div>
            </div>
            <div class="actions">
                <div class="two fluid ui inverted buttons">
                    <div class="ui cancel red basic inverted button">
                        <i class="remove icon"></i>
                        {{ modalNo }}
                    </div>
                    <div class="ui ok green basic inverted button">
                        <i class="checkmark icon"></i>
                        {{ modalYes }}
                    </div>
                </div>
            </div>
        </div>
        <div class="column">
            <h2 class="ui header">{{ contentHeader }}</h2>
        </div>
        <div class="column">
            <div id="category-accordion" class="ui fluid styled accordion">
                <div class="ui active inverted dimmer" v-if="!isLoaded">
                    <div class="ui large text loader">Loading</div>
                </div>
                <div class="active title">
                    <i class="dropdown icon"></i>
                </div>
                <div class="active content">
                    <div v-for="category in categories" class="accordion">
                        <div class="title">
                            <i class="dropdown icon"></i>
                            <i class="big teal minus square icon" v-on:click="delCategory"
                               :data-id="category.id"></i>
                            <span v-for="locale in availablesDatasLocalesList">
                                <div class="ui labeled input">
                                    <div class="ui label">{{ locale }}</div>
                                    <input type="text"
                                           :name="category.id + '_' + locale"
                                           :data-id="category.id"
                                           :data-key="locale"
                                           v-model="category['description'][locale]"
                                           v-on:keyup.enter="updateCategory"
                                           v-on:focus="focused={'id': category.id, 'locale': locale, 'value': category['description'][locale]}"
                                           v-on:blur="blured={'id': category.id, 'locale': locale, 'value': category['description'][locale]}" />
                                </div>
                            </span>
                        </div>
                        <div class="content">
                            <categories-updatable
                                    :categories="category.children"
                                    :availables-locales-list="availablesLocalesList"
                                    :parent-id="category.id">
                            </categories-updatable>
                        </div>
                    </div>
                    <div class="ui teal segment">
                        <i class="big teal add square icon" v-on:click="addCategory"
                           :data-value="categoryName"></i>
                        <span v-for="locale in availablesDatasLocalesList">
                            <div class="ui labeled input">
                                <div class="ui mini label">{{ locale }}</div>
                                <input type="text" placeholder="Nouvelle Catégorie"
                                       :name="'newCategory_' + locale"
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
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        directives: {focus: focus},
        props: {
            contentHeader: String,
            loadErrorMessage: String,
            addErrorMessage: String,
            delErrorMessage: String,
            patchErrorMessage: String,
            patchSuccessMessage: String,
            modalYes: String,
            modalNo: String,
            modalDelHeader: String,
            modalDelDescription: String,
            routeCategory: String,
            availablesLocalesList: String
        },
        data: () => {
            return {
                isLoaded: false,
                sendMessage: false,
                typeMessage: '',
                message: '',
                availablesDatasLocalesList: {},
                focused: {},
                blured: {},
                categories: {},
                categoryName: []
            };
        },
        mounted () {
            this.getCategories();
            this.availablesDatasLocalesList = JSON.parse(this.availablesLocalesList);
            $('.accordion').each(function () {
                $(this).accordion({
                    selector: {
                        trigger: '.title .icon'
                    }
                });
            });
            this.$on('getCategories', function (withLoadIndicator) {
                this.getCategories(withLoadIndicator);
            });
            this.$on('addCategory', function (postValue) {
               this.addCategory(null, postValue);
            });
            this.$on('delCategory', function (id) {
                this.delCategory(null, id);
            });
            this.$on('updateCategory', function (postValue) {
                this.updateCategory(null, postValue);
            });
            this.$on('patchError', function ($message) {
                if($message != undefined && $message != ''){
                    this.sendToast($message, 'error');
                } else {
                    this.sendToast(this.patchErrorMessage, 'error');
                }
            });
            this.$watch('blured', function () {
                if(this.blured.id == this.focused.id && this.blured.locale == this.focused.locale && this.blured.value != this.focused.value) {
                    this.updateCategory();
                }
            });
        },
        methods: {
            getCategories: function (withLoadIndicator) {
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                this.$http.get(this.routeCategory)
                        .then(
                                (response) => {
                                    this.categories = response.data;
                                    this.isLoaded = true;
                                },
                                (response) => {
                                    this.sendToast(this.loadErrorMessage, 'error');
                                }
                        );
            },
            addCategory: function (event, emitPostValue) {
                let isEmpty = true;
                let postValue = {};
                if(emitPostValue != undefined){
                    postValue = emitPostValue;
                    isEmpty = false;
                } else if (this.categoryName != undefined) {
                    postValue['descriptions']={};
                    for (let lang in this.categoryName) {
                        postValue['descriptions'][lang] = this.categoryName[lang];
                        if (this.categoryName[lang] != '') {
                            isEmpty = false;
                        }
                    }

                }
                if (!isEmpty) {
                    this.isLoaded = false;
                    this.categoryName = [];
                    this.$http.post(this.routeCategory, postValue)
                            .then(
                                    (response) => {
                                        this.getCategories();
                                    },
                                    (response) => {
                                        this.isLoaded = true;
                                        if (response.status == 409) {
                                            this.sendToast(response.body, 'error');
                                        } else {
                                            this.sendToast(this.addErrorMessage, 'error');
                                        }
                                    }
                            );
                }
            },
            delCategory: function (event, id) {
                var categoryId = undefined;
                if (event != undefined && event.target.dataset.id != undefined && event.target.dataset.id > 0) {
                    categoryId = event.target.dataset.id;
                } else if (id != undefined && id > 0) {
                    categoryId = id;
                }

                if(categoryId != undefined && categoryId > 0) {
                    var that = this;
                    $('.ui.basic.modal').modal({
                        closable: false,
                        onApprove: function () {
                            that.isLoaded = false;
                            that.$http.delete(that.routeCategory + '/' + categoryId)
                                    .then(
                                            (response) => {
                                                that.getCategories();
                                            },
                                            (response) => {
                                                that.isLoaded = true;
                                                if (response.status == 409) {
                                                    that.sendToast(response.body, 'error');
                                                } else {
                                                    that.sendToast(that.delErrorMessage, 'error');
                                                }
                                            }
                                    );
                        }
                    }).modal('show');
                }
            },
            updateCategory: function (event, emitPostValue) {
                let postValue = {};
                let key ='';
                let id = '';
                if(emitPostValue != undefined) {
                    postValue = emitPostValue.postValue;
                    key = emitPostValue.key;
                    id = emitPostValue.id;
                } else {
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
                }
                if(postValue[key] != undefined && postValue[key] != ''){
                    this.$http.patch(this.routeCategory + '/' + id, {description: postValue})
                            .then(
                                    (response) => {
                                        this.getCategories(false);
                                        this.sendToast(this.patchSuccessMessage, 'success');
                                    },
                                    (response) => {
                                        this.getMetaCategories(false);
                                        if (response.status == 409) {
                                            this.sendToast(response.body, 'error');
                                        } else {
                                            this.sendToast(this.patchErrorMessage, 'error');
                                        }
                                    }
                            );
                } else {
                    this.getCategories(false);
                    this.sendToast(this.patchErrorMessage, 'error');
                }
            },
            sendToast: function (message, type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            }
        }
    }
</script>
