<?php
/**
 * ****************************************************************************
 * SHORTCUTS - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * ****************************************************************************
 */

include_once XOOPS_ROOT_PATH."/class/xoopsobject.php";

if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}

class shortcuts extends XoopsObject
{
	var $db;

	function shortcuts($id = null)
	{
		$this->db =& Database::getInstance();
		$this->initVar('shortcutid',XOBJ_DTYPE_INT,null,false,10);
		$this->initVar('uid',XOBJ_DTYPE_INT,null,false,10);
		$this->initVar('date',XOBJ_DTYPE_INT,null,false,10);
		$this->initVar('title',XOBJ_DTYPE_TXTBOX, null, false,255);
		$this->initVar('url',XOBJ_DTYPE_TXTBOX, null, false,255);
		$this->initVar('hits',XOBJ_DTYPE_INT,null,false,10);
		$this->initVar('rating',XOBJ_DTYPE_INT,null,false,10);
		if ( !empty($id) ) {
			if ( is_array($id) ) {
				$this->assignVars($id);
			} else {
					$this->load(intval($id));
			}
		} else {
			$this->setNew();
		}
	}

	function load($id)
	{
		$sql = 'SELECT * FROM '.$this->db->prefix('shortcuts').' WHERE shortcutid='.intval($id);
		$myrow = $this->db->fetchArray($this->db->query($sql));
		$this->assignVars($myrow);
		if (!$myrow) {
			$this->setNew();
		}
	}

  	function UpdateUrlHits()
  	{
  		$sql =sprintf("UPDATE %s SET hits=hits+1 WHERE shortcutid=%u",$this->db->prefix('shortcuts'), intval($this->getVar('shortcutid')));
  		return($this->db->queryF($sql));
  	}

	/**
 	 * Returns the user name for the current page (if the parameter is null)
 	 */
	function uname($uid=0)
	{
		global $xoopsConfig;
		static $tblusers = Array();
		if(empty($uid)) {
			$uid = $this->getVar('uid');
		}

		if(is_array($tblusers) && array_key_exists($uid,$tblusers)) {
			return 	$tblusers[$uid];
		}
		$tblusers[$uid] = XoopsUser::getUnameFromId($uid);
		return $tblusers[$uid];
	}
}


class ShortcutsshortcutsHandler extends XoopsObjectHandler
{
	function &create($isNew = true)	{
		$objet = new shortcuts();
		if ($isNew) {
			$objet->setNew();
		}
		return $objet;
	}

	function &get($id)	{
		$ret = null;
		$sql = 'SELECT * FROM '.$this->db->prefix('shortcuts').' WHERE shortcutid='.intval($id);
		if (!$result = $this->db->query($sql)) {
			return $ret;
		}
		$numrows = $this->db->getRowsNum($result);
		if ($numrows == 1) {
			$objet = new shortcuts();
			$objet->assignVars($this->db->fetchArray($result));
			return $objet;
		}
		return $ret;
	}

	function insert(&$objet, $force = false) {
		if (get_class($objet) != 'shortcuts') {
			return false;
		}
		if (!$objet->isDirty()) {
			return true;
		}
		if (!$objet->cleanVars()) {
			foreach($objet->getErrors() as $oneerror) {
				trigger_error($oneerror);
			}
			return false;
		}
		foreach ($objet->cleanVars as $k => $v) {
				${$k} = $v;
		}
		if($date == 0) {
			$date = time();
		}

		if ($objet->isNew()) {
			$format = "INSERT INTO %s (shortcutid, uid, date, title, url, hits, rating) VALUES (%u, %u, %u, %s, %s, %u, %d)";
			$sql = sprintf($format, $this->db->prefix('shortcuts'),$this->db->genId($this->db->prefix('shortcuts')."_up_shortcuts_seq"),$uid, $date, $this->db->quoteString($title), $this->db->quoteString($url), $hits, $rating);
			$force = true;
		} else {
			$format = "UPDATE %s set uid=%u, date=%u, title=%s, url=%s, hits=%u, rating=%d WHERE shortcutid=%u";
			$sql = sprintf($format, $this->db->prefix('shortcuts'),$uid, $date, $this->db->quoteString($title), $this->db->quoteString($url), $hits, $rating, $shortcutid);
		}
		if (false != $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}
		if (!$result) {
			return false;
		}
		if (empty($shortcutid)) {
			$shortcutid = $this->db->getInsertId();
		}
		$objet->assignVar('shortcutid', $shortcutid);
		return $shortcutid;
	}


	function delete(&$objet, $force = false)
	{
		if (get_class($objet) != 'shortcuts') {
			return false;
		}
		$sql = sprintf("DELETE FROM %s WHERE shortcutid = %u", $this->db->prefix('shortcuts'), $objet->getVar('shortcutid'));
		if ($force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}
		if (!$result) {
			return false;
		}
		return true;
	}


	function &getObjects($criteria = null, $id_as_key = false)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT * FROM '.$this->db->prefix('shortcuts');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
			if ($criteria->getSort() != '') {
				$sql .= ' ORDER BY '.$criteria->getSort().' '.$criteria->getOrder();
			}
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		}
		$result = $this->db->query($sql, $limit, $start);
		if (!$result) {
			return $ret;
		}
		while ($myrow = $this->db->fetchArray($result)) {
			if (!$id_as_key) {
				$ret[] = new shortcuts($myrow);
			} else {
				$ret[$myrow['shortcutid']] = new shortcuts($myrow);
			}
		}
		return $ret;
	}


	function &getObjects2($selected_fields = null, $criteria = null)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT '.$selected_fields.' FROM '.$this->db->prefix('shortcuts');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
			if($criteria->groupby!='') {
				$sql .= ' '.$criteria->getGroupby();
			}
			if ($criteria->getSort() != '') {
				$sql .= ' ORDER BY '.$criteria->getSort().' '.$criteria->getOrder();
			}
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		}
		$result = $this->db->query($sql, $limit, $start);
		if (!$result) {
			return $ret;
		}
		while ($myrow = $this->db->fetchArray($result)) {
			$ret[] = array('cpt' => $myrow['cpt'], 'lib' => $myrow['lib']);
		}
		return $ret;
	}


	function getCount($criteria = null)
	{
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('shortcuts');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		$result = $this->db->query($sql);
		if (!$result) {
			return 0;
		}
		list($count) = $this->db->fetchRow($result);
		return $count;
	}


	function deleteAll($criteria = null)
	{
		$sql = 'DELETE FROM '.$this->db->prefix('shortcuts');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		if (!$result = $this->db->queryF($sql)) {
			return false;
		}
		return true;
	}
}
?>