<template>
    <div class="ui one column grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div class="column">
            <h2 class="ui header">{{ contentHeader }}</h2>
        </div>
        <div class="column">
            <form class="ui form" :action="routeAdvertFormPost" method="post">
                <input type="hidden" name="_token" :value="xCsrfToken"/>
                <input type="hidden" name="category" :value="categoryId"/>
                <input type="hidden" name="currency" :value="currency" />
                <div class="ui error message"></div>
                <type-advert-radio-button
                        :route-get-list-type="routeGetListType"
                        :first-menu-name="listTypeFirstMenuName"
                        :old-choice="oldType">
                </type-advert-radio-button>
                <div class="field">
                    <categories-dropdown-menu
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

                <div class="required field">
                    <label>{{ advertFormPriceLabel }}</label>
                    <div class="ui right labeled input">
                        <input name="price" type="number" min="0.01" step="0.01" :value="price">
                        <currencies-input-right-label
                                :route-list-currencies="routeListCurrencies"
                                :first-menu-name="currenciesFirstMenuName"
                                :old-currency="oldCurrency">
                        </currencies-input-right-label>
                    </div>
                </div>


                <button type="submit" class="ui primary button">{{ formValidationButtonLabel }}</button>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'contentHeader',
            'advertFormTitleLabel',
            'advertFormDescriptionLabel',
            'advertFormPriceLabel',
            'loadErrorMessage',
            'categoryRouteMetaCategory',
            'categoryFirstMenuName',
            'routeGetListType',
            'routeAdvertFormPost',
            'listTypeFirstMenuName',
            'formValidationButtonLabel',
            'formTitleMinValid',
            'formTitleMaxValid',
            'formDescriptionMinValid',
            'formDescriptionMaxValid',
            'formPointingMinimumChars',
            'old',
            'routeListCurrencies',
            'currenciesFirstMenuName'
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
                oldType: '',
                oldCurrency: '',
                sendMessage: false,
                typeMessage: '',
                message:'',
                currency:''
            };
        },
        mounted () {
            this.$on('categoryChoice', function (event) {
                this.categoryChoice(event.id);
            });
            this.$on('currencyChoice', function (event) {
                this.currencyChoice(event.cur);
            });
            this.$on('loadError', function () {
                this.sendToast(this.loadErrorMessage, 'error');
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
                this.currency=JSON.parse(this.old).currency;
                this.oldCurrency= JSON.parse(this.old).currency;
            }
        },
        methods: {
            categoryChoice: function (id) {
                this.categoryId = id;
            },
            currencyChoice: function (currency) {
                this.currency = currency;
            },
            sendToast: function(message,type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            }
        }
    }
</script>
