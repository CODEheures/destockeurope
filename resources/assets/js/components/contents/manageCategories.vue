<template>
    <div class="ui one column grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div :id="'modal-'+_uid" class="ui basic modal">
            <i class="close icon"></i>
            <div class="header">
                {{ strings.modalDelHeader }}
            </div>
            <div class="image content">
                <div class="image">
                    <i class="trash icon"></i>
                </div>
                <div class="description">
                    <p>{{ strings.modalDelDescription }}</p>
                </div>
            </div>
            <div class="actions">
                <div class="two fluid ui inverted buttons">
                    <div class="ui cancel red basic inverted button">
                        <i class="remove icon"></i>
                        {{ strings.modalNo }}
                    </div>
                    <div class="ui ok green basic inverted button">
                        <i class="checkmark icon"></i>
                        {{ strings.modalYes }}
                    </div>
                </div>
            </div>
        </div>
        <div class="column">
            <h2 class="ui header">{{ strings.contentHeader }}</h2>
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
                    <div v-for="(category,index) in categories" :id="'accordion-'+_uid+'-'+index" class="accordion">
                        <div class="title flex">
                            <div>
                                <i class="dropdown icon"></i>
                                <i class="big blue minus square icon" v-on:click="delCategory"
                                   :data-id="category.id" v-if="category.canBeDeleted"></i>
                                <i class="big ban icon" v-else></i>
                                <span v-for="locale in properties.availableLocalesList">
                                    <div class="ui labeled input">
                                        <div class="ui label">{{ locale }}</div>
                                        <input type="text"
                                               :name="category.id + '_' + locale"
                                               :data-id="category.id"
                                               :data-key="locale"
                                               v-model="category['description'][locale]"
                                               v-on:keyup.enter="updateCategory"
                                               v-on:focus="focused={'id': category.id, 'locale': locale, 'value': category['description'][locale]}"
                                               v-on:blur="blured={'id': category.id, 'locale': locale, 'value': category['description'][locale]}"/>
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
                            ></categories-updatable>
                        </div>
                    </div>
                    <div class="ui blue segment">
                        <i class="big blue add square icon" v-on:click="addCategory"></i>
                        <span v-for="locale in properties.availableLocalesList">
                            <div class="ui labeled input">
                                <div class="ui mini label">{{ locale }}</div>
                                <input type="text" placeholder="Nouvelle Catégorie"
                                       :name="'newCategory-' + _uid + '_' + locale"
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
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        directives: {focus: focus},
        props: {
            //vue routes
            routeShiftUpCategory: String,
            routeShiftDownCategory: String,
            routeAppendToCategory: String,
        },
        data: () => {
            return {
                strings: {},
                properties: {},
                isLoaded: false,
                sendMessage: false,
                typeMessage: '',
                message: '',
                focused: {},
                blured: {},
                categories: {},
                categoryName: {},
            };
        },
        mounted () {
            this.strings = this.$store.state.strings['manage-categories'];
            this.properties = this.$store.state.properties['global'];
            this.getCategories();
            this.$on('categoryChoice', function (event) {
                this.appendToCategory(event.id, event.parentId);
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
            this.$on('shiftDown', function (event) {
                this.shiftCategory(event, 'down');
            });
            this.$on('shiftUp', function (event) {
                this.shiftCategory(event, 'up');
            });
            this.$on('patchError', function ($message) {
                if ($message != undefined && $message != '') {
                    this.sendToast($message, 'error');
                } else {
                    this.sendToast(this.strings.patchErrorMessage, 'error');
                }
            });
            this.$watch('blured', function () {
                if (this.blured.id == this.focused.id && this.blured.locale == this.focused.locale && this.blured.value != this.focused.value) {
                    this.updateCategory();
                }
            });
        },
        updated () {
            for(let index in this.categories){
                $('#accordion-'+this._uid+'-'+index).accordion({
                    selector: {
                        trigger: '.title > div > .dropdown.icon'
                    }
                });
            }
        },
        methods: {
            getCategories: function (withLoadIndicator) {
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                let that = this;
                axios.get(this.properties.routeCategory+'?withInfos=true')
                    .then(function (response) {
                        that.categories = response.data;
                        that.isLoaded = true;
                    })
                    .catch(function (error) {
                        that.sendToast(that.strings.loadErrorMessage, 'error');
                    });
            },
            addCategory: function (event, emitPostValue) {
                let isEmpty = true;
                let postValue = {};
                if (emitPostValue != undefined) {
                    postValue = emitPostValue;
                    isEmpty = false;
                } else if (this.categoryName != undefined) {
                    postValue['descriptions'] = {};
                    for (let lang in this.categoryName) {
                        postValue['descriptions'][lang] = this.categoryName[lang];
                        if (this.categoryName[lang] != '') {
                            isEmpty = false;
                        }
                    }

                }
                if (!isEmpty) {
                    this.isLoaded = false;
                    this.categoryName = {};
                    let that = this;
                    axios.post(this.properties.routeCategory, postValue)
                        .then(function (response) {
                            that.getCategories();
                        })
                        .catch(function (error) {
                            that.isLoaded = true;
                            if (error.response && error.response.status == 409) {
                                that.sendToast(error.response.data, 'error');
                            } else {
                                that.sendToast(that.strings.addErrorMessage, 'error');
                            }
                        });
                }
            },
            delCategory: function (event, id) {
                let categoryId = undefined;
                if (event != undefined && event.target.dataset.id != undefined && event.target.dataset.id > 0) {
                    categoryId = event.target.dataset.id;
                } else if (id != undefined && id > 0) {
                    categoryId = id;
                }

                if (categoryId != undefined && categoryId > 0) {
                    let that = this;
                    $('#modal-'+this._uid).modal({
                        closable: false,
                        onApprove: function () {
                            that.isLoaded = false;
                            axios.delete(that.properties.routeCategory + '/' + categoryId)
                                .then(function (response) {
                                    that.getCategories();
                                })
                                .catch(function (error) {
                                    that.isLoaded = true;
                                    if (error.response && error.response.status == 409) {
                                        that.sendToast(error.response.data, 'error');
                                    } else {
                                        that.sendToast(that.strings.delErrorMessage, 'error');
                                    }
                                });
                        }
                    }).modal('show');
                }
            },
            updateCategory: function (event, emitPostValue) {
                let postValue = {};
                let key = '';
                let id = '';
                let that = this;
                if (emitPostValue != undefined) {
                    postValue = emitPostValue.postValue;
                    key = emitPostValue.key;
                    id = emitPostValue.id;
                } else {
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
                }
                if (postValue[key] != undefined && postValue[key] != '') {
                    axios.patch(this.properties.routeCategory + '/' + id, {description: postValue})
                        .then(function (response) {
                            //that.getCategories(false);
                            that.sendToast(that.strings.patchSuccessMessage, 'success');
                        })
                        .catch(function (error) {
                            that.getCategories(false);
                            if (error.response && error.response.status == 409) {
                                that.sendToast(error.response.data, 'error');
                            } else {
                                that.sendToast(that.strings.patchErrorMessage, 'error');
                            }
                        });
                } else {
                    this.getCategories(false);
                    this.sendToast(this.strings.patchErrorMessage, 'error');
                }
            },
            sendToast: function (message, type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            },
            shiftUp: function (event) {
                this.shiftCategory(event, 'up');
            },
            shiftDown: function (event) {
                this.shiftCategory(event, 'down');
            },
            shiftCategory(event, way){
                let animTime = 600;
                let me = $(event.target).closest('.accordion');

                let sibling = null;
                if (way == 'down') {
                    sibling = $(me).next('.accordion');
                } else {
                    sibling = $(me).prev('.accordion');
                }

                let meTop = ($(me).position()).top;
                let siblingTop = ($(sibling).position()).top;

                //Animation
                $(me).addClass('main-action');
                $(sibling).addClass('sub-action');
                $(me).animate(
                        {top: siblingTop - meTop},
                        {
                            duration: animTime,
                            complete: function () {
                                $(me).removeClass('main-action');
                            }
                        }
                );
                let that = this;
                $(sibling).animate(
                        {top: meTop - siblingTop},
                        {
                            duration: animTime,
                            complete: function () {
                                $(sibling).removeClass('sub-action');
                                let route = null;
                                if (way == 'down') {
                                    route = that.routeShiftDownCategory;
                                } else {
                                    route = that.routeShiftUpCategory;
                                }
                                axios.patch(route, {id: event.target.dataset.value})
                                    .then(function (response) {
                                        that.getCategories(false);
                                        $(me).css('top', 0);
                                        $(sibling).css('top', 0);
                                        that.sendToast(that.strings.patchSuccessMessage, 'success');
                                    })
                                    .catch(function (error) {
                                        that.getCategories(false);
                                        $(me).animate('top', 0);
                                        $(sibling).animate('top', 0);
                                        if (error.response && error.response.status == 409) {
                                            that.sendToast(error.response.data, 'error');
                                        } else {
                                            that.sendToast(this.strings.patchErrorMessage, 'error');
                                        }
                                    });
                            }
                        }
                );

            },
            appendToCategory(childId, parentId){
                let that = this;
                axios.patch(this.routeAppendToCategory, {childId: childId, parentId: parentId})
                    .then(function (response) {
                        that.getCategories(false);
                        that.sendToast(that.strings.patchSuccessMessage, 'success');
                    })
                    .catch(function (error) {
                        if (error.response && error.response.status == 409) {
                            that.sendToast(error.response.data, 'error');
                        } else {
                            that.sendToast(this.strings.patchErrorMessage, 'error');
                        }
                    });
            }
        }
    }
</script>
