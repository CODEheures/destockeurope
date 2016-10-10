
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

// Vue.component('example', require('./components/Example.vue'));
Vue.component('category-vertical-menu', require('./components/categoryVerticalMenu.vue'));
Vue.component('category-updatable', require('./components/categoryUpdatable.vue'));
// Vue.component('example', function (resolve) {
//     require(['./components/Example.vue'], resolve);
// });

const app = new Vue({
    el: '#app'
});
