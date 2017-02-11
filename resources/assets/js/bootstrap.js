
window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

window.$ = window.jQuery = require('jquery');
window.moment = require('moment');
// window.noUiSlider = require('./nouislider');
window.ionRangeSlider = require('ion-rangeslider');
window.DestockMap = require('./destockMap');
window.Parser = require('url');
window.axios = require('axios');
/*require('bootstrap-sass');*/
/**
 * Vue is a modern JavaScript library for building interactive web interfaces
 * using reactive data binding and reusable components. Vue's API is clean
 * and simple, leaving you to focus on building your next great project.
 */

window.Vue = require('vue');
//require('vue-resource');
require('vue-focus');

/**
 * We'll register a HTTP interceptor to attach the "CSRF" header to each of
 * the outgoing requests issued by this application. The CSRF middleware
 * included with  will automatically verify the header's value.
 */

// Vue.http.interceptors.push((request, next) => {
//     let myHost = window.location.hostname;
//     let requestHost = Parser.parse(request.url).hostname;
//     if(myHost == requestHost){
//         request.headers.set('X-CSRF-TOKEN', destockShareVar.csrfToken);
//     }
//     next();
// });

// axios.interceptors.request.use(function (config) {
//     let myHost = window.location.hostname;
//     let requestHost = Parser.parse(config.url).hostname;
//     if(myHost == requestHost){
//         config.headers['X-CSRF-TOKEN'] =  destockShareVar.csrfToken;
//     }
//     return config;
// }, function (error) {
//     return Promise.reject(error);
// });


Vue.config.devtools = true;
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by . Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from "laravel-echo"

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });
