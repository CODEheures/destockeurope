<template>
    <div>
        <form class="ui form" :action="routeAdvertFormPost" method="post">
            <input type="hidden" name="_token" :value="xCsrfToken"/>
            <input type="hidden" name="category" :value="categoryId"/>
            <div class="ui error message"></div>
            <type-radio-button
                    :load-error-message="advertListTypeLoadErrorMessage"
                    :route-get-list-type="routeGetListType"
                    :first-menu-name="listTypeFirstMenuName"
                    :old-choice="oldType">
            </type-radio-button>
            <div class="field">
                <categories-dropdown-menu
                        :load-error-message="categoryLoadErrorMessage"
                        :route-meta-category="categoryRouteMetaCategory"
                        :first-menu-name="categoryFirstMenuName"
                        :old-choice="oldCategoryId">
                </categories-dropdown-menu>
            </div>
            <div class="required field">
                <label>{{ advertFormTitleLabel }}</label>
                <input name="title" type="text" :placeholder="advertFormTitleLabel" v-model:value="title" :maxlength="formTitleMaxValid">
                <transition name="p-fade">
                    <span class="ui red pointing basic label notransition" v-show="title.length<formTitleMinValid">{{ formTitleMinValid }}{{formPointingMinimumChars }}</span>
                </transition>
            </div>
            <div class="required field">
                <label>{{ advertFormDescriptionLabel }}</label>
                <textarea name="description" v-model="description"></textarea>
                <transition name="p-fade">
                    <span class="ui red pointing basic label notransition" v-show="description.length<formDescriptionMinValid">{{ formDescriptionMinValid }}{{formPointingMinimumChars }}</span>
                </transition>
            </div>
            <div class="three wide required field">
                <label>{{ advertFormPriceLabel }}</label>
                <input name="price" type="number" min="0.01" step="0.01" :value="price">
            </div>
            <button type="submit" class="ui primary button">{{ formValidationButtonLabel }}</button>
        </form>

    </div>
</template>

<script>
    export default {
        props: [
            'advertFormTitleLabel',
            'advertFormDescriptionLabel',
            'advertFormPriceLabel',
            'categoryLoadErrorMessage',
            'categoryRouteMetaCategory',
            'categoryFirstMenuName',
            'routeGetListType',
            'routeAdvertFormPost',
            'advertListTypeLoadErrorMessage',
            'listTypeFirstMenuName',
            'formValidationButtonLabel',
            'formTitleMinValid',
            'formTitleMaxValid',
            'formDescriptionMinValid',
            'formDescriptionMaxValid',
            'formPointingMinimumChars',
            'old',
        ],
        data: () => {
            return {
                categoryId: '',
                listType: [],
                type: '',
                title: '',
                description: '',
                price: '',
                xCsrfToken: '',
                oldCategoryId: '',
                oldType: ''
            };
        },
        mounted () {
            this.$on('categoryChoice', function (event) {
                this.categoryChoice(event.id);
            });
            this.$on('typeChoice', function (event) {
                this.typeChoice(event.value);
            });
            this.xCsrfToken = Laravel.csrfToken;
            if (this.old != undefined) {
                this.categoryId = JSON.parse(this.old).category;
                this.oldCategoryId = JSON.parse(this.old).category;
                this.title = JSON.parse(this.old).title;
                this.description = JSON.parse(this.old).description;
                this.price = JSON.parse(this.old).price / 100;
                this.type = JSON.parse(this.old).type;
                this.oldType = JSON.parse(this.old).type;
            }
        },
        methods: {
            categoryChoice: function (id) {
                this.categoryId = id;
            }
        }
    }
</script>
