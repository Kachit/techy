define( 'Page.PasswordRecovery', [ 'Page', 'Form' ], function( Page, Form ){

    return OClass( Page, {
        name: 'PasswordRecovery',

        onLoad: function(){
            new Form( $('#passwordRecoveryForm'), {
                fields: {
                    login: {
                        validators: [{ name: 'NotEmpty', msg: I18n.get( 'auth.forms.email.notEmpty' ) }]
                    }
                }
            });
        }
    });
});
