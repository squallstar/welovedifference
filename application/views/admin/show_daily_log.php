<span style="font-size:11px"><?php
if (isset($file)) {
	$t = file_get_contents($file);
	echo nl2br($t);
}


?></span>
<br /><br /><br />