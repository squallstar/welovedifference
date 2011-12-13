<h1><?php echo $week->title; ?></h1>
<p><?php echo $week->description; ?></p>

<br /><br />
<?php
if (count($week_photos)) { ?>

<table width="98%" cellpadding="0" border="0" class="adm_tbl" id="photo_table">
	<thead>
		<td>Informazioni autore</td>
		<td>Immagine</td>
		<td>Azioni</td>
	</thead>
<?php
	foreach ($week_photos as $photo) { ?>

	<tr id="<?php echo $photo->id; ?>">
		<td>
			<strong>Nome: <?php echo $photo->author_name; ?></strong><br /><br />
			E-mail: <a href="mailto:<?php echo $photo->author_email; ?>" target="_blank"><?php echo $photo->author_email; ?></a><br /><br />
			Www: <a href="http://<?php echo $photo->author_www; ?>" target="_blank"><?php echo $photo->author_www; ?></a><br /><br />
			<i>Titolo: <strong><?php echo $photo->abstract?$photo->abstract:'(Nessuno)'; ?></strong></i><br />
			</td>
		<td align="center"><img width="160px" onclick="$(this).attr('width', '320px');" src="<?php echo base_url().$photo->img_path; ?>" border="0" alt="" /></td>
		
		<td>
			<i>Stato:</i> <strong><?php echo $photo->visible == 1 ? 'Approvata' : '<span style="color:red">Inviata</span>'; ?></strong><br /><br />
			<?php if ($photo->visible != 1) { ?><a href="<?php echo base_url(); ?>anana/week/<?php echo $week->week; ?>/approve/<?php echo $photo->id; ?>">Approva foto</a><br /><br /><?php } ?>
			<a onclick="return confirm('Do you want to delete this photo?');" href="<?php echo base_url(); ?>anana/week/<?php echo $week->week; ?>/delete/<?php echo $photo->id; ?>">Scarta foto</a>
			<br /><br />
			Priorit&agrave;: <strong><?php echo $photo->priority; ?></strong> <a href="<?php echo base_url(); ?>anana/week/<?php echo $week->week; ?>/priority-up/<?php echo $photo->id; ?>">Su</a> <a href="<?php echo base_url(); ?>anana/week/<?php echo $week->week; ?>/priority-down/<?php echo $photo->id; ?>">Gi&ugrave;</a><br /><br />
			Likes: <strong><?php echo $photo->likes_count; ?></strong>
		</td>		
	</tr>
		

<?php
	} ?>
	
</table>
<br /><br />

<style type="text/css">
tr.table_drag_class, tr.table_drag_class td {
	background: #fbfce6;
}
.adm_tbl tr:hover td {
	background: #d1eae3;
}
</style>

<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.tablednd.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    // Initialise the table
    $("#photo_table").tableDnD({
    	    onDragClass: "table_drag_class",
    	    onDrop: function(table, row) {
                var rows = table.tBodies[0].rows;
				var order_rows = '';
                for (var i=0; i<rows.length; i++) {
                    order_rows += "|"+rows[i].id;
                }
                $.post(see.url+'anana/update_photo_priority', { photos : order_rows });
    	    }
    	});
    
});
</script>


<?php
}
?>
