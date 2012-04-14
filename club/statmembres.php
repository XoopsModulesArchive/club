<?php
// $Id$
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

require '../../mainfile.php';

$GLOBALS['xoopsOption']['template_main'] = 'club_statmembres.html';
include XOOPS_ROOT_PATH.'/header.php';

$statmembreSimpleHandler = xoops_getmodulehandler('statmembresSimple', 'club');
$statmembreDoubleHandler = xoops_getmodulehandler('statmembresDouble', 'club');
$statmembreMixteHandler = xoops_getmodulehandler('statmembresMixte', 'club');

$statmembreSimple = $statmembreSimpleHandler->getObjects(null,false,false);
$statmembreDouble = $statmembreDoubleHandler->getObjects(null,false,false);
$statmembreMixte = $statmembreMixteHandler->getObjects(null,false,false);

$nbMembres = 0;
$totalCat = array(
				'statmembres_poussin'=>0,
				'statmembres_benjamin'=>0,
				'statmembres_minime'=>0,
				'statmembres_cadet'=>0,
				'statmembres_junior'=>0,
				'statmembres_senior'=>0,
				'statmembres_veteran'=>0
			);

for($i=0;$i<21;$i++) {
	foreach($statmembreSimple[$i] as $k=>$v) {
		if($k == 'statmembres_cat')
			continue;
		$totalCat[$k] += $v;
		$nbMembres += $v;
		if(!isset($statmembreSimple[$i]['stat'])) {
			$statmembreSimple[$i]['stat'] = $statmembreSimple[$i][$k];
			$statmembreDouble[$i]['stat'] = $statmembreDouble[$i][$k];
			$statmembreMixte[$i]['stat'] = $statmembreMixte[$i][$k];
		} else {
			$statmembreSimple[$i]['stat'] += $statmembreSimple[$i][$k];
			$statmembreDouble[$i]['stat'] += $statmembreDouble[$i][$k];
			$statmembreMixte[$i]['stat'] += $statmembreMixte[$i][$k];
		}
	}
}

$xoopsTpl->assign('statMembreSimple', $statmembreSimple);
$xoopsTpl->assign('statMembreDouble', $statmembreDouble);
$xoopsTpl->assign('statMembreMixte', $statmembreMixte);
$xoopsTpl->assign('statMembreAge', $totalCat);
$xoopsTpl->assign('nbMembres', $nbMembres);

include(XOOPS_ROOT_PATH."/footer.php");

?>
