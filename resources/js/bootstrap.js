// window._ = require('lodash');

try {
    window.bootstrap = require('bootstrap');

} catch (e) {}

import $ from "jquery";
window.$ = window.jQuery = $;

import toastr from "toastr";
window.toastr = toastr;

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Промяна на името на инпут файл 
$(document).on('change', '.custom-file-input', function (event) {
    $(this).next('.custom-file-label').html(event.target.files[0].name);
});
