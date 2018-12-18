<div class="field">
	<label class="field-label"><?php esc_html_e("Laukai yra privalomi:","booked"); ?><i class="required-asterisk booked-icon booked-icon-required"></i></label>
</div>

	<div class="field">
		<input value="" placeholder="<?php esc_html_e('Vardas*','booked'); ?>..." type="text" class="textfield" name="guest_name" />
		<input value="" placeholder="<?php esc_html_e('Pavardė*','booked'); ?>..." type="text" class="textfield" name="guest_surname" />
    </div>

    <div class="field">
       		<input value="" placeholder="<?php esc_html_e('Telefonas*','booked'); ?>..." type="text" class="textfield" name="guest_phone" />
			<input value="" placeholder="<?php esc_html_e('El. paštas*','booked'); ?>..." type="email" class="textfield" name="guest_email" />
    </div>
	<div class="field">
			<textarea rows="4" cols="50" placeholder="<?php esc_html_e('Komentaras','booked'); ?>..." class="textfield" name="guest_comment"></textarea>
	</div>