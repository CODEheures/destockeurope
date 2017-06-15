
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and .
 */

require('./bootstrap');
require('../semantic/dist/semantic');
//require('./semantic');
require('swiper');
require('./myjs');
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

//Generics
// Vue.component('range-filter-old', require('./components/generics/filters/rangeFilterOld.vue'));
Vue.component('range-filter', require('./components/generics/filters/rangeFilter.vue'));
Vue.component('location-filter', require('./components/generics/filters/locationFilter.vue'));
Vue.component('search-filter', require('./components/generics/filters/searchFilter.vue'));
Vue.component('type-radio-button', require('./components/generics/filters/typeRadioButton.vue'));
Vue.component('currencies-dropdown', require('./components/generics/currencies/currenciesDropdown.vue'));
Vue.component('currencies-button', require('./components/generics/currencies/currenciesButton.vue'));
Vue.component('currencies-input-right-label', require('./components/generics/currencies/currenciesInputRightLabel.vue'));
Vue.component('locales-dropdown', require('./components/generics/locales/localesDropdown.vue'));
Vue.component('langs-dropdown', require('./components/generics/locales/langsDropdown.vue'));
Vue.component('toast', require('./components/generics/messages/toast.vue'));
Vue.component('breadcrumb', require('./components/generics/breadcrumb/breadcrumb.vue'));
Vue.component('steps', require('./components/generics/steps/step.vue'));
Vue.component('steps-light', require('./components/generics/steps/stepLight.vue'));
Vue.component('googleMap', require('./components/generics/location/map.vue'));
Vue.component('pagination', require('./components/generics/paginations/pagination.vue'));
Vue.component('swiper-gallerie', require('./components/generics/swipers/gallerie.vue'));
Vue.component('swiper-top', require('./components/generics/swipers/swiperTop.vue'));
Vue.component('swiper-thumbs', require('./components/generics/swipers/swiperThumbs.vue'));
Vue.component('margin-input-field', require('./components/generics/fields/marginInput.vue'));
Vue.component('quantities-input-field', require('./components/generics/fields/quantitiesInput.vue'));
Vue.component('advert-manage-button', require('./components/generics/fields/manageButton.vue'));
Vue.component('vimeo-uploader', require('./components/generics/uploaders/vimeo.vue'));
Vue.component('photo-uploader', require('./components/generics/uploaders/photo.vue'));
Vue.component('notifications-activer', require('./components/generics/notifications/activer.vue'));
//ADS
Vue.component('masterads', require('./components/generics/ads/master.vue'));
Vue.component('double-square', require('./components/generics/ads/doubleSquares.vue'));
Vue.component('vertical-160x600', require('./components/generics/ads/vertical160x600.vue'));
Vue.component('horizontal-728x90', require('./components/generics/ads/horizontal728x90.vue'));
Vue.component('horizontal-468x60', require('./components/generics/ads/horizontal468x60.vue'));
Vue.component('horizontal-234x60', require('./components/generics/ads/horizontal234x60.vue'));

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
Vue.component('adverts-by-list', require('./components/adverts/byList/advertsByList.vue'));
Vue.component('adverts-by-list-item', require('./components/adverts/byList/item.vue'));
Vue.component('advert-by-id', require('./components/adverts/byId/advertById.vue'));
Vue.component('advert-highlight', require('./components/adverts/highlight/advertHighlight.vue'));

//invoices manager
Vue.component('invoice-filter', require('./components/invoices/invoicesFilter.vue'));
Vue.component('invoices-by-list', require('./components/invoices/byList/invoicesByList.vue'));
Vue.component('invoices-by-list-item', require('./components/invoices/byList/item.vue'));

//users manager
Vue.component('user-filter', require('./components/users/usersFilter.vue'));
Vue.component('users-by-list', require('./components/users/byList/usersByList.vue'));
Vue.component('users-by-list-item', require('./components/users/byList/item.vue'));

//Portal page vue
Vue.component('portal', require('./components/contents/portal.vue'));
//Welcome page vue
Vue.component('welcome1', require('./components/contents/welcome1.vue'));
//User page
Vue.component('user-account', require('./components/contents/userAccount.vue'));
Vue.component('user-account-register', require('./components/contents/userAccountRegister.vue'));
Vue.component('personnal-list', require('./components/contents/personnalList.vue'));
//Categories page
Vue.component('manage-categories', require('./components/contents/manageCategories.vue'));
//Application page
Vue.component('manage-application', require('./components/contents/manageApplication.vue'));
//Dashboard page
Vue.component('dashboard-admin', require('./components/contents/dashboardAdmin.vue'));
//mange invoices page
Vue.component('manage-invoices', require('./components/contents/manageInvoices.vue'));
//mange users page
Vue.component('manage-users', require('./components/contents/manageUsers.vue'));
//createAdvert page
Vue.component('create-advert-form', require('./components/contents/createAdvert.vue'));
//editAdvert page
Vue.component('edit-advert-form', require('./components/contents/editAdvert.vue'));
//approve advert page
Vue.component('approve-advert-form', require('./components/contents/approveAdvert.vue'));
//show advert page
Vue.component('show-advert1', require('./components/contents/showAdvert1.vue'));
//review payment page
Vue.component('review-for-payment', require('./components/contents/reviewForPayment.vue'));

const app = new Vue({
    el: '#app'
});
