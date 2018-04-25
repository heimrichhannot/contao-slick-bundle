global.$ = global.jQuery = require('jquery');

require('slick-carousel');
require('../scss/contao-slick-bundle.scss');

($ => {

    $.fn.randomize = function(selector) {
        (selector ? this.find(selector) : this).parent().each(function() {
            $(this).children(selector).sort(function() {
                return Math.random() - 0.5;
            }).detach().appendTo(this);
        });

        return this;
    };

    let slickBundle = {
        init : function(){
            $('.slick').each(function(){
                let $this = $(this),
                    container = $this.find('.slick-container'),
                    total = container.children().length;

                // initialize slick, if more than one slide
                if (total > 1) {
                    let config = container.data('config');

                    $this.data('slick', container.not('.slick-initialized').slick(config));

                    // add slick-initialized for non-slick sliders, otherwise the will stay invisible
                } else {
                    container.addClass('slick-initialized');
                }
            });
        }
    };

    module.exports = slickBundle;

    $(document).ready(function() {
        slickBundle.init();
    });

    $(document).on('shown.bs.modal', '.modal', function() {
        slickBundle.init();
    });

})(jQuery);