<?php
/*
 * Copyright (c) 2014 Robert Plummer
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy 
 * of this software and associated documentation files (the "Software"), to deal 
 * in the Software without restriction, including without limitation the rights to 
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of 
 * the Software, and to permit persons to whom the Software is furnished to do 
 * so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all 
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, 
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES 
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND 
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT 
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, 
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING 
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR 
 * OTHER DEALINGS IN THE SOFTWARE. 
 * -----------------------------------------------------------------------
 */

# Confirm MediaWiki environment
if (!defined('MEDIAWIKI')) die();

# Credits
$wgExtensionCredits['other'][] = array(
    'name'=>'wikiLingo',
    'author'=>'Robert Plummer &lt;RobertLeePlummerJr@gmail.com&gt;',
    'url'=>'https://github.com/wikiLingo/mediawiki',
    'description'=>'Adds support for wikiLingo',
    'version'=>'0.1'
);

# Attach Hook
$wgHooks['ParserBeforeStrip'][] = 'wikiLingoIfy';

require_once("vendor/autoload.php");
require_once("PluginStub.php");
$wikiLingoScripts = new WikiLingo\Utilities\Scripts();
global $wikiLingoParser;
$wikiLingoParser = new WikiLingo\Parser($wikiLingoScripts);

$pluginStub = new PluginStub();
$wikiLingoParser->events->bind(new WikiLingo\Event\Expression\Plugin\Exists(function(WikiLingo\Expression\Plugin &$plugin) use ($pluginStub, $wikiLingoParser) {
    if (!$plugin->exists) {
        switch ($plugin->classType) {
            default:
                $plugin->exists = true;
                $plugin->class = $pluginStub;
                $wikiLingoParser->pluginInstances[$plugin->classType] = $pluginStub;
        }
    }
}));
/**
 * Processes any Markdown sytnax in the text.
 * Usage: $wgHooks['ParserBeforeStrip'][] = 'wikiLingoIfy';
 * @param $parser
 * @param $text
 * @return bool
 */
function wikiLingoIfy($parser, &$text) {
    global $wikiLingoParser;
    # wikiLingo
    $text = $wikiLingoParser->parse($text);
    return true;
}