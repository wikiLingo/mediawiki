<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 5/22/14
 * Time: 2:52 PM
 */

class PluginStub extends WikiLingo\Plugin\Base
{
    public function __construct()
    {
        $this->allowLines = true;
    }

    public function render(WikiLingo\Expression\Plugin &$plugin, &$body = '', &$renderer, &$parser)
    {
        $element = $renderer->element('WikiLingo\\Expression\\Plugin', 'span');
        $element->detailedAttributes['data-plugin-type'] = $plugin->type;
        $element->detailedAttributes['data-plugin-parameters'] = urlencode(json_encode($plugin->parametersRaw));
        $element->useDetailedAttributes = true;
        $element->staticChildren[] = $plugin->renderedChildren;
        $output = $element->render();
        return $output;
    }
}