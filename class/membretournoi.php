<?php

if (!defined("XOOPS_ROOT_PATH")) {
	die("XOOPS root path not defined");
}

include_once 'ClubPersistableObjectHandler.php';

class ClubMembretournoi extends XoopsObject
{

	var $externalKey = array();

	function ClubMembretournoi()
	{
		$this->initVar('membre_licence', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('tournoi_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('membretournoi_is_simple', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('membretournoi_s_serie', XOBJ_DTYPE_TXTBOX, '', false);
		$this->initVar('membretournoi_is_double', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('membretournoi_d_partenaire', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('membretournoi_d_serie', XOBJ_DTYPE_TXTBOX, '', false);
		$this->initVar('membretournoi_is_mixte', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('membretournoi_m_partenaire', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('membretournoi_m_serie', XOBJ_DTYPE_TXTBOX, '', false);
		
		$this->externalKey['membre_licence'] = array('className'=>'membre', 'getMethodeName'=>'get', 'keyName'=>'membre', 'core'=>false);
		$this->externalKey['membretournoi_d_partenaire'] = array('className'=>'membre', 'getMethodeName'=>'get', 'keyName'=>'part_double', 'core'=>false);
		$this->externalKey['membretournoi_m_partenaire'] = array('className'=>'membre', 'getMethodeName'=>'get', 'keyName'=>'part_mixte', 'core'=>false);
	}
	
	function getExternalKey($key) {
		return $this->externalKey[$key];
	}
	
}

class ClubMembretournoiHandler extends ClubPersistableObjectHandler {


	function ClubMembretournoiHandler(&$db)
	{
		$this->ClubPersistableObjectHandler($db, 'club_membretournoi', 'ClubMembretournoi', array('membre_licence','tournoi_id'));
	}
	
	function isInscrit($tournoi, $membre) {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('tournoi_id',$tournoi));
		$criteria->add(new Criteria('membre_licence',$membre));
		
		return $this->getCount($criteria) == 1;
	}
	
	function getMembresTournoi($tournoi) {
		return $this->getObjects(new Criteria('tournoi_id', $tournoi));
	}
	
	function enregInscriptions($data) {
		
		if($data['membre_licence'] <= 0) {
			return;
		}
		
		if($data['membretournoi_s_serie']) {
			$data['membretournoi_is_simple'] = 1;
		} else {
			$data['membretournoi_is_simple'] = 0;
		}

		if($data['membretournoi_d_partenaire'] != -1 && $data['membretournoi_d_serie']) {
			$data['membretournoi_is_double'] = 1;
		} else {
			$data['membretournoi_is_double'] = 0;
			$data['membretournoi_d_partenaire'] = 0;
			$data['membretournoi_d_serie'] = '';
		}

		if($data['membretournoi_m_partenaire'] != -1 && $data['membretournoi_m_serie']) {
			$data['membretournoi_is_mixte'] = 1;
		} else {
			$data['membretournoi_is_mixte'] = 0;
			$data['membretournoi_m_partenaire'] = 0;
			$data['membretournoi_m_serie'] = '';
		}

		// Si le membre est déja inscrit au tournoi
		if($this->isInscrit($data['tournoi_id'], $data['membre_licence'])) {
			$membretournoi = $this->get(array($data['membre_licence'], $data['tournoi_id']));
			
			// Si membre n'était pas inscrit en double en X et que le partenaire de double change
			if($membretournoi->getVar('membretournoi_d_partenaire') != $data['membretournoi_d_partenaire'] && $membretournoi->getVar('membretournoi_d_partenaire') != 0) {
				$oldPartDouble = $this->get(array($membretournoi->getVar('membretournoi_d_partenaire'), $data['tournoi_id']));
				// Si l'ancien partenaire n'était inscrit qu'en double, on supprime son inscription, sinon on supprime uniquement son inscription en double
				if(!$oldPartDouble->getVar('membretournoi_is_simple') && !$oldPartDouble->getVar('membretournoi_is_mixte')) {
					$this->delete(array($oldPartDouble->getVar('membre_licence'), $oldPartDouble->getVar('tournoi_id')));
				} else {
					$oldPartDouble->setVar('membretournoi_is_double',0);
					$oldPartDouble->setVar('membretournoi_d_partenaire',0);
					$this->insert($oldPartDouble);
				}
			}
			
			// Si membre n'était pas inscrit en mixte en X et que le partenaire de mixte change
			if($membretournoi->getVar('membretournoi_m_partenaire') != $data['membretournoi_m_partenaire'] && $membretournoi->getVar('membretournoi_m_partenaire') != 0) {
				$oldPartMixte = $this->get(array($membretournoi->getVar('membretournoi_m_partenaire'), $data['tournoi_id']));
				// Si l'ancien partenaire n'était inscrit qu'en double, on supprime son inscription, sinon on supprime uniquement son inscription en double
				if(!$oldPartMixte->getVar('membretournoi_is_simple') && !$oldPartMixte->getVar('membretournoi_is_double')) {
					$this->delete(array($oldPartMixte->getVar('membre_licence'), $oldPartMixte->getVar('tournoi_id')));
				} else {
					$oldPartMixte->setVar('membretournoi_is_mixte',0);
					$oldPartMixte->setVar('membretournoi_m_partenaire',0);
					$this->insert($oldPartMixte);
				}
			}
			
		} else {
			$membretournoi = $this->create();
		}
		$membretournoi->setVars($data);
		$this->insert($membretournoi);

		// Si le partenaire de double n'est pas X
		if($data['membretournoi_d_partenaire']) {
			// Si le partenaire est déja inscrit au tournoi
			if($this->isInscrit($data['tournoi_id'], $data['membretournoi_d_partenaire'])) {
				$partDouble = $this->get(array($data['membretournoi_d_partenaire'], $data['tournoi_id']));
			} else {
				$partDouble = $this->create();
				$partDouble->setVar('tournoi_id', $data['tournoi_id']);
				$partDouble->setVar('membre_licence', $data['membretournoi_d_partenaire']);
			}
			
			// Mise à jour et insertion du partenaire de double
			$partDouble->setVar('membretournoi_is_double', 1);
			$partDouble->setVar('membretournoi_d_partenaire', $data['membre_licence']);
			$partDouble->setVar('membretournoi_d_serie', $data['membretournoi_d_serie']);
			
			$this->insert($partDouble);
		}

		// Si le partenaire de mixte n'est pas X
		if($data['membretournoi_m_partenaire']) {
			// Si le partenaire est déja inscrit au tournoi
			if($this->isInscrit($data['tournoi_id'], $data['membretournoi_m_partenaire'])) {
				$partMixte = $this->get(array($data['membretournoi_m_partenaire'], $data['tournoi_id']));
			} else {
				$partMixte = $this->create();
				$partMixte->setVar('tournoi_id', $data['tournoi_id']);
				$partMixte->setVar('membre_licence', $data['membretournoi_m_partenaire']);
			}
			
			// Mise à jour et insertion du partenaire de double
			$partMixte->setVar('membretournoi_is_mixte', 1);
			$partMixte->setVar('membretournoi_m_partenaire', $data['membre_licence']);
			$partMixte->setVar('membretournoi_m_serie', $data['membretournoi_m_serie']);
			
			$this->insert($partMixte);
		}
		
	}
	
}

?>
