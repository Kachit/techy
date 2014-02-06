define( 'Autocomplete', [], function(){

    var
        $dropdown,
        $selectedDropdownItem,

        deselectDropdownItem = function(){
            if( $selectedDropdownItem ){
                $selectedDropdownItem.removeClass( this.options.dropdownItemActiveClass );
                $selectedDropdownItem = null;
            }
        },

        selectDropdownItem = function( event ){
            deselectDropdownItem.call( this );
            $selectedDropdownItem = $( event.target ).closest('li').addClass( this.options.dropdownItemActiveClass );
        },

        chooseDropdownItem = function( event ){
            $dropdown.hide();
            this.options.choose( this.$input, $( event.target ).closest('li').data('item'));
            return false;
        },


        defaultSearch = function defaultSearch( search ){
            return function( item ){
                return item.toLowerCase().indexOf( search ) === 0;
            }
        },

        defaultChoose = function defaultChoose( $input, item ){
            $input.val( item );
        },

        defaultFormat = function defaultFormat( item ){
            return '<li><a href="#">' + item + '</a></li>';
        },

        defaultSelect = function defaultSelect( opts, search ){
            return $.grep( opts.collection[search.substr( 0, opts.requestChars )], opts.search( search ));
        },

        defaultSettings = {
            data: null,
            collection: null,

            url: null,
            requestData: null,

            select: defaultSelect,
            search: defaultSearch,
            choose: defaultChoose,
            format: defaultFormat,

            dropdownClass: 'well token-input-dropdown',
            dropdownListClass: 'nav nav-list',
            dropdownItemActiveClass: 'active',

            requestChars: 1,
            cacheResults: true
        };

    return OClass({
        options: null,

        $input: null,

        init: function( input, options ){
            this.options = OExtend({}, defaultSettings, options || {});

            var opts = this.options,
                self = this;

            if(!opts.collection )
                opts.collection = {};

            this.$input = $(input);
            this.$input.attr( 'autocomplete', 'off' );
            this.$input.keyup(function(){
                if( self.$input.attr('disabled'))
                    return;
                var val = self.$input.val();
                if( val == '' || val.length < opts.requestChars ){
                    $dropdown && $dropdown.hide();
                    return;
                }
                var search = val.toLowerCase(),
                    searchChars = search.substr( 0, opts.requestChars );
                if(!opts.collection.hasOwnProperty( searchChars )){
                    if( opts.cacheResults )
                        opts.collection[searchChars] = [];
                    if( opts.url ){
                        var req = {
                            url: opts.url,
                            type: 'POST',
                            dataType: 'json',
                            success: function( json ){
                                if( opts.cacheResults )
                                    opts.collection[searchChars] = json.data;
                                else
                                    opts.data = json.data;
                                self.showDropdown( opts.select( opts, search ));
                            }
                        };
                        if( opts.url instanceof Function ){
                            req.url = opts.url( opts, val );
                            req.type = 'GET';
                        }
                        else{
                            // post {search:'<chars>'} by default
                            req.data = opts.requestData ? opts.requestData( search ) : { search: searchChars };
                        }
                        $.ajax( req );
                    }
                }
                else{
                    self.showDropdown( opts.select( opts, search ));
                }
            });
        },

        showDropdown: function( collection ){
            if( $dropdown ){
                $dropdown
                    .hide()
                    .empty()
                    .attr( 'class', this.options.dropdownClass );
            }else{
                $dropdown = $('<div>')
                    .addClass( this.options.dropdownClass )
                    .appendTo('body')
                    .hide();
            }

            var opts = this.options,

                $selectedDropdownItem,

                $dropdownList = $('<ul>')
                    .appendTo( $dropdown )
                    .addClass( opts.dropdownListClass )
                    .mouseover( selectDropdownItem.bind( this ))
                    .mousedown( chooseDropdownItem.bind( this ))
                    .hide();

            $.each(
                collection,
                function( index, item ){
                    var $li = $( opts.format( item )).appendTo( $dropdownList );

                    if(!index )
                        $selectedDropdownItem = $li.addClass( opts.dropdownItemActiveClass );

                    $.data( $li.get(0), 'item', item );
                }
            );

            $dropdown
                .css({
                    position: 'absolute',
                    top: this.$input.offset().top + this.$input.outerHeight(),
                    left: this.$input.offset().left,
                    zindex: 999
                })
                .show();
            $dropdownList.slideDown('fast');
        }
    });
});
