<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  system.instantsuggest
 *
 * @copyright   Copyright (C) 2013 InstantSuggest.com. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

class PlgSystemInstantSuggestInstallerScript
{
        /**
         * Constructor
         *
         * @param   JAdapterInstance  $adapter  The object responsible for running this script
         */
        //public function __constructor(JAdapterInstance $adapter);
 
        /**
         * Called before any type of action
         *
         * @param   string  $route  Which action is happening (install|uninstall|discover_install)
         * @param   JAdapterInstance  $adapter  The object responsible for running this script
         *
         * @return  boolean  True on success
         */
        //public function preflight($route, JAdapterInstance $adapter);
 
        /**
         * Called after any type of action
         *
         * @param   string  $route  Which action is happening (install|uninstall|discover_install)
         * @param   JAdapterInstance  $adapter  The object responsible for running this script
         *
         * @return  boolean  True on success
         */
        //public function postflight($route, JAdapterInstance $adapter);
 
        /**
         * Called on installation
         *
         * @param   JAdapterInstance  $adapter  The object responsible for running this script
         *
         * @return  boolean  True on success
         */
        public function install(JAdapterInstance $adapter) {
            $this->onInstallOrUpdate();
        }
 
        /**
         * Called on update
         *
         * @param   JAdapterInstance  $adapter  The object responsible for running this script
         *
         * @return  boolean  True on success
         */
        public function update(JAdapterInstance $adapter) {
            $this->onInstallOrUpdate();
        }
 
        /**
         * Called on uninstallation
         *
         * @param   JAdapterInstance  $adapter  The object responsible for running this script
         */
        //public function uninstall(JAdapterInstance $adapter);
        
        private function onInstallOrUpdate() {
            $table = JTable::getInstance('Extension');
            
            if($table->load(array('type' => 'plugin', 'element' => 'instantsuggest'))) {
                $table->enabled = 1;
                $table->store();
            }
        }
}
