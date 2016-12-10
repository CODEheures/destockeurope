
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./semantic');
require('swiper');
require('./myjs');
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

//Generics
Vue.component('range-filter', require('./components/generics/filters/rangeFilter.vue'));
Vue.component('search-filter', require('./components/generics/filters/searchFilter.vue'));
Vue.component('type-radio-button', require('./components/generics/filters/typeRadioButton.vue'));
Vue.component('currencies-dropdown', require('./components/generics/currencies/currenciesDropdown.vue'));
Vue.component('currencies-input-right-label', require('./components/generics/currencies/currenciesInputRightLabel.vue'));
Vue.component('locales-dropdown', require('./components/generics/locales/localesDropdown.vue'));
Vue.component('toast', require('./components/generics/messages/toast.vue'));
Vue.component('breadcrumb', require('./components/generics/breadcrumb/breadcrumb.vue'));
Vue.component('steps', require('./components/generics/steps/step.vue'));
Vue.component('steps-light', require('./components/generics/steps/stepLight.vue'));
Vue.component('googleMap', require('./components/generics/location/map.vue'));
Vue.component('pagination', require('./components/generics/paginations/pagination.vue'));
Vue.component('masterads', require('./components/generics/ads/master.vue'));
Vue.component('swiper', require('./components/generics/swipers/swiper.vue'));
Vue.component('swiper-slide', require('./components/generics/swipers/slide.vue'));
Vue.component('swiper-gallerie', require('./components/generics/swipers/gallerie.vue'));

//Catégories
Vue.component('categories-lateral-vertical-menu', require('./components/categories/lateral/vertical/menu.vue'));
Vue.component('recursive-categories-lateral-vertical-menu', require('./components/categories/lateral/vertical/recursive.vue'));
Vue.component('categories-horizontal-menu', require('./components/categories/horizontal/menu.vue'));
Vue.component('recursive-categories-horizontal-menu', require('./components/categories/horizontal/recursive.vue'));
Vue.component('categories-lateral-accordion-menu', require('./components/categories/lateral/accordion/menu.vue'));
Vue.component('recursive-categories-lateral-accordion-menu', require('./components/categories/lateral/accordion/recursive.vue'));
Vue.component('categories-dropdown-menu', require('./components/categories/dropdown/all/menu.vue'));
Vue.component('recursive-categories-dropdown-menu', require('./components/categories/dropdown/all/recursive.vue'));
Vue.component('categories-list-move-to', require('./components/categories/dropdown/listMoveTo/menu.vue'));
Vue.component('recursive-categories-list-move-to', require('./components/categories/dropdown/listMoveTo/recursive.vue'));
Vue.component('categories-updatable', require('./components/categories/categoriesUpdatable.vue'));

//adverts
Vue.component('advert-filter', require('./components/adverts/advertsFilter.vue'));
Vue.component('adverts-by-list', require('./components/adverts/advertsByList.vue'));
Vue.component('advert-by-id', require('./components/adverts/advertById.vue'));

//Welcome page vue
Vue.component('welcome1', require('./components/contents/welcome1.vue'));
Vue.component('welcome2', require('./components/contents/welcome2.vue'));
//User page
Vue.component('user-account', require('./components/contents/userAccount.vue'));
//Categories page
Vue.component('manage-categories', require('./components/contents/manageCategories.vue'));
//Application page
Vue.component('manage-application', require('./components/contents/manageApplication.vue'));
//createAdvert page
Vue.component('create-advert-form', require('./components/contents/createAdvert.vue'));
//approve advert page
Vue.component('approve-advert-form', require('./components/contents/approveAdvert.vue'));
//show advert page
Vue.component('show-advert1', require('./components/contents/showAdvert1.vue'));
//review payment page
Vue.component('review-for-payment', require('./components/contents/reviewForPayment.vue'));

const app = new Vue({
    el: '#app'
});
