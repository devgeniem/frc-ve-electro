<div class="wrap">
    <h1>
        <?php _e('SOPA link generator'); ?>
    </h1>

    <div id="poststuff">
        <div class="postbox">
            <div class="inside">

                <div>
                    <label style="width: 8em;display: inline-block;" for="selectedProductCode">Product name</label>
                    <input class="js-sopa-input" id="selectedProductCode" type="text">
                </div>

                <div style="margin-top:0.5em;">
                    <label style="width: 8em;display: inline-block;" for="campaignCode">Campaign code</label>
                    <input class="js-sopa-input" id="campaignCode" type="text">
                </div>

                <div style="margin-top:0.5em;">
                    <label style="width: 8em;display: inline-block;" for="brand">Bonus product?</label>
                    <input class="js-sopa-input" id="brand" value="SBonus" type="checkbox">
                </div>

                <div style="margin-top:1em;">
                    URL: 
                    <a class="js-sopa-url"
                        style="display:inline-block; font-family: monospace; padding: 0.5em 0.75em; background-color: #f3f3f3;"
                        target="_blank"
                    >
                    URL
                    </a>
                </div>
                <div style="margin-top:1em;">
                    <span class="js-sopa-copy button-primary">
                        <?= _e('Copy link'); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function($) {

    var generate = function(values) {
        var values = values || {};
        var baseUri = '<?= VE\Electro\Electro::sopaLink(); ?>';

        values['locale'] =  '<?= VE\Electro\Electro::getLocale(); ?>';

        var params = $.param(values);

        var url =  baseUri + '?' + params;

        $('.js-sopa-url').text(url).attr('href', url);
    };

    var $input = $('.js-sopa-input');
    $input.on('keyup change', function(e) {
       e.preventDefault();

       var values = {};

        $input.each(function(index, element) {
            var $el = $(element);

            if ( $el.attr('type') === 'checkbox' && $el.prop('checked') ) {
                values[$el.attr('id')] = $el.val();
            }

            if ($el.attr('type') === 'text' && $el.val() ) {
                values[$el.attr('id')] = $el.val();
            }
        });

        generate(values);

    });

    $('.js-sopa-copy').on('click', function(e) {
        e.preventDefault();

        var $temp = $('<input>');
        $('body').append($temp);
        $temp.val($('.js-sopa-url').text()).select();
        document.execCommand('copy');
        $temp.remove();
    })

    generate();

})(jQuery);
</script>
