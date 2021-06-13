<?php wp_enqueue_script(SUBSACT_NAME); ?>

<!-- Uppdate inputs -->
<?php activations_update_lic_activations_inputs(); ?>

<div class="tab_btns">
    <button style="background-color:#fff" class="button-tab first" onclick="openCity('first')">Lic Activations</button>
    <button class="button-tab second" onclick="openCity('second')">Settings</button>
    <button class="button-tab third" onclick="openCity('third')">Colors</button>
</div>

<!-- First elements -->
<div id="first" class="tabs">
    <div id="licactivations">
        <form action="" method="post" id="lic_activitions">

            <table id="licactivations_tbl" class="widefat">
                <thead>
                    <th><strong>Product ID</strong></th>
                    <th><strong>#Of License</strong></th>
                    <th><strong>Product Code</strong></th>
                    <th><strong>Comment</strong></th>
                </thead>
                <tbody>
                    <?php
                        for($i = 1; $i <= 4;$i++){
                            ?>
                            <tr data-id="<?php echo $i; ?>">
                                <td><input type="number" name="product_id_<?php echo $i; ?>" value="<?php echo get_option('product_id_'.$i); ?>"></td>
                                <td><input type="number" name="license_<?php echo $i; ?>" value="<?php echo get_option('license_'.$i); ?>"></td>
                                <td><input type="text" name="product_code_<?php echo $i; ?>" value="<?php echo get_option('product_code_'.$i); ?>"></td>
                                <td><input type="text" name="comment_<?php echo $i; ?>" value="<?php echo get_option('comment_'.$i); ?>"></td>
                            </tr>
                            <?php
                        }
                    ?>
                    <tr><td><?php submit_button( 'Save', 'submit', 'inputs_val' );?></td></tr>
                </tbody>
            </table>
        </form>
    </div>
</div>

<!-- Second elements -->
<div id="second" class="tabs" style="display:none">
<?php
echo '<form action="options.php" method="post" id="subsactivations_url">';
echo '<table class="form-table">';

settings_fields( 'subsactivations_settings_section' );
do_settings_fields( 'subsactivations_settings_page', 'subsactivations_settings_section' );

echo '</table>';
submit_button('Save');
echo '</form>';
?>
</div>
<!-- Third Elements -->
<div id="third" class="w3-container tabs" style="display:none">
<?php
echo '<form action="options.php" method="post" id="activations_colors">';
echo '<table class="form-table">';

settings_fields( 'activations_colors_section' );
do_settings_fields( 'activations_colors', 'activations_colors_section' );

echo '</table>';
submit_button();
echo '<button id="rest_color">Reset</button>';
echo '</form>';
?>
</div>

<script>
function openCity(elem) {
    var g;
    var x = document.getElementsByClassName('button-tab');
    for (g = 0; g < x.length; g++) {
        x[g].style.backgroundColor = "transparent"; 
    }
    document.getElementsByClassName(elem)[0].style.backgroundColor = "#fff";

    var i;
    var x = document.getElementsByClassName("tabs");
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";  
    }
    document.getElementById(elem).style.display = "block";  
}
</script>