<?php
    $options = get_option('woowib_setting');
	$checkFirstSet = get_option('_first_set_enable_payment');
	$defaultBank = ['bank_bca','bank_bri','bank_bni','bank_mandiri']; 
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
		<tr>
			<th><label><?php _e('Minimum s/d Maximal Range Payment Code ','woowib'); ?></label></th>
			<td> 
				<input type="number" name="min_kode_payment" class="regular-text" id="min_kode_payment" value="<?= (!$options['min_kode_payment'] || $options['min_kode_payment']=='')?'1':$options['min_kode_payment'];?>" min="1"> s/d 
				<input type="number" name="max_kode_payment" class="regular-text" id="max_kode_payment" value="<?= (!$options['max_kode_payment'] || $options['max_kode_payment']=='')?'200':$options['max_kode_payment'];?>" min="1"><br>
				<em>Minimum code payment 1 s/d maximal 1000 default 200</em>
				
            </td>
		</tr>
		<tr>
			<th><label><?php _e('Enable Payment Code For Payment Gateways','woowib'); ?></label></th>
			<td> 
	 			<div class="row">
					<?php $no=1; foreach ($enabled_gateways as $key => $value): ?>
						<?php if ( in_array($value['id'],$defaultBank) ) : ?>
							<div class="column" >
								<input name="enabled_gateways[]" type="checkbox" id="enabled_gateways" value="<?=$value['id'];?>" <?= (in_array($value['id'],$options['enabled_gateways']) || ( !$checkFirstSet && in_array($value['id'],$defaultBank)))?'checked':''?>> <?= $value['title'];?>
							</div>
						<?php $no++; endif;?>
					<?php $no++; endforeach;?>
				</div>
            </td>
		</tr>
	</table>
	<p class="submit">
		<button class="button button-primary">SAVE</button>
	</p>
	<input type="hidden" name="nonce_setting_woowib_1" value="<?= wp_create_nonce('woowib_setting'); ?>">
</form>