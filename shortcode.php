<?php

class AJE_VideosSyncPDFShortcode
{

	public static function video( $atts, $content = "" ) {
		$atts = shortcode_atts(array('id' => NULL, 'type' => NULL, 'largeur' => "100%", 'hauteur' => "400px"), $atts);
		global $wpdb;
		$id = $atts['id'];
		$type = $atts['type'];
		$largeur = $atts['largeur'];
		$hauteur = $atts['hauteur'];

		$video = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}aje_videosyncropdf_videos` WHERE `code` LIKE '$id';");
		$video = $video[0];

		ob_start();

		if( $type == "video" ){
			include(REPERTOIRE_VIDEOSYNCPDF."exemples/video.php");
		}

		if( $type == "pdf" ){
			include(REPERTOIRE_VIDEOSYNCPDF."exemples/pdf.php");
		}

		if( $type <= NULL ){
			echo "Le type n'as pas été défini. 'video' ou 'pdf' ?";
		}

		return ob_get_clean();
	}


}
add_shortcode( 'videosyncpdf', array( 'AJE_VideosSyncPDFShortcode', 'video' ) );