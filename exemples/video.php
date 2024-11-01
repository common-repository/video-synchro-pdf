<?php
echo "<video class='videoPlayer' id='videoPlayer".$video->code."' ontimeupdate='update".$video->code."(this)' controls style='width:$largeur;height:$hauteur;'>
  <source src='".$video->mp4."' type='video/mp4'>
  <source src='".$video->webm."' type='video/webm'>
  <source src='".$video->ogg."' type='video/ogg'>
</video>
<div id='divPlayer".$video->code."' style='display:none;'>
  <div id='progressBarControl".$video->code."'>
    <div id='progressBar".$video->code."' onclick='clickProgress".$video->code."('videoPlayer".$video->code."', this, event)' style='width: 0%;'>0%</div>
  </div>
  <span id='progressTime".$video->code."'>0:00</span>
  <button class='control' onclick='play".$video->code."('videoPlayer".$video->code."', this)'>Play</button>
  <button class='control' onclick='resume".$video->code."('videoPlayer".$video->code."')'>Stop</button>
</div>";

echo "<script type='text/javascript'>
var pageActuelle =0;
function changerPage".$video->code."(page) {
  //document.getElementById('frame".$video->code."').setAttribute('src','".URL_VIDEOSYNCPDF."exemples/rien.php?page='+page+'&url=".URL_VIDEOSYNCPDF."&file=".$video->pdf."');
  document.getElementById('frame".$video->code."').setAttribute('src','".URL_VIDEOSYNCPDF."exemples/pdfjs/web/viewer.html?file=".$video->pdf."#page='+page);
}";

echo "function play".$video->code."(idPlayer, control) {
  var player = document.querySelector('#' + idPlayer);
  if (player.paused) {
    player.play".$video->code."();
    control.textContent = 'Pause';
  } else {
    player.pause();
    control.textContent = 'Play';
  }
}";

echo "function resume".$video->code."(idPlayer) {
  var player = document.querySelector('#' + idPlayer);
  player.currentTime = 0;
  player.pause();
}
function volume".$video->code."(idPlayer, vol) {
  var player = document.querySelector('#' + idPlayer);
  player.volume = vol;
}";

echo "function update".$video->code."(player) {
  var duration = player.duration;    // Durée totale
  var time     = player.currentTime; // Temps écoulé
  var fraction = time / duration;
  var percent  = Math.ceil(fraction * 100);
  var progress = document.querySelector('#progressBar".$video->code."');
  progress.style.width = percent + '%';
  progress.textContent = percent + '%';
  changePageAvecTime".$video->code."(time);
  document.querySelector('#progressTime".$video->code."').textContent = formatTime".$video->code."(time);
}";

echo "function formatTime".$video->code."(time) {
  var hours = Math.floor(time / 3600);
  var mins  = Math.floor((time % 3600) / 60);
  var secs  = Math.floor(time % 60);
  if (secs < 10) {
      secs = '0' + secs;
  }
  if (hours) {
      if (mins < 10) {
          mins = '0' + mins;
      }
      return hours + ':' + mins + ':' + secs; // hh:mm:ss
  } else {
      return mins + ':' + secs; // mm:ss
  }
}";

echo "function changePageAvecTime".$video->code."(time) {";
  $timers = array_reverse( explode(",", str_replace('<br />'."\r\n", "", $video->timers) ) );
  $i = 0;
  $iMax = count($timers);
  $heure_precedente=0; $minute_precedente=0; $seconde_precedente=0;
  foreach ($timers as $timer) {
    $pages = explode("->", $timer);
    $page = $pages[1];
    $temps = explode(":", $pages[0]);
    $temps_en_secondes = $temps[0]*3600 + $temps[1]*60 + $temps[2];
    if( $i==0 ){
      echo "if(time>=$temps_en_secondes){
          changerPage".$video->code."($page);
          pageActuelle=$page;
      }";
    }elseif( $i<($iMax-1) ){
      echo "if(time>=$temps_en_secondes && time<$temps_en_secondes_precedent){
          changerPage".$video->code."($page);
          pageActuelle=$page;
      }";
    }elseif( $i==($iMax-1) ){
      echo "if(time>=$temps_en_secondes && time<$temps_en_secondes_precedent){
          changerPage".$video->code."($page);
          pageActuelle=$page;
      }
      if(time>0 && time<$temps_en_secondes){
          changerPage".$video->code."(1);
          pageActuelle=1;
      }";
    }
    $temps_en_secondes_precedent = $temps_en_secondes;
    $i++;
  }
echo "}";

echo "function clickProgress".$video->code."(idPlayer, control, event) {
  var parent = getPosition".$video->code."(control);    // La position absolue de la progressBar
  var target = getMousePosition".$video->code."(event); // L'endroit du la progressBar où on a cliqué
  var player = document.querySelector('#' + idPlayer);
  var x = target.x - parent.x;
  var y = target.y - parent.y;
  var wrapperWidth = document.querySelector('#progressBarControl".$video->code."').offsetWidth;
  var percent  = Math.ceil((x / wrapperWidth) * 100);
  var duration = player.duration;
  player.currentTime = (duration * percent) / 100;
}";

echo "function getMousePosition".$video->code."(event) {
  return {
      x: event.pageX,
      y: event.pageY
  };
}
function getPosition".$video->code."(element){
  var top = 0, left = 0;
  do {
      top  += element.offsetTop;
      left += element.offsetLeft;
  } while (element = element.offsetParent);
  return { x: left, y: top };
}
</script>";