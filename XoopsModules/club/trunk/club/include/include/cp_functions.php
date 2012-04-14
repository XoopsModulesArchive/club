<?php
// $Id$
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
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
define('XOOPS_CPFUNC_LOADED', 1);

function xoops_cp_header()
{
	global $xoopsConfig, $xoopsUser;
	if (!headers_sent()) {
		header('Content-Type:text/html; charset='._CHARSET);
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	}
	echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>";
	echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'._LANGCODE.'" lang="'._LANGCODE.'">
	<head>
	<meta http-equiv="content-type" content="text/html; charset='._CHARSET.'" />
	<meta http-equiv="content-language" content="'._LANGCODE.'" />
	<title>'.htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES).' Administration</title>
	<script type="text/javascript" src="'.XOOPS_URL.'/include/xoops.js"></script>
	';
	echo '<link rel="stylesheet" type="text/css" media="all" href="'.XOOPS_URL.'/xoops.css" />';
        echo '<link rel="stylesheet" type="text/css" media="all" href="'.XOOPS_URL.'/modules/system/style.css" />';
        include_once XOOPS_CACHE_PATH.'/adminmenu.php';
        $moduleperm_handler =& xoops_gethandler('groupperm');
        $admin_mids = $moduleperm_handler->getItemIds('module_admin', $xoopsUser->getGroups());
?>

<script type='text/javascript'>
<!--
var thresholdY = 15; // in pixels; threshold for vertical repositioning of a layer
var ordinata_margin = 20; // to start the layer a bit above the mouse vertical coordinate
// -->
</script>

<script type='text/javascript' src='<?php echo XOOPS_URL."/include/layersmenu.js";?>'></script>

<script type='text/javascript'>
<!--
<?php
		echo $xoops_admin_menu_js;
?>
function moveLayers() {
<?php
		echo $xoops_admin_menu_ml;
?>
}
function shutdown() {
<?php
		echo $xoops_admin_menu_sd;
?>
}
if (NS4) {
document.onmousedown = function() { shutdown(); }
} else {
document.onclick = function() { shutdown(); }
}
// -->
</script>

<?php
        $logo = file_exists(XOOPS_THEME_URL."/".getTheme()."/images/logo.gif") ? XOOPS_THEME_URL."/".getTheme()."/images/logo.gif" : XOOPS_URL."/images/logo.gif";
        echo "</head>
        <body>
        <table border='0' width='100%' cellspacing='0' cellpadding='0'>
          <tr>
            <td bgcolor='#2F5376'><a href='http://xoops.sourceforge.net/' target='_blank'><img src='".XOOPS_URL."/modules/system/images/logo.gif' alt='".htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES)."' /></a></td>
            <td align='right' bgcolor='#2F5376' colspan='2'><img src='".XOOPS_URL."/modules/system/images/xoops2.gif' alt='' /></td>
          </tr>
          <tr>
            <td align='left' colspan='3' class='bg5'>
              <table border='0' width='100%' cellspacing='0' cellpadding='0'>
                <tr>
                  <td width='1%'><img src='".XOOPS_URL."/modules/system/images/hbar_left.gif' width='10' height='23' /></td>
                  <td background='".XOOPS_URL."/modules/system/images/hbar_middle.gif'>&nbsp;<a href='".XOOPS_URL."/admin.php'>"._CPHOME."</a>&nbsp;|&nbsp;<a href='".XOOPS_URL."/admin.php?xoopsorgnews=1'>XOOPS News</a></td>
                  <td background='".XOOPS_URL."/modules/system/images/hbar_middle.gif' align='right'><a href='".XOOPS_URL."/user.php?op=logout'>"._LOGOUT."</a>&nbsp;|&nbsp;<a href='".XOOPS_URL."/'>"._YOURHOME."</a> &nbsp;</td>
                  <td width='1%'><img src='".XOOPS_URL."/modules/system/images/hbar_right.gif' width='10' height='23' /></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <table border='0' cellpadding='0' cellspacing='0' width='100%'>
          <tr>
            <td width='2%' valign='top' class='bg5'  background='".XOOPS_URL."/modules/system/images/bg_menu.gif' align='center'></td>
            <td width='15%' valign='top' class='bg5' align='center'><img src='".XOOPS_URL."/modules/system/images/menu.gif' /><br />
              <table border='0' cellpadding='4' cellspacing='0' width='100%'>";
        foreach ( array_keys($xoops_admin_menu_ft) as $adm ) {
            if ( in_array($adm, $admin_mids) ) {
                echo "<tr><td align='center'>".$xoops_admin_menu_ft[$adm]."</td></tr>";
            }
        }
        echo "
              </table>
              <br />
            </td>
            <td align='left' valign='top' width='82%'>
              <div class='content'><br />\n";
}

function xoops_cp_footer()
{
	global $xoopsConfig, $xoopsLogger;
	echo"
              </div><br />
            </td>
            <td width='1%' background='".XOOPS_URL."/modules/system/images/bg_content.gif'></td>
          </tr>
          <tr>
            <td align='center' colspan='4' class='bg5' height='15'>
              <table border='0' width='100%' cellspacing='0' cellpadding='0'>
                <tr>
                  <td width='1%'><img src='".XOOPS_URL."/modules/system/images/hbar_left.gif' width='10' height='23' /></td>
                  <td width='98%' background='".XOOPS_URL."/modules/system/images/hbar_middle.gif' align='center'><div class='fontSmall'>Powered by&nbsp;".XOOPS_VERSION." &copy; 2001-".date("Y")." <a href='http://xoops.sourceforge.net/' target='_blank'>The XOOPS Project</a></div></td><td width='1%'><img src='".XOOPS_URL."/modules/system/images/hbar_right.gif' width='10' height='23' /></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>";
    include XOOPS_CACHE_PATH.'/adminmenu.php';
	echo $xoops_admin_menu_dv;
	echo "
        </body>
      </html>
    ";
	echo $GLOBALS['xoopsLogger']->render( '' );
	ob_end_flush();
}

// We need these because theme files will not be included
function OpenTable()
{
	echo "<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #2F5376;'><tr class='bg4'><td valign='top'>\n";
}

function CloseTable()
{
	echo '</td></tr></table>';
}

function themecenterposts($title, $content)
{
	echo '<table cellpadding="4" cellspacing="1" width="98%" class="outer"><tr><td class="head">'.$title.'</td></tr><tr><td><br />'.$content.'<br /></td></tr></table>';
}

function myTextForm($url , $value)
{
	return '<form action="'.$url.'" method="post"><input type="submit" value="'.$value.'" /></form>';
}

function xoopsfwrite()
{
	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		return false;
	} else {

    }
	if (!xoops_refcheck()) {
		return false;
	} else {

	}
    return true;
}

function xoops_module_get_admin_menu()
{
    /************************************************************
	* Based on:
	* - PHP Layers Menu 1.0.7(c)2001,2002 Marco Pratesi <pratesi@telug.it>
	* - TreeMenu 1.1 - Bjorge Dijkstra <bjorge@gmx.net>
	************************************************************
	* - php code Optimized by DuGris
	************************************************************/

	$left = 105;
	$top = 135;
	$js = "";
	$moveLayers = "";
	$shutdown = "";
	$firstleveltable = "";
	$menu_layers = "";

	$module_handler =& xoops_gethandler('module');
	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('hasadmin', 1));
	$criteria->add(new Criteria('isactive', 1));
	$criteria->setSort('mid');
	$mods = $module_handler->getObjects($criteria);

	foreach ($mods as $mod) {

      $mid 				= $mod->getVar('mid');
		$module_name	= $mod->getVar('name');
		$module_url		= "\".XOOPS_URL.\"/modules/".$mod->getVar('dirname')."/".trim($mod->getInfo('adminindex'));
		$module_img		= "<img src='\".XOOPS_URL.\"/modules/".$mod->getVar('dirname')."/".$mod->getInfo('image')."' alt='' />";
		$module_desc	= "<b>\"._VERSION.\":</b> ".round($mod->getVar('version')/100 , 2)."<br /><b>\"._DESCRIPTION.\":</b> ".$mod->getInfo('description');

		$top = $top + 15;

		$js .= "\nfunction popUpL" . $mid . "() {\n	shutdown();\n	popUp('L" . $mid . "',true);}";
		$moveLayers .= "\n	setleft('L" . $mid . "'," . $left . ");\n	settop('L" . $mid . "'," . $top . ");";
		$shutdown .= "\n	popUp('L" . $mid . "',false);";
		$firstleveltable .= "$" . "xoops_admin_menu_ft[".$mid."] = \"<a href='" . $module_url . "' title='" . $module_name . "' onmouseover='moveLayerY(\\\"L" . $mid . "\\\", currentY, event) ; popUpL" . $mid . "(); ' />" . $module_img . "</a><br />\";\n";
		$menu_layers .= "\n<div id='L" . $mid . "' style='position: absolute; visibility: hidden; z-index:1000;' >\n<table class='admin_layer' cellpadding='0' cellspacing='0'>\n<tr><th nowrap='nowrap'>" . $module_name . "</th></tr>\n<tr><td class='even' nowrap='nowrap'>";

		$adminmenu = $mod->getAdminMenu();

		if ($mod->getVar('hasnotification') || ($mod->getInfo('config') && is_array($mod->getInfo('config'))) || ($mod->getInfo('comments') && is_array($mod->getInfo('comments')))) {
			$adminmenu[] = array('link' => '".XOOPS_URL."/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod='.$mid, 'title' => _PREFERENCES, 'absolute' => true);
		}
		if ( count($adminmenu) != 0 ) {
			$currenttarget = "";
			foreach ( $adminmenu as $menuitem ) {
				$menu_link		= trim($menuitem['link']);
				$menu_title		= trim($menuitem['title']);
				$menu_target	= isset($menuitem['target']) ? " target='" . trim($menuitem['target']) . "'" : '';
				if (isset($menuitem['absolute']) && $menuitem['absolute']) {
					$menu_link = (empty($menu_link)) ? "#" : $menu_link;
				} else {
					$menu_link = (empty($menu_link)) ? "#" : "\".XOOPS_URL.\"/modules/".$mod->getVar('dirname')."/".$menu_link;
				}

				$menu_layers .= "\n<img src='\".XOOPS_URL.\"/images/pointer.gif' width='8' height='8' alt='' />&nbsp;<a href='" . $menu_link . "'" . $menu_target ." onmouseover='popUpL" . $mid . "' />" .$menu_title. "</a><br />\n";
			}
		}

		$menu_layers .= "\n<div style='margin-top: 5px; font-size: smaller; text-align: right;'><a href='#' onmouseover='shutdown();'>["._CLOSE."]</a></div></td></tr><tr><th style='font-size: smaller; text-align: left;'>" . $module_img . "<br />" . $module_desc . "</th></tr></table></div>\n";
	}

	$menu_layers .= "\n<script language='JavaScript'>\n<!--\nmoveLayers();\nXoLoaded = 1;\n// -->\n</script>\n";

	$content = "<"."?php\n";
	$content .= "\$xoops_admin_menu_js = \"".$js."\n\";\n\n";
	$content .= "\$xoops_admin_menu_ml = \"".$moveLayers."\n\";\n\n";
	$content .= "\$xoops_admin_menu_sd = \"".$shutdown."\n\";\n\n";
	$content .= $firstleveltable . "\n";
	$content .= "\$xoops_admin_menu_dv = \"".$menu_layers."\";\n";
	$content .= "\n?".">";

   return $content;
}

function xoops_module_write_admin_menu($content)
{
    if (!xoopsfwrite()) {
        return false;
    }
    $filename = XOOPS_CACHE_PATH.'/adminmenu.php';
    if ( !$file = fopen($filename, "w") ) {
        echo 'failed open file';
        return false;
    }
    if ( fwrite($file, $content) == -1 ) {
        echo 'failed write file';
        return false;
    }
    fclose($file);

	// write index.html file in cache folder
	// file is delete after clear_cache (smarty)
    xoops_write_index_file( XOOPS_CACHE_PATH );
    return true;
}

function xoops_write_index_file( $path = '') {
    if ( empty($path) ) {
        return false;
    }
    if (!xoopsfwrite()) {
        return false;
    }

    $path = substr($path, -1) == "/" ? substr($path, 0, -1) : $path;
    $filename = $path . '/index.html';
    if ( file_exists($filename) ) {
        return true;
    }
    if ( !$file = fopen($filename, "w") ) {
        echo 'failed open file';
        return false;
    }
    if ( fwrite($file, "<script>history.go(-1);</script>") == -1 ) {
        echo 'failed write file';
        return false;
    }
    fclose($file);
    return true;
}
?>