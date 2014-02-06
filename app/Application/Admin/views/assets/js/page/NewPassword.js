define( 'Page.NewPassword', [ 'Page', 'Form' ], function( Page, Form ){

    return OClass( Page, {
        name: 'NewPassword',

        onLoad: function(){
            new Form( $('#newPasswordForm'), {
                fields: {
                    password: {
                        validators: [
                            { name: 'NotEmpty', msg: I18n.get( 'auth.forms.password.notEmpty' ) },
                            { name: 'Common', msg: I18n.get( 'auth.forms.password.tooShort' ), fn: function( value ){
                                return value.length >= 6;
                            }}
                        ]
                    },
                    password2: {
                        validators: [
                            { name: 'NotEmpty', msg: I18n.get( 'auth.forms.password.confirmNotEmpty' ) },
                            { name: 'Common', msg: I18n.get( 'auth.forms.password.notEqual' ), fn: function( value ){
                                return value === this.$form.find('[name=password]').val();
                            }}
                        ]
                    }
                },
                success: function( content ){
                    if( content.success )
                        setTimeout( function(){ window.location.href = '/'; }, 2000 );
                }
            });
        }
    });
});
