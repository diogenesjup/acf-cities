(function($) {
    function initialize_field($el) {
        if (!$el.length) {
            return;
        }
        
        var $countries      = $el.find('[data-select="countries"]');
        var $states         = $el.find('[data-select="states"]');
        var $stateInput     = $el.find('.state-input');
        var $customStateChk = $el.find('.custom-state-toggler');
        var $cities         = $el.find('[data-select="cities"]');
        var $cityInput      = $el.find('.city-input');
        var $customCityChk  = $el.find('.custom-city-toggler');

        var currentCountry  = $countries.val();
        var currentState    = $states.data('value');

        var statesHtml      = $states.html();
        var citiesHtml      = $cities.html();

        /**
         * Populate states on load
         */
        $.ajax({
            url: 'http://api.londrinaweb.com.br/PUC/Estados/'+ currentCountry +'/0/10000',
            dataType: 'jsonp'
        }).done(function(data) {
            if (data.length > 0) {
                $states.html(statesHtml);

                data.forEach(function(state) {
                    var selectedState = ($states.data('value') === state.UF) ? 'selected' : '';

                    $states.append('<option value="'+ state.UF +'" '+ selectedState +'>'+ state.Estado +'</option>');
                });
            }
        });

        /**
         * Populate cities on load
         */
        if (currentState) {
            $.ajax({
                url: 'http://api.londrinaweb.com.br/PUC/Cidades/'+ currentState +'/'+ currentCountry +'/0/1000',
                dataType: 'jsonp'
            }).done(function(data) {
                if (data.length > 0) {
                    $cities.html(citiesHtml);

                    data.forEach(function(city) {
                        var selectedCity = ($cities.data('value') === city) ? 'selected' : '';

                        $cities.append('<option value="'+ city +'" '+ selectedCity +'>'+ city +'</option>');
                    });
                }
            });
        }

        /**
         * Populate states on country change
         */
        $countries.on('change', function(e) {
            var country = $(this).val();

            if (country === 'BR') {
                if ($customStateChk.is(':checked')) {
                    $customStateChk.click();
                }

                if ($customCityChk.is(':checked')) {
                    $customCityChk.click();
                }

                $.ajax({
                    url: 'http://api.londrinaweb.com.br/PUC/Estados/'+ country +'/0/10000',
                    dataType: 'jsonp'
                }).done(function(data) {
                    if (data.length > 0) {
                        $states.html(statesHtml);

                        data.forEach(function(state) {
                            $states.append('<option value="'+ state.UF +'">'+ state.Estado +'</option>');
                        });
                    }
                });
            } else {
                if (!$customStateChk.is(':checked')) {
                    $customStateChk.click();
                }

                if (!$customCityChk.is(':checked')) {
                    $customCityChk.click();
                }
            }
        });

        /**
         * Toggles the text input for the state when the user can't find it in the select
         */
        $customStateChk.on('click', function(e) {
            if ($customStateChk[0].checked) {
                $stateInput.show();
                $stateInput.attr('name', $states.attr('name'));

                $states.hide();
            } else {
                $stateInput.hide();
                $stateInput.removeAttr('name');

                $states.show();
            }
        });

        /**
         * Populate cities on state change
         */
        $states.on('change', function(e) {
            var country = $countries.val();
            var state   = $(this).val();

            $.ajax({
                url: 'http://api.londrinaweb.com.br/PUC/Cidades/'+ state +'/'+ country +'/0/1000',
                dataType: 'jsonp'
            }).done(function(data) {
                if (data.length > 0) {
                    $cities.html(citiesHtml);

                    data.forEach(function(city) {
                        $cities.append('<option value="'+ city +'">'+ city +'</option>');
                    });
                }
            });
        });

        /**
         * Toggles the text input for the city when the user can't find it in the select
         */
        $customCityChk.on('click', function(e) {
            if ($customCityChk[0].checked) {
                $cityInput.show();
                $cityInput.attr('name', $cities.attr('name'));

                $cities.hide();
            } else {
                $cityInput.hide();
                $cityInput.removeAttr('name');

                $cities.show();
            }
        });
    }
    
    if (typeof acf.add_action !== 'undefined') {
        /*
        *  ready append (ACF5)
        *
        *  These are 2 events which are fired during the page load
        *  ready = on page load similar to $(document).ready()
        *  append = on new DOM elements appended via repeater field
        *
        *  @type    event
        *  @date    20/07/13
        *
        *  @param   $el (jQuery selection) the jQuery element which contains the ACF fields
        *  @return  n/a
        */
        
        acf.add_action('ready append', function($el) {
            var field = acf.get_field({ type : 'cities' }, $el);
            
            initialize_field(field);
        });
    } else {
        /*
        *  acf/setup_fields (ACF4)
        *
        *  This event is triggered when ACF adds any new elements to the DOM. 
        *
        *  @type    function
        *  @since   1.0.0
        *  @date    01/01/12
        *
        *  @param   event       e: an event object. This can be ignored
        *  @param   Element     postbox: An element which contains the new HTML
        *
        *  @return  n/a
        */
        
        $(document).on('acf/setup_fields', function(e, postbox) {
            $(postbox).find('.field[data-field_type="cities"]').each(function() {   
                initialize_field($(this));
            });
        });
    }
})(jQuery);
