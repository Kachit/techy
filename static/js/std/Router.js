define( 'Router', [ 'Globals' ], function( Globals ){

var
    Module = this,

    page = null;

    $(document).ready(function(){
        var controller = Globals.get( 'controller');
        if( controller ){
            var Page = Module.getModule( 'Page.' + controller );
            page = new Page();
        }
    });

    return {};
});
