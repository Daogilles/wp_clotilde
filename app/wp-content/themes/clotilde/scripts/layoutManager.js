/**
 * module de gestion de l'interface utilisateur
 */

        //console.info('layoutManager --> LOADED');

        /**
         * Constructeur du module
         */
        CLO.LayoutManager = function () {
            /**
             * Variable privée au constructeur
             */


            var base = this;
            var loadT, loadM, timeG;
            CLO.config.screen.width = $(window).width();
            CLO.config.screen.height = $(window).height();
            /**
             * Initialisation du module
             */
            this.initialize = function () {

                CLO.config.screen.width = $(window).width();
                CLO.config.screen.height = $(window).height();
                if ( CLO.config.device != 'mobile' ) {
                    var ieVersion = base.getIeBrowserVersion();
                }

                var isAndroid = this.isAndroid();
                
                if (isAndroid) {
                    $('body').addClass('android');
                } else if (ieVersion !== -1 && ieVersion <= 9) {
                    $('body').addClass('msie');
                } else {
                    $('body').addClass('modern');
                }
                
                // $menu = $('.menu-menu-container');
                // $hover = $('<span class="menu-active-elmt"></span>');
                // $menu.append($hover);

                CLO.config.itemSize = $('div.scroll').length;
                // console.log($('div.scroll').length);
                CLO.config.activeItem = 0;

                // $('.menu-item:nth-child(1)').addClass('active');

                if( !($('html').hasClass('mobile')) ){
                    if(CLO.config.screen.width <= 800){

                    }
                }else{
                    
                }

                var method;
                var noop = function () {};
                var methods = [
                    'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
                    'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
                    'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
                    'timeStamp', 'trace', 'warn'
                ];
                var length = methods.length;
                var console = (window.console = window.console || {});

                while (length--) {
                    method = methods[length];

                    // Only stub undefined methods.
                    if (!console[method]) {
                        console[method] = noop;
                    }
                }

                if(!($("html").hasClass('ie8')) ){
                    
                }else{

                }
                
            };
            
            /**
             * Function qui teste si le type du navigateur est msie
             * @see http://msdn.microsoft.com/en-us/library/ms537509(v=vs.85).ASPx
             */
            
            this.getIeBrowserVersion = function() {
                // Returns the version of Internet Explorer or a -1
                // (indicating the use of another browser).
                var rv = -1; // Return value assumes failure.
                if (navigator.appName == 'Microsoft Internet Explorer')
                {
                    var ua = navigator.userAgent;
                    var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
                    if (re.exec(ua) != null) {
                        rv = parseFloat( RegExp.$1 );    
                    }
                }
                return rv;
            };

            this.isAndroid = function () {
                var platform = false;
                if (window.device) {
                    if (window.device.platform) {
                        switch (window.device.platform.toLowerCase()) {
                            case 'android':
                                platform = 'android';
                                break;
                        }
                    } else {
                        if (navigator.userAgent.match(/(android)/i)) {
                            platform = 'android';
                        }
                    }

                } else {
                    if (navigator.userAgent.match(/(android)/i)) {
                        platform = 'android';
                    }
                }

                //return 'android';
                return platform;

            };

        }
