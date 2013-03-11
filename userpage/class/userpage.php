<?php
/**
 * ****************************************************************************
 * USERPAGE - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * ****************************************************************************
 */

require_once XOOPS_ROOT_PATH."/class/xoopsobject.php";
require_once XOOPS_ROOT_PATH.'/modules/userpage/include/functions.php';

class userpage extends XoopsObject
{
	function userpage($id=null)
	{
		$this->db =& Database::getInstance();
		$this->initVar('up_pageid',XOBJ_DTYPE_INT,null,false,10);
		$this->initVar('up_uid',XOBJ_DTYPE_INT,null,false,10);
		$this->initVar('up_title',XOBJ_DTYPE_TXTBOX, null, false,255);
		$this->initVar('up_text',XOBJ_DTYPE_TXTAREA, null, false);
		$this->initVar('up_created',XOBJ_DTYPE_INT,null,false,10);
		$this->initVar('up_hits',XOBJ_DTYPE_INT,null,false,10);
		$this->initVar('dohtml', XOBJ_DTYPE_INT, 0, false);
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
		$sql = 'SELECT * FROM '.$this->db->prefix('userpage').' WHERE up_pageid='.intval($id);
		$myrow = $this->db->fetchArray($this->db->query($sql));
		$this->assignVars($myrow);
		if (!$myrow) {
			$this->setNew();
		}
	}


	/**
 	 * Returns the user name for the current page (if the parameter is null)
 	 */
	function uname($uid=0)
	{
		global $xoopsConfig;
		static $tblusers = array();
		$option=-1;
		if(empty($uid)) {
			$uid=$this->getVar('up_uid');
		}

		if(is_array($tblusers) && array_key_exists($uid,$tblusers)) {
			return 	$tblusers[$uid];
		}
		$tblusers[$uid]=XoopsUser::getUnameFromId($uid);
		return $tblusers[$uid];
	}
}


class UserpageuserpageHandler extends XoopsObjectHandler
{
	function &create($isNew = true)	{
		$objet = new userpage();
		if ($isNew) {
			$objet->setNew();
		}
		return $objet;
	}

	function &get($id)	{
		$ret = null;
		$sql = 'SELECT * FROM '.$this->db->prefix('userpage').' WHERE up_pageid='.intval($id);
		if (!$result = $this->db->query($sql)) {
			return $ret;
		}
		$numrows = $this->db->getRowsNum($result);
		if ($numrows == 1) {
			$objet = new userpage();
			$objet->assignVars($this->db->fetchArray($result));
			return $objet;
		}
		return $ret;
	}


	function insert(&$objet, $force = false) {
		if (get_class($objet) != 'userpage') {
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
		if ($objet->isNew()) {
			$format = "INSERT INTO %s (up_pageid, up_uid, up_title, up_text, up_created, up_hits) VALUES (%u, %u, %s, %s, %u, %u)";
			$sql = sprintf($format, $this->db->prefix('userpage'),$this->db->genId($this->db->prefix('userpage')."_up_pageid_seq"),$up_uid, $this->db->quoteString($up_title),$this->db->quoteString($up_text),$up_created, $up_hits);
			$force = true;
		} else {
			$format = "UPDATE %s SET up_uid=%d, up_title=%s, up_text=%s, up_created=%u, up_hits=%u WHERE up_pageid = %u";
			$sql = sprintf($format, $this->db->prefix('userpage'),$up_uid,$this->db->quoteString($up_title), $this->db->quoteString($up_text), $up_created, $up_hits, $up_pageid);
		}
		if ($force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}
		if (!$result) {
			return false;
		}
		if (empty($up_pageid)) {
			$up_pageid = $this->db->getInsertId();
		}
		$objet->assignVar('up_pageid', $up_pageid);
		return $up_pageid;
	}


	function delete(&$objet, $force = false)
	{
		if (get_class($objet) != 'userpage') {
			return false;
		}
		$sql = sprintf("DELETE FROM %s WHERE up_pageid = %u", $this->db->prefix('userpage'), $objet->getVar('up_pageid'));
		if (false != $force) {
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
		$sql = 'SELECT * FROM '.$this->db->prefix('userpage');
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
				$ret[] = new userpage($myrow);
			} else {
				$ret[$myrow['up_pageid']] = new userpage($myrow);
			}
		}
		return $ret;
	}


	/**
	 * Function used to return random pages
	 */
	function getRandomPages($criteria = null, $id_as_key = false, $items_count=10)
	{
		$ret = $rand_keys = $ret3 = array();
		$limit=$start=0;
		$sql = 'SELECT up_pageid FROM '.$this->db->prefix('userpage');
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
		while ( $myrow = $this->db->fetchArray($result) ) {
			$ret[] = $myrow['up_pageid'];
		}
		$cnt=count($ret);
		if($cnt)	{
			if($items_count>$cnt) {
				$items_count=$cnt;
			}
			$rand_keys = array_rand($ret, $items_count);
			if($items_count>1) {
				for($i=0;$i < $items_count;$i++) {
					$onepage=$ret[$rand_keys[$i]];
					$ret3[]= new userpage($onepage);
				}
			} else {
				$ret3[]= new userpage($ret[$rand_keys]);
			}
		}
		return $ret3;
	}



	function getCount($criteria = null)
	{
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('userpage');
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
		$sql = 'DELETE FROM '.$this->db->prefix('userpage');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		if (!$result = $this->db->queryF($sql)) {
			return false;
		}
		return true;
	}


	/**
 	* Update current page's counter
 	*/
	function UpdateCounter($up_pageid)
	{
		$sql = "UPDATE " . $this->db->prefix("userpage") . " SET up_hits=up_hits+1 WHERE up_pageid = " . intval($up_pageid);
		if($this->db->queryF($sql)) {
			return true;
		} else {
			return false;
		}
	}
}
?>