<?php

$modversion['name'] = "Club";
$modversion['version'] = 1.07;
$modversion['description'] = "Module de gestion de club de badminton";
$modversion['credits'] = "http://www.cregybad.org/";
$modversion['author'] = "Zoullou";
$modversion['help'] = "";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 0;
$modversion['image'] = "images/club_logo.png";
$modversion['onInstall'] = 'include/install_function.php';
$modversion['onUpdate'] = 'include/update_function.php';
$modversion['dirname'] = "club";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Menu
$modversion['hasMain'] = 1;
$modversion['sub'][0]['name'] = 'Test';
$modversion['sub'][0]['url'] = "test.php";
$modversion['sub'][1]['name'] = 'Les adhérents';
$modversion['sub'][1]['url'] = "membres.php";
$modversion['sub'][2]['name'] = 'L\'équipe 1';
$modversion['sub'][2]['url'] = "equipe.php?id=1";
$modversion['sub'][3]['name'] = 'Bureau';
$modversion['sub'][3]['url'] = "bureau.php";
$modversion['sub'][4]['name'] = 'Stat Membres';
$modversion['sub'][4]['url'] = "statmembres.php";
$modversion['sub'][5]['name'] = 'Tournois';
$modversion['sub'][5]['url'] = "tournois.php";

// SQL
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][1] = "club_resultmembre";
$modversion['tables'][2] = "club_fonction";
$modversion['tables'][3] = "club_membre";
$modversion['tables'][4] = "club_statmembres_double";
$modversion['tables'][5] = "club_statmembres_mixte";
$modversion['tables'][6] = "club_statmembres_simple";
$modversion['tables'][7] = "club_club";
$modversion['tables'][8] = "club_equipe";
$modversion['tables'][9] = "club_tournoi";
$modversion['tables'][10] = "club_palmares";
$modversion['tables'][11] = "club_membretournoi";
$modversion['tables'][12] = "club_membresaison";

$i = 0;
// Config items
$modversion['config'][$i]['name'] = 'poona_login';
$modversion['config'][$i]['title'] = 'MI_CLUB_POONA_LOGIN';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'text';
$i++;
$modversion['config'][$i]['name'] = 'poona_pass';
$modversion['config'][$i]['title'] = 'MI_CLUB_POONA_PASS';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'password';
$modversion['config'][$i]['valuetype'] = 'text';
$i++;
$modversion['config'][$i]['name'] = 'poona_req_id';
$modversion['config'][$i]['title'] = 'MI_CLUB_POONA_REQID';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;
$i++;
$modversion['config'][$i]['name'] = 'graph_fontSize';
$modversion['config'][$i]['title'] = 'MI_CLUB_G_FONTSIZE';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['options'] = array('5'=>5,'6'=>6,'7'=>7,'8'=>8,'9'=>9,'10'=>10,'11'=>11,'12'=>12,'13'=>13,'14'=>14,'15'=>15);
$modversion['config'][$i]['default'] = 8;
$i++;
$modversion['config'][$i]['name'] = 'graph_backgroudColor';
$modversion['config'][$i]['title'] = 'MI_CLUB_G_BCOLOR';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'color';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '#FFFFFF';
$i++;
$modversion['config'][$i]['name'] = 'graph_fontColor';
$modversion['config'][$i]['title'] = 'MI_CLUB_G_FCOLOR';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'color';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '#000000';
$i++;
$modversion['config'][$i]['name'] = 'graph_simpleLineColor';
$modversion['config'][$i]['title'] = 'MI_CLUB_G_SLINECOLOR';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'color';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '#0000FF';
$i++;
$modversion['config'][$i]['name'] = 'graph_doubleLineColor';
$modversion['config'][$i]['title'] = 'MI_CLUB_G_DLINECOLOR';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'color';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '#008080';
$i++;
$modversion['config'][$i]['name'] = 'graph_mixteLineColor';
$modversion['config'][$i]['title'] = 'MI_CLUB_G_MLINECOLOR';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'color';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '#FF0000';
$i++;
$modversion['config'][$i]['name'] = 'create_XoUser';
$modversion['config'][$i]['title'] = 'MI_CLUB_CREATE_XOUSER';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 1;
$i++;

// Templates
$modversion['templates'][1]['file'] = 'club_membres.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'club_detail-membre.html';
$modversion['templates'][2]['description'] = '';
$modversion['templates'][3]['file'] = 'club_equipe.html';
$modversion['templates'][3]['description'] = '';
$modversion['templates'][4]['file'] = 'club_bureau.html';
$modversion['templates'][4]['description'] = '';
$modversion['templates'][5]['file'] = 'club_statmembres.html';
$modversion['templates'][5]['description'] = '';
$modversion['templates'][6]['file'] = 'club_membre_class.html';
$modversion['templates'][6]['description'] = '';
$modversion['templates'][7]['file'] = 'club_tournois.html';
$modversion['templates'][7]['description'] = '';
$modversion['templates'][8]['file'] = 'club_detail-tournoi.html';
$modversion['templates'][8]['description'] = '';


// Block
$modversion['blocks'][1]['file'] = "club_blocks.php";
$modversion['blocks'][1]['name'] = "Anniversaires";
$modversion['blocks'][1]['description'] = "Anniversiares du jour";
$modversion['blocks'][1]['show_func'] = "clubAnnivShow";
$modversion['blocks'][1]['options'] = "";
$modversion['blocks'][1]['edit_func'] = "clubAnnivEdit";
$modversion['blocks'][1]['template'] = 'club_block_anniv.html';

$modversion['blocks'][2]['file'] = "club_blocks.php";
$modversion['blocks'][2]['name'] = "Tournois à venir";
$modversion['blocks'][2]['description'] = "Liste des tournois à venir";
$modversion['blocks'][2]['show_func'] = "clubNextTournoiShow";
$modversion['blocks'][2]['options'] = "";
$modversion['blocks'][2]['edit_func'] = "clubNextTournoiEdit";
$modversion['blocks'][2]['template'] = 'club_block_next_tournoi.html';

$modversion['blocks'][3]['file'] = "club_blocks.php";
$modversion['blocks'][3]['name'] = "Derniers résultats de tournoi";
$modversion['blocks'][3]['description'] = "Derniers résultats de tournoi des membres du club";
$modversion['blocks'][3]['show_func'] = "clubPalmaresLastTournoiShow";
$modversion['blocks'][3]['options'] = "";
$modversion['blocks'][3]['edit_func'] = "clubPalmaresLastTournoiEdit";
$modversion['blocks'][3]['template'] = 'club_block_last_result.html';

?>
