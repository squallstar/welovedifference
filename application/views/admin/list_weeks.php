<?php if (isset($next_week)) { ?>
<h3>LA PROSSIMA SETTIMANA:</h3>
<p>
	<?php echo '['.$next_week->week.'] <span style="color:#000;font-weight:bold">'.$next_week->title.'</span> (Termina il '.$next_week->visibility_date.')<br />'.$next_week->description.'<br />'.
	'<a href="'.base_url().'anana/week/'.$next_week->week.'">Gestione foto</a>'; ?>
	<br />
</p>
<hr />
<?php } ?>
<h3>LISTA SETTIMANE:</h3>
<?php
foreach ($weeks as $week) {
	echo '<div class="abox">['.$week->week.'] <strong style="color:#000">'.$week->title.'</strong> ('.
	date_helper($week->visibility_date).')<br /><br />'.
	//$week->description.'<br />'.
	($week->pending > 0 ? '<strong style="color:red">&lt;&lt;&lt; '.$week->pending.' FOTO IN ATTESA DI APPROVAZIONE &gt;&gt;&gt;</strong><br /><br />' : '').
	'<div style="font-size:12px;line-height:13px">'.
	'<a class="abutton" href="'.base_url().'anana/week/'.$week->week.'">Gestione foto</a> - '.
	/*<a onclick="return confirm(\'Sei sicuro di voler eliminare la settimana e tutte le sue foto?? Fai attenzione!!!\');" href="'.base_url().'anana/delete_week/'.$week->id.'">Elimina settimana</a> - '.*/
	($week->is_open == 0 ? '<a class="abutton" onclick="$(this).fadeOut(); return confirm(\'Sei sicuro di voler aprire la settimana?\');" href="'.base_url().'anana/open_week/'.$week->id.'">Apri settimana</a>' : '<strong>Settimana aperta</strong>').' - '.	
	($week->likes_sent == 0 ? '<a class="abutton" onclick="$(this).fadeOut(); return confirm(\'Sei sicuro di voler inviare le mail con i likes a tutti gli utenti???\');" href="'.base_url().'anana/send_likes_mail/'.$week->id.'">Invia mail likes</a>' : 'Mail likes gi&agrave; inviata').' - '.
	($week->published_sent == 0 ? '<a class="abutton" onclick="$(this).fadeOut(); return confirm(\'Sei sicuro di voler inviare le mail informative sul nuovo tema della settimana agli utenti???\');" href="'.base_url().'anana/send_themeweek_mail/'.$week->id.'">Invia mail nuovo tema</a>' : 'Mail del nuovo tema gi&agrave; inviata').' - '.
	($week->winner_sent == 0 ? '<a class="abutton" onclick="$(this).fadeOut(); return confirm(\'Sei sicuro di voler inviare la mail al vincitore?\');" href="'.base_url().'anana/send_winner_mail/'.$week->id.'">Invia mail al vincitore</a>' : 'Mail vincitore gi&agrave; inviata').
	
	'</div></div>';
}
?>


<style type="text/css">
.abutton {
	display: inline-block;
}
.abox {
	display: block;
	margin-bottom: 20px;
	border: 1px solid #DDD;
	background: #EEE;
	padding: 20px;
}
</style>