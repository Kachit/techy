define( 'Utils', [ 'Template' ], function( Template ){
    var $errors = $('#global-errors'),

        ALERT_TPL = '<div class="alert fade in{{1}}"><a class="close" data-dismiss="alert" href="#">&times;</a>{{2}}</div>',

        Utils = {
            numberFormat: function( number, decimals ){
                var n = number,
                    c = decimals === -1 ?
                        ((n || 0).toString().split('.')[1] || '').length : // preserve decimals
                        (isNaN(decimals = Math.abs(decimals)) ? 2 : decimals),
                    d = '.',
                    t = ' ',
                    s = n < 0 ? "-" : "",
                    i = String(parseInt(n = Math.abs(+n || 0).toFixed(c), 10)),
                    j = i.length > 3 ? i.length % 3 : 0;

                return s + (j ? i.substr(0, j) + t : "") +
                    i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) +
                    (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
            },

            toNumber: function ( val ){
                return Number( val.replace( /[\s%]+/g , ''));
            },

            percent: function( val ){
                return this.numberFormat( val * 100 ) + '%';
            },

            autoInputSelection: function( input ){
                return function(){
                    input.focus();
                    input.selectionStart = 0;
                    input.selectionEnd = input.value.length;
                    return false;
                };
            },

            showAlert: function( text, type, $container ){
                if( type )
                    type = ' alert-'+ type;
                var $alert = $( Template.parse( ALERT_TPL, type, text ))
                    .appendTo( $container ? $container : $errors  )
                    .hide()
                    .slideDown()
                    .bind( 'close', function (){
                        $alert.slideUp();
                    })
                    .bind( 'closed', function (){
                        $alert.remove();
                    });
            }
        };

    $(document).ready(function(){
        $('input[readonly]').click(function(){
            this.selectionStart = 0;
            this.selectionEnd = this.value.length;
            return false;
        });
    });

    return Utils;
});
