(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[5],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/pages/Requests.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/pages/Requests.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_Pagination_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../components/Pagination.vue */ "./resources/js/components/Pagination.vue");
/* harmony import */ var mdbvue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! mdbvue */ "./node_modules/mdbvue/lib/index.js");
/* harmony import */ var mdbvue__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(mdbvue__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _util__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../util */ "./resources/js/util.js");


function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
 // ★ 追加



/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      requests: [],
      currentPage: 0,
      lastPage: 0,
      is_active: false
    };
  },
  components: {
    mdbBtn: mdbvue__WEBPACK_IMPORTED_MODULE_2__["mdbBtn"],
    mdbListGroup: mdbvue__WEBPACK_IMPORTED_MODULE_2__["mdbListGroup"],
    mdbListGroupItem: mdbvue__WEBPACK_IMPORTED_MODULE_2__["mdbListGroupItem"],
    mdbInput: mdbvue__WEBPACK_IMPORTED_MODULE_2__["mdbInput"],
    mdbTextarea: mdbvue__WEBPACK_IMPORTED_MODULE_2__["mdbTextarea"],
    mdbFormInline: mdbvue__WEBPACK_IMPORTED_MODULE_2__["mdbFormInline"],
    mdbModal: mdbvue__WEBPACK_IMPORTED_MODULE_2__["mdbModal"],
    mdbModalHeader: mdbvue__WEBPACK_IMPORTED_MODULE_2__["mdbModalHeader"],
    mdbModalTitle: mdbvue__WEBPACK_IMPORTED_MODULE_2__["mdbModalTitle"],
    mdbModalBody: mdbvue__WEBPACK_IMPORTED_MODULE_2__["mdbModalBody"],
    mdbModalFooter: mdbvue__WEBPACK_IMPORTED_MODULE_2__["mdbModalFooter"],
    mdbIcon: mdbvue__WEBPACK_IMPORTED_MODULE_2__["mdbIcon"],
    Pagination: _components_Pagination_vue__WEBPACK_IMPORTED_MODULE_1__["default"]
  },
  // リクエスト取得
  methods: {
    fetchRequests: function fetchRequests() {
      var _this = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee() {
        var response;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                _context.next = 2;
                return axios.get("/api/requests");

              case 2:
                response = _context.sent;

                if (!(response.status !== _util__WEBPACK_IMPORTED_MODULE_3__["OK"])) {
                  _context.next = 6;
                  break;
                }

                _this.$store.commit('error/setCode', response.status);

                return _context.abrupt("return", false);

              case 6:
                ;
                _this.requests = response.data.data;
                console.log(_this.requests);
                _this.currentPage = response.data.current_page;
                _this.lastPage = response.data.last_page;

              case 11:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    },
    // リクエスト拒否
    refuseRequests: function refuseRequests(id, index) {
      var _this2 = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee2() {
        var response;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee2$(_context2) {
          while (1) {
            switch (_context2.prev = _context2.next) {
              case 0:
                _this2.is_active = true;
                _context2.next = 3;
                return axios["delete"]("/api/requests/".concat(id));

              case 3:
                response = _context2.sent;

                if (!(response.status !== _util__WEBPACK_IMPORTED_MODULE_3__["OK"])) {
                  _context2.next = 7;
                  break;
                }

                _this2.$store.commit('error/setCode', response.status);

                return _context2.abrupt("return", false);

              case 7:
                ;

                _this2.fetchRequests();

                _this2.is_active = false;

              case 10:
              case "end":
                return _context2.stop();
            }
          }
        }, _callee2);
      }))();
    },
    // リクエスト許可
    acceptRequests: function acceptRequests(id, index) {
      var _this3 = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee3() {
        var response;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee3$(_context3) {
          while (1) {
            switch (_context3.prev = _context3.next) {
              case 0:
                _this3.is_active = true;
                _context3.next = 3;
                return axios.put("/api/requests/".concat(id));

              case 3:
                response = _context3.sent;

                if (!(response.status !== _util__WEBPACK_IMPORTED_MODULE_3__["OK"])) {
                  _context3.next = 7;
                  break;
                }

                _this3.$store.commit('error/setCode', response.status);

                return _context3.abrupt("return", false);

              case 7:
                ;

                _this3.fetchRequests();

                _this3.is_active = false;

              case 10:
              case "end":
                return _context3.stop();
            }
          }
        }, _callee3);
      }))();
    },
    // リクエストをやめる
    removeRequest: function removeRequest(index) {
      delete this.requests[index];
    }
  },
  watch: {
    $route: {
      handler: function handler() {
        var _this4 = this;

        return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee4() {
          return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee4$(_context4) {
            while (1) {
              switch (_context4.prev = _context4.next) {
                case 0:
                  _context4.next = 2;
                  return _this4.fetchRequests();

                case 2:
                case "end":
                  return _context4.stop();
              }
            }
          }, _callee4);
        }))();
      },
      immediate: true
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/pages/Requests.vue?vue&type=template&id=2a642f66&":
/*!******************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/pages/Requests.vue?vue&type=template&id=2a642f66& ***!
  \******************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    [
      _vm.requests.length <= 0
        ? _c("div", { staticClass: "h4" }, [
            _vm._v("\n        チャットリクエストはありません。\n    ")
          ])
        : _vm._e(),
      _vm._v(" "),
      _vm.requests.length > 0
        ? _c(
            "mdb-list-group",
            { attrs: { flush: "" } },
            _vm._l(_vm.requests, function(request, index) {
              return _c("mdb-list-group-item", { key: index }, [
                _c("div", [
                  _c(
                    "div",
                    { staticClass: "d-flex w-100 justify-content-between" },
                    [
                      _c(
                        "small",
                        [
                          _c(
                            "router-link",
                            {
                              staticClass: "mb-2",
                              attrs: {
                                to: {
                                  name: "userDetail",
                                  params: { user_id: request.id }
                                }
                              }
                            },
                            [_vm._v(_vm._s(request.name))]
                          )
                        ],
                        1
                      )
                    ]
                  ),
                  _vm._v(" "),
                  _c("p", {
                    staticClass: "mb-2",
                    domProps: { innerHTML: _vm._s(request.pivot.message) }
                  }),
                  _vm._v(" "),
                  _c("small", [
                    _vm._v(
                      _vm._s(request.pivot.created_at) +
                        "\n\n                      "
                    ),
                    _c(
                      "span",
                      {
                        staticClass: "ml-1 h3 mousepointer-hand",
                        class: { active_fav: _vm.is_active },
                        on: {
                          click: function($event) {
                            return _vm.acceptRequests(request.id, index)
                          }
                        }
                      },
                      [
                        _c("mdb-icon", {
                          attrs: { icon: "check", color: "success" }
                        })
                      ],
                      1
                    ),
                    _vm._v(" "),
                    _c(
                      "span",
                      {
                        staticClass: "ml-1 h3 mousepointer-hand",
                        class: { active_fav: _vm.is_active },
                        on: {
                          click: function($event) {
                            return _vm.refuseRequests(request.id, index)
                          }
                        }
                      },
                      [
                        _c("mdb-icon", {
                          attrs: { icon: "times", color: "danger" }
                        })
                      ],
                      1
                    )
                  ])
                ])
              ])
            }),
            1
          )
        : _vm._e(),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "d-flex justify-content-center" },
        [
          _c("Pagination", {
            attrs: {
              component: "requests",
              "current-page": _vm.currentPage,
              "last-page": _vm.lastPage
            }
          })
        ],
        1
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/pages/Requests.vue":
/*!*****************************************!*\
  !*** ./resources/js/pages/Requests.vue ***!
  \*****************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Requests_vue_vue_type_template_id_2a642f66___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Requests.vue?vue&type=template&id=2a642f66& */ "./resources/js/pages/Requests.vue?vue&type=template&id=2a642f66&");
/* harmony import */ var _Requests_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Requests.vue?vue&type=script&lang=js& */ "./resources/js/pages/Requests.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Requests_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Requests_vue_vue_type_template_id_2a642f66___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Requests_vue_vue_type_template_id_2a642f66___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/pages/Requests.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/pages/Requests.vue?vue&type=script&lang=js&":
/*!******************************************************************!*\
  !*** ./resources/js/pages/Requests.vue?vue&type=script&lang=js& ***!
  \******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Requests_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./Requests.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/pages/Requests.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Requests_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/pages/Requests.vue?vue&type=template&id=2a642f66&":
/*!************************************************************************!*\
  !*** ./resources/js/pages/Requests.vue?vue&type=template&id=2a642f66& ***!
  \************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Requests_vue_vue_type_template_id_2a642f66___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./Requests.vue?vue&type=template&id=2a642f66& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/pages/Requests.vue?vue&type=template&id=2a642f66&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Requests_vue_vue_type_template_id_2a642f66___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Requests_vue_vue_type_template_id_2a642f66___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);