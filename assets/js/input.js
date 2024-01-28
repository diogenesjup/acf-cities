(function($) {
    // Função para inicializar o campo personalizado
    function initialize_field($el) {
        // Seletor para o campo de estados e cidades
        var $states = $el.find('[data-select="states"]');
        var $cities = $el.find('[data-select="cities"]');

        // Carregar estados do JSON
        $.getJSON('/wp-content/plugins/acf-cities-master/data/estados-cidades.json', function(data) {
            $states.empty().append('<option value="">Selecione um Estado</option>');
            data.estados.forEach(function(estado) {
                $states.append('<option value="' + estado.sigla + '">' + estado.nome + '</option>');
            });
        });

        // Evento de mudança para carregar as cidades baseadas no estado selecionado
        $states.on('change', function() {
            var estadoSelecionado = $(this).val();

            // Carregar as cidades correspondentes ao estado selecionado
            $.getJSON('/wp-content/plugins/acf-cities-master/data/estados-cidades.json', function(data) {
                var cidades = data.estados.find(function(estado) {
                    return estado.sigla === estadoSelecionado;
                }).cidades;

                $cities.empty().append('<option value="">Selecione uma Cidade</option>');
                cidades.forEach(function(cidade) {
                    $cities.append('<option value="' + cidade + '">' + cidade + '</option>');
                });
            });
        });
    }

    // Ação do ACF para inicializar o campo
    acf.add_action('ready append', function($el) {
        // Inicializa o campo para cada elemento encontrado
        acf.get_field({ type: 'cities' }, $el).each(function() {
            initialize_field($(this));
        });
    });

})(jQuery);
