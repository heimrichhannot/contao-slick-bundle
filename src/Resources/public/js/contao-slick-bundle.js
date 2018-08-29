(function($) {
    window.SLICK_BUNDLE = {
        init: function() {
            $('.slick').each(function() {

                var $this = $(this),
                    container = $this.find('.slick-container'),
                    total = container.children().length;

                // initialize slick, if more than one slide
                if (total > 1) {
                    var config = container.data('slickConfig');
                    var initCallback = container.data('slickInitCallback');
                    var afterInitCallback = container.data('slickAfterInitCallback');

                    // don't init sliders if inside a hidden collapse -> done in shown.bs.collapse event
                    if ($this.closest('.collapse').length <= 0 || $this.closest('.collapse').hasClass('show'))
                    {
                        if(typeof initCallback !== 'undefined'){
                            window.SLICK_BUNDLE.executeFunctionByName(initCallback, window, [container]);
                        }

                        var slick = $this.data('slick', container.not('.slick-initialized').slick(config));

                        if(typeof afterInitCallback !== 'undefined'){
                            window.SLICK_BUNDLE.executeFunctionByName(afterInitCallback, window, [slick, container]);
                        }
                    }

                    // add slick-initialized for non-slick sliders, otherwise the will stay invisible
                } else {
                    container.addClass('slick-initialized');
                }
            });
        },
        executeFunctionByName : function(functionName, context, args) {
            var namespaces = functionName.split(".");
            var func = namespaces.pop();
            for(var i = 0; i < namespaces.length; i++) {
                context = context[namespaces[i]];
            }

            if(typeof context[func] !== 'function'){
                console.log(func + ' within '+ window + ' context does not exist.');
                return;
            }

            return context[func].apply(context, args);
        }
    };


    $(document).ready(function() {
        SLICK_BUNDLE.init();
    });

    $(document).on('shown.bs.modal', '.modal', function() {
        SLICK_BUNDLE.init();
    });

    $(document).on('shown.bs.collapse', '.collapse', function() {
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