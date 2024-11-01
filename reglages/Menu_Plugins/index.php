<?php
global $wpdb;

if ( $_GET['ajouter'] == "oui" ) {
	$code = wp_generate_password( 8, false );
	
  $wpdb->query( $wpdb->prepare( 
    "
      INSERT INTO `{$wpdb->prefix}aje_videosyncropdf_videos`
      (`code`, `nom`, `mp4`, `webm`, `ogg`, `pdf`, `timers`)
      VALUES (%s, %s, '', '', '', '', %s);
    ", 
    array($code, 'Video '.$code, "00:01:05->2,
00:02:01->3")//bien marqué le retour à la ligne sans indentation
  ));
}

if ( $_GET['suprim_id'] > NULL ) {
	$code = wp_generate_password( 8, false );

  $wpdb->query( $wpdb->prepare( 
    "DELETE FROM `{$wpdb->prefix}aje_videosyncropdf_videos` WHERE `id` = %s;", 
    array($_GET['suprim_id']) 
  ));
}

$videos = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}aje_videosyncropdf_videos ORDER BY `{$wpdb->prefix}aje_videosyncropdf_videos`.`id` ASC");

?>
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;border-color:#ccc;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#ccc;color:#333;background-color:#fff;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#ccc;color:#333;background-color:#f0f0f0;}
.tg .tg-hjma{background-color:#ffffff; text-align: center;}
.tg .tg-hjma-noborder{background-color:#ffffff; border: none;}

.tg-hjma input[type="text"] {
  width: 100%;
  border: none;
  box-shadow: none;
  background-color: #D6D6D6;
  font-family: monospace;
  padding: 6px 5px 6px 5px;
}
</style>

<br><br>
<h2><?php _e( 'Your videos', 'videosynchropdf' ); ?></h2>
<br><br>

<table class="tg" style="undefined;table-layout: fixed; width: 95%;margin-left:2%">
<colgroup>
<col style="width: 15%">
<col style="width: 25%">
<col style="width: 25%">
<col style="width: 10%">
</colgroup>
<tr>
<th class="tg-031e"><?php _e( 'Name', 'videosynchropdf' ); ?></th>
<th class="tg-031e"><?php _e( 'Video shortcode', 'videosynchropdf' ); ?></th>
<th class="tg-031e"><?php _e( 'PDF shortcode', 'videosynchropdf' ); ?></th>
<th class="tg-031e"><?php _e( 'Remove', 'videosynchropdf' ); ?> ?</th>
</tr>
<?php
foreach ($videos as $video) { ?>
  <tr>
    <td class="tg-hjma" title="Modifier"><?php echo '<a href="admin.php?page=aje_videosyncropdf_videos&p=modifVideo&code='.$video->code.'"><strong>'.$video->nom.'</strong></a>'; ?></td>
    <td class="tg-hjma"><input type="text" value='[videosyncpdf type="video" id="<?php echo $video->code; ?>" hauteur="400px" largeur="100%"]' ></td>
    <td class="tg-hjma"><input type="text" value='[videosyncpdf type="pdf" id="<?php echo $video->code; ?>" hauteur="400px" largeur="100%"]' ></td>
    <td class="tg-hjma"><?php echo "<a onclick='confirmation_supprimer(\"$video->id\");' href='javascript:' >".__( 'Remove', 'videosynchropdf' )."</a>"; ?></td>
  </tr>
  <?php
} ?>

</table>

<br><br>
<input type="button" name="ok" value="<?php _e( 'Add a video', 'videosynchropdf' ); ?>" onclick="document.location='?page=aje_videosyncropdf_videos&ajouter=oui'">

<script type="text/javascript">
function confirmation_supprimer(id){
	var r = confirm("<?php _e( 'Do you really delete this video ?', 'videosynchropdf' ); ?>");
	if (r == true) {
	    document.location='?page=aje_videosyncropdf_videos&suprim_id='+id;
	} else {}
}
</script>