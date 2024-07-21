try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('admin-lte');
    require('bootstrap');
} catch (e) {}
