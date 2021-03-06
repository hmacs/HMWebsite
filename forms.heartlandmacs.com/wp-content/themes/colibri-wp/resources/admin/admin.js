/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 363);
/******/ })
/************************************************************************/
/******/ ({

/***/ 363:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(364);


/***/ }),

/***/ 364:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


__webpack_require__(365);

// admin notice
(function ($) {
    var $notice_container = $('.colibri-admin-big-notice--container');
    var selectedFrontPage = 0;

    $notice_container.on('click', '.predefined-front-pages li', function (event) {
        var $item = $(event.currentTarget);
        $item.addClass('selected');
        $item.siblings().removeClass('selected');
    });

    function disableNotice() {
        wp.ajax.post('colibriwp_disable_big_notice');
    }

    function toggleProcessing(value) {
        $(window).on('beforeunload.colibri-admin-big-notice', function () {
            return true;
        });
        if (value) {
            $('.colibri-admin-big-notice').addClass('processing');
            $('.colibri-admin-big-notice .action-buttons').fadeOut();
        } else {
            $('.colibri-admin-big-notice').removeClass('processing');
        }
    }

    function showOverlay(message) {
        var $overlay = jQuery('.colibri-customizer-overlay');

        if (!$overlay.length) {
            $overlay = jQuery('' + '<div class="colibri-customizer-overlay">\n' + '        <div class="colibri-customizer-overlay-content">\n' + '            <span class="colibri-customizer-overlay-loader"></span>\n' + '            <span class="colibri-customizer-overlay-message"></span>\n' + '        </div>\n' + '    </div>');

            jQuery('body').append($overlay);
        }

        $('.colibri-customizer-overlay-message').html(message);
        $overlay.fadeIn();
    }

    function hideOverlay() {
        var $overlay = jQuery('.colibri-customizer-overlay');
        $overlay.fadeOut();
    }

    function pluginNotice(message) {
        $notice_container.find('.plugin-notice .message').html(message);
        $notice_container.find('.plugin-notice').fadeIn();
        showOverlay(message);
    }

    function installBuilder(callback) {
        pluginNotice(colibriwp_builder_status.messages.installing);
        $.get(colibriwp_builder_status.install_url).done(function () {
            toggleProcessing(true);
            activateBuilder(callback);
        }).always(function () {
            $(window).off('beforeunload.colibri-admin-big-notice');
        });
    }

    function activateBuilder(callback) {
        pluginNotice(colibriwp_builder_status.messages.activating);

        wp.ajax.post('colibriwp_activate_plugin', { slug: colibriwp_builder_status.slug }).done(function () {
            $(window).off('beforeunload.colibri-admin-big-notice');
            if (callback) {
                callback();
            } else {
                var url = new URL(window.location.toString());
                url.searchParams.append('colibri_generator_callback', 'site_imported_notice');
                window.location = url.toString();
            }
        });
    }

    function processBuilderInstalationStepts(callback) {
        wp.ajax.post('colibriwp_front_set_predesign', { index: selectedFrontPage });
        if (colibriwp_builder_status.status === "not-installed") {
            toggleProcessing(true);
            installBuilder(callback);
        }

        if (colibriwp_builder_status.status === "installed") {
            toggleProcessing(true);
            activateBuilder(callback);
        }
    }

    $notice_container.on('click', '.start-with-predefined-design-button', function () {
        selectedFrontPage = $('.selected[data-index]').data('index');
        processBuilderInstalationStepts();
    });

    $notice_container.on('click', '.view-all-demos', function () {
        selectedFrontPage = 0;
        processBuilderInstalationStepts(function () {
            window.location = colibriwp_builder_status.view_demos_url;
        });
    });

    $notice_container.parent().on('click', '.notice-dismiss', disableNotice);
})(jQuery);

/***/ }),

/***/ 365:
/***/ (function(module, exports) {

module.exports = "./../images/logo.jpg";

/***/ })

/******/ });
