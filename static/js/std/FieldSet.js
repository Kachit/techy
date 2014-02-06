define( 'FieldSet', [ 'Form.Field', 'Template' ], function( Field, Template ){

var
    emptyFn = function(){},

    ALERT_TPL = '<div class="alert fade in{{1}}"><a class="close" data-dismiss="alert" href="#">&times;</a>{{2}}</div>';

return OClass({
    $fieldSet: null,

    _fields: null,
    _alerts: null,

    init: function( $fieldSet, options ){
        options = options || {};

        var type = $fieldSet.attr('data-type'),
            fields = options.fields || {};
        if(!type )
            type = 'json';

        this.$fieldSet = $fieldSet;
        this._fields = {};
        this._alerts = [];
        for( var i in fields ){
            if( fields.hasOwnProperty( i )){
                this._fields[i] = new Field( this, i, fields[i] );
            }
        }
    },

    validate: function(){
        this.clearAlerts();
        var valid = true;
        for( var i in this._fields ){
            if( this._fields.hasOwnProperty( i ))
                valid = this._fields[i].validate() && valid;
        }
        return valid;
    },

    validateField: function( name ){
        if( this._fields.hasOwnProperty( name ))
            return this._fields[name].validate();
        else
            return false;
    },

    clearAlerts: function(){
        var $alert;
        while( $alert = this._alerts.pop())
            $alert.alert('close');
    },

    /**
     * @param {string} text
     * @param {string} type
     */
    showAlert: function( text, type ){
        if( type )
            type = ' alert-'+ type;
        var $errors = this.$fieldSet.find('.-form-errors-');
        if(!$errors.length )
            $errors = $('#global-errors');
        var $alert = $( Template.parse( ALERT_TPL, type, text ))
                .appendTo( $errors )
                .hide()
                .slideDown()
                .bind( 'close', function (){
                    $alert.slideUp();
                })
                .bind( 'closed', function (){
                    $alert.remove();
                });
        this._alerts.push( $alert );
    },

    showFieldsErrors: function( errors,/*String?*/prefix ){
        prefix = prefix ? prefix + '-' : '';

        var fieldName;
        for( var i in errors ){
            if( errors.hasOwnProperty( i )){
                fieldName = prefix + i;
                if( errors[i] instanceof Array ){
                    if( this._fields.hasOwnProperty( fieldName )){
                        this._fields[fieldName].error( I18n.get( errors[i][0] ));
                    }else{
                        this.showAlert( I18n.get( errors[i][0] ), 'error' );
                    }
                }else if( errors[i] instanceof Object ){
                    this.showFieldsErrors( errors[i], fieldName )
                }else{
                    if( this._fields.hasOwnProperty( fieldName )){
                        this._fields[fieldName].error( I18n.get( errors[i] ));
                    }else{
                        this.showAlert( I18n.get( errors[i] ), 'error' );
                    }
                }
            }
        }
    }
});
});
