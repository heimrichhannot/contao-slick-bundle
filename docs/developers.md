# Developer documentation

## Hooks

Hook                  | Description
--------------------- | -----------
compileSlickNewsList  | Modify slick news frontend module output.
compileSlickEventList | Modify slick event frontend module output.

## JS Events

### huh.slick.beforeInit

Event is dispatched before initializing slick instance. Call `Event.preventDefault()` to prevent initialization.

Parameters: 
* {Element} _element_: Element container .slick css class
* {Element} _slickContainer_: Element contains .slick-container css class becoming slick instance
* {object} _config_: Slick config

Bubbles: true  
Cancelable: yes  
Interface: CustomEvent

### huh.slick.afterInit

Event is dispatched after initializing slick. 

Parameters: 
* {jQuery} _slickInstance_: Element container .slick css class
* {Element} _element_: Element container .slick css class
* {Element} _slickContainer_: Element contains .slick-container css class becoming slick instance

Bubbles: true    
Cancelable: false  
Interface: CustomEvent


## Add slick to a custom element

If you need to add slick to a custom element, you need to add the slick assets. The easiest way (and it also optional supports encore bundle) is to use the `FrontendAsset` service.

```php
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use HeimrichHannot\SlickBundle\Asset\FrontendAsset;

class MyFrontendModuleController extends AbstractFrontendModuleController
{
    private $frontendAsset;

    public function __construct(FrontendAsset $frontendAsset)
    {
        $this->frontendAsset = $frontendAsset;
    }

    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
    {
        // Do something...

        $this->frontendAsset->addFrontendAssets();
        return $template->getResponse();
    }
}
```