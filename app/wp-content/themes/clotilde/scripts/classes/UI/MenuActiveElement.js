(function () {

    function State(left, right) {
        this.left = left;
        this.right = right;
    };

    window.CLO.UI = window.CLO.UI || {};

    CLO.UI.MenuActiveElement = Backbone.View.extend({
        tagName : 'span',
        className : 'menu-active-elmt',
        $wrapperElmt : null,
        menuElmts : {},
        currentElmt : null,
        $prevElmt : null,
        currentState : null,

        left : 0,
        width : 0,
        initialize : function(param) {
            this.$wrapperElmt = param.$wrapperElmt;
            var scope = this;
            this.$wrapperElmt.find('a').each(function() {
                var $t = $(this),
                    url = $t.attr('href').replace(CLO.CONFIG.ROOT_URL, '').replace(/\/$/,"");

                scope.menuElmts[url] = {
                  $e : $t,
                  width : 0,
                  left : 0,
                  url : url
                };
                if($t.parent().hasClass('current-menu-item')) {
                    scope.currentElmt = scope.menuElmts[url];
                }
            });

            this.currentState = null;
        },
        create : function() {
            this.$el.appendTo(this.$wrapperElmt);
            this.resize(CLO._.wW, CLO._.wH);
            this.currentElmt && this.goTo(this.currentElmt);
            return this;
        },
        findByUrl : function(url) {
            if(!url || !url.length) return;
            url = url.replace(/\/$/,"");

            this.goTo(this.menuElmts[url] || null);

        },
        goTo : function(elmt) {
            var scope = this;
            var nextState = new State(0, 0);
            if(elmt) {
                //mettre toute les données left/width en cache
                nextState.left = ( elmt.left - this.left );
                nextState.right = this.width - ( nextState.left + elmt.width );
            } else {
                if(this.currentState && this.currentElmt) {
                    TweenLite.to(this.$el, 0.2, { right : this.currentState.right + this.currentElmt.width /2, left : this.currentState.left + this.currentElmt.width / 2});
                }
                this.currentState = null;
                return;
            }

            if(!this.currentState) { // voilà voilà voilà
                TweenLite.set(this.$el, {right : nextState.right + elmt.width / 2, left: nextState.left + elmt.width/2});
                TweenLite.to(this.$el, 0.2, { right : nextState.right, left : nextState.left});
            } else {
                if(nextState.left > this.currentState.left) {

                    TweenLite.to(this.$el, 0.2, { right : this.currentState.right + 10, ease : Power2.easeOut, onComplete : function() {
                        TweenLite.to(scope.$el, 0.2, { right : nextState.right, ease : Power4.easeInOut});

                        setTimeout(function() {
                            TweenLite.to(scope.$el, 0.3, { left : nextState.left + 10, ease : Power4.easeOut, onComplete : function() {
                                TweenLite.to(scope.$el, 0.1, { left : nextState.left, ease : Power4.easeInOut});
                            }});
                        }, 120);
                    }});
                } else if(nextState.left < this.currentState.left) {


                    TweenLite.to(this.$el, 0.2, { left : this.currentState.left + 10, ease : Power2.easeOut, onComplete : function() {
                        TweenLite.to(scope.$el, 0.2, { left : nextState.left, ease : Power4.easeInOut});

                        setTimeout(function() {
                            TweenLite.to(scope.$el, 0.3, { right : nextState.right + 10, ease : Power4.easeOut, onComplete : function() {
                                TweenLite.to(scope.$el, 0.1, { right : nextState.right, ease : Power4.easeInOut});
                            }});
                        }, 120);
                    }});

                } else {
                    TweenLite.to(this.$el, 0.2, { right : nextState.right, left : nextState.left});
                }
            }


            this.currentElmt = elmt;
            this.currentState = nextState;
        },
        resize : function(w, h) {
            var scope = this;
            // this.left = this.$wrapperElmt.offset().left;
            // this.width = this.$wrapperElmt.width();
            // _.each(this.menuElmts, function(obj, key) {0
            //     obj.left = obj.$e.offset().left;
            //     obj.width = obj.$e.width();
            //     scope.menuElmts[key] = obj;
            // });
            //@todo : surement faire un goTo ou un truc du genre
        }
    });

})();