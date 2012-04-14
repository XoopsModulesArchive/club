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

$GLOBALS['xoopsOption']['template_main'] = 'club_membre_class.html';
include XOOPS_ROOT_PATH.'/header.php';

$membreHandler = xoops_getmodulehandler('membre', 'club');

$categorie = null;
if(isset($_GET['cat'])) {
	$categorie = $_GET['cat'];
}

$membres = $membreHandler->objectToArray($membreHandler->getMembreParClassementDouble($_GET['class'], $categorie));

$xoopsTpl->assign('membres', $membres);
$xoopsTpl->assign('classement', $_GET['class']);
$xoopsTpl->assign('tableau', 'double');
$xoopsTpl->assign('categorie', $categorie);

include(XOOPS_ROOT_PATH."/footer.php");

?>