define( 'AutocompletedList', [ 'Autocomplete' ], function( Autocomplete ){

    var defaultSettings = {
            collection: null,

            primary: null,

            selectClass: 'input-medium',
            buttonClass: 'btn',

            idParam: 'id',
            nameParam: 'name',
            subsParam: 'subs',
            subIdParam: 'id',
            subNameParam: 'name',

            allSubs: '...'
        };

    return OClass({
        options: null,

        $input: null,
        $inputId: null,
        $div: null,
        $select: null,
        $button: null,

        onChange: OEvent,

        init: function( input, options ){
            this.options = OExtend({}, defaultSettings, options || {});
            this.$input = $(input);

            var initialId = Number( this.$input.data('id')),
                self = this,
                opts = this.options;

            this.$inputId = $('<input type="hidden" name="' + this.$input.attr('name') + '" />')
                .insertAfter( this.$input )
                .val( initialId );
            this.$input.removeAttr('name');
            this.$div = $('<div class="input-append"></div>')
                .insertAfter( this.$inputId )
                .hide();
            this.$select = $('<select name="'+ this.$input.data('sub') +'"></select>')
                .addClass( opts.selectClass )
                .appendTo( this.$div );
            this.$button = $('<button>' + I18n.get( 'general.cancel') + '</button>')
                .addClass( opts.buttonClass )
                .appendTo( this.$div );

            this.options = OExtend({
                choose: this.fillSubs.bind( this ),
                format: function( item ){
                    return '<li><a href="#">' + item[opts.nameParam] + '</a></li>';
                },
                search: function( search ){
                    return function( item ){
                        return item[opts.nameParam].toLowerCase().indexOf( search ) === 0;
                    }
                }
            }, this.options );

            this.autocomplete = new Autocomplete( this.$input, this.options );

            this.$button.click( function(){
                self.$div.hide();
                self.$input.show().val('').removeAttr('disabled');
                self.$inputId.val('');
                self.$select.empty();
                self.onChange(0,0);
                return false;
            });
            this.$select.change( function(){
                self.onChange( Number( self.$inputId.val()), Number( self.$select.val()));
            });

            if( initialId && opts.initialRequest ){
                $.ajax({
                    url: opts.url,
                    type: 'POST',
                    dataType: 'json',
                    data: opts.initialRequest( initialId ),
                    success: function( json ){
                        self.fillSubs( self.$input, json.data[0], self.$input.data('sub-id'));
                    }
                });
            }
        },

        fillSubs: function( $input, item,/*int?*/ subId ){
            var opts = this.options,
                name = item[opts.nameParam],
                subs = item[opts.subsParam];
            this.$inputId.val( item[opts.idParam] );
            this.$select.empty();
            this.$select.append( '<option value="">' + name + ' &mdash; ' + opts.allSubs + '</option>' );
            for( var i = 0, l = subs.length; i < l; i++ ){
                if( subs[i][opts.subIdParam]!=null )
                    this.$select.append(
                        '<option value="' + subs[i][opts.subIdParam]
                            + ( subId == subs[i][opts.subIdParam] ? '" selected="selected' : '' )
                            + '">' + name + ' &mdash; ' + subs[i][opts.subNameParam] + '</option>'
                    );
            }
            this.$input.hide().attr('disabled','disabled');
            this.$div.show();
            this.onChange( item[opts.idParam], Number( subId ));
        }
    });
});
