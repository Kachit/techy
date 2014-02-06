define( 'Page.Admins', [ 'Page', 'Form', 'Template', 'Globals' ], function( Page, Form, Template, Globals ){

    var TD_ADMIN = [
        '<tr><td>{{user_id}}</td><td>{{login}}</td></tr>'
    ].join('');

    return OClass( Page, {
        name: 'Admins',

        onLoad: function(){
            var $admins = $('#admins'),
                $formNewAdmin = $('#formNewAdmin');

            new Form( $formNewAdmin, {
                fields: {
                    login: {
                        validators: [{ name: 'NotEmpty', msg: 'Введите login' }]
                    }
                },
                success: function( json ){
                    var admin = json.admin;
                    if( admin ){
                        $admins.prepend( $( Template.parse( TD_ADMIN, admin )));
                    }
                }
            });
        }
    });
});
