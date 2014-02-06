define( 'AuthMixin', [ 'Form' ], function( Form ){

    return {
        initilizeAuth: function(){
            new Form( $('#signInForm'), {
                fields: {
                    login: {
                        validators: [{ name: 'NotEmpty', msg: I18n.get( 'auth.forms.email.notEmpty' ) }]
                    },
                    password: {
                        validators: [{ name: 'NotEmpty', msg: I18n.get( 'auth.forms.password.notEmpty' ) }]
                    }
                }
            });
        }
    };
});
