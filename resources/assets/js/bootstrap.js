
window._ = require('lodash');
window.$ = window.jQuery = require('jquery');
window.moment = require('moment');
window.ionRangeSlider = require('ion-rangeslider');
window.Parser = require('url');
window.axios = require('axios');
window.DestockMap = require('./destockMap');
window.DestockTools = require('./destockTools');
window.Vue = require('vue');
window.Vuex = require('vuex');

require('amcharts3/amcharts/amcharts');
require('amcharts3/amcharts/gauge');
require('amcharts3/amcharts/serial');
require('amcharts3/amcharts/themes/light');
// require('amcharts3/amcharts/plugins/export/export');
//require('amcharts3/images/style.css');
// require('amcharts3/amcharts/plugins/export/libs/fabric.js/fabric')
// require('amcharts3/amcharts/plugins/export/libs/FileSaver.js/FileSaver')
// require('amcharts3/amcharts/plugins/export/libs/jszip/jszip')
// require('amcharts3/amcharts/plugins/export/libs/pdfmake/pdfmake')


require('vue-focus');

Vue.config.devtools = window.destockShareVar.vueJsDevTool;
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
