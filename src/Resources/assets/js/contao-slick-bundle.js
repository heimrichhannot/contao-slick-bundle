import 'jquery';
import 'slick-carousel';
import 'nodelist-foreach-polyfill';
import 'custom-event-polyfill';
import '../scss/contao-slick-bundle.scss';

class SlickBundle
{
    static initPage()
    {
        SlickBundle.initElement(document);
    }

    static initElement(element)
    {
        element.querySelectorAll('.slick').forEach((element) => {
            SlickBundle.initSlickElement(element);
        });
    }

    static initSlickElement(element)
    {
        let slickContainer = element.querySelector('.slick-container');
        if (null == slickContainer) {
            return;
        }

        let total = slickContainer.childElementCount;

        if (total < 2) {
            // add slick-initialized for non-slick sliders, otherwise the will stay invisible
            slickContainer.classList.add('slick-initialized');
            return;
        }

        let config = {};
        if ('slickConfig' in slickContainer.dataset) {
            config = JSON.parse(slickContainer.dataset.slickConfig);
        }
        // @todo: Remove in next major version
        let initCallback = null;
        if ('slickInitCallback' in slickContainer.dataset) {
            console.log("Using slickInitCallback is deprecated and will be removed in next major version of slick bundle!");
            initCallback = slickContainer.dataset.slickInitCallback;
        }
        // @todo: Remove in next major version
        let afterInitCallback = null;
        if ('slickAfterInitCallback' in slickContainer.dataset) {
            console.log("Using slickAfterInitCallback is deprecated and will be removed in next major version of slick bundle!");
            afterInitCallback = slickContainer.dataset.slickAfterInitCallback;
        }

        // @todo: Remove in next major version
        if (initCallback !== null) {
            SlickBundle.executeFunctionByName(initCallback, window, [container]);
        }

        let result = slickContainer.dispatchEvent(new CustomEvent('huh.slick.beforeInit', {
            detail: {
                element: element,
                slickContainer: slickContainer,
                config: config
            },
            bubbles: true,
            cancelable: true
        }));

        if (false === result || slickContainer.classList.contains('slick-initialized')) {
            return;
        }

        // Todo: Remove in next major version
        let collapse = $(element).closest('.collapse');
        if (collapse.length > 0 && !collapse.hasClass('show')) {
            collapse.on('shown.bs.collapse', () => {
                SlickBundle.initSlickElement(collapse[0]);
            });
            console.log("Checking for collapse in slick bundle is deprecated and will be removed in next major version. Use beforeInit event instead.");
            return;
        }

        let slickInstance = $(slickContainer).slick(config);

        // legacy support?
        // @todo: Remove in next major version
        $(slickContainer).data('slick', slickInstance);

        slickContainer.dispatchEvent(new CustomEvent('huh.slick.afterInit', {
            detail: {
                slickInstance: slickInstance,
                element: element,
                slickContainer: slickContainer
            },
            bubbles: true,
            cancelable: false
        }));

        // @todo: Remove in next major version
        if (afterInitCallback !== null) {
            SlickBundle.executeFunctionByName(afterInitCallback, window, [slickInstance, container]);
        }
    }

    static executeFunctionByName(functionName, context, args) {
        let namespaces = functionName.split('.');
        let func = namespaces.pop();
        for (let i = 0; i < namespaces.length; i++) {
            context = context[namespaces[i]];
        }

        if (typeof context[func] !== 'function') {
            console.log(func + ' within ' + window + ' context does not exist.');
            return;
        }

        return context[func].apply(context, args);
    }
}

if (document.readyState !== 'loading') {
    SlickBundle.initPage();
} else {
    document.addEventListener('DOMContentLoaded', SlickBundle.initPage);
}

// @todo: Remvoe in next major version
$(document).on('shown.bs.modal', '.modal', SlickBundle.initPage);

document.addEventListener('huh.list.list_update_complete', SlickBundle.initPage);