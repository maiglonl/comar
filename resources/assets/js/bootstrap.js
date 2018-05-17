/* Lodash */
window._ = require('lodash');

/* Popper */
window.Popper = require('popper.js').default;

/* jQuery*/
window.$ = window.jQuery = require('jquery');
//require('jquery-ui');

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

/* Toastr */
window.toastr = require('toastr');
toastr.options.timeOut = 5000;

/* Axios */
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
