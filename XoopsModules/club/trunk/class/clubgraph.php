<?php

class ClubGraph {

	function graphStatHommeFemme($nbHomme, $nbFemme) {

		require_once 'pear/Image/Graph.php';

		$Graph =& Image_Graph::factory('graph', array(400, 300));

		// add a TrueType font
		$Font =& $Graph->addNew('font', XOOPS_ROOT_PATH.'/modules/club/include/verdana.ttf');
		// set the font size to 7 pixels
		$Font->setSize($GLOBALS['xoopsModuleConfig']['graph_fontSize']);
		$Font->setColor($GLOBALS['xoopsModuleConfig']['graph_fontColor']);

		$Graph->setFont($Font);

		$Graph->setBackgroundColor($GLOBALS['xoopsModuleConfig']['graph_backgroudColor']);

		// create the plotarea
		$Graph->add(
			Image_Graph::vertical(
				Image_Graph::factory('title', array('Répartition Homme Femme', 12)),
				Image_Graph::vertical(
					$Plotarea = Image_Graph::factory('plotarea'),
					$Legend = Image_Graph::factory('legend'),
					90
				),
				5
			)
		);

		if($nbHomme != 0 || $nbFemme != 0) {

			$Legend->setPlotarea($Plotarea);

			// create the 1st dataset
			$Dataset1 =& Image_Graph::factory('dataset');
			$Dataset1->addPoint('Homme', $nbHomme);
			$Dataset1->addPoint('Femme', $nbFemme);
			// create the 1st plot as smoothed area chart using the 1st dataset
			$Plot =& $Plotarea->addNew('pie', array(&$Dataset1));
			$Plotarea->hideAxis();

			// create a Y data value marker
			$Marker =& $Plot->addNew('Image_Graph_Marker_Value', IMAGE_GRAPH_VALUE_Y);
			// create a pin-point marker type
			$PointingMarker =& $Plot->addNew('Image_Graph_Marker_Pointing_Angular', array(-40, &$Marker));
			// and use the marker on the 1st plot
			$Plot->setMarker($PointingMarker);
			// format value marker labels as percentage values

			$Plot->Radius = 2;

			$FillArray =& Image_Graph::factory('Image_Graph_Fill_Array');
			$Plot->setFillStyle($FillArray);
			for($i=0;$i<2;$i++) {
				$FillArray->addNew('gradient', array(IMAGE_GRAPH_GRAD_RADIAL, 'white', $this->_camenbertColor[$i]));
			}

			$Plot->explode(5);

			$PointingMarker->setLineColor(false);
			$Marker->setBorderColor(false);
			$Marker->setFillColor(false);
		}

		// output the Graph
		$nomFichier = XOOPS_ROOT_PATH.'/modules/club/cache/repartition-homme-femme.png';
		$Graph->done(array('filename' => $nomFichier));

	}

	function graphStatCategorieAge($data) {

		require_once 'pear/Image/Graph.php';

		$Graph =& Image_Graph::factory('graph', array(400, 300));

		// add a TrueType font
		$Font =& $Graph->addNew('font', XOOPS_ROOT_PATH.'/modules/club/include/verdana.ttf');
		// set the font size to 7 pixels
		$Font->setSize($GLOBALS['xoopsModuleConfig']['graph_fontSize']);
		$Font->setColor($GLOBALS['xoopsModuleConfig']['graph_fontColor']);

		$Graph->setFont($Font);

		$Graph->setBackgroundColor($GLOBALS['xoopsModuleConfig']['graph_backgroudColor']);

		// create the plotarea
		$Graph->add(
			Image_Graph::vertical(
				Image_Graph::factory('title', array('Répartition par catégorie d\'age', 12)),
				Image_Graph::horizontal(
					$Plotarea = Image_Graph::factory('plotarea'),
					$Legend = Image_Graph::factory('legend'),
					70
				),
				5
			)
		);

		if($data['Poussin'] != 0 || $data['Benjamin'] != 0 || $data['Minime'] != 0 || $data['Cadet'] != 0 || $data['Junior'] != 0 || $data['Senior'] != 0 || $data['Vétéran'] != 0) {

			$Legend->setPlotarea($Plotarea);

			// create the 1st dataset
			$Dataset1 =& Image_Graph::factory('dataset');
			foreach($data as $k=>$v) {
				if($v > 0) {
					$Dataset1->addPoint($k, $v);
				}
			}
			// create the 1st plot as smoothed area chart using the 1st dataset
			$Plot =& $Plotarea->addNew('pie', array(&$Dataset1));
			$Plotarea->hideAxis();

			// create a Y data value marker
			$Marker =& $Plot->addNew('Image_Graph_Marker_Value', IMAGE_GRAPH_VALUE_Y);
			// create a pin-point marker type
			$PointingMarker =& $Plot->addNew('Image_Graph_Marker_Pointing_Angular', array(20, &$Marker));
			// and use the marker on the 1st plot
			$Plot->setMarker($PointingMarker);
			// format value marker labels as percentage values

			$Plot->Radius = 2;

			$FillArray =& Image_Graph::factory('Image_Graph_Fill_Array');
			$Plot->setFillStyle($FillArray);
			for($i=0;$i<7;$i++) {
				$FillArray->addNew('gradient', array(IMAGE_GRAPH_GRAD_RADIAL, 'white', $this->_camenbertColor[$i]));
			}

			$Plot->explode(5);

			$PointingMarker->setLineColor(false);
			$Marker->setBorderColor(false);
			$Marker->setFillColor(false);

		}

		// output the Graph
		$nomFichier = XOOPS_ROOT_PATH.'/modules/club/cache/repartition-age.png';
		$Graph->done(array('filename' => $nomFichier));

	}

	function graphClassementSaisonJoueur($membre, $saisonAnneeDeb, $saisonAnneeF) {

		require_once 'pear/Image/Graph.php';

		$classmembreHandler = xoops_getmodulehandler('resultmembre', 'club');

		// Récupération des données
		$classMembreDatas = $classmembreHandler->getResultmembreSaison($membre, $saisonAnneeDeb, $saisonAnneeF);

		// Initialisation des données pour la création du graph
		$graphData = array(
			'simple'=>array(
			'09'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'10'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'11'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'12'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'01'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'02'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'03'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'04'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'05'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'06'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'07'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null)
			),
			'double'=>array(
			'09'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'10'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'11'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'12'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'01'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'02'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'03'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'04'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'05'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'06'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'07'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null)
			),
			'mixte'=>array(
			'09'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'10'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'11'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'12'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'01'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'02'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'03'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'04'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'05'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'06'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
			'07'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null)
			)
		);

		$class = array('NC','D4','D3','D2','D1','C4','C3','C2','C1','B4','B3','B2','B1','A4','A3','A2','A1');
		$classReverse = array('NC'=>0,'D4'=>1,'D3'=>2,'D2'=>3,'D1'=>4,'C4'=>5,'C3'=>6,'C2'=>7,'C1'=>8,'B4'=>9,'B3'=>10,'B2'=>11,'B1'=>12,'A4'=>13,'A3'=>14,'A2'=>15,'A1'=>16);

		$nomMois = array('09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec','01'=>'Jan','02'=>'Fev','03'=>'Mar','04'=>'Avr','05'=>'Mai','06'=>'Jun','07'=>'Jul');
		$jours = array('01','07','15','23');

		foreach($classMembreDatas as $classMembreData) {
			$date = explode('-', $classMembreData['resultmembre_date']);

			$graphData['simple'][$date[1]][$date[2]] = $classReverse[$classMembreData['resultmembre_class_simple']];
			$graphData['double'][$date[1]][$date[2]] = $classReverse[$classMembreData['resultmembre_class_double']];
			$graphData['mixte'][$date[1]][$date[2]] = $classReverse[$classMembreData['resultmembre_class_mixte']];
		}

		// Création des dataset pour le graph
		$datasetSimple =& Image_Graph::factory('dataset');
		$datasetDouble =& Image_Graph::factory('dataset');
		$datasetMixte =& Image_Graph::factory('dataset');

		foreach($nomMois as $numM=>$nomM) {
			foreach($jours as $jour) {
				if($numM == '07' && ($jour == '07' || $jour == '15' || $jour == '23'))
					continue;

				$datasetSimple->addPoint("\n$jour\n$nomM", $graphData['simple'][$numM][$jour]);
				$datasetDouble->addPoint("\n$jour\n$nomM", $graphData['double'][$numM][$jour]);
				$datasetMixte->addPoint("\n$jour\n$nomM", $graphData['mixte'][$numM][$jour]);
			}
		}

		$Graph =& Image_Graph::factory('graph', array(600, 400));

		$Font =& $Graph->addNew('font', XOOPS_ROOT_PATH.'/modules/club/include/verdana.ttf');
		$Font->setSize($GLOBALS['xoopsModuleConfig']['graph_fontSize']);
		$Font->setColor($GLOBALS['xoopsModuleConfig']['graph_fontColor']);

		$Graph->setFont($Font);

		$Graph->setBackgroundColor($GLOBALS['xoopsModuleConfig']['graph_backgroudColor']);

		$Graph->add(
			Image_Graph::vertical(
				Image_Graph::vertical(
					Image_Graph::factory('title', array('Variation du classement au cour de la saison', 12)),
					Image_Graph::factory('title', array($saisonAnneeDeb.'-'.$saisonAnneeF, 8)),
					90
				),
				Image_Graph::vertical(
					$plotarea = Image_Graph::factory('plotarea'),
					$legend = Image_Graph::factory('legend'),
					85
				),
				9
			)
		);
		$legend->setPlotarea($plotarea);

		$gridX =& $plotarea->addNew('line_grid', null, IMAGE_GRAPH_AXIS_X);
		$gridX->setLineColor('gray@0.1');

		$gridY =& $plotarea->addNew('line_grid', null, IMAGE_GRAPH_AXIS_Y);
		$gridY->setLineColor('gray@0.1');

		$plotSimple =& $plotarea->addNew('smooth_line', array(&$datasetSimple));
		$plotSimple->setLineColor($GLOBALS['xoopsModuleConfig']['graph_simpleLineColor']);
		$plotSimple->setTitle('Simple');

		$plotDouble =& $plotarea->addNew('smooth_line', array(&$datasetDouble));
		$plotDouble->setLineColor($GLOBALS['xoopsModuleConfig']['graph_doubleLineColor']);
		$plotDouble->setTitle('Double');

		$plotMixte =& $plotarea->addNew('smooth_line', array(&$datasetMixte));
		$plotMixte->setLineColor($GLOBALS['xoopsModuleConfig']['graph_mixteLineColor']);
		$plotMixte->setTitle('Mixte');

		$axisX =& $plotarea->getAxis(IMAGE_GRAPH_AXIS_X);
		$axisX->setLabelInterval(2);
		$axisX->setLabelOption('font', array('size'=>7));

		$axisY =& $plotarea->getAxis(IMAGE_GRAPH_AXIS_Y);
		$axisY->setLabelInterval(1);
		$axisY->setDataPreprocessor(Image_Graph::factory('Image_Graph_DataPreprocessor_Array',array($class)));
		$axisY->showLabel(IMAGE_GRAPH_LABEL_ZERO);
		$axisY->setTitle('Classement', 'vertical');

		// output the Graph
		$nomFichier = XOOPS_ROOT_PATH.'/modules/club/cache/classement-saison-'.$saisonAnneeDeb.'-'.$saisonAnneeF.'-'.$membre->getVar('membre_licence').'.png';
		$Graph->done(array('filename' => $nomFichier));

	}

	function graphClassementSuiviJoueur($membre, $saisonAnneeDeb, $saisonAnneeF) {

		require_once 'pear/Image/Graph.php';

		$classmembreHandler = xoops_getmodulehandler('resultmembre', 'club');

		// Récupération des données
		$classMembreDatas = $classmembreHandler->getResultmembre($membre, $saisonAnneeDeb, $saisonAnneeF);

		$class = array('NC','D4','D3','D2','D1','C4','C3','C2','C1','B4','B3','B2','B1','A4','A3','A2','A1','T50','T20','T10','T5');
		$classReverse = array('NC'=>0,'D4'=>1,'D3'=>2,'D2'=>3,'D1'=>4,'C4'=>5,'C3'=>6,'C2'=>7,'C1'=>8,'B4'=>9,'B3'=>10,'B2'=>11,'B1'=>12,'A4'=>13,'A3'=>14,'A2'=>15,'A1'=>16,'T50'=>17,'T20'=>18,'T10'=>19,'T5'=>20,'null'=>null);
		$nomMois = array('01'=>'Jan','02'=>'Fev','03'=>'Mar','04'=>'Avr','05'=>'Mai','06'=>'Jun','07'=>'Jul','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');

		// Création des dataset pour le graph
		$datasetSimple =& Image_Graph::factory('dataset');
		$datasetDouble =& Image_Graph::factory('dataset');
		$datasetMixte =& Image_Graph::factory('dataset');

		for($i=$saisonAnneeDeb;$i<=$saisonAnneeF;$i++) {

			foreach($nomMois as $numM=>$nomM) {
				if(($i == $saisonAnneeDeb && $numM < 9) || ($i == $saisonAnneeF && $numM > 7))
					continue;

				if(!isset($classMembreDatas[$i][$numM])) {
					$classMembreDatas[$i][$numM]['resultmembre_class_simple'] = 'null';
					$classMembreDatas[$i][$numM]['resultmembre_class_double'] = 'null';
					$classMembreDatas[$i][$numM]['resultmembre_class_mixte'] = 'null';
				}
				$datasetSimple->addPoint("\n$nomM\n$i", $classReverse[$classMembreDatas[$i][$numM]['resultmembre_class_simple']]);
				$datasetDouble->addPoint("\n$nomM\n$i", $classReverse[$classMembreDatas[$i][$numM]['resultmembre_class_double']]);
				$datasetMixte->addPoint("\n$nomM\n$i", $classReverse[$classMembreDatas[$i][$numM]['resultmembre_class_mixte']]);
			}

		}

		$Graph =& Image_Graph::factory('graph', array(600, 400));

		$Font =& $Graph->addNew('font', XOOPS_ROOT_PATH.'/modules/club/include/verdana.ttf');
		$Font->setSize($GLOBALS['xoopsModuleConfig']['graph_fontSize']);
		$Font->setColor($GLOBALS['xoopsModuleConfig']['graph_fontColor']);

		$Graph->setFont($Font);

		$Graph->setBackgroundColor($GLOBALS['xoopsModuleConfig']['graph_backgroudColor']);

		$Graph->add(
			Image_Graph::vertical(
				Image_Graph::vertical(
					Image_Graph::factory('title', array('Variation du classement', 12)),
					Image_Graph::factory('title', array($saisonAnneeDeb.'-'.$saisonAnneeF, 8)),
					90
				),
				Image_Graph::vertical(
					$plotarea = Image_Graph::factory('plotarea'),
					$legend = Image_Graph::factory('legend'),
					85
				),
				9
			)
		);
		$legend->setPlotarea($plotarea);

		$gridX =& $plotarea->addNew('line_grid', null, IMAGE_GRAPH_AXIS_X);
		$gridX->setLineColor('gray@0.1');

		$gridY =& $plotarea->addNew('line_grid', null, IMAGE_GRAPH_AXIS_Y);
		$gridY->setLineColor('gray@0.1');

		$plotSimple =& $plotarea->addNew('smooth_line', array(&$datasetSimple));
		$plotSimple->setLineColor($GLOBALS['xoopsModuleConfig']['graph_simpleLineColor']);
		$plotSimple->setTitle('Simple');

		$plotDouble =& $plotarea->addNew('smooth_line', array(&$datasetDouble));
		$plotDouble->setLineColor($GLOBALS['xoopsModuleConfig']['graph_doubleLineColor']);
		$plotDouble->setTitle('Double');

		$plotMixte =& $plotarea->addNew('smooth_line', array(&$datasetMixte));
		$plotMixte->setLineColor($GLOBALS['xoopsModuleConfig']['graph_mixteLineColor']);
		$plotMixte->setTitle('Mixte');

		$axisX =& $plotarea->getAxis(IMAGE_GRAPH_AXIS_X);
		$axisX->setLabelInterval(3);
		$axisX->setLabelOption('font', array('size'=>7));

		$axisY =& $plotarea->getAxis(IMAGE_GRAPH_AXIS_Y);
		$axisY->setLabelInterval(1);
		$axisY->setDataPreprocessor(Image_Graph::factory('Image_Graph_DataPreprocessor_Array',array($class)));
		$axisY->showLabel(IMAGE_GRAPH_LABEL_ZERO);
		$axisY->setTitle('Classement', 'vertical');

		// output the Graph
		$nomFichier = XOOPS_ROOT_PATH.'/modules/club/cache/classement-'.$membre->getVar('membre_licence').'.png';
		$Graph->done(array('filename' => $nomFichier));

	}

	function graphMoyenneSaisonJoueur(&$membre, $moyMembreDatas, $saisonAnneeDeb, $saisonAnneeF) {

		require_once 'pear/Image/Graph.php';

		// Initialisation des données pour la création du graph
		$graphData = array(
		'simple'=>array(
		'09'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'10'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'11'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'12'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'01'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'02'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'03'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'04'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'05'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'06'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'07'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null)
		),
		'double'=>array(
		'09'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'10'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'11'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'12'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'01'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'02'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'03'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'04'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'05'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'06'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'07'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null)
		),
		'mixte'=>array(
		'09'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'10'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'11'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'12'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'01'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'02'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'03'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'04'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'05'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'06'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null),
		'07'=>array('01'=>null,'07'=>null,'15'=>null,'23'=>null)
		)
		);

		$nomMois = array('09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec','01'=>'Jan','02'=>'Fev','03'=>'Mar','04'=>'Avr','05'=>'Mai','06'=>'Jun','07'=>'Jul');
		$jours = array('01','07','15','23');

		foreach($moyMembreDatas as $moyMembreData) {
			$date = explode('-', $moyMembreData['resultmembre_date']);

			$graphData['simple'][$date[1]][$date[2]] = $moyMembreData['resultmembre_moy_simple'];
			$graphData['double'][$date[1]][$date[2]] = $moyMembreData['resultmembre_moy_double'];
			$graphData['mixte'][$date[1]][$date[2]] = $moyMembreData['resultmembre_moy_mixte'];
		}

		// Création des dataset pour le graph
		$datasetSimple =& Image_Graph::factory('dataset');
		$datasetDouble =& Image_Graph::factory('dataset');
		$datasetMixte =& Image_Graph::factory('dataset');

		foreach($nomMois as $numM=>$nomM) {
			foreach($jours as $jour) {
				if($numM == '07' && ($jour == '07' || $jour == '15' || $jour == '23'))
					continue;

				$datasetSimple->addPoint("\n$jour\n$nomM", $graphData['simple'][$numM][$jour]);
				$datasetDouble->addPoint("\n$jour\n$nomM", $graphData['double'][$numM][$jour]);
				$datasetMixte->addPoint("\n$jour\n$nomM", $graphData['mixte'][$numM][$jour]);
			}
		}

		$Graph =& Image_Graph::factory('graph', array(600, 400));

		$Font =& $Graph->addNew('font', XOOPS_ROOT_PATH.'/modules/club/include/verdana.ttf');
		$Font->setSize($GLOBALS['xoopsModuleConfig']['graph_fontSize']);
		$Font->setColor($GLOBALS['xoopsModuleConfig']['graph_fontColor']);

		$Graph->setFont($Font);

		$Graph->setBackgroundColor($GLOBALS['xoopsModuleConfig']['graph_backgroudColor']);

		$Graph->add(
			Image_Graph::vertical(
				Image_Graph::vertical(
					Image_Graph::factory('title', array('Variation de la moyenne au cour de la saison', 12)),
					Image_Graph::factory('title', array('Saison '.$saisonAnneeDeb.'-'.$saisonAnneeF, 8)),
					90
				),
				Image_Graph::vertical(
					$plotarea = Image_Graph::factory('plotarea'),
					$legend = Image_Graph::factory('legend'),
					85
				),
				9
			)
		);
		$legend->setPlotarea($plotarea);

		$gridX =& $plotarea->addNew('line_grid', null, IMAGE_GRAPH_AXIS_X);
		$gridX->setLineColor('gray@0.1');

		$gridY =& $plotarea->addNew('line_grid', null, IMAGE_GRAPH_AXIS_Y);
		$gridY->setLineColor('gray@0.1');

		$plotSimple =& $plotarea->addNew('smooth_line', array(&$datasetSimple));
		$plotSimple->setLineColor($GLOBALS['xoopsModuleConfig']['graph_simpleLineColor']);
		$plotSimple->setTitle('Simple');

		$plotDouble =& $plotarea->addNew('smooth_line', array(&$datasetDouble));
		$plotDouble->setLineColor($GLOBALS['xoopsModuleConfig']['graph_doubleLineColor']);
		$plotDouble->setTitle('Double');

		$plotMixte =& $plotarea->addNew('smooth_line', array(&$datasetMixte));
		$plotMixte->setLineColor($GLOBALS['xoopsModuleConfig']['graph_mixteLineColor']);
		$plotMixte->setTitle('Mixte');

		$axisX =& $plotarea->getAxis(IMAGE_GRAPH_AXIS_X);
		$axisX->setAxisIntersection('min');
		$axisX->setLabelInterval(2);
		$axisX->setLabelOption('font', array('size'=>7));

		$axisY =& $plotarea->getAxis(IMAGE_GRAPH_AXIS_Y);
		$axisY->showLabel(IMAGE_GRAPH_LABEL_ZERO);
		$axisY->setTitle('CPPP', 'vertical');

		// output the Graph
		$nomFichier = XOOPS_ROOT_PATH.'/modules/club/cache/moyenne-saison-'.$saisonAnneeDeb.'-'.$saisonAnneeF.'-'.$membre->getVar('membre_licence').'.png';
		$Graph->done(array('filename' => $nomFichier));

	}

	function graphMoyenneSuiviJoueur(&$membre, $moyMembreDatas, $saisonAnneeDeb, $saisonAnneeF) {

		require_once 'pear/Image/Graph.php';

		$nomMois = array('01'=>'Jan','02'=>'Fev','03'=>'Mar','04'=>'Avr','05'=>'Mai','06'=>'Jun','07'=>'Jul','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');

		// Création des dataset pour le graph
		$datasetSimple =& Image_Graph::factory('dataset');
		$datasetDouble =& Image_Graph::factory('dataset');
		$datasetMixte =& Image_Graph::factory('dataset');

		for($i=$saisonAnneeDeb;$i<=$saisonAnneeF;$i++) {

			foreach($nomMois as $numM=>$nomM) {
				if(($i == $saisonAnneeDeb && $numM < 9) || ($i == $saisonAnneeF && $numM > 7))
					continue;

				if(!isset($moyMembreDatas[$i][$numM])) {
					$moyMembreDatas[$i][$numM]['resultmembre_moy_simple'] = null;
					$moyMembreDatas[$i][$numM]['resultmembre_moy_double'] = null;
					$moyMembreDatas[$i][$numM]['resultmembre_moy_mixte'] = null;
				}
				$datasetSimple->addPoint("\n$nomM\n$i", $moyMembreDatas[$i][$numM]['resultmembre_moy_simple']);
				$datasetDouble->addPoint("\n$nomM\n$i", $moyMembreDatas[$i][$numM]['resultmembre_moy_double']);
				$datasetMixte->addPoint("\n$nomM\n$i", $moyMembreDatas[$i][$numM]['resultmembre_moy_mixte']);
			}

		}

		$Graph =& Image_Graph::factory('graph', array(600, 400));

		$Font =& $Graph->addNew('font', XOOPS_ROOT_PATH.'/modules/club/include/verdana.ttf');
		$Font->setSize($GLOBALS['xoopsModuleConfig']['graph_fontSize']);
		$Font->setColor($GLOBALS['xoopsModuleConfig']['graph_fontColor']);

		$Graph->setFont($Font);

		$Graph->setBackgroundColor($GLOBALS['xoopsModuleConfig']['graph_backgroudColor']);

		$Graph->add(
			Image_Graph::vertical(
				Image_Graph::vertical(
					Image_Graph::factory('title', array('Variation de la moyenne', 12)),
					Image_Graph::factory('title', array('Saison '.$saisonAnneeDeb.'-'.$saisonAnneeF, 8)),
					90
				),
				Image_Graph::vertical(
					$plotarea = Image_Graph::factory('plotarea'),
					$legend = Image_Graph::factory('legend'),
					85
				),
				9
			)
		);
		$legend->setPlotarea($plotarea);

		$gridX =& $plotarea->addNew('line_grid', null, IMAGE_GRAPH_AXIS_X);
		$gridX->setLineColor('gray@0.1');

		$gridY =& $plotarea->addNew('line_grid', null, IMAGE_GRAPH_AXIS_Y);
		$gridY->setLineColor('gray@0.1');

		$plotSimple =& $plotarea->addNew('smooth_line', array(&$datasetSimple));
		$plotSimple->setLineColor($GLOBALS['xoopsModuleConfig']['graph_simpleLineColor']);
		$plotSimple->setTitle('Simple');

		$plotDouble =& $plotarea->addNew('smooth_line', array(&$datasetDouble));
		$plotDouble->setLineColor($GLOBALS['xoopsModuleConfig']['graph_doubleLineColor']);
		$plotDouble->setTitle('Double');

		$plotMixte =& $plotarea->addNew('smooth_line', array(&$datasetMixte));
		$plotMixte->setLineColor($GLOBALS['xoopsModuleConfig']['graph_mixteLineColor']);
		$plotMixte->setTitle('Mixte');

		$axisX =& $plotarea->getAxis(IMAGE_GRAPH_AXIS_X);
		$axisX->setAxisIntersection('min');
		$axisX->setLabelInterval(3);
		$axisX->setLabelOption('font', array('size'=>7));

		$axisY =& $plotarea->getAxis(IMAGE_GRAPH_AXIS_Y);
		$axisY->showLabel(IMAGE_GRAPH_LABEL_ZERO);
		$axisY->setTitle('CPPP', 'vertical');

		// output the Graph
		$nomFichier = XOOPS_ROOT_PATH.'/modules/club/cache/moyenne-'.$membre->getVar('membre_licence').'.png';
		$Graph->done(array('filename' => $nomFichier));

	}

}

?>
