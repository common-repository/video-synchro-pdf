<?php

/*
Plugin Name: Videos sync PDF
Plugin URI: https://www.a-j-evolution.com/plugins-wordpress/video-synchro-pdf/
Description: Synchronize an HTML5 video with a simple PDF file !
Author: JANSSENS Arthur
Version: 1.7.4
Requires at least: 3.1
Tested up to: 4.5
Author URI: https://www.a-j-evolution.com/
Text Domain: videosynchropdf
Domain Path: /langues/
*/


define( 'REPERTOIRE_VIDEOSYNCPDF', plugin_dir_path( __FILE__ ) );
define( 'URL_VIDEOSYNCPDF', plugin_dir_url( __FILE__ ) );

class AJE_VideosSyncPDF
{
    public function __construct()
    {
		register_activation_hook(__FILE__, array($this, 'install'));//Se lance à l'activation du plugin
		register_deactivation_hook(__FILE__, array($this, 'uninstall'));//Se lance à la désactivation du plugin

		add_action('admin_menu', array($this, 'videosyncropdf_menuadmin'));//Ajout d'un menu dédié

		add_action( 'plugins_loaded', array($this, 'myplugin_load_textdomain'));//Ajout de la traduction

        include_once REPERTOIRE_VIDEOSYNCPDF.'shortcode.php';
        new AJE_VideosSyncPDFShortcode();
    }

    public function myplugin_load_textdomain() {
	  load_plugin_textdomain( 'videosynchropdf', false, plugin_basename( dirname( __FILE__ ) ) . '/langues' ); 
	}

	public static function install()
	{
		global $wpdb;//Prefixe des tables Wordpress

	    $wpdb->query("CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}aje_videosyncropdf_videos` (
	  	  `id` int(5) NOT NULL AUTO_INCREMENT,
		  `code` text NOT NULL,
		  `nom` varchar(255) NOT NULL,
		  `mp4` text NOT NULL,
		  `webm` text NOT NULL,
		  `ogg` text NOT NULL,
		  `pdf` text NOT NULL,
		  `timers` mediumtext NOT NULL,
		  UNIQUE KEY `id` (`id`)
		);");

		$wpdb->query("INSERT INTO `{$wpdb->prefix}aje_videosyncropdf_videos` (`code`, `nom`, `mp4`, `webm`, `ogg`, `pdf`, `timers`) VALUES ('test1test', '".__( 'Video example', 'videosynchropdf' )."', '".URL_VIDEOSYNCPDF."exemples/video.mp4', '".URL_VIDEOSYNCPDF."exemples/video.webm', '', '".URL_VIDEOSYNCPDF."exemples/exemple.pdf', '00:00:05->2,
00:00:10->3,
00:00:15->4,
00:00:20->5');");//bien marqué le retour à la ligne sans indentation

	}

	public static function uninstall()
	{
		global $wpdb;
	    //$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}aje_videosyncropdf_videos;");
	}

    public function videosyncropdf_menuadmin()
	{	
		include( REPERTOIRE_VIDEOSYNCPDF.'icone.php' );
		//Menu simple
		//paramettre: Titre, Nom affiché dans le menu de WordPress, option, url de la page(?page=<nom>), le contenu de la page menu.
    	add_menu_page('Vidéos synchro PDF', 'Vidéos synchro PDF', 'manage_options', 'aje_videosyncropdf_videos', array($this, 'page_index'), $icone);
    	//Sous-menu (PS: autant que necessaire)
    	//add_submenu_page('aje_espaceclient', 'Ajouter des options', 'Ajouter options', 'manage_options', 'gestionParams-aje_espaceclient', array($this, 'menu_html_params'));
    	
	}

	public function page_index()
	{
		include(REPERTOIRE_VIDEOSYNCPDF.'reglages/Menu_Plugins/tout.php');
	}

}

new AJE_VideosSyncPDF();