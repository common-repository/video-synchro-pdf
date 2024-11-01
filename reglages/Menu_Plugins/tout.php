<?php
if ($_GET['p']<=NULL) {
	include(REPERTOIRE_VIDEOSYNCPDF.'reglages/Menu_Plugins/index.php');
}else{
	include(REPERTOIRE_VIDEOSYNCPDF.'reglages/Menu_Plugins/'.$_GET['p'].'.php');
}