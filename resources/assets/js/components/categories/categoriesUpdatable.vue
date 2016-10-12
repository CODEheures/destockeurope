<template>
    <div>
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
        <div class="ui message close error" v-show="hasAddError">
            <p>{{ addErrorMessage }}</p>
        </div>
        <div class="ui message close error" v-show="hasDelError">
            <p>{{ delErrorMessage }}</p>
        </div>
        <div class="ui message close error" v-show="hasPatchError">
            <p>{{ patchErrorMessage }}</p>
        </div>
        <div class="ui divided list" v-if="!hasError">
            <div class="ui active inverted dimmer" v-if="!isLoaded">
                <div class="ui large text loader">Loading</div>
            </div>
            <div v-for="metaCategory in metaCategories" class="item">
                <i class="big teal minus square icon" v-on:click="delMetaCategory" :data-id="metaCategory.id"></i>
                <div class="content">
                    <div class="ui huge transparent input">
                        <input type="text" :name="metaCategory.id" :data-id="metaCategory.id" class="header"
                               :value="metaCategory.title" v-on:input="updateMetaCategory"/>
                    </div>
                    <div class="ui divided list">
                        <div v-for="category in metaCategory.categories" class="item">
                            <i class="large blue minus square icon" v-on:click="delCategory" :data-id="category.id"></i>
                            <div class="content">
                                <div class="ui transparent input">
                                    <input type="text" :name="category.id" :data-id="category.id" class="header"
                                           :value="category.title" v-on:input="updateCategory"/>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <i class="large blue add square icon" v-on:click="addCategory" :data-value="categoryName[metaCategory.id]" :data-meta-category-id="metaCategory.id"></i>
                            <div class="content">
                                <div class="description">
                                    <div class="ui transparent input">
                                        <input type="text"
                                               name="newCategory"
                                               class="header"
                                               placeholder="Nouvelle sous-catégorie"
                                               :data-meta-category-id="metaCategory.id"
                                               v-on:keyup.enter="addCategory"
                                               v-model:value="categoryName[metaCategory.id]"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item">
                <i class="big teal add square icon" v-on:click="addMetaCategory" :data-value="metaCategoryName"></i>
                <div class="content">
                    <div class="description">
                        <div class="ui huge transparent input">
                            <input type="text"
                                   name="newMetaCategory"
                                   class="header"
                                   placeholder="Nouvelle Catégorie"
                                   v-on:keyup.enter="addMetaCategory"
                                   v-model:value="metaCategoryName"
                            />
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="ui message error" v-else>
            <p>{{ loadErrorMessage }}</p>
        </div>
    </div>
</template>


<script>
    export default {
        props: [
            'csrfToken',
            'loadErrorMessage',
            'addErrorMessage',
            'delErrorMessage',
            'patchErrorMessage',
            'modalYes',
            'modalNo',
            'modalDelHeader',
            'modalDelDescription',
            'routeMetaCategory',
            'routeCategory'],
        data: () => {
            return {
                metaCategories: [],
                isLoaded: false,
                hasError: false,
                hasAddError: false,
                hasDelError: false,
                hasPatchError: false,
                metaCategoryName: '',
                categoryName: [],
                inProgressUpdateMetaCategory: false,
                oldUpdateMetaCategory: '',
                inProgressUpdateCategory: false,
                oldUpdateCategory: ''
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
                                    this.hasError = true;
                                    this.isLoaded = true;
                                }
                        );
            },
            addMetaCategory: function (event) {
                if(this.metaCategoryName != undefined && this.metaCategoryName !='') {
                    this.isLoaded = false;
                    let postValue= this.metaCategoryName;
                    this.metaCategoryName = '';
                    this.$http.post(this.routeMetaCategory, {title: postValue}, {headers: {'X-CSRF-TOKEN': this.csrfToken}})
                            .then(
                                    (response) => {
                                        this.hasAddError = false;
                                        this.getMetaCategories();
                                    },
                                    (response) => {
                                        if(response.status==409) {
                                            this.addErrorMessage = response.body;
                                        }
                                        this.hasAddError = true;
                                    }
                            );
                }

            },
            delMetaCategory: function (event) {
                if(event.target.dataset.id != undefined && event.target.dataset.id > 0) {
                    var that = this;
                    $('.ui.basic.modal').modal({
                        closable: false,
                        onApprove: function () {
                            that.isLoaded = false;
                            that.$http.delete(that.routeMetaCategory + '/' + event.target.dataset.id, {headers: {'X-CSRF-TOKEN': that.csrfToken}})
                                    .then(
                                            (response) => {
                                                that.hasDelError = false;
                                                that.getMetaCategories();
                                            },
                                            (response) => {
                                                if (response.status == 409) {
                                                    that.delErrorMessage = response.body;
                                                }
                                                that.hasDelError = true;
                                            }
                                    );
                        }
                    }).modal('show');
                }
            },
            updateMetaCategory: function (event) {
                if(event.target.name != undefined && event.target.value != undefined && event.target.name > 0 && event.target.value != '') {

                    if(!this.inProgressUpdateMetaCategory){
                        this.inProgressUpdateMetaCategory= true;
                        this.oldUpdateMetaCategory = '';
                        var that = this;
                        var myInterval = setInterval(function () {
                            if(that.oldUpdateMetaCategory == event.target.value) {
                                that.oldUpdateMetaCategory = '';
                                clearTimeout(myInterval);
                                that.$http.patch(that.routeMetaCategory + '/' + event.target.name, {title: event.target.value}, {headers: {'X-CSRF-TOKEN': that.csrfToken}})
                                        .then(
                                                (response) => {
                                                    that.hasPatchError = false;
                                                    that.getMetaCategories(false);
                                                    that.inProgressUpdateMetaCategory= false;
                                                },
                                                (response) => {
                                                    that.getMetaCategories(false);
                                                    that.hasPatchError = true;
                                                    if(response.status==409) {
                                                        that.patchErrorMessage = response.body;
                                                    }
                                                    that.inProgressUpdateMetaCategory= false;
                                                }
                                        );
                            } else {
                                that.oldUpdateMetaCategory = event.target.value;
                            }
                        }, 1000);
                    }
                }
            },
            addCategory: function (event) {
                if(this.categoryName != undefined && this.categoryName !='') {
                    this.isLoaded = false;
                    let metaCategoryId = event.target.dataset.metaCategoryId;
                    let postValueName= this.categoryName[metaCategoryId];
                    this.categoryName[metaCategoryId] = '';
                    this.$http.post(this.routeCategory, {title: postValueName, metaCategoryId: metaCategoryId}, {headers: {'X-CSRF-TOKEN': this.csrfToken}})
                            .then(
                                    (response) => {
                                        this.hasAddError = false;
                                        this.getMetaCategories();
                                    },
                                    (response) => {
                                        if(response.status==409) {
                                            this.addErrorMessage = response.body;
                                        }
                                        this.hasAddError = true;
                                    }
                            );
                }

            },
            delCategory: function (event) {
                if(event.target.dataset.id != undefined && event.target.dataset.id > 0) {
                    this.isLoaded = false;
                    this.$http.delete(this.routeCategory + '/' +  event.target.dataset.id, {headers: {'X-CSRF-TOKEN': this.csrfToken}})
                            .then(
                                    (response) => {
                                        this.hasDelError = false;
                                        this.getMetaCategories();
                                    },
                                    (response) => {
                                        if(response.status==409) {
                                            this.delErrorMessage = response.body;
                                        }
                                        this.hasDelError = true;
                                    }
                            );
                }
            },
            updateCategory: function (event) {
                if(event.target.name != undefined && event.target.value != undefined && event.target.name > 0 && event.target.value != '') {
                    if(!this.inProgressUpdateCategory){
                        this.inProgressUpdateCategory= true;
                        this.oldUpdateCategory = '';
                        var that = this;
                        var myInterval = setInterval(function () {
                            if(that.oldUpdateCategory == event.target.value) {
                                that.oldUpdateCategory = '';
                                clearTimeout(myInterval);
                                that.$http.patch(that.routeCategory + '/' + event.target.name, {title: event.target.value}, {headers: {'X-CSRF-TOKEN': that.csrfToken}})
                                        .then(
                                                (response) => {
                                                    that.hasPatchError = false;
                                                    that.getMetaCategories(false);
                                                    that.inProgressUpdateCategory= false;
                                                },
                                                (response) => {
                                                    that.getMetaCategories(false);
                                                    that.hasPatchError = true;
                                                    if(response.status==409) {
                                                        that.patchErrorMessage = response.body;
                                                    }
                                                    that.inProgressUpdateCategory= false;
                                                }
                                        );
                            } else {
                                that.oldUpdateCategory = event.target.value;
                            }
                        }, 1000);
                    }
                }
            }
        }
    }
</script>
