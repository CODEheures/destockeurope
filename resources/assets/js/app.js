
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

//Filters
Vue.component('advert-filter', require('./components/filters/advertsFilter.vue'));
Vue.component('range-filter', require('./components/filters/rangeFilter.vue'));

const app = new Vue({
    el: '#app'
});
