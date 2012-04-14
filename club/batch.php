<?php

set_time_limit(600);

// Initialisation pour modifier la valeur de débug
$xoopsOption['nocommon'] = true;
include 'C:\Apps\wamp\www\club\mainfile.php';
$conn = @mysql_pconnect(XOOPS_DB_HOST, XOOPS_DB_USER, XOOPS_DB_PASS);
if(!$conn) {
	die("Unable to connect to database");
}
if (!mysql_select_db(XOOPS_DB_NAME)) {
	die("Unable to select database");
}
$result = mysql_query("UPDATE `xoops_config` SET `conf_value` = '1' WHERE `conf_name` = 'debug_mode' LIMIT 1 ;");
if(!$result) {
	die("Unable to active error reporting");
}

// Inclusion du common
include XOOPS_ROOT_PATH."/include/common.php";
echo "Début du traitement : ".date('l dS \of F Y h:i:s A')."\n\n";

$membreHandler = xoops_getmodulehandler('membre', 'club');

$membreHandler->miseAJourMembresAvecHisto();
//$membreHandler->miseAJourMembres();
//$membreHandler->_buildGraphJoueurs();

$types = array(
	E_USER_NOTICE => 'Notice',
	E_USER_WARNING => 'Warning',
	E_USER_ERROR => 'Error',
	E_NOTICE => 'Notice',
	E_WARNING => 'Warning',
	E_STRICT => 'Strict',
);

foreach ( $xoopsLogger->errors as $error ) {
	echo isset( $types[ $error['errno'] ] ) ? $types[ $error['errno'] ] : 'Unknown';
	echo sprintf( ": %s in file %s line %s\n", $error['errstr'], $error['errfile'], $error['errline'] )."\n";
}

foreach ($xoopsLogger->queries as $q) {
    if (isset($q['error'])) {
        echo htmlentities($q['sql'])."\n\t".$q['errno']."\n\tError message: ".$q['error']."\n";
    } else {
        echo htmlentities($q['sql'])."\n";
    }
}

// Désactivation du mode DEBUG
mysql_query("UPDATE `xoops_config` SET `conf_value` = '0' WHERE `conf_name` = 'debug_mode' LIMIT 1 ;");

echo "\nFin du traitement : ".date('l dS \of F Y h:i:s A')."\n\n";

echo "################################################################################\n\n\n";

?>
