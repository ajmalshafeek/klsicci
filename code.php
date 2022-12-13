<?php
$imgResized=imagecreatefrompng("http://localhost/dev/resources/2/myOrg/logo.png");
imagepng($imgResized, "./1630497732.png");
imagedestroy($imgResized);
?>