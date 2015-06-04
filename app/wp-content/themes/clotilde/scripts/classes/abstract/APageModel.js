/**
 */
(function (w) {
    w.CLO.abstract = w.CLO.abstract || {};

    var APageModel = Backbone.Model.extend({
        url : '',
        defaults : {
          id : null,
          content : null,
          title : null,
          seoTitle : '',
          seoDesc : '',
          bodyClass : '',
          pageType : '',
          data : []
        },
        isLoaded : false,
        isLoading : true,
        view : null,
        initialize : function(params) {
            this.url = CLO.CONFIG.ROOT_URL+( params.url || '');
            return this;
        },
        fetch : function(params) {
            var scope = this;
            this.isLoading = true;
            Backbone.Model.prototype.fetch.call(this, {
                success : function() {
                    scope.isLoading = false;
                    scope.isLoaded = true;
                    params.success && params.success();
                },
                error : function(model, xhr, options) {
                  window.log(model);
                        window.log(xhr);
                        window.log(options);
                    scope.isLoading = false;
                    scope.isLoaded = true;
                    params.error && params.error();
                }
            });
        }
    });

    w.CLO.abstract.APageModel = APageModel;
})(window);