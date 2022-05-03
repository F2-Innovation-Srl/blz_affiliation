/** 
 * Traccia link di affiliazione 
 * 
 * che espongono  il seguente output:
 * <a href="{{URL}}" data-vars-blz-affiliate="{{eventLabel}}" target="_blank" rel="nofollow">{{text}}</a>
 * 
 */
 function AffiliateLinkActivator(container, number, GAID) {

    this.GAID = (typeof GAID == 'undefined') ? 'UA-118680447-7' : GAID;  // il default è TEST OMNIA
    this.number = (typeof number == 'undefined') ? 0 : number;
    this.container = container;

    this.init = function() {

        this.selectors = [].map.call(this.container.querySelectorAll('[data-vars-blz-affiliate]'), function(a, i){
            
            /// bisogna dare a ciascun elemento 
            /// un id
            a.id = 'affiliation_tracking_'+this.number+'_'+i;

            return { 
                selector : '#'+a.id,
                type: "event", 
                name : a.dataset.varsBlzAffiliate,
                args : {event_category : 'affiliate-tracking', event_label : location.pathname } 
            }
        }.bind(this));

        console.log('_activators ', this.selectors);

        if(this.selectors.length)
            this.tracker = new Tracker({ 
                selectors : this.selectors,                
                gaid : this.GAID
            });

    };

    this.init();
}

function AffiliateLinksManager( GAID ) {

    this.GAID = (typeof GAID == 'undefined') ? 'UA-118680447-7' : GAID;  // il default è TEST OMNIA
    this.affiliateLinks = [];

    /**
     * 
     * @param {*} index 
     */
    this.exists = function(index) {
        
        return (typeof this.affiliateLinks[index] !== 'undefined');
    };
    
    /**
     * 
     * @param {*} index 
     * @param {*} container 
     */
    this.add = function(index, container) {

        if(typeof container != 'undefined')
            if(container.querySelector('[data-vars-blz-affiliate]') != null)
                this.affiliateLinks[index] = new AffiliateLinkActivator(container, index, this.GAID);
    };

}
document.addEventListener("DOMContentLoaded", function() {

    // prende il GA ID dalla pagina. il default è TEST OMNIA
    var blz_GAID = (typeof blz_affiliation_ga == 'undefined') ? 'UA-118680447-7' : blz_affiliation_ga;

    if( document.querySelector('[data-vars-blz-affiliate]') ) {
        if (typeof UI !== 'undefined' ) {

            if( UI.infinite ) {

                var affLinks = new AffiliateLinksManager( blz_GAID );

                var ui_container = document.querySelector(UI.infinite.container);
                var ui_contents = ui_container.querySelectorAll('article');

                var index   = UI.infinite.current_content;
                var container = ui_contents[index];

                console.log('__ ui_contents',ui_contents);

                if(!affLinks.exists(index))
                    affLinks.add(index, container);

                UI.infinite.loader.loadCallbackStack.push(function(){

                    ui_contents = ui_container.querySelectorAll('article');
                
                    for(var i=0; i< ui_contents.length; i++){

                        container = ui_contents[i];
                        if(!affLinks.exists(i))
                            affLinks.add(i, container);
                    }

                }.bind(this));
            }

        } 
    
        if ( typeof affLinks == 'undefined' || (typeof affLinks != 'undefined' && !affLinks.exists(0) )) {

                var affiliateLinks = new AffiliateLinkActivator( document.body, 0, blz_GAID );
            }
        }

}.bind(this));