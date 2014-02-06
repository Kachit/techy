define( 'Page.SignIn', [ 'Page', 'AuthMixin' ], function( Page, AuthMixin ){

    return OClass( Page, AuthMixin, {
        name: 'SignIn',

        onLoad: function(){
            this.initilizeAuth();
        }
    });

});
