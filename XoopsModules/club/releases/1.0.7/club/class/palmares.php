<?php

if (!defined("XOOPS_ROOT_PATH")) {
	die("XOOPS root path not defined");
}

include_once 'ClubPersistableObjectHandler.php';

class ClubPalmares extends XoopsObject
{

	var $externalKey = array();

	function ClubPalmares()
	{
		$this->initVar('membre_licence', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('tournoi_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('palmares_s', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('palmares_d', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('palmares_m', XOBJ_DTYPE_INT, 0, false);
		
		$this->externalKey['membre_licence'] = array('className'=>'membre', 'getMethodeName'=>'get', 'keyName'=>'membre', 'core'=>false);
		$this->externalKey['tournoi_id'] = array('className'=>'tournoi', 'getMethodeName'=>'get', 'keyName'=>'tournoi', 'core'=>false);
	}
	
	function getExternalKey($key) {
		return $this->externalKey[$key];
	}
	
}

class ClubPalmaresHandler extends ClubPersistableObjectHandler {


	function ClubPalmaresHandler(&$db)
	{
		$this->ClubPersistableObjectHandler($db, 'club_palmares', 'ClubPalmares', array('membre_licence','tournoi_id'));
	}
	
	function getPalmaresJoueur($membre) {
		
		$membreHandler = xoops_getmodulehandler('membre', 'club');
		
		$sql = "SELECT * FROM ".$this->db->prefix('club_palmares')." palm, ".$this->db->prefix('club_membretournoi')." mtourn, ".$this->db->prefix('club_tournoi')." t WHERE palm.tournoi_id = mtourn.tournoi_id AND palm.membre_licence = $membre AND mtourn.membre_licence = $membre AND t.tournoi_id = palm.tournoi_id ORDER BY t.tournoi_start_date;";
		$result = $this->db->query($sql);
		$ret = array();
		while ($myrow = $this->db->fetchArray($result)) {
			// On récupère le membre partenaire uniquement si il y a une valeur pour ce tableau
			if($myrow['palmares_d'] != 0) {
				$membre = $membreHandler->objectToArray($membreHandler->get($myrow['membretournoi_d_partenaire']));
				$myrow['part_double'] = $membre;
			}
			// On récupère le membre partenaire uniquement si il y a une valeur pour ce tableau
			if($myrow['palmares_m'] != 0) {
				$membre = $membreHandler->objectToArray($membreHandler->get($myrow['membretournoi_m_partenaire']));
				$myrow['part_double'] = $membre;
			}
			$ret[] = $myrow;
			
		}
		
		return $ret;
	}
	
	function getPalmaresLastTournoi() {
		
		$sql = "SELECT * FROM `".$this->db->prefix('club_palmares')."` AS p, `".$this->db->prefix('club_tournoi')."` AS t, `".$this->db->prefix('club_membre')."` AS m, `".$this->db->prefix('club_membretournoi')."` mt WHERE p.tournoi_id = t.tournoi_id AND p.membre_licence = m.membre_licence AND mt.membre_licence = p.membre_licence AND mt.tournoi_id = p.tournoi_id ORDER BY t.tournoi_start_date DESC LIMIT 0 , 10;";
		$result = $this->db->query($sql);
		$ret = array();
		while ($myrow = $this->db->fetchArray($result)) {
			$ret[] = $myrow;
		}
		
		return $ret;
	}
	
	function getPalmaresTournoi($tournoi) {
		
		return $this->getObjects(new Criteria('tournoi_id',$tournoi));
		
	}
	
	function isPalmares($tournoi, $membre) {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('tournoi_id',$tournoi));
		$criteria->add(new Criteria('membre_licence',$membre));
		
		return $this->getCount($criteria) == 1;
	}
	
}

?>
