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
            <div id="metaCategory-accordion" class="ui fluid styled accordion">
                <div class="ui active inverted dimmer" v-if="!isLoaded">
                    <div class="ui large text loader">Loading</div>
                </div>
                <div class="active title">
                    <i class="dropdown icon"></i>
                </div>
                <div class="active content">
                    <div v-for="metaCategory in metaCategories" class="accordion">
                        <div class="title">
                            <i class="dropdown icon"></i>
                            <i class="big teal minus square icon" v-on:click="delMetaCategory"
                               :data-id="metaCategory.id"></i>
                            <span v-for="locale in availablesDatasLocalesList">
                                <div class="ui labeled input">
                                    <div class="ui label">{{ locale }}</div>
                                    <input type="text"
                                           :name="metaCategory.id + '_' + locale"
                                           :data-id="metaCategory.id"
                                           :data-key="locale"
                                           v-model="metaCategory['description'][locale]"
                                           v-on:keyup.enter="updateMetaCategory"
                                           v-on:focus="focused={'id': metaCategory.id, 'locale': locale, 'value': metaCategory['description'][locale]}"
                                           v-on:blur="blured={'id': metaCategory.id, 'locale': locale, 'value': metaCategory['description'][locale]}" />
                                </div>
                            </span>
                        </div>
                        <div class="content">
                            <categories-updatable
                                    :route-category="routeCategory"
                                    :parent-categories="metaCategory.categories"
                                    :availables-locales-list="availablesLocalesList"
                                    :meta-category-id="metaCategory.id"
                                    :parent-id="parentId">
                            </categories-updatable>
                        </div>
                    </div>
                    <div class="ui teal segment">
                        <i class="big teal add square icon" v-on:click="addMetaCategory"
                           :data-value="metaCategoryName"></i>
                        <span v-for="locale in availablesDatasLocalesList">
                            <div class="ui labeled input">
                                <div class="ui mini label">{{ locale }}</div>
                                <input type="text" placeholder="Nouvelle Catégorie"
                                       :name="'newMetaCategory_' + locale"
                                       :data-key="locale"
                                       v-on:keyup.enter="addMetaCategory"
                                       v-model:value="metaCategoryName[locale]"
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
            routeMetaCategory: String,
            routeCategory: String,
            availablesLocalesList: String
        },
        data: () => {
            return {
                metaCategories: [],
                isLoaded: false,
                metaCategoryName: [],
                sendMessage: false,
                typeMessage: '',
                message: '',
                availablesDatasLocalesList: {},
                parentId: 0,
                focused: {},
                blured: {}
            };
        },
        mounted () {
            this.getMetaCategories();
            this.availablesDatasLocalesList = JSON.parse(this.availablesLocalesList);
            $('.accordion').each(function () {
                $(this).accordion({
                    selector: {
                        trigger: '.title .icon'
                    }
                });
            });
            this.$on('getMetaCategories', function (param) {
                this.getMetaCategories(param);
            });
            this.$on('addError', function ($message) {
                if($message != undefined && $message != ''){
                    this.sendToast($message, 'error');
                } else {
                    this.sendToast(this.addErrorMessage, 'error');
                }
            });
            this.$on('delError', function ($message) {
                if($message != undefined && $message != ''){
                    this.sendToast($message, 'error');
                } else {
                    this.sendToast(this.delErrorMessage, 'error');
                }
            });
            this.$on('patchError', function ($message) {
                if($message != undefined && $message != ''){
                    this.sendToast($message, 'error');
                } else {
                    this.sendToast(this.patchErrorMessage, 'error');
                }
            });
            this.$on('patchSuccess', function ($message) {
                if($message != undefined && $message != ''){
                    this.sendToast($message, 'success');
                } else {
                    this.sendToast(this.patchSuccessMessage, 'success');
                }
            });
            this.$watch('blured', function () {
                if(this.blured.id == this.focused.id && this.blured.locale == this.focused.locale && this.blured.value != this.focused.value) {
                    this.updateMetaCategory();
                }
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
                                    this.isLoaded = true;
                                },
                                (response) => {
                                    this.sendToast(this.loadErrorMessage, 'error');
                                }
                        );
            },
            addMetaCategory: function (event) {
                if (this.metaCategoryName != undefined) {
                    let isEmpty = true;
                    let postValue = {};
                    for (let metaCategory in this.metaCategoryName) {
                        postValue[metaCategory] = this.metaCategoryName[metaCategory];
                        if (this.metaCategoryName[metaCategory] != '') {
                            isEmpty = false;
                        }
                    }

                    if (!isEmpty) {
                        this.isLoaded = false;
                        this.metaCategoryName = [];
                        this.$http.post(this.routeMetaCategory, {descriptions: postValue})
                                .then(
                                        (response) => {
                                            this.getMetaCategories();
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
                }
            },
            delMetaCategory: function (event) {
                if (event.target.dataset.id != undefined && event.target.dataset.id > 0) {
                    var that = this;
                    $('.ui.basic.modal').modal({
                        closable: false,
                        onApprove: function () {
                            that.isLoaded = false;
                            that.$http.delete(that.routeMetaCategory + '/' + event.target.dataset.id, {})
                                    .then(
                                            (response) => {
                                                that.getMetaCategories();
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
            updateMetaCategory: function (event) {
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
                    this.$http.patch(this.routeMetaCategory + '/' + id, {description: postValue})
                            .then(
                                    (response) => {
                                        this.getMetaCategories(false);
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
                    this.getMetaCategories(false);
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
