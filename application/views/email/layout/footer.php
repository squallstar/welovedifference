<?php

$do_not_receive = $language == 'italian' ?
	'Se non desideri ricevere ulteriori comunicazioni, scrivi una mail con oggetto "CANCELLAZIONE" all\'indirizzo' :
	'If you wish to stop receiving future mailings from us then please write an e-mail<br />with "DELETE" as subject to' ;

		  ?></table><br />
			<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td style="padding:3px 40px">
					<font face="Sans-serif" size="1" color="#000000" style="line-height:13px"><?php echo $do_not_receive; ?> <a href="mailto:<?php echo $this->config->item('website_email'); ?>" style="color:#000000"><?php echo $this->config->item('website_email'); ?></a><br />
					<a href="<?php echo site_url(); ?>" style="color:#000000">WeLoveDifference</a> 2011&copy; - All rights reserved.<br />
					Designed by <a href="http://www.mint-sugar.com" style="color:#000000">MintSugar</a>, coded by <a href="http://www.squallstar.it/" style="color:#000000">Squallstar Studio</a>.
					<br />&nbsp;<br />&nbsp;
					</font>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>