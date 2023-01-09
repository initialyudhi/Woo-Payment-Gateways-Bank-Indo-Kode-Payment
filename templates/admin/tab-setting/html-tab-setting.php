<?php
    $options = get_option('woowib_setting');

?>

<h4>Setting</h4>
<form action="" method="POST">
    
	<table class="form-table">
		<tr>
			<th><label><?php _e('Kode Payment','woowib'); ?></label></th>
			<td> 
                <input name="enable_kode_payment" type="checkbox" id="enable_kode_payment" value="1" <?= ($options['enable_kode_payment']==1)?'checked':''?>>
                <em>If checked, enable payment method automatically adds a payment code (3 digits) to the total order</em>
                 
            </td>
		</tr>
		 
	</table>
	<p class="submit">
		<button class="button button-primary">SAVE</button>
	</p>
	<input type="hidden" name="nonce_setting_woowib_1" value="<?= wp_create_nonce('woowib_setting'); ?>">
</form>