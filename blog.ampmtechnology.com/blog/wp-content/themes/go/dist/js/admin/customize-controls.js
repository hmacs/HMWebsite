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
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
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
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./.dev/assets/admin/js/customize-controls.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./.dev/assets/admin/js/customize-controls.js":
/*!****************************************************!*\
  !*** ./.dev/assets/admin/js/customize-controls.js ***!
  \****************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _customize_controls_range_control__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./customize/controls/range-control */ "./.dev/assets/admin/js/customize/controls/range-control.js");
/* harmony import */ var _customize_controls_range_control__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_customize_controls_range_control__WEBPACK_IMPORTED_MODULE_0__);

/**
 * Scripts within the customizer controls window.
 *
 * Contextually shows the color hue control and informs the preview
 * when users open or close the front page sections section.
 */

(function () {
  wp.customize.bind('ready', function () {
    /**
     * Function to hide/show Customizer options, based on another control.
     *
     * Parent option, Affected Control, Value which affects the control.
     *
     * @param {String} parentSetting The setting that will toggle the display of the control.
     * @param {String} affectedControl The control that will be toggled.
     * @param {Array} values The values the parentSetting must have for the affectedControl to be displayed.
     * @param {Integer} speed The speed of the toggle animation.
     */
    function customizerOptionDisplay(parentSetting, affectedControl, values) {
      var speed = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 100;
      wp.customize(parentSetting, function (setting) {
        wp.customize.control(affectedControl, function (control) {
          /**
           * Toggle the visibility of a control.
           */
          var visibility = function visibility() {
            if (values.includes(setting.get())) {
              control.container.slideDown(speed);
            } else {
              control.container.slideUp(speed);
            }
          };

          visibility();
          setting.bind(visibility);
        });
      });
    }
    /**
     * Function to hide/show Customizer options, based on another control.
     *
     * Parent option, Affected Control, Value which affects the control.
     *
     * @param {String} parentSetting The setting that will toggle the display of the control.
     * @param {String} affectedControl The control that will be toggled.
     * @param {Integer} speed The speed of the toggle animation.
     */


    function customizerImageOptionDisplay(parentSetting, affectedControl) {
      var speed = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 100;
      wp.customize(parentSetting, function (setting) {
        wp.customize.control(affectedControl, function (control) {
          /**
           * Toggle the visibility of a control.
           */
          var visibility = function visibility() {
            if (setting.get() && 'none' !== setting.get() && '0' !== setting.get()) {
              control.container.slideDown(speed);
            } else {
              control.container.slideUp(speed);
            }
          };

          visibility();
          setting.bind(visibility);
        });
      });
    } // Only show the Footer Header Color selector, if the footer variation is 2 or 4.


    customizerOptionDisplay('footer_variation', 'footer_heading_color', ['footer-3', 'footer-4']); // Footer nav locations #2 and #3 are only available on Footer Variation #3 and #4.

    customizerOptionDisplay('footer_variation', 'nav_menu_locations[footer-2]', ['footer-3', 'footer-4']);
    customizerOptionDisplay('footer_variation', 'nav_menu_locations[footer-3]', ['footer-3', 'footer-4']); // Only show the following options, if a logo is uploaded.

    customizerImageOptionDisplay('custom_logo', 'logo_width');
    customizerImageOptionDisplay('custom_logo', 'logo_width_mobile');
  });
})(jQuery);

/***/ }),

/***/ "./.dev/assets/admin/js/customize/controls/range-control.js":
/*!******************************************************************!*\
  !*** ./.dev/assets/admin/js/customize/controls/range-control.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function ($, api) {
  api.bind('ready', function () {
    $('.range_control__reset').on('click', function () {
      var rangeContainer = $(this).parent();
      var rangeInput = rangeContainer.find('input[data-input-type="range"]');
      var controlValue = rangeContainer.find('.range_control__value');
      var defaultValue = rangeInput.data('default-value');
      rangeInput.val(defaultValue);
      controlValue.find('span').html(defaultValue);
      controlValue.find('input').val(defaultValue);
      var customizeSetting = controlValue.find('input').data('customize-setting-link');
      wp.customize.control(customizeSetting).setting.set(defaultValue);
    });
  });
})(jQuery, wp.customize);

/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoianMvYWRtaW4vY3VzdG9taXplLWNvbnRyb2xzLmpzIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vL3dlYnBhY2svYm9vdHN0cmFwIiwid2VicGFjazovLy8uLy5kZXYvYXNzZXRzL2FkbWluL2pzL2N1c3RvbWl6ZS1jb250cm9scy5qcyIsIndlYnBhY2s6Ly8vLi8uZGV2L2Fzc2V0cy9hZG1pbi9qcy9jdXN0b21pemUvY29udHJvbHMvcmFuZ2UtY29udHJvbC5qcyJdLCJzb3VyY2VzQ29udGVudCI6WyIgXHQvLyBUaGUgbW9kdWxlIGNhY2hlXG4gXHR2YXIgaW5zdGFsbGVkTW9kdWxlcyA9IHt9O1xuXG4gXHQvLyBUaGUgcmVxdWlyZSBmdW5jdGlvblxuIFx0ZnVuY3Rpb24gX193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCkge1xuXG4gXHRcdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuIFx0XHRpZihpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSkge1xuIFx0XHRcdHJldHVybiBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXS5leHBvcnRzO1xuIFx0XHR9XG4gXHRcdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG4gXHRcdHZhciBtb2R1bGUgPSBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSA9IHtcbiBcdFx0XHRpOiBtb2R1bGVJZCxcbiBcdFx0XHRsOiBmYWxzZSxcbiBcdFx0XHRleHBvcnRzOiB7fVxuIFx0XHR9O1xuXG4gXHRcdC8vIEV4ZWN1dGUgdGhlIG1vZHVsZSBmdW5jdGlvblxuIFx0XHRtb2R1bGVzW21vZHVsZUlkXS5jYWxsKG1vZHVsZS5leHBvcnRzLCBtb2R1bGUsIG1vZHVsZS5leHBvcnRzLCBfX3dlYnBhY2tfcmVxdWlyZV9fKTtcblxuIFx0XHQvLyBGbGFnIHRoZSBtb2R1bGUgYXMgbG9hZGVkXG4gXHRcdG1vZHVsZS5sID0gdHJ1ZTtcblxuIFx0XHQvLyBSZXR1cm4gdGhlIGV4cG9ydHMgb2YgdGhlIG1vZHVsZVxuIFx0XHRyZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG4gXHR9XG5cblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGVzIG9iamVjdCAoX193ZWJwYWNrX21vZHVsZXNfXylcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubSA9IG1vZHVsZXM7XG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlIGNhY2hlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmMgPSBpbnN0YWxsZWRNb2R1bGVzO1xuXG4gXHQvLyBkZWZpbmUgZ2V0dGVyIGZ1bmN0aW9uIGZvciBoYXJtb255IGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uZCA9IGZ1bmN0aW9uKGV4cG9ydHMsIG5hbWUsIGdldHRlcikge1xuIFx0XHRpZighX193ZWJwYWNrX3JlcXVpcmVfXy5vKGV4cG9ydHMsIG5hbWUpKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIG5hbWUsIHsgZW51bWVyYWJsZTogdHJ1ZSwgZ2V0OiBnZXR0ZXIgfSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGRlZmluZSBfX2VzTW9kdWxlIG9uIGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uciA9IGZ1bmN0aW9uKGV4cG9ydHMpIHtcbiBcdFx0aWYodHlwZW9mIFN5bWJvbCAhPT0gJ3VuZGVmaW5lZCcgJiYgU3ltYm9sLnRvU3RyaW5nVGFnKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFN5bWJvbC50b1N0cmluZ1RhZywgeyB2YWx1ZTogJ01vZHVsZScgfSk7XG4gXHRcdH1cbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsICdfX2VzTW9kdWxlJywgeyB2YWx1ZTogdHJ1ZSB9KTtcbiBcdH07XG5cbiBcdC8vIGNyZWF0ZSBhIGZha2UgbmFtZXNwYWNlIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDE6IHZhbHVlIGlzIGEgbW9kdWxlIGlkLCByZXF1aXJlIGl0XG4gXHQvLyBtb2RlICYgMjogbWVyZ2UgYWxsIHByb3BlcnRpZXMgb2YgdmFsdWUgaW50byB0aGUgbnNcbiBcdC8vIG1vZGUgJiA0OiByZXR1cm4gdmFsdWUgd2hlbiBhbHJlYWR5IG5zIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDh8MTogYmVoYXZlIGxpa2UgcmVxdWlyZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy50ID0gZnVuY3Rpb24odmFsdWUsIG1vZGUpIHtcbiBcdFx0aWYobW9kZSAmIDEpIHZhbHVlID0gX193ZWJwYWNrX3JlcXVpcmVfXyh2YWx1ZSk7XG4gXHRcdGlmKG1vZGUgJiA4KSByZXR1cm4gdmFsdWU7XG4gXHRcdGlmKChtb2RlICYgNCkgJiYgdHlwZW9mIHZhbHVlID09PSAnb2JqZWN0JyAmJiB2YWx1ZSAmJiB2YWx1ZS5fX2VzTW9kdWxlKSByZXR1cm4gdmFsdWU7XG4gXHRcdHZhciBucyA9IE9iamVjdC5jcmVhdGUobnVsbCk7XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18ucihucyk7XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShucywgJ2RlZmF1bHQnLCB7IGVudW1lcmFibGU6IHRydWUsIHZhbHVlOiB2YWx1ZSB9KTtcbiBcdFx0aWYobW9kZSAmIDIgJiYgdHlwZW9mIHZhbHVlICE9ICdzdHJpbmcnKSBmb3IodmFyIGtleSBpbiB2YWx1ZSkgX193ZWJwYWNrX3JlcXVpcmVfXy5kKG5zLCBrZXksIGZ1bmN0aW9uKGtleSkgeyByZXR1cm4gdmFsdWVba2V5XTsgfS5iaW5kKG51bGwsIGtleSkpO1xuIFx0XHRyZXR1cm4gbnM7XG4gXHR9O1xuXG4gXHQvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5uID0gZnVuY3Rpb24obW9kdWxlKSB7XG4gXHRcdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuIFx0XHRcdGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0TW9kdWxlRXhwb3J0cygpIHsgcmV0dXJuIG1vZHVsZTsgfTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgJ2EnLCBnZXR0ZXIpO1xuIFx0XHRyZXR1cm4gZ2V0dGVyO1xuIFx0fTtcblxuIFx0Ly8gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSBmdW5jdGlvbihvYmplY3QsIHByb3BlcnR5KSB7IHJldHVybiBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqZWN0LCBwcm9wZXJ0eSk7IH07XG5cbiBcdC8vIF9fd2VicGFja19wdWJsaWNfcGF0aF9fXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIlwiO1xuXG5cbiBcdC8vIExvYWQgZW50cnkgbW9kdWxlIGFuZCByZXR1cm4gZXhwb3J0c1xuIFx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oX193ZWJwYWNrX3JlcXVpcmVfXy5zID0gXCIuLy5kZXYvYXNzZXRzL2FkbWluL2pzL2N1c3RvbWl6ZS1jb250cm9scy5qc1wiKTtcbiIsImltcG9ydCAnLi9jdXN0b21pemUvY29udHJvbHMvcmFuZ2UtY29udHJvbCc7XG5cbi8qKlxuICogU2NyaXB0cyB3aXRoaW4gdGhlIGN1c3RvbWl6ZXIgY29udHJvbHMgd2luZG93LlxuICpcbiAqIENvbnRleHR1YWxseSBzaG93cyB0aGUgY29sb3IgaHVlIGNvbnRyb2wgYW5kIGluZm9ybXMgdGhlIHByZXZpZXdcbiAqIHdoZW4gdXNlcnMgb3BlbiBvciBjbG9zZSB0aGUgZnJvbnQgcGFnZSBzZWN0aW9ucyBzZWN0aW9uLlxuICovXG5cbiggZnVuY3Rpb24oKSB7XG5cblx0d3AuY3VzdG9taXplLmJpbmQoICdyZWFkeScsIGZ1bmN0aW9uICgpIHtcblxuXHRcdC8qKlxuXHRcdCAqIEZ1bmN0aW9uIHRvIGhpZGUvc2hvdyBDdXN0b21pemVyIG9wdGlvbnMsIGJhc2VkIG9uIGFub3RoZXIgY29udHJvbC5cblx0XHQgKlxuXHRcdCAqIFBhcmVudCBvcHRpb24sIEFmZmVjdGVkIENvbnRyb2wsIFZhbHVlIHdoaWNoIGFmZmVjdHMgdGhlIGNvbnRyb2wuXG5cdFx0ICpcblx0XHQgKiBAcGFyYW0ge1N0cmluZ30gcGFyZW50U2V0dGluZyBUaGUgc2V0dGluZyB0aGF0IHdpbGwgdG9nZ2xlIHRoZSBkaXNwbGF5IG9mIHRoZSBjb250cm9sLlxuXHRcdCAqIEBwYXJhbSB7U3RyaW5nfSBhZmZlY3RlZENvbnRyb2wgVGhlIGNvbnRyb2wgdGhhdCB3aWxsIGJlIHRvZ2dsZWQuXG5cdFx0ICogQHBhcmFtIHtBcnJheX0gdmFsdWVzIFRoZSB2YWx1ZXMgdGhlIHBhcmVudFNldHRpbmcgbXVzdCBoYXZlIGZvciB0aGUgYWZmZWN0ZWRDb250cm9sIHRvIGJlIGRpc3BsYXllZC5cblx0XHQgKiBAcGFyYW0ge0ludGVnZXJ9IHNwZWVkIFRoZSBzcGVlZCBvZiB0aGUgdG9nZ2xlIGFuaW1hdGlvbi5cblx0XHQgKi9cblx0XHRmdW5jdGlvbiBjdXN0b21pemVyT3B0aW9uRGlzcGxheSggcGFyZW50U2V0dGluZywgYWZmZWN0ZWRDb250cm9sLCB2YWx1ZXMsIHNwZWVkID0gMTAwICkge1xuXHRcdFx0d3AuY3VzdG9taXplKCBwYXJlbnRTZXR0aW5nLCBmdW5jdGlvbiggc2V0dGluZyApIHtcblx0XHRcdFx0d3AuY3VzdG9taXplLmNvbnRyb2woIGFmZmVjdGVkQ29udHJvbCwgZnVuY3Rpb24gKCBjb250cm9sICkge1xuXHRcdFx0XHRcdC8qKlxuXHRcdFx0XHRcdCAqIFRvZ2dsZSB0aGUgdmlzaWJpbGl0eSBvZiBhIGNvbnRyb2wuXG5cdFx0XHRcdFx0ICovXG5cdFx0XHRcdFx0Y29uc3QgdmlzaWJpbGl0eSA9IGZ1bmN0aW9uKCkge1xuXHRcdFx0XHRcdFx0aWYgKCB2YWx1ZXMuaW5jbHVkZXMoIHNldHRpbmcuZ2V0KCkgKSApIHtcblx0XHRcdFx0XHRcdFx0Y29udHJvbC5jb250YWluZXIuc2xpZGVEb3duKCBzcGVlZCApO1xuXHRcdFx0XHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0XHRcdFx0Y29udHJvbC5jb250YWluZXIuc2xpZGVVcCggc3BlZWQgKTtcblx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHR9O1xuXG5cdFx0XHRcdFx0dmlzaWJpbGl0eSgpO1xuXHRcdFx0XHRcdHNldHRpbmcuYmluZCggdmlzaWJpbGl0eSApO1xuXHRcdFx0XHR9ICk7XG5cdFx0XHR9ICk7XG5cdFx0fVxuXG5cdFx0LyoqXG5cdFx0ICogRnVuY3Rpb24gdG8gaGlkZS9zaG93IEN1c3RvbWl6ZXIgb3B0aW9ucywgYmFzZWQgb24gYW5vdGhlciBjb250cm9sLlxuXHRcdCAqXG5cdFx0ICogUGFyZW50IG9wdGlvbiwgQWZmZWN0ZWQgQ29udHJvbCwgVmFsdWUgd2hpY2ggYWZmZWN0cyB0aGUgY29udHJvbC5cblx0XHQgKlxuXHRcdCAqIEBwYXJhbSB7U3RyaW5nfSBwYXJlbnRTZXR0aW5nIFRoZSBzZXR0aW5nIHRoYXQgd2lsbCB0b2dnbGUgdGhlIGRpc3BsYXkgb2YgdGhlIGNvbnRyb2wuXG5cdFx0ICogQHBhcmFtIHtTdHJpbmd9IGFmZmVjdGVkQ29udHJvbCBUaGUgY29udHJvbCB0aGF0IHdpbGwgYmUgdG9nZ2xlZC5cblx0XHQgKiBAcGFyYW0ge0ludGVnZXJ9IHNwZWVkIFRoZSBzcGVlZCBvZiB0aGUgdG9nZ2xlIGFuaW1hdGlvbi5cblx0XHQgKi9cblx0XHRmdW5jdGlvbiBjdXN0b21pemVySW1hZ2VPcHRpb25EaXNwbGF5KCBwYXJlbnRTZXR0aW5nLCBhZmZlY3RlZENvbnRyb2wsIHNwZWVkID0gMTAwICkge1xuXHRcdFx0d3AuY3VzdG9taXplKCBwYXJlbnRTZXR0aW5nLCBmdW5jdGlvbiggc2V0dGluZyApIHtcblx0XHRcdFx0d3AuY3VzdG9taXplLmNvbnRyb2woIGFmZmVjdGVkQ29udHJvbCwgZnVuY3Rpb24gKCBjb250cm9sICkge1xuXHRcdFx0XHRcdC8qKlxuXHRcdFx0XHRcdCAqIFRvZ2dsZSB0aGUgdmlzaWJpbGl0eSBvZiBhIGNvbnRyb2wuXG5cdFx0XHRcdFx0ICovXG5cdFx0XHRcdFx0Y29uc3QgdmlzaWJpbGl0eSA9IGZ1bmN0aW9uKCkge1xuXHRcdFx0XHRcdFx0aWYgKCBzZXR0aW5nLmdldCgpICYmICdub25lJyAhPT0gc2V0dGluZy5nZXQoKSAmJiAnMCcgIT09IHNldHRpbmcuZ2V0KCkgKSB7XG5cdFx0XHRcdFx0XHRcdGNvbnRyb2wuY29udGFpbmVyLnNsaWRlRG93biggc3BlZWQgKTtcblx0XHRcdFx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdFx0XHRcdGNvbnRyb2wuY29udGFpbmVyLnNsaWRlVXAoIHNwZWVkICk7XG5cdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0fTtcblxuXHRcdFx0XHRcdHZpc2liaWxpdHkoKTtcblx0XHRcdFx0XHRzZXR0aW5nLmJpbmQoIHZpc2liaWxpdHkgKTtcblx0XHRcdFx0fSApO1xuXHRcdFx0fSApO1xuXHRcdH1cblxuXHRcdC8vIE9ubHkgc2hvdyB0aGUgRm9vdGVyIEhlYWRlciBDb2xvciBzZWxlY3RvciwgaWYgdGhlIGZvb3RlciB2YXJpYXRpb24gaXMgMiBvciA0LlxuXHRcdGN1c3RvbWl6ZXJPcHRpb25EaXNwbGF5KCAnZm9vdGVyX3ZhcmlhdGlvbicsICdmb290ZXJfaGVhZGluZ19jb2xvcicsIFsgJ2Zvb3Rlci0zJywgJ2Zvb3Rlci00JyBdICk7XG5cblx0XHQvLyBGb290ZXIgbmF2IGxvY2F0aW9ucyAjMiBhbmQgIzMgYXJlIG9ubHkgYXZhaWxhYmxlIG9uIEZvb3RlciBWYXJpYXRpb24gIzMgYW5kICM0LlxuXHRcdGN1c3RvbWl6ZXJPcHRpb25EaXNwbGF5KCAnZm9vdGVyX3ZhcmlhdGlvbicsICduYXZfbWVudV9sb2NhdGlvbnNbZm9vdGVyLTJdJywgWyAnZm9vdGVyLTMnLCAnZm9vdGVyLTQnIF0gKTtcblx0XHRjdXN0b21pemVyT3B0aW9uRGlzcGxheSggJ2Zvb3Rlcl92YXJpYXRpb24nLCAnbmF2X21lbnVfbG9jYXRpb25zW2Zvb3Rlci0zXScsIFsgJ2Zvb3Rlci0zJywgJ2Zvb3Rlci00JyBdICk7XG5cblx0XHQvLyBPbmx5IHNob3cgdGhlIGZvbGxvd2luZyBvcHRpb25zLCBpZiBhIGxvZ28gaXMgdXBsb2FkZWQuXG5cdFx0Y3VzdG9taXplckltYWdlT3B0aW9uRGlzcGxheSggJ2N1c3RvbV9sb2dvJywgJ2xvZ29fd2lkdGgnICk7XG5cdFx0Y3VzdG9taXplckltYWdlT3B0aW9uRGlzcGxheSggJ2N1c3RvbV9sb2dvJywgJ2xvZ29fd2lkdGhfbW9iaWxlJyApO1xuXHR9ICk7XG5cbn0gKSggalF1ZXJ5ICk7XG4iLCIoIGZ1bmN0aW9uICggJCwgYXBpICkge1xuXG5cdGFwaS5iaW5kKCAncmVhZHknLCBmdW5jdGlvbiAoKSB7XG5cdFx0JCggJy5yYW5nZV9jb250cm9sX19yZXNldCcgKS5vbiggJ2NsaWNrJywgZnVuY3Rpb24gKCkge1xuXHRcdFx0Y29uc3QgcmFuZ2VDb250YWluZXIgPSAkKCB0aGlzICkucGFyZW50KCk7XG5cblx0XHRcdGNvbnN0IHJhbmdlSW5wdXQgICA9IHJhbmdlQ29udGFpbmVyLmZpbmQoICdpbnB1dFtkYXRhLWlucHV0LXR5cGU9XCJyYW5nZVwiXScgKTtcblx0XHRcdGNvbnN0IGNvbnRyb2xWYWx1ZSA9IHJhbmdlQ29udGFpbmVyLmZpbmQoICcucmFuZ2VfY29udHJvbF9fdmFsdWUnICk7XG5cblx0XHRcdGNvbnN0IGRlZmF1bHRWYWx1ZSA9IHJhbmdlSW5wdXQuZGF0YSggJ2RlZmF1bHQtdmFsdWUnICk7XG5cblx0XHRcdHJhbmdlSW5wdXQudmFsKCBkZWZhdWx0VmFsdWUgKTtcblx0XHRcdGNvbnRyb2xWYWx1ZS5maW5kKCAnc3BhbicgKS5odG1sKCBkZWZhdWx0VmFsdWUgKTtcblx0XHRcdGNvbnRyb2xWYWx1ZS5maW5kKCAnaW5wdXQnICkudmFsKCBkZWZhdWx0VmFsdWUgKTtcblxuXHRcdFx0Y29uc3QgY3VzdG9taXplU2V0dGluZyA9IGNvbnRyb2xWYWx1ZS5maW5kKCAnaW5wdXQnICkuZGF0YSggJ2N1c3RvbWl6ZS1zZXR0aW5nLWxpbmsnICk7XG5cdFx0XHR3cC5jdXN0b21pemUuY29udHJvbCggY3VzdG9taXplU2V0dGluZyApLnNldHRpbmcuc2V0KCBkZWZhdWx0VmFsdWUgKTtcblx0XHR9ICk7XG5cdH0gKTtcblxufSApKCBqUXVlcnksIHdwLmN1c3RvbWl6ZSApO1xuIl0sIm1hcHBpbmdzIjoiO0FBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7Ozs7Ozs7Ozs7OztBQ2xGQTtBQUFBO0FBQUE7QUFBQTtBQUVBOzs7Ozs7O0FBT0E7QUFFQTtBQUVBOzs7Ozs7Ozs7O0FBVUE7QUFBQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFFQTs7Ozs7Ozs7Ozs7QUFTQTtBQUFBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBRUE7QUFDQTtBQUNBO0FBRUE7QUFDQTtBQUNBO0FBRUE7Ozs7Ozs7Ozs7O0FDcEZBO0FBRUE7QUFDQTtBQUNBO0FBRUE7QUFDQTtBQUVBO0FBRUE7QUFDQTtBQUNBO0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFFQTs7OztBIiwic291cmNlUm9vdCI6IiJ9