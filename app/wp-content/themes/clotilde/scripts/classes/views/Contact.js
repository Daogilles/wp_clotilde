/**
 */
(function (w) {
    w.CLO.views = w.CLO.views || {};

    w.CLO.views.Contact = CLO.abstract.APageView.extend({
        $val: null,
        $contact: null,
    	events : {
            'focus input' : '_focus',
            'blur input' : '_blur',
            'focus textarea' : '_focus',
            'blur textarea' : '_blur',
            'submit #infoForm' : '_submit'
        },
        initialize : function(params) {
            CLO.abstract.APageView.prototype.initialize.call(this, params);            
        },
        _focus : function (e){
            $val = $(e.currentTarget).val();
            $(e.currentTarget).val('');
        },
        _blur : function (e){
            if($(e.currentTarget).val() == ''){
                $(e.currentTarget).val($val);
            }
            
        },
        _submit : function(e){
            var scope = this;
            scope.$contact = $('#infoForm');
            e.preventDefault();
            e.stopPropagation();
            scope.$contact.find('span').remove();
            var data = $(e.currentTarget).serialize() || {};
            $.ajax({
                url : $(e.currentTarget).attr('action'),
                data : data,
                dataType : 'json',
                type : 'POST',
                success : function(d) {
                    if(d.status == 'success'){
                        scope.$contact.append('<span>Merci, votre message a bien été envoyé.</span>');
                        setTimeout(function(){
                            scope.$contact.find('#nom').val('nom');
                            scope.$contact.find('#email').val('email');
                            scope.$contact.find('#objet').val('Objet');
                            scope.$contact.find('textarea').val('Message');
                            scope.$contact.find('span').fadeOut().remove();
                        }, 3000);
                        
                    }else{
                        scope.$contact.append('<span>Veuillez remplir tous les champs.</span>');
                    }
                }
            })
        },
        show : function() {
            var $high = document.querySelector('#contact');
            $high.style.height = (window.innerHeight-43)+'px';
        }
    });
})(window);
