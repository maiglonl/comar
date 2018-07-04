/* Lodash */
window._ = require('lodash');

/* Popper */
window.Popper = require('popper.js').default;

/* jQuery*/
window.$ = window.jQuery = require('jquery');
window.$ = $.extend(require('jquery-ui-bundle'));
$.widget.bridge('uitooltip', $.ui.tooltip);

/* Axios */
let token = document.head.querySelector('meta[name="csrf-token"]');
window.axios = require('axios');
window._axios = axios.create({
	headers: {'X-CSRF-TOKEN': token.content}
});

/* VueJs */
window.Vue = require('vue');
var VueResource = require('vue-resource');
import VueTheMask from 'vue-the-mask';
Vue.use(VueResource);
Vue.use(VueTheMask);
window.EventHub = new Vue();

/* Bootstrap */
require('bootstrap');

/* DataTables */
import dt from 'datatables.net-bs4';
import 'datatables.net-buttons-bs4';
import 'datatables.net-responsive-bs4';

/* jQuery-validate */
import 'jquery-validation/dist/jquery.validate';
import 'jquery-validation/dist/additional-methods';
import 'jquery-validation/dist/localization/messages_pt_BR';

/* Fancybox */
require('@fancyapps/fancybox');

/* SweetAlert 2 */
window.swal = require('sweetalert2');

/* jsMatchHeight */
require('jquery-match-height');

/* bootstrap-select */
require('bootstrap-select');

/* Toastr */
window.toastr = require('toastr');
toastr.options.timeOut = 5000;
