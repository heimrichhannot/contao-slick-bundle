global.$ = global.jQuery = require('jquery');

import 'slick-carousel';
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
                    let config = container.data('slickConfig');
                    let initCallback = container.data('slickInitCallback');
                    let afterInitCallback = container.data('slickAfterInitCallback');

                    // don't init sliders if inside a hidden collapse -> done in shown.bs.collapse event
                    if ($this.closest('.collapse').length <= 0 || $this.closest('.collapse').hasClass('show'))
                    {
                        if(typeof initCallback !== 'undefined'){
                            slickBundle.executeFunctionByName(initCallback, window, [container]);
                        }

                        let slick = $this.data('slick', container.not('.slick-initialized').slick(config));

                        if(typeof afterInitCallback !== 'undefined'){
                            slickBundle.executeFunctionByName(afterInitCallback, window, [slick, container]);
                        }
                    }

                    // add slick-initialized for non-slick sliders, otherwise the will stay invisible
                } else {
                    container.addClass('slick-initialized');
                }
            });
        },
        executeFunctionByName : function(functionName, context, args) {
            let namespaces = functionName.split(".");
            let func = namespaces.pop();
            for(let i = 0; i < namespaces.length; i++) {
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
        slickBundle.init();
    });

    $(document).on('shown.bs.modal', '.modal', function() {
        slickBundle.init();
    });

    $(document).on('shown.bs.collapse', '.collapse', function() {
        slickBundle.init();
    });

})(jQuery);