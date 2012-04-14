<?php

if (!defined("XOOPS_ROOT_PATH")) {
	die("XOOPS root path not defined");
}

include_once 'ClubPersistableObjectHandler.php';

class ClubResultmembre extends XoopsObject
{

	function ClubResultmembre()
	{
		$this->initVar('membre_licence', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('resultmembre_date', XOBJ_DTYPE_TXTBOX, '', false);
		$this->initVar('resultmembre_class_simple', XOBJ_DTYPE_TXTBOX, 'NC', false, 255);
		$this->initVar('resultmembre_class_double', XOBJ_DTYPE_TXTBOX, 'NC', false, 255);
		$this->initVar('resultmembre_class_mixte', XOBJ_DTYPE_TXTBOX, 'NC', false, 255);
		$this->initVar('resultmembre_moy_simple', XOBJ_DTYPE_TXTBOX, '0.0', false, 255);
		$this->initVar('resultmembre_moy_double', XOBJ_DTYPE_TXTBOX, '0.0', false, 255);
		$this->initVar('resultmembre_moy_mixte', XOBJ_DTYPE_TXTBOX, '0.0', false, 255);
	}

}

class ClubResultmembreHandler extends ClubPersistableObjectHandler {


	function ClubResultmembreHandler(&$db)
	{
		$this->ClubPersistableObjectHandler($db, 'club_resultmembre', 'ClubResultmembre', array('membre_licence','resultmembre_date'));
	}

	function getResultmembreSaison(&$membre, $start, $end) {

		$sql = "SELECT * FROM ".$this->table." WHERE `membre_licence` = '".$membre->getVar('membre_licence')."' AND `resultmembre_date` BETWEEN '$start-09-01' AND '$end-07-01' ORDER BY resultmembre_date;";
		$result = $this->db->queryF($sql);

		$ret = array();
		while ($row = $this->db->fetchArray($result)) {
			$ret[] = $row;
		}

		return $ret;
	}

	function getResultmembre(&$membre, $start, $end) {

		$sql = "SELECT * FROM ".$this->table." WHERE `membre_licence` = '".$membre->getVar('membre_licence')."' AND `resultmembre_date` LIKE '%-01' AND `resultmembre_date` BETWEEN '$start-09-01' AND '$end-07-01' ORDER BY resultmembre_date;";
		$result = $this->db->queryF($sql);

		$ret = array();
		while ($row = $this->db->fetchArray($result)) {
			$date = explode("-",$row['resultmembre_date']);
			if($date[2] != 1)
				continue;
			$ret[$date[0]][$date[1]] = $row;
		}

		return $ret;
	}

}

?>
