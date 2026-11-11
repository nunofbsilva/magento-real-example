/**
 * Test fixture: intentional violations for Norma Priority Advisor (commonly_neglected).
 * Rule: adobe-empty-catch-block
 */
define([], function () {
    'use strict';

    return {
        read: function (key) {
            var value = null;

            try {
                value = JSON.parse(window.sessionStorage.getItem(key));
            } catch (e) {}

            return value;
        },

        write: function (key, value) {
            try {
                window.sessionStorage.setItem(key, JSON.stringify(value));
            } catch (e) {
            }
        }
    };
});
