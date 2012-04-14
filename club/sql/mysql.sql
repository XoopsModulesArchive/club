CREATE TABLE `club_club` (
  `club_id` int(11) NOT NULL default '0',
  `club_sigle` varchar(10) NOT NULL default '',
  `club_nom` varchar(50) NOT NULL default '',
  `club_isparent` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`club_id`)
) ;

CREATE TABLE `club_equipe` (
  `equipe_id` int(11) NOT NULL auto_increment,
  `equipe_photo` int(11) default NULL,
  `equipe_rank_in_club` int(11) NOT NULL default '0',
  `club_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`equipe_id`)
) ;

CREATE TABLE `club_fonction` (
  `fonction_id` int(11) NOT NULL auto_increment,
  `fonction_nom` varchar(50) NOT NULL default '',
  `fonction_ordre` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`fonction_id`)
) ;

CREATE TABLE `club_membre` (
  `membre_licence` int(11) NOT NULL auto_increment,
  `membre_nom` varchar(50) NOT NULL default '',
  `membre_prenom` varchar(50) NOT NULL default '',
  `membre_birth` date NOT NULL default '0000-00-00',
  `membre_categorie` varchar(50) NOT NULL default '',
  `membre_sexe` enum('H','F') NOT NULL,
  `membre_class_s` enum('NC','D4','D3','D2','D1','C4','C3','C2','C1','B4','B3','B2','B1','A4','A3','A2','A1','T50','T20','T10','T5') NOT NULL,
  `membre_class_d` enum('NC','D4','D3','D2','D1','C4','C3','C2','C1','B4','B3','B2','B1','A4','A3','A2','A1','T50','T20','T10','T5') NOT NULL,
  `membre_class_m` enum('NC','D4','D3','D2','D1','C4','C3','C2','C1','B4','B3','B2','B1','A4','A3','A2','A1','T50','T20','T10','T5') NOT NULL,
  `membre_moy_s` float NOT NULL default '0',
  `membre_moy_d` float NOT NULL default '0',
  `membre_moy_m` float NOT NULL default '0',
  `membre_moyd_s` float NOT NULL,
  `membre_moyd_d` float NOT NULL,
  `membre_moyd_m` float NOT NULL,
  `membre_mclass_s` enum('NC','D4','D3','D2','D1','C4','C3','C2','C1','B4','B3','B2','B1','A4','A3','A2','A1','T50','T20','T10','T5') NOT NULL,
  `membre_mclass_d` enum('NC','D4','D3','D2','D1','C4','C3','C2','C1','B4','B3','B2','B1','A4','A3','A2','A1','T50','T20','T10','T5') NOT NULL,
  `membre_mclass_m` enum('NC','D4','D3','D2','D1','C4','C3','C2','C1','B4','B3','B2','B1','A4','A3','A2','A1','T50','T20','T10','T5') NOT NULL,
  `membre_mmoy_s` float NOT NULL default '0',
  `membre_mmoy_d` float NOT NULL default '0',
  `membre_mmoy_m` float NOT NULL default '0',
  `membre_vclass_s` enum('NC','D4','D3','D2','D1','C4','C3','C2','C1','B4','B3','B2','B1','A4','A3','A2','A1','T50','T20','T10','T5') NOT NULL,
  `membre_vclass_d` enum('NC','D4','D3','D2','D1','C4','C3','C2','C1','B4','B3','B2','B1','A4','A3','A2','A1','T50','T20','T10','T5') NOT NULL,
  `membre_vclass_m` enum('NC','D4','D3','D2','D1','C4','C3','C2','C1','B4','B3','B2','B1','A4','A3','A2','A1','T50','T20','T10','T5') NOT NULL,
  `fonction_id` int(3) NOT NULL default '0',
  `membre_equipe` int(3) NOT NULL default '0',
  `membre_actif` tinyint(1) NOT NULL default '0',
  `membre_nolicence` tinyint(1) NOT NULL,
  `membre_photo` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`membre_licence`)
) ;

CREATE TABLE `club_membresaison` (
  `membre_licence` int(11) NOT NULL,
  `saison_saison` varchar(9) NOT NULL,
  PRIMARY KEY  (`membre_licence`,`saison_saison`)
) ;

CREATE TABLE `club_membretournoi` (
  `membre_licence` int(11) NOT NULL,
  `tournoi_id` int(11) NOT NULL,
  `membretournoi_is_simple` tinyint(1) NOT NULL default '0',
  `membretournoi_s_serie` enum('NC','D','C','B','A') NOT NULL,
  `membretournoi_is_double` tinyint(1) NOT NULL default '0',
  `membretournoi_d_partenaire` int(11) NOT NULL default '0',
  `membretournoi_d_serie` enum('NC','D','C','B','A') NOT NULL,
  `membretournoi_is_mixte` tinyint(1) NOT NULL default '0',
  `membretournoi_m_partenaire` int(11) NOT NULL default '0',
  `membretournoi_m_serie` enum('NC','D','C','B','A') NOT NULL,
  PRIMARY KEY  (`membre_licence`,`tournoi_id`),
  UNIQUE KEY `unique_double` (`membre_licence`,`tournoi_id`,`membretournoi_is_double`),
  UNIQUE KEY `unique_simple` (`membre_licence`,`tournoi_id`,`membretournoi_is_simple`),
  UNIQUE KEY `unique_mixte` (`membre_licence`,`tournoi_id`,`membretournoi_is_mixte`)
) ;

CREATE TABLE `club_palmares` (
  `membre_licence` int(11) NOT NULL,
  `tournoi_id` int(11) NOT NULL,
  `palmares_s` tinyint(4) NOT NULL default '0',
  `palmares_d` tinyint(4) NOT NULL default '0',
  `palmares_m` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`membre_licence`,`tournoi_id`)
) ;

CREATE TABLE `club_resultmembre` (
  `membre_licence` int(11) NOT NULL,
  `resultmembre_date` date NOT NULL,
  `resultmembre_class_simple` enum('NC','D4','D3','D2','D1','C4','C3','C2','C1','B4','B3','B2','B1','A4','A3','A2','A1','T50','T20','T10','T5') NOT NULL,
  `resultmembre_class_double` enum('NC','D4','D3','D2','D1','C4','C3','C2','C1','B4','B3','B2','B1','A4','A3','A2','A1','T50','T20','T10','T5') NOT NULL,
  `resultmembre_class_mixte` enum('NC','D4','D3','D2','D1','C4','C3','C2','C1','B4','B3','B2','B1','A4','A3','A2','A1','T50','T20','T10','T5') NOT NULL,
  `resultmembre_moy_simple` float NOT NULL,
  `resultmembre_moy_double` float NOT NULL,
  `resultmembre_moy_mixte` float NOT NULL,
  PRIMARY KEY  (`membre_licence`,`resultmembre_date`)
) ;

CREATE TABLE `club_statmembres_double` (
  `statmembres_cat` enum('NC','D4','D3','D2','D1','C4','C3','C2','C1','B4','B3','B2','B1','A4','A3','A2','A1','T50','T20','T10','T5') NOT NULL,
  `statmembres_poussin` int(3) NOT NULL default '0',
  `statmembres_benjamin` int(3) NOT NULL default '0',
  `statmembres_minime` int(3) NOT NULL default '0',
  `statmembres_cadet` int(3) NOT NULL default '0',
  `statmembres_junior` int(3) NOT NULL default '0',
  `statmembres_senior` int(3) NOT NULL default '0',
  `statmembres_veteran` int(3) NOT NULL default '0',
  PRIMARY KEY  (`statmembres_cat`)
) ;

CREATE TABLE `club_statmembres_mixte` (
  `statmembres_cat` enum('NC','D4','D3','D2','D1','C4','C3','C2','C1','B4','B3','B2','B1','A4','A3','A2','A1','T50','T20','T10','T5') NOT NULL,
  `statmembres_poussin` int(3) NOT NULL default '0',
  `statmembres_benjamin` int(3) NOT NULL default '0',
  `statmembres_minime` int(3) NOT NULL default '0',
  `statmembres_cadet` int(3) NOT NULL default '0',
  `statmembres_junior` int(3) NOT NULL default '0',
  `statmembres_senior` int(3) NOT NULL default '0',
  `statmembres_veteran` int(3) NOT NULL default '0',
  PRIMARY KEY  (`statmembres_cat`)
) ;

CREATE TABLE `club_statmembres_simple` (
  `statmembres_cat` enum('NC','D4','D3','D2','D1','C4','C3','C2','C1','B4','B3','B2','B1','A4','A3','A2','A1','T50','T20','T10','T5') NOT NULL,
  `statmembres_poussin` int(3) NOT NULL default '0',
  `statmembres_benjamin` int(3) NOT NULL default '0',
  `statmembres_minime` int(3) NOT NULL default '0',
  `statmembres_cadet` int(3) NOT NULL default '0',
  `statmembres_junior` int(3) NOT NULL default '0',
  `statmembres_senior` int(3) NOT NULL default '0',
  `statmembres_veteran` int(3) NOT NULL default '0',
  PRIMARY KEY  (`statmembres_cat`)
) ;

CREATE TABLE `club_tournoi` (
  `tournoi_id` int(11) NOT NULL auto_increment,
  `tournoi_name` varchar(255) NOT NULL,
  `tournoi_desc` text NOT NULL,
  `tournoi_series` varchar(255) NOT NULL,
  `tournoi_start_date` int(11) NOT NULL,
  `tournoi_end_date` int(11) NOT NULL,
  PRIMARY KEY  (`tournoi_id`)
) ;

INSERT INTO `club_statmembres_double` (`statmembres_cat`, `statmembres_poussin`, `statmembres_benjamin`, `statmembres_minime`, `statmembres_cadet`, `statmembres_junior`, `statmembres_senior`, `statmembres_veteran`) VALUES 
('T5', 0, 0, 0, 0, 0, 0, 0),
('T10', 0, 0, 0, 0, 0, 0, 0),
('T20', 0, 0, 0, 0, 0, 0, 0),
('T50', 0, 0, 0, 0, 0, 0, 0),
('A1', 0, 0, 0, 0, 0, 0, 0),
('A2', 0, 0, 0, 0, 0, 0, 0),
('A3', 0, 0, 0, 0, 0, 0, 0),
('A4', 0, 0, 0, 0, 0, 0, 0),
('B1', 0, 0, 0, 0, 0, 0, 0),
('B2', 0, 0, 0, 0, 0, 0, 0),
('B3', 0, 0, 0, 0, 0, 0, 0),
('B4', 0, 0, 0, 0, 0, 0, 0),
('C1', 0, 0, 0, 0, 0, 0, 0),
('C2', 0, 0, 0, 0, 0, 0, 0),
('C3', 0, 0, 0, 0, 0, 0, 0),
('C4', 0, 0, 0, 0, 0, 0, 0),
('D1', 0, 0, 0, 0, 0, 0, 0),
('D2', 0, 0, 0, 0, 0, 0, 0),
('D3', 0, 0, 0, 0, 0, 0, 0),
('D4', 0, 0, 0, 0, 0, 0, 0),
('NC', 0, 0, 0, 0, 0, 0, 0);

INSERT INTO `club_statmembres_mixte` (`statmembres_cat`, `statmembres_poussin`, `statmembres_benjamin`, `statmembres_minime`, `statmembres_cadet`, `statmembres_junior`, `statmembres_senior`, `statmembres_veteran`) VALUES 
('T5', 0, 0, 0, 0, 0, 0, 0),
('T10', 0, 0, 0, 0, 0, 0, 0),
('T20', 0, 0, 0, 0, 0, 0, 0),
('T50', 0, 0, 0, 0, 0, 0, 0),
('A1', 0, 0, 0, 0, 0, 0, 0),
('A2', 0, 0, 0, 0, 0, 0, 0),
('A3', 0, 0, 0, 0, 0, 0, 0),
('A4', 0, 0, 0, 0, 0, 0, 0),
('B1', 0, 0, 0, 0, 0, 0, 0),
('B2', 0, 0, 0, 0, 0, 0, 0),
('B3', 0, 0, 0, 0, 0, 0, 0),
('B4', 0, 0, 0, 0, 0, 0, 0),
('C1', 0, 0, 0, 0, 0, 0, 0),
('C2', 0, 0, 0, 0, 0, 0, 0),
('C3', 0, 0, 0, 0, 0, 0, 0),
('C4', 0, 0, 0, 0, 0, 0, 0),
('D1', 0, 0, 0, 0, 0, 0, 0),
('D2', 0, 0, 0, 0, 0, 0, 0),
('D3', 0, 0, 0, 0, 0, 0, 0),
('D4', 0, 0, 0, 0, 0, 0, 0),
('NC', 0, 0, 0, 0, 0, 0, 0);

INSERT INTO `club_statmembres_simple` (`statmembres_cat`, `statmembres_poussin`, `statmembres_benjamin`, `statmembres_minime`, `statmembres_cadet`, `statmembres_junior`, `statmembres_senior`, `statmembres_veteran`) VALUES 
('T5', 0, 0, 0, 0, 0, 0, 0),
('T10', 0, 0, 0, 0, 0, 0, 0),
('T20', 0, 0, 0, 0, 0, 0, 0),
('T50', 0, 0, 0, 0, 0, 0, 0),
('A1', 0, 0, 0, 0, 0, 0, 0),
('A2', 0, 0, 0, 0, 0, 0, 0),
('A3', 0, 0, 0, 0, 0, 0, 0),
('A4', 0, 0, 0, 0, 0, 0, 0),
('B1', 0, 0, 0, 0, 0, 0, 0),
('B2', 0, 0, 0, 0, 0, 0, 0),
('B3', 0, 0, 0, 0, 0, 0, 0),
('B4', 0, 0, 0, 0, 0, 0, 0),
('C1', 0, 0, 0, 0, 0, 0, 0),
('C2', 0, 0, 0, 0, 0, 0, 0),
('C3', 0, 0, 0, 0, 0, 0, 0),
('C4', 0, 0, 0, 0, 0, 0, 0),
('D1', 0, 0, 0, 0, 0, 0, 0),
('D2', 0, 0, 0, 0, 0, 0, 0),
('D3', 0, 0, 0, 0, 0, 0, 0),
('D4', 0, 0, 0, 0, 0, 0, 0),
('NC', 0, 0, 0, 0, 0, 0, 0);
