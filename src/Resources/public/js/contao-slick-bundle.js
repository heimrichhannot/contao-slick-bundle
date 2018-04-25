(function($) {
    window.SLICK_BUNDLE = {
        init: function() {
            $('.slick').each(function() {

                var $this = $(this),
                    container = $this.find('.slick-container'),
                    total = container.children().length;

                // initialize slick, if more than one slide
                if (total > 1) {
                    var config = container.data('config');
                    $this.data('slick', container.not('.slick-initialized').slick(config));

                    // add slick-initialized for non-slick sliders, otherwise the will stay invisible
                } else {
                    container.addClass('slick-initialized');
                }
            });
        },
    };

    $(document).ready(function() {
        SLICK_BUNDLE.init();
    });

    $(document).on('shown.bs.modal', '.modal', function() {
        SLICK_BUNDLE.init();
    });

    $.fn.randomize = function(selector) {
        (selector ? this.find(selector) : this).parent().each(function() {
            $(this).children(selector).sort(function() {
                return Math.random() - 0.5;
            }).detach().appendTo(this);
        });

        return this;
    };

})(jQuery);