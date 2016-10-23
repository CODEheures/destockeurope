
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./semantic');
require('./myjs');
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

//Generics
Vue.component('range-filter', require('./components/generics/filters/rangeFilter.vue'));
Vue.component('currencies-dropdown', require('./components/generics/currencies/currenciesDropdown.vue'));
Vue.component('currencies-input-right-label', require('./components/generics/currencies/currenciesInputRightLabel.vue'));
Vue.component('locales-dropdown', require('./components/generics/locales/localesDropdown.vue'));
Vue.component('toast', require('./components/generics/messages/toast.vue'));

//Catégories
Vue.component('categories-lateral-menu', require('./components/categories/lateral/menu.vue'));
Vue.component('recursive-categories-lateral-menu', require('./components/categories/lateral/recursive.vue'));
Vue.component('categories-dropdown-menu', require('./components/categories/dropdown/menu.vue'));
Vue.component('recursive-categories-dropdown-menu', require('./components/categories/dropdown/recursive.vue'));
Vue.component('categories-updatable', require('./components/categories/categoriesUpdatable.vue'));

//adverts
Vue.component('price-advert-filter', require('./components/adverts/priceFilter.vue'));
Vue.component('type-advert-radio-button', require('./components/adverts/typeRadioButton.vue'));
Vue.component('adverts-by-list', require('./components/adverts/advertsByList.vue'));

//Welcome page vue
Vue.component('welcome', require('./components/contents/welcome.vue'));
//User page
Vue.component('user-account', require('./components/contents/userAccount.vue'));
//Categories page
Vue.component('meta-categories-updatable', require('./components/contents/metaCategoriesUpdatable.vue'));
//createAdvert page
Vue.component('create-advert-form', require('./components/contents/createAdvert.vue'));
//approve advert page
Vue.component('approve-advert-form', require('./components/contents/approveAdvert.vue'));

const app = new Vue({
    el: '#app'
});
