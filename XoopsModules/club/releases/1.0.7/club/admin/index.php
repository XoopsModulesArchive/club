<?php

include '../../../include/cp_header.php';
include '../../../class/xoopsformloader.php';
include 'function.php';

function extgalleryLastVersion() {
	return @file_get_contents("http://www.zoullou.net/club.version");
}

function isUpToDate() {
	$version = extgalleryLastVersion();
	return $GLOBALS['xoopsModule']->getVar('version') >= $version;
}

xoops_cp_header();
adminMenu(1);

echo '<fieldset><legend style="font-weight:bold; color:#990000;">Administration du module Club</legend>';
echo '<fieldset><legend style="font-weight:bold; color:#0A3760;">Information de mise � jour</legend>';
if(!extgalleryLastVersion()) {
	echo "<span style=\"color:black; font-weight:bold;\">Erreur dans la v�rification de la derniere version du module</span>";
} else if(!isUpToDate()) {
	echo "<h3 style=\"color:red;\">Votre version du module Club est obsol�te.</h3><br /><form action=\"upgrade.php\" method=\"post\"><input type=\"hidden\" name=\"step\" value=\"download\" /><input class=\"formButton\" value=\"Mise � jour\" type=\"submit\" /></form>";
} else {
	echo "<span style=\"color:green;\">Vous utilisez la derni�re version du module Club avec les derni�res mises � jour de stabilit� et de s�curit�.</span>";
}
echo '</fieldset>';

xoops_cp_footer();

?>
