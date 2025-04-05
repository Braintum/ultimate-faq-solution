/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ ((module) => {

module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/***/ ((module) => {

module.exports = window["wp"]["blocks"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["data"];

/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

module.exports = window["React"];

/***/ }),

/***/ "react/jsx-runtime":
/*!**********************************!*\
  !*** external "ReactJSXRuntime" ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["ReactJSXRuntime"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__);


 // Import useState from React



(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)('ultimate-faq-solution/block', {
  title: 'FAQ',
  icon: 'editor-help',
  category: 'widgets',
  attributes: {
    group: {
      type: 'string',
      default: ''
    },
    exclude: {
      type: 'array',
      default: []
    },
    column: {
      type: 'string',
      default: []
    },
    behaviour: {
      type: 'string',
      default: []
    },
    elements_order: {
      type: 'string',
      default: []
    },
    hideTitle: {
      type: 'boolean',
      default: false // default to false
    }
  },
  /**
   * Edit function for the Ultimate FAQ block.
   *
   * This function renders the block's edit interface, including the settings panel
   * for selecting FAQ groups and excluding specific groups when "All" is selected.
   *
   * @param {Object} props - The properties passed to the edit function.
   * @param {Object} props.attributes - The block's attributes.
   * @param {Function} props.setAttributes - Function to update block attributes.
   * @param {boolean} props.isSelected - Whether the block is selected in the editor.
   * @param {Object} props.context - The block's context.
   *
   * @returns {JSX.Element} The edit interface for the block.
   */
  edit({
    attributes,
    setAttributes,
    isSelected,
    context
  }) {
    const faqGroups = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.useSelect)(select => {
      return select('core').getEntityRecords('postType', 'ufaqsw', {
        per_page: -1
      });
    }, []);
    const columns = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.useSelect)(select => {
      return [{
        label: 'Choose a column',
        value: ''
      }, {
        label: '1',
        value: '1'
      }, {
        label: '2',
        value: '2'
      }, {
        label: '3',
        value: '3'
      }];
    }, []);
    const behaviours = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.useSelect)(select => {
      return [{
        label: 'Choose a behaviour',
        value: ''
      }, {
        label: 'Accordion',
        value: 'accordion'
      }, {
        label: 'Toggle',
        value: 'toggle'
      }];
    }, []);
    const orders = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.useSelect)(select => {
      return [{
        label: 'Choose an order',
        value: ''
      }, {
        label: 'ASC',
        value: 'asc'
      }, {
        label: 'DESC',
        value: 'desc'
      }];
    }, []);
    const shortcode = function () {
      if (!attributes.group) {
        return '';
      }
      let text = '[ ';
      if ('all' === attributes.group) {
        text += 'ufaqsw-all ';
        if (attributes.exclude.length > 0) {
          text += 'exclude="' + attributes.exclude.join(',') + '" ';
        }
        if (attributes.column.length > 0) {
          text += 'column="' + attributes.column + '" ';
        }
        if (attributes.behaviour.length > 0) {
          text += 'behaviour="' + attributes.behaviour + '" ';
        }
      } else {
        text += 'ufaqsw id="' + attributes.group + '" ';
      }
      if (attributes.elements_order.length > 0) {
        text += 'elements_order="' + attributes.elements_order + '" ';
      }
      if (attributes.hideTitle) {
        text += 'title_hide="1" ';
      }
      text += ']';
      return text;
    };
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.Fragment, {
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_4__.InspectorControls, {
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelBody, {
          title: "FAQ Settings",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {
            label: "Select FAQ Group",
            help: "Choose a group to display FAQs from.",
            value: attributes.group,
            options: faqGroups ? [{
              label: 'Select a FAQ group',
              value: ''
            }, {
              label: 'All',
              value: 'all'
            }].concat(faqGroups.map(group => ({
              label: group.title,
              value: group.id
            }))) : [{
              label: 'Loading...',
              value: ''
            }],
            onChange: value => setAttributes({
              group: value
            })
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.CheckboxControl, {
            label: "Hide group title",
            help: "Check to hide the group title. This will override the faq group setting.",
            checked: attributes.hideTitle,
            onChange: value => setAttributes({
              hideTitle: value
            })
          }), attributes.group === 'all' && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {
            label: "Behaviour",
            help: "Choose a behaviour for the FAQ display. This will override the faq group settings.",
            value: attributes.behaviour,
            options: behaviours,
            onChange: value => setAttributes({
              behaviour: value
            })
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {
            label: "Order",
            value: attributes.elements_order,
            options: orders,
            onChange: value => setAttributes({
              elements_order: value
            })
          }), attributes.group === 'all' && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {
            label: "Column",
            help: "Select Column to display FAQs from.",
            value: attributes.column,
            options: columns,
            onChange: value => setAttributes({
              column: value
            })
          }), attributes.group === 'all' && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelBody, {
            title: "Exclude FAQ Groups",
            children: faqGroups && faqGroups.map(group => /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.CheckboxControl, {
              label: group.title,
              checked: attributes.exclude.includes(group.id),
              onChange: isChecked => {
                const updatedExclude = isChecked ? [...attributes.exclude, group.id] : attributes.exclude.filter(id => id !== group.id);
                setAttributes({
                  exclude: updatedExclude
                });
              }
            }, group.id))
          })]
        })
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
        className: "components-placeholder is-large",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          class: "components-placeholder__label",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("span", {
            className: "faq-block-icon dashicons dashicons-editor-help",
            role: "img",
            "aria-label": "FAQ Icon"
          }), " FAQ Block"]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
          className: "components-placeholder__instructions",
          children: "Configure the block from right panel - Faq Settings"
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "components-placeholder__fieldset",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("label", {
            children: "Generated Shortcode"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("input", {
            className: "components-text-control__input",
            type: "text",
            disabled: true,
            value: shortcode() // Dynamically set the value using the shortcode function
          })]
        })]
      })]
    });
  },
  save() {
    // Rendered server-side via shortcode, so return null
    return null;
  }
});
})();

/******/ })()
;
//# sourceMappingURL=index.js.map