(function() {

        var plugin_name = 'affiliate_link_programs_button';
        
        
        // "editor_tracked_button" is the name of the registered plugin
        // into the Button class
        tinymce.create('tinymce.plugins.' + plugin_name , {
            
            /**
             * Initializes the plugin, this will be executed after the plugin has been created.
             * This call is done before the editor instance has finished it's initialization so use the onInit event
             * of the editor instance to intercept that event.
             *
             * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
             * @param {string} url Absolute URL to where the plugin is located.
             */
            init : function(ed, url) {
    
                    // Register the button
                    ed.addButton(plugin_name, {
                            title : 'Inserisci Program Link',
                            cmd : plugin_name + '_cmd',
                            icon: 'link',
                            image : url + '/assets/favourite.svg'
                    });
    
                    
                    // Register the command so that it can be invoked by using 
                    // tinyMCE.activeEditor.execCommand('affiliate_link');
                    ed.addCommand(plugin_name + '_cmd', 
                            
                            // callback
                            function() {

                                var post = window.location.search.replace(/.*(post=\d+).*/,'$1');
                                
                                ed.windowManager.open({                                        
                                        file : ajaxurl + '?action=affiliate_link_programs_action&' + post,
                                        width  : 650,
                                        height : 400,
                                        inline : 1
                                }, {
                                        plugin_url : url, // Plugin absolute URL
                                        //some_custom_arg : 'custom arg' // Custom argument
                                });
                            }
                    );
    
    
                    // Add a node change handler, selects the button in the UI when a image is selected
                    ed.onNodeChange.add(function(ed, cm, n) {
                            cm.setActive(plugin_name, n.nodeName == 'IMG');
                    });
            },
    
            /**
             * Creates control instances based in the incomming name. This method is normally not
             * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
             * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
             * method can be used to create those.
             *
             * @param {String} n Name of the control to create.
             * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
             * @return {tinymce.ui.Control} New control instance or null if no control was created.
             */
            createControl : function(n, cm) {
                    return null;
            },
    
            /**
             * Returns information about the plugin as a name/value array.
             * The current keys are longname, author, authorurl, infourl and version.
             *
             * @return {Object} Name/value array containing information about the plugin.
             */
            getInfo : function() {
                    return {
                            longname : plugin_name + ' plugin',
                            author : 'Some author',
                            authorurl : 'http://tinymce.moxiecode.com',
                            infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/' + plugin_name,
                            version : "1.0"
                    };
            }
        });
        
        
        // Register plugin
        tinymce.PluginManager.add(plugin_name, tinymce.plugins[plugin_name]);
    
    
    })();