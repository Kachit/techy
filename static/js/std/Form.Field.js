define( 'Form.Field', [], function(){

    return OClass({
        $form: null,
        $field: null,
        $block: null,
        $errorBlock: null,

        _Form: null,
        _name: null,
        _invalid: false,

        // options
        validators: null,
        filters: null,

        tooltipPlacement: 'right',

        /**
         * @param {Object} Form
         * @param {string} name
         * @param {Object?} options
         */
        init: function ( Form, name, options ){
            this.$form = Form.$form;
            this.$field = this.$form.find('[name='+ name.replace('[','\\[').replace(']','\\]') +']');
            this.$block = this.$field.closest('.-field-container-');
            this.filters = [];
            this.validators = [];

            this.$errorBlock = this.$block.find('.-error-');
            if(!this.$errorBlock.length )
                this.$errorBlock = null;

            // extending with options
            $.extend( this, { isVisible: 'hidden' !== this.$field.attr( 'type' ), on: {}}, options || {} );
            // defaults
            !this.hasOwnProperty('hasBlur')  && ( this.hasBlur   = this.isVisible );
            !this.hasOwnProperty('hasKeyUp') && ( this.hasKeyUp  = this.isVisible );

            // initialize
            this._Form = Form;
            this._name = name;

            // connect
            if( this.hasBlur && this.validators.length )
                this.$field.on( 'focusout', this.validate.bind( this ));

            if( this.hasKeyUp && ( this.validators.length || this.filters.length ))
                this.$field.on( 'keyup', this.keyUp.bind( this ));

            for( var i in this.on ){
                if( this.on.hasOwnProperty( i )){
                    this.$field.on( i, this.on[i].bind( this ));
                }
            }
            this.hasOwnProperty('inactive') && ( this.deactivate() );
        },

        filter: function(){
            var value = this.$field.val(),
                startValue = value,
                filters = this.filters;
            if(!value )
                return;
            for( var i = 0, q = filters.length; i < q; i++ ){
                try{
                    value = this['filter'+ filters[i].name]( value, filters[i] );
                }
                catch(e){
                    console.error( 'Filter error in field ', this._name, e.stack );
                }
            }
            if( value !== startValue )
                this.$field.val( value );
        },

        activate: function(){
            this.$field.removeAttr( 'disabled' );
        },

        deactivate: function(){
            this.$field.attr( 'disabled', 'disabled' );
        },

        validate: function(){
            var valid = true,
                value = this.$field.val(),
                validators = this.validators,
                res;
            if(!value )
                value = '';
            for( var i = 0, q = validators.length; i < q; i++ ){
                try{
                    res = this['validate'+ validators[i].name]( value, validators[i] );
                    valid = valid && res;
                    if(!res )
                        this.error( validators[i].msg );
                }
                catch(e){
                    console.error( 'Validate error in field ', this._name, e.stack, e.message );
                }
            }
            if( valid ){
                if( this._invalid ){
                    this._invalid = false;
                    this.success();
                }
            }
            return valid;
        },

        keyUp: function(){
            this.filter();
            if( this._invalid )
                this.validate();
        },

        success: function(){
            if( this.isVisible ){
                this.$block.removeClass('has-error').addClass('has-success');
                if( this.$errorBlock ){
                    this.$errorBlock.html('');
                }
            }
        },

        error: function( msg ){
            this._invalid = true;
            if( this.isVisible ){
                this.$block.addClass('has-error').removeClass('has-success');
                if( this.$errorBlock ){
                    this.$errorBlock.html( msg );
                }
                else {
                    var $field = this.$field;
                    $field.tooltip({
                        trigger: 'manual',
                        placement: this.tooltipPlacement,
                        title: msg
                    }).tooltip('show');
                    this.$form.find('[name]').focus(function(){
                        $field.tooltip('destroy');
                    });
                }
            }else{
                this._Form.showAlert( msg, 'danger' );
            }
        },

        /** ---------------------------------------------------------------------------
         * Validators
         *
         * @param value
         * @param options
         ** --------------------------------------------------------------------------- */
        validateNotEmpty: function( value, options ){
            return ( '' !== value )
        },

        validateNotEmptyOptions: function( value, options ){
            return this.$field.filter(':checked').length > 0;
        },

        validateMatch: function( value, options ){
            return value.match( options.match )
        },

        validateIntBetween: function( value, options ){
            value = Number( value );
            return !(
                ( options.min && value < options.min ) ||
                    ( options.max && value > options.max )
                )
        },

        validateCommon: function( value, options ){
            return options.fn.call( this, value )
        },

        /** ---------------------------------------------------------------------------
         * Filters
         *
         * @param value
         * @param options
         ** --------------------------------------------------------------------------- */
        filterNumber: function( value, options ){
            value = value.replace( /[^\d\.]/g, '' );
            if( options.int )
                return value.replace( /\./g, '' );
            var i = value.indexOf( '.' ),
                k = value.indexOf( '.', i + 1 );
            while( k >= 0 ){
                value = value.substr( 0, k ) + value.substr( k + 1 );
                k = value.indexOf( '.', i + 1 );
            }
            return value;
        },

        filterNotLongerThan: function( value, options ){
            return value.substr( 0, options.length );
        },

        filterClear: function( value, options ){
            return value.replace( options.what, '' );
        }
    });

});
