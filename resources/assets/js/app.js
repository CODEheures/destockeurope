
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
Vue.component('categories-vertical-menu', require('./components/categories/categoriesVerticalMenu.vue'));
Vue.component('categories-dropdown-menu', require('./components/categories/categoriesDropdownMenu.vue'));
Vue.component('categories-updatable', require('./components/categories/categoriesUpdatable.vue'));

//Generics
Vue.component('range-filter', require('./components/filters/rangeFilter.vue'));

//adverts
Vue.component('advert-filter', require('./components/adverts/advertsFilter.vue'));
Vue.component('type-advert-dropdown', require('./components/adverts/typeDropdown.vue'));
Vue.component('type-radio-button', require('./components/adverts/typeRadioButton.vue'));
Vue.component('create-advert-form', require('./components/adverts/forms/createAdvert.vue'));
Vue.component('approve-advert-form', require('./components/adverts/forms/approveAdvert.vue'));
Vue.component('adverts-by', require('./components/adverts/list/advertsBy.vue'));

//Welcome page vue
Vue.component('welcome', require('./components/welcome/welcome.vue'));

const app = new Vue({
    el: '#app'
});
