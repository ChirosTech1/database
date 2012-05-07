obj.ajax_lists['available_servers'] = new ajax_list($('available_servers').getElement('div.ajax_list'), {
            query: {
                layout: 'dashboard/available_servers_item',
                model: 'encoding_servers',
                limit: -1,
               
                hide_header: true,
                hide_labels: true,
                hide_footer: true
            },
            auto_refresh: true,
           
            // I traced down the memory leak here, everything else is fine
            onRefresh: function() {
                // Move servers around and add text
                $('faulty_servers').empty();
                $('available_servers').getElements('div.ajax_list li.server.error').each(function(server) {
                    server.inject('faulty_servers');
                });
                $('servers_down').getElement('span.no_servers_down').set('text', down_servers.length);                 
                $('available_servers').getElement('span.no_avail_servers').set('text', $('available_servers').getElements('div.ajax_list li.server').length);
               
                $$('li.server').each(function(server) {
                    server.addEvent('click', function() {
                        new servers.ajax_server_view(server.getElement('.serverid').get('text'), {
                            events: {
                                success_submit: function() {
                                    obj.ajax_lists['available_servers'].refresh();
                                }
                            }
                        });                        
                    });
                });
 
                // Add tips to the server
                new Tips('li.server', {
                    hideDelay: 5,
                    showDelay: 0,
                    className: 'tips'
                });
               
                $$('.tips').setStyle('visibility', 'hidden');
            }.bind(obj.ajax_lists['available_servers'])
        });
