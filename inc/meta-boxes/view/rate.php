<label for="page_rate"><?= __( 'Select Page Rate', 'dart-theme' ); ?>
    <select name="page_rate" id="page_rate">
        <option value="-1" <?php if($value==-1) echo "selected='selected'"; ?>>---</option>
        <?php for( $i = 1; $i < 6; $i++ ) : ?>
            <option value="<?= $i; ?>" <?php if($value==$i) echo "selected='selected'"; ?>><?= $i; ?></option>
        <?php endfor; ?>
    </select>
</label>