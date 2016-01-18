<?php

if (!defined("XOOPS_ROOT_PATH")) {
	die("XOOPS root path not defined");
}

include_once 'ClubPersistableObjectHandler.php';

class ClubTournoi extends XoopsObject
{

	function ClubTournoi()
	{
		$this->initVar('tournoi_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('tournoi_name', XOBJ_DTYPE_TXTBOX, '', false);
		$this->initVar('tournoi_desc', XOBJ_DTYPE_TXTBOX, '', false);
		$this->initVar('tournoi_series', XOBJ_DTYPE_TXTBOX, '', false);
		$this->initVar('tournoi_inscr_date', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('tournoi_start_date', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('tournoi_end_date', XOBJ_DTYPE_INT, 0, false);
	}

}

class ClubTournoiHandler extends ClubPersistableObjectHandler {


	function ClubTournoiHandler(&$db)
	{
		$this->ClubPersistableObjectHandler($db, 'club_tournoi', 'ClubTournoi', 'tournoi_id');
	}

	function makeDateString(&$data) {
		$startDate = date('j', $data['tournoi_start_date']);
		$endDate = date('j', $data['tournoi_end_date']);
		
		$data['formated_inscr_date'] = date('j/m/Y', $data['tournoi_inscr_date']);
		
		if($endDate - $startDate == 0) {
			$data['formated_date'] = "Le ".date('j/m/Y', $data['tournoi_start_date']);
			return;
		} elseif($endDate - $startDate == 1) {
			$data['formated_date'] = "les ".date('j/m/Y', $data['tournoi_start_date'])." et ".date('j/m/Y', $data['tournoi_end_date']);
			return;
		} else {
		
		}
		
	}
	
	function makeDatesString(&$data) {
		$nbTournoi = count($data);
		for($i=0;$i<$nbTournoi;$i++) {
			$this->makeDateString($data[$i]);
		}
	}
	
	function checkSeries(&$data) {
		$allowedSeries = array('NC','D','C','B','A');
		$series = strtoupper($data->getVar('tournoi_series'));
		$data->setVar('tournoi_series',$series);
		$series = explode('|', $series);
		
		foreach($series as $serie) {
			if(!in_array($serie, $allowedSeries)) {
				return false;
			}
		}
		return true;
	}
	
	function getNextTournoi() {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('tournoi_start_date',time(), '>'));
		$criteria->setSort('tournoi_start_date');
		$criteria->setOrder('ASC');
		
		return $this->getObjects($criteria);
	}
	
}

?>
