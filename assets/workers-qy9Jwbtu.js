function _classCallCheck(a, n) { if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function"); }
function _defineProperties(e, r) { for (var t = 0; t < r.length; t++) { var o = r[t]; o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o); } }
function _createClass(e, r, t) { return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", { writable: !1 }), e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
var b = Object.defineProperty;
var p = function p(i, t, o) {
  return t in i ? b(i, t, {
    enumerable: !0,
    configurable: !0,
    writable: !0,
    value: o
  }) : i[t] = o;
};
var u = function u(i, t, o) {
  return p(i, _typeof(t) != "symbol" ? t + "" : t, o);
};
(function () {
  "use strict";

  var i = {
    name: "main-db"
  };
  JSON.parse(JSON.stringify(self.LOBBY_SITE_CONFIG || null));
  var t = /*#__PURE__*/function () {
    function t(s) {
      _classCallCheck(this, t);
      u(this, "dbPromise");
      u(this, "dbConfig");
      u(this, "dataBase");
      this.dbConfig = s;
    }
    return _createClass(t, [{
      key: "isReady",
      value: function isReady() {
        var _this = this;
        return indexedDB ? (this.dbPromise || (this.dbPromise = new Promise(function (s, e) {
          if (!indexedDB) {
            console.error("indexedDB not supported!"), e("indexedDB not supported!");
            return;
          }
          var _this$dbConfig = _this.dbConfig,
            r = _this$dbConfig.name,
            d = _this$dbConfig.version,
            n = indexedDB.open(r, d);
          n.onerror = function () {
            console.error("database open failed!", n.error), _this.dbPromise = void 0, e(n.error);
          }, n.onsuccess = function () {
            var a = n.result;
            _this.dataBase = a, s(a);
          };
        })), this.dbPromise) : (console.error("indexedDB not supported!"), Promise.reject("indexedDB not supported!"));
      }
    }, {
      key: "read",
      value: function read(s) {
        var _this2 = this;
        var e = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : "httpResponses";
        return new Promise(function (r, d) {
          if (!_this2.dataBase.objectStoreNames.contains(e)) return r("Table not created");
          var a = _this2.dataBase.transaction([e]).objectStore(e).get(s);
          a.onerror = function () {
            console.error("database read failed!", a.error), d(a.error);
          }, a.onsuccess = function () {
            r(a.result || null);
          };
        });
      }
    }]);
  }();
  var o = new t({
    name: i.name
  });
  function l(c, s, e) {
    var _this3 = this;
    var r = e.key,
      d = e.dataSheetName;
    o.isReady().then(function () {
      o.read(r, d).then(function (n) {
        _this3.postMessage({
          uuid: c,
          type: s,
          status: 1,
          payload: n
        });
      });
    });
  }
  self.onmessage = function (c) {
    var _this4 = this;
    var _c$data = c.data,
      s = _c$data.uuid,
      e = _c$data.type,
      r = _c$data.payload;
    switch (e) {
      case "DB_GET_BY_KEY":
        l.call(this, s, e, r);
        break;
      case "DB_READY":
        o.isReady().then(function () {
          _this4.postMessage({
            uuid: s,
            type: e,
            status: 1,
            payload: {
              messages: "pong"
            }
          });
        });
        break;
      default:
        console.error("Unknown event type: ".concat(e));
    }
  };
})();