<?php
echo "
<iframe class='framepdf' id='frame".$video->code."' src='".URL_VIDEOSYNCPDF."exemples/pdfjs/web/viewer.html?file=".$video->pdf."#page=1' frameborder='0' width='$largeur' height='$hauteur' style='width:$largeur;height:$hauteur;'></iframe>
";