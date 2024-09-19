/**
 * Selects & Tags
 */

'use strict';

$(function() {
    const selectPicker = $('.selectpicker')

    // Bootstrap Select
    // --------------------------------------------------------------------
    if (selectPicker.length) {
        selectPicker.selectpicker();
        handleBootstrapSelectEvents();
    }
});