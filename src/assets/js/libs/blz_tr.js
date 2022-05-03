"use strict";

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { _defineProperty(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }

/**
 * @class Tracker
 * 
 * @responsibility
 * track Google Analytics events of the given element list
 * keep count of the already tracked urls for each page and avoids tracking 
 * an url more than once for each page
 * 
 * allows the pageview tracking as well
 * 
 * @param {Object} args {
 *      selectors :  [ { selector : "string", type: "event|pageview", name : "name", args : {event_category, event_label, value } },  ... ],
 *      gaid : 'GA_MEASUREMENT_ID'
 * }
 * 
 * @tutorial https://developers.google.com/analytics/devguides/collection/gtagjs/sending-data
 * 
 *  FANTASTICO IL MODO DI CREARE GRUPPI SU ANALYTICS
 *  FANTASTICO IL FATTO CHE SI POSSONO FARE DELLE EVENT_CALLBACK
 */
var Tracker = /*#__PURE__*/function () {
  function Tracker(args) {
    var _this = this;

    _classCallCheck(this, Tracker);

    // google analytics measurament id
    this.gaid = typeof args.gaid === 'undefined' ? null : args.gaid; // array of selectors to track views and clicks

    this.selectors = typeof args.selectors === 'undefined' ? [] : // add a control for viewsEnabled default to true
    args.selectors.map(function (selector) {
      return _objectSpread(_objectSpread({}, {
        viewsEnabled: true
      }), selector);
    }); /// instantiate a new gtag if it's not present
    /// and disable automatic pageview tracking

    if (typeof gtag == 'undefined') {
      this.insertGTAG(this.gaid, function () {
        window.gtag('config', this.gaid, {
          'send_page_view': false,
          'groups': 'blztrkpgn'
        });
      }.bind(this));
    } else {
      window.gtag('config', this.gaid, {
        'send_page_view': false,
        'groups': 'blztrkpgn'
      });
    } /// bind visibility to this


    this.IntersectingEventCheck = this.IntersectingEventCheck.bind(this);
    this.trackEvt = this.trackEvt.bind(this); /// setup the observer

    this.observer = new IntersectionObserver(function (entries) {
      entries.forEach(_this.IntersectingEventCheck);
    });
    this.init();
  }

  _createClass(Tracker, [{
    key: "IntersectingEventCheck",
    value: function IntersectingEventCheck(entry) {
      if (entry.isIntersecting) {
        var data = JSON.parse(entry.target.dataset.blzTrackingData); /// call the callback

        this.trackEvt(data.action, data.args, this.gaid); /// stop observing

        this.observer.unobserve(entry.target);
      }
    }
    /**
     * Scan the selector list and initialize each tracker
     */

  }, {
    key: "init",
    value: function init() {
      var _this2 = this;

      // scan the selector list and initialize each tracker
      this.selectors.forEach(function (selector) {
        return _this2._initSelector(selector);
      });
    }
    /**
     * initialize a single selector to be tracked 
     * for clicks and views
     */

  }, {
    key: "_initSelector",
    value: function _initSelector(selector) {
      var _this3 = this;

      switch (selector.type) {
        case 'event':
          var elements = document.querySelectorAll(selector.selector);
          [].map.call(elements, function (element) {
            var args = typeof selector.args != 'undefined' ? selector.args : {
              'event_category': 'track'
            };

            _this3.initClick(element, selector.name, args);

            if (selector.viewsEnabled) _this3.initView(element, selector.name, args);
          });
          break;

        case 'pageview':
          break;
      }
    }
    /**
     * @method initView
     *
     */

  }, {
    key: "initView",
    value: function initView(element, name, args) {
      // check if already in the watch queue
      if (typeof element.dataset.blzTracking !== 'undefined') return;
      element.dataset.blzTrackingData = JSON.stringify({
        action: name + ' view',
        args: args
      }); // Aggiungere gli elementi all'observer

      this.observer.observe(element);
    }
    /**
     * @method initClick
     * prende tutti i selettori e attiva gli eventi per rilevare i click
     *
     * @param {*} element
     */

  }, {
    key: "initClick",
    value: function initClick(element, name, args) {
      var _this4 = this;

      var action = name + ' click';
      element.addEventListener('click', function () {
        return _this4.trackEvt(action, args);
      });
    }
    /**
     * @method add
     * 
     * tells the tracker to start tracking a selector views and clicks
     * set event name and category by args, action is automatic
     * 
     * @param {string} selector 
     * @param {'event'|'pageview'} type      
     * @param {string} name 
     * @param {object} args : { page_title : 'string', page_path : 'string', event_name : 'string', event_category : 'string'}     
     * @param {bool} viewsEnabled : default true
     */

  }, {
    key: "add",
    value: function add(selector, type, name, args, viewsEnabled) {
      var _viewsEnabled = typeof viewsEnabled != 'undefined' ? viewsEnabled : true;

      var _selector = {
        selector: selector,
        type: type,
        name: name,
        viewsEnabled: _viewsEnabled
      };
      if (typeof args != 'undefined') _selector.args = args; // console.log('_t| add to tracking: ',_selector);

      this._initSelector(_selector);
    }
    /**
     * @method trackPV
     * 
     * @param {page_title, page_path} args 
     * @param {*} GAID - google analytics measurament id
     */

  }, {
    key: "trackPV",
    value: function trackPV(args, GAID) {
      var gaid = typeof GAID != 'undefined' ? GAID : this.gaid;
      if (gaid == null) return;
      var title = null;
      var path = null; // path without the domain

      var currentpath = window.location.pathname + window.location.search + window.location.hash;

      if (args != null) {
        title = typeof args.page_title != 'undefined' ? args.page_title : document.title;
        path = typeof args.page_path != 'undefined' ? args.page_path : currentpath;
        gtag('config', gaid, {
          'page_title': title,
          'page_path': path
        });
      } else {
        gtag('config', gaid);
      }
    }
    /**
     * @method trackEvt
     *
     * @param {string} name { 
     * @param {Object} args { 
     *      'callback' : function
     *      'event_category': <category>,
     *      'event_label': <label>,
     *      'value': <value>
     * }
     * @param {*} GAID - google analytics measurament id
     */

  }, {
    key: "trackEvt",
    value: function trackEvt(name, args, GAID) {
      var gaid = typeof GAID != 'undefined' ? GAID : this.gaid;
      var evt_args = typeof args === 'undefined' ? {} : args; // evt_args.send_to = 'blztrkpgn';  //  { 'send_to' : 'blztrkpgn' };

      evt_args.send_to = gaid; //  { 'send_to' : GAID' };

      evt_args.event_callback = function () {
        console.log('_t| now tracking... ', name, evt_args, gaid);
      }.bind(this);

      if (gaid == null) return;
      if (typeof name == 'undefined' || name == null || name == '') return;
      window.gtag('event', name, evt_args);
    }
    /**
     * Instatiate a new GA tracker
     * and execute a callback when loaded
     * 
     * @param {string}   GAID 
     * @param {function} callback 
     */

  }, {
    key: "insertGTAG",
    value: function insertGTAG(GAID) {
      var callback = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : function () {};
      var script = document.createElement('script');
      script.async = true;
      script.src = "https://www.googletagmanager.com/gtag/js?id=".concat(GAID); /// execute the callback when loaded

      script.addEventListener("load", callback);
      var init = document.createElement('script');
      init.textContent = "\n            window.dataLayer = window.dataLayer || [];\n            function gtag(){ dataLayer.push(arguments); }\n            gtag('set', { 'send_page_view': false });\n            gtag('js', new Date());            \n        ";
      document.head.appendChild(script);
      document.head.appendChild(init);
    }
  }]);

  return Tracker;
}();
/*
https://developers.google.com/analytics/devguides/collection/gtagjs/events

gtag('event', <action>, {
  'event_category': <category>,
  'event_label': <label>,
  'value': <value>
});

<action> is the string that will appear as the event action in Google Analytics Event reports.
<category> is the string that will appear as the event category.
<label> is the string that will appear as the event label.
<value> is a non-negative integer that will appear as the event value.
*/