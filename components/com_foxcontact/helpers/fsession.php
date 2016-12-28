<?php

defined('_JEXEC') or die ('Restricted access');

jimport('joomla.utilities.date');

class FSession
	{
	private $db;
	protected $Id;
	protected $Cid;
	protected $Mid;

	public function __construct($id, $cid, $mid)
		{		
		$this->Id  = $id;
		$this->Cid = intval($cid);
		$this->Mid = intval($mid);  // Need intval for the sql: i.e. WHERE mid = 0
		$this->db = JFactory::getDBO();
		}


	public function Save($string, $keyword)
		{
		$now = new JDate();
/*
		$sql = "INSERT INTO #__fcf_sessions (id, cid, mid, keyword, birth, data) VALUES ('$this->Id', $this->Cid, $this->Mid, '$keyword', '" . $now->toMySQL() . "', '$string');";
		$this->db->setQuery($sql);
		$result = $this->db->query();
		if (!$result)
			{
			// Existing session. Update it.
			//$now = date("Y-m-d H:i:s");
			$sql = "UPDATE #__fcf_sessions SET data = '$string', birth = '" . $now->toMySQL() . "' WHERE id = '$this->Id' AND cid = $this->Cid AND mid = $this->Mid AND keyword = '$keyword';";
			$this->db->setQuery($sql);
			$result = $this->db->query();
			}
*/

		$sql = "SELECT data FROM #__fcf_sessions WHERE id = '$this->Id' AND cid = $this->Cid AND mid = $this->Mid AND keyword = '$keyword';";
		$this->db->setQuery($sql);
		$this->db->query();
		if ((bool)$this->db->getNumRows())
			{
			// Existing session. Update it.
			$sql = "UPDATE #__fcf_sessions SET data = '$string', birth = '" . $now->toMySQL() . "' WHERE id = '$this->Id' AND cid = $this->Cid AND mid = $this->Mid AND keyword = '$keyword';";
			$this->db->setQuery($sql);
			$result = $this->db->query();
			}
		else
			{
			$sql = "INSERT INTO #__fcf_sessions (id, cid, mid, keyword, birth, data) VALUES ('$this->Id', $this->Cid, $this->Mid, '$keyword', '" . $now->toMySQL() . "', '$string');";
			$this->db->setQuery($sql);
			$result = $this->db->query();
			}

		return $result;  // db->query() returns only boolean
		}


	public function Load($keyword)
		{
		$this->PurgeExpiredSessions();

		$sql = "SELECT data FROM #__fcf_sessions WHERE id = '$this->Id' AND cid = $this->Cid AND mid = $this->Mid AND keyword = '$keyword';";
		$this->db->setQuery($sql);
		return $this->db->loadResult();
		}


	public function PurgeValue($keyword)
		{
		$sql = "UPDATE #__fcf_sessions SET data = NULL WHERE id = '$this->Id' AND cid = $this->Cid AND mid = $this->Mid AND keyword = '$keyword';";
		$this->db->setQuery($sql);
		$this->db->query();
		}


	public function Clear($keyword)
		{
		$sql = "DELETE FROM #__fcf_sessions WHERE id = '$this->Id' AND cid = $this->Cid AND mid = $this->Mid AND keyword = '$keyword';";
		$this->db->setQuery($sql);
		$this->db->query();
		}


	private function PurgeExpiredSessions()
		{
		$application = JFactory::getApplication();
		$lifetime = $application->getCfg("lifetime");
		//$date = new DateTime("-" . $lifetime . " minute");
		//$sqldate = $date->format("Y-m-d H:i:s");
		$date = new JDate("-" . $lifetime . " minute");
		$sql = "DELETE FROM #__fcf_sessions WHERE birth < '" . $date->toMySQL() . "';";
		$this->db->setQuery($sql);
		$this->db->query();
		}


	}

?>