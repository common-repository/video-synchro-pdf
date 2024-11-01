<?php
global $wpdb;

if ( !empty($_POST['nom']) ) {
	$nom = sanitize_text_field($_POST['nom']);
	$mp4 = sanitize_text_field($_POST['mp4']);
	$webm = sanitize_text_field($_POST['webm']);
	$ogg = sanitize_text_field($_POST['ogg']);
	$pdf = sanitize_text_field($_POST['pdf']);

	$timers = nl2br(trim(stripslashes( sanitize_text_field($_POST['timers']) )));
	$timers = str_replace(", ", ",\r\n<br />", $timers);

	$wpdb->query( $wpdb->prepare( 
    "
      UPDATE `{$wpdb->prefix}aje_videosyncropdf_videos`
      SET `nom` = %s, `mp4` = %s,
      `webm` = %s, `ogg` = %s,
      `pdf` = %s, `timers` = %s
      WHERE `code` LIKE %s;
    ", 
    array($nom, $mp4, $webm, $ogg, $pdf, $timers, $_GET['code']) 
  ));
}

if ( !empty($_GET['code']) ) {

	$video = $wpdb->get_results(
		$wpdb->prepare(
			"
			SELECT * FROM `{$wpdb->prefix}aje_videosyncropdf_videos`
			WHERE `code` LIKE %s
			",
		array($_GET['code'])
	));
	$video = $video[0];
	?>

	<style type="text/css">
		.tg  {border-collapse:collapse;border-spacing:0;}
		.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border:none;overflow:hidden;word-break:normal;}
		.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border:none;border-width:1px;overflow:hidden;word-break:normal;}
		.tg .tg-cxkv{background-color:#ffffff}
		.tg .tg-bsv2{background-color:#efefef}
		.tg .tg-bsv2 span {
			font-size: 11px;
			font-style: italic;
		}
		.tg tr {
			border-bottom: 1px solid #E6E6E6;
		}
		.tg tr input[type="text"] {
			width: 100%;
	    	border: none;
	    	box-shadow: none;
		}

		.tgg  {border-collapse:collapse;border-spacing:0;}
		.tgg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
		.tgg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
		.tgg .tgg-s6z2{text-align:center}
		.tgg .tgg-0onx{background-color:none;color:#000000;border: none;}
		.tgg .tgg-0lge{background-color:#656565;color:#ffffff;text-align:center;border: 1px solid black;}
		.tgg-s6z2 input, .tgg-0onx input{width: 100%;}

		textarea {
			width: 58%;
			height: 200px;
			float: left;
		}
		#zoneverifier {
			float: left;
			height: 200px;
			width: 37%;
			margin-left: 1%;
			overflow: scroll;
		}
		#zoneverifier, textarea {
			font-size: 14px;
			padding: 1%;
		}

	</style>


	<br><br>
	<h2><?php _e( 'Edit Video', 'videosynchropdf' ); ?></h2>
	<p><?php _e( 'Add multiple video formats to be compatible with older web browsers. The MP4 format is supported by all recent browsers, it is most important, others are not required.', 'videosynchropdf' ); ?></p>

	<form action="" method="POST" enctype="multipart/form-data">

	<table class="tg" style="undefined;table-layout: fixed; width: 90%; border:none; ">
	<colgroup>
	<col style="width: 20%">
	<col style="width: 70%">
	</colgroup>
	  <tbody><tr>
	    <th class="tg-bsv2"><?php _e( 'Name', 'videosynchropdf' ); ?> *</th>
	    <th class="tg-cxkv"><input type="text" name="nom" placeholder="<?php _e( 'Indicatif title', 'videosynchropdf' ); ?>" value="<?php echo $video->nom; ?>"></th>
	  </tr>
	  <tr>
	    <td class="tg-bsv2"><?php _e( 'PDF file', 'videosynchropdf' ); ?> *</td>
	    <td class="tg-cxkv"><input type="text" name="pdf" placeholder="http://exemple.com/fichier.pdf" value="<?php echo $video->pdf; ?>"></td>
	  </tr>
	  <tr>
	    <td class="tg-bsv2"><?php _e( 'MP4 video', 'videosynchropdf' ); ?> *</td>
	    <td class="tg-cxkv"><input type="text" name="mp4" placeholder="http://exemple.com/video.mp4" value="<?php echo $video->mp4; ?>"></td>
	  </tr>
	  <tr>
	    <td class="tg-bsv2"><?php _e( 'WebM video', 'videosynchropdf' ); ?></td>
	    <td class="tg-cxkv"><input type="text" name="webm" placeholder="http://exemple.com/video.webm" value="<?php echo $video->webm; ?>"></td>
	  </tr>
	  <tr>
	    <td class="tg-bsv2"><?php _e( 'OGG video', 'videosynchropdf' ); ?></td>
	    <td class="tg-cxkv"><input type="text" name="ogg" placeholder="http://exemple.com/video.ogg" value="<?php echo $video->ogg; ?>"></td>
	  </tr>
	</tbody></table>


	<br><br>
	<h2><?php _e( 'Add timers', 'videosynchropdf' ); ?></h2>
	<p><?php _e( 'A timer is defined like this: "HH:MM:SS->NUMERO_PAGE". Each line must correspond to a single timer, example below delete.', 'videosynchropdf' ); ?></p>

	<script type="text/javascript">
		function isInt(n) {
	   		return n % 1 === 0;
		}

		function verifier () {
			var texte = document.getElementById('timers').value;
			var virgule = texte.split(",");
			var texteverif = "";
			var max, heure, minute, seconde, style, styleH, styleM, styleS;
			var arrPages = [];

			for (i = 0; i < virgule.length; i++) { 
				var	pages =  virgule[i].split("->");
				arrPages.push(pages[1]);

				if(i==0){max=2;}else{max=3;}

				heure = pages[0].substring(0,max);
				minute = pages[0].substring(max+1,max+3);
				seconde = pages[0].substring(max+4,max+7);


				if( !isInt(pages[1]) ){style=" style='color:red'";}
				if( !isInt(heure) ){styleH=" style='color:red'";}
				if( !isInt(minute) ){styleM=" style='color:red'";}
				if( !isInt(seconde) ){styleS=" style='color:red'";}

				texteverif += "<span"+style+"><strong>Page "+pages[1]+"</strong></span> <?php _e( 'to', 'videosynchropdf' );?> <span"+styleH+">"+heure+"H<span> <span"+styleM+">"+minute+"min</span> <?php _e( 'and', 'videosynchropdf' );?> <span"+styleS+">"+seconde+"s</span>.<br>";
				style=0; styleH=0; styleM=0; styleS=0;
				heure=0; minute=0; seconde=0;
				max=0;
			}

			document.getElementById("zoneverifier").innerHTML = texteverif;
		}
	</script>

	<textarea onkeyup="verifier()" onchange="verifier()" id="timers" name="timers"><?php echo str_replace("<br />", "", $video->timers); ?></textarea>
	<div id="zoneverifier"></div>
	<br style="clear:both;">
	<p><?php _e( 'Timers and pages need to be put in order. For example, page 2 can not be placed after the page 3 under penalty of bug.', 'videosynchropdf' ); ?></p>
	<script type="text/javascript">verifier();</script>

	<input type="submit" name="ok" value="<?php _e( 'Edit', 'videosynchropdf' ); ?>">

	</form>

<?php 
}