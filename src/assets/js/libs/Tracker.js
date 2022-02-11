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
class Tracker {
    
    constructor (args) {

        // google analytics measurament id
        this.gaid      = ( typeof args.gaid     === 'undefined') ? null : args.gaid;
        
        // array of selectors to track views and clicks
        this.selectors = (typeof args.selectors === 'undefined') ? [] :
            // add a control for viewsEnabled default to true
            args.selectors.map( selector => ({ ...{ viewsEnabled : true } , ...selector }) );

        
        /// instantiate a new gtag if it's not present
        /// and disable automatic pageview tracking
        if( typeof gtag == 'undefined' ) {
            
            this.insertGTAG( this.gaid, () => { 
                gtag( 'config', this.gaid, { 'send_page_view': false, 'groups' : 'blz_ga_tracker' } ); 
            } );
        
        } else {                    
            gtag( 'config', this.gaid, { 'send_page_view': false, 'groups' : 'blz_ga_tracker'  } );
        }
    

        /// bind visibility to this
        
        this.IntersectingEventCheck = this.IntersectingEventCheck.bind( this );
        this.trackEvt = this.trackEvt.bind(this);

        /// setup the observer
        this.observer = new IntersectionObserver( entries => {
        
            entries.forEach( this.IntersectingEventCheck ); 
        });

        this.init();
    }

    
    IntersectingEventCheck( entry ) {

        if(entry.isIntersecting) {

            let data = JSON.parse( entry.target.dataset.blzTrackingData ) ;

            /// call the callback
            this.trackEvt( data.action, data.args, this.gaid );

            /// stop observing
            this.observer.unobserve( entry.target ); 
        }           

    }


    /**
     * Scan the selector list and initialize each tracker
     */
    init() {

        // scan the selector list and initialize each tracker
        this.selectors.forEach( selector => this._initSelector( selector ) );
               
    }
    

    /**
     * initialize a single selector to be tracked 
     * for clicks and views
     */ 
    _initSelector( selector ) {

        switch( selector.type ) {

            case 'event':
                
                const elements = document.querySelectorAll( selector.selector );

                [].map.call( elements, element => {

                    let args = ( typeof selector.args != 'undefined') ? selector.args : { 'event_category' : 'track' };
                    
                    this.initClick( element, selector.name, args);

                    if( selector.viewsEnabled ) 
                        this.initView( element, selector.name, args );

                });
                break;

            case 'pageview' :
                break;
        }
        
    }

    /**
     * @method initView
     *
     */
    initView( element, name, args ) {

        // check if already in the watch queue
        if( typeof element.dataset.blzTracking !== 'undefined' ) return;

        element.dataset.blzTrackingData = JSON.stringify( {action : name + ' view', args : args} );
        
        // Aggiungere gli elementi all'observer
        this.observer.observe( element );
    }


    /**
     * @method initClick
     * prende tutti i selettori e attiva gli eventi per rilevare i click
     *
     * @param {*} element
     */
    initClick(element, name, args) {
        
        let action = name + ' click';
        
        element.addEventListener('click', () => this.trackEvt( action, args ));
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
    add( selector, type, name, args, viewsEnabled){

        let _viewsEnabled = ( typeof viewsEnabled != 'undefined') ? viewsEnabled : true;
        
        let _selector = { 
            selector     : selector,
            type         : type,
            name         : name,
            viewsEnabled : _viewsEnabled
        };
        
        if(typeof args != 'undefined') _selector.args = args;
        // console.log('_t| add to tracking: ',_selector);
        this._initSelector( _selector );
    }


    /**
     * @method trackPV
     * 
     * @param {page_title, page_path} args 
     * @param {*} GAID - google analytics measurament id
     */
    trackPV(args, GAID) {

        let gaid = (typeof GAID != 'undefined') ? GAID : this.gaid;
        if(gaid == null) return;

        let title = null;
        let path = null;
        
        // path without the domain
        let currentpath =  window.location.pathname+window.location.search+window.location.hash;

        if(args != null) {
            title = (typeof args.page_title != 'undefined') ? args.page_title : document.title;
            path = (typeof args.page_path != 'undefined') ? args.page_path : currentpath; 
            
            gtag('config', gaid, { 'page_title' : title, 'page_path': path });
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
    trackEvt( name, args, GAID ) {

        let gaid = (typeof GAID != 'undefined') ? GAID : this.gaid;

        // console.log('_t| now tracking... ', name, args, gaid);
        
        if(gaid == null) return;
        if(typeof name == 'undefined' || name == null || name == '') return;

        let send_to = { 'send_to' : 'blz_ga_tracker'};

        if(typeof args != 'undefined')

            gtag('event', name, { ...send_to, ...args } );

        else

            gtag('event', name, send_to );
    }   


    /**
     * Instatiate a new GA tracker
     * and execute a callback when loaded
     * 
     * @param {string}   GAID 
     * @param {function} callback 
     */
    insertGTAG( GAID , callback = function(){} ) {
        
        var script = document.createElement('script');
        script.async = true;
        script.src = `https://www.googletagmanager.com/gtag/js?id=${GAID}`;
        
        /// execute the callback when loaded
        script.addEventListener( "load", callback );
        
        var init = document.createElement('script');
        init.textContent = `
            window.dataLayer = window.dataLayer || [];
            function gtag(){ dataLayer.push(arguments); }
            gtag('set', { 'send_page_view': false });
            gtag('js', new Date());            
        `; 
    
        document.head.appendChild(script);
        document.head.appendChild(init);
    }
}



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

