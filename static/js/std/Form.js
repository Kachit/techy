define( 'Form', [ 'Form.Field', 'Template' ], function( Field, Template ){

var
    emptyFn = function(){},

    ALERT_TPL =
        '<div class="alert fade in{{1}}"><a class="close" data-dismiss="alert" href="#">&times;</a>{{2}}</div>';

return OClass({
    $form: null,

    _locked: false,
    _fields: null,
    _alerts: null,

    tooltipPlacement: 'right',
    tooltipOn: null,

    init: function( $form, options ){
        options = options || {};

        var type = $form.attr('data-type'),
            fields = options.fields || {};
        if(!type )
            type = 'json';

        // extending with options
        $.extend( this, options );

        this.$form = $form;
        this.$form.data( 'form', this );
        this._fields = {};
        this._alerts = [];
        var opts;
        for( var i in fields ){
            if( fields.hasOwnProperty( i )){
                opts = {};
                $.extend( opts, fields[i], { tooltipPlacement: this.tooltipPlacement });
                this._fields[i] = new Field( this, i, opts );
            }
        }
        this.initAjax( type, options.success || emptyFn, options.error || emptyFn );
    },

    addField: function( name, options ){
        if( this._fields.hasOwnProperty( name ) ){
            this._fields[name].activate();
        }else{
            this._fields[name] = new Field( this, name, options );
        }
    },

    addFields: function( fields ){
        var Form = this;
        for( var i in fields )
            if( fields.hasOwnProperty( i ))
                Form.addField( i, fields[i] );
    },

    removeField: function( name ){
        if( this._fields.hasOwnProperty( name ) ){
            delete this._fields[name];
        }
    },

    removeFields: function( fields ){
        var Form = this;
        for( var i in fields )
            if( fields.hasOwnProperty( i ) )
                Form.removeField( i );
    },

    activate: function( name ){
        if( this._fields.hasOwnProperty( name ) )
            this._fields[name].activate();
    },

    deactivate: function( name ){
        if( this._fields.hasOwnProperty( name ) )
            this._fields[name].deactivate();
    },

    lock: function(){
        this._locked = true;
        this.$form.find( '[type=submit]' ).attr( 'disabled', true );
    },

    unlock: function(){
        this._locked = false;
        this.$form.find( '[type=submit]' ).attr( 'disabled', false );
    },

    /**
     * @param {string} type
     * @param {Function?} success
     * @param {Function?} error
     */
    initAjax: function( type, success, error ){
        var self = this;
        this.$form.ajaxForm({
            dataType: type,
            beforeSubmit: function(){
                if( self._locked )
                    return false;

                self.lock();

                var result = self.validate();
                if(!result )
                    self.unlock();

                return result;
            },
            complete: function(){
                self.unlock();
            },
            success: function( content ){
                if( 'json' === type ){
                    if( content.error ){
                        self.showAlert( I18n.get( content.error ), 'danger' );
                        error && error.call( self, content );
                        return;
                    }
                    else if( content.fields_errors ){
                        self.showFieldsErrors( content.fields_errors );
                        error && error.call( self, content );
                        return;
                    }
                    else if( content.warning ){
                        self.showAlert( I18n.get( content.warning ));
                        error && error.call( self, content );
                        return;
                    }
                    else if( content.success ){
                        self.showAlert( I18n.get( content.success ), 'success' );
                    }
                    else if( content.redirect ){
                        window.location.href = content.redirect;
                    }
                }
                success && success.call( self, content );
            }
        });
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
        var $errors = this.$form.find('.-form-errors-');
        if(!$errors.length )
            $errors = $('#global-errors');
        if( $errors.length ){
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
        } else {
            var $tooltipOn = this.$form.find( this.tooltipOn ? this.tooltipOn : '[type=submit]:first' );
            if( $tooltipOn.length ){
                $tooltipOn.tooltip({
                    trigger: 'manual',
                    placement: this.tooltipPlacement,
                    title: text
                }).tooltip('show');
                this.$form.find('[name]').focus(function(){
                    $tooltipOn.tooltip('destroy');
                });
            }
        }
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
                        this.showAlert( I18n.get( errors[i][0] ), 'danger' );
                    }
                }else if( errors[i] instanceof Object ){
                    this.showFieldsErrors( errors[i], fieldName )
                }else{
                    if( this._fields.hasOwnProperty( fieldName )){
                        this._fields[fieldName].error( I18n.get( errors[i] ));
                    }else{
                        this.showAlert( I18n.get( errors[i] ), 'danger' );
                    }
                }
            }
        }
    }
});
});
