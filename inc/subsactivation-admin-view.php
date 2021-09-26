<?php wp_enqueue_script(SUBSACT_NAME); ?>

<!-- Uppdate inputs -->
<?php activations_update_lic_activations_inputs(); ?>

<div class="tab_btns">
    <button style="background-color:#fff" class="button-tab first" onclick="tabs_items('first')">Lic Activations</button>
    <button class="button-tab second" onclick="tabs_items('second')">Settings</button>
    <button class="button-tab third" onclick="tabs_items('third')">Colors</button>
    <button class="button-tab four" onclick="tabs_items('four')">Disable Updating</button>
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
                        global $wpdb;
                        for($i = 0; $i < 4;$i++){
                            $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}lic_produce_info");
                            ?>
                            <tr>
                                <td>
                                    <input type="number" name="product_id-<?php echo $i; ?>" value="<?php echo (!empty($results[$i]->product_id)?$results[$i]->product_id:''); ?>">

                                    <input type="hidden" name="id-<?php echo $i; ?>" value="<?php echo (!empty($results[$i]->ID)?$results[$i]->ID:''); ?>">
                                </td>
                                <td>
                                    <input type="number" name="of_license-<?php echo $i; ?>" value="<?php echo (!empty($results[$i]->of_License)?$results[$i]->of_License:''); ?>">

                                    <input type="hidden" name="id-<?php echo $i; ?>" value="<?php echo (!empty($results[$i]->ID)?$results[$i]->ID:''); ?>">
                                </td>
                                <td>
                                    <input type="text" name="product_code-<?php echo $i; ?>" value="<?php echo (!empty($results[$i]->product_code)?$results[$i]->product_code:''); ?>">

                                    <input type="hidden" name="id-<?php echo $i; ?>" value="<?php echo (!empty($results[$i]->ID)?$results[$i]->ID:''); ?>">
                                </td>
                                <td>
                                    <input type="text" name="comment-<?php echo $i; ?>" value="<?php echo (!empty($results[$i]->comment)?$results[$i]->comment:''); ?>">
                                    
                                    <input type="hidden" name="id-<?php echo $i; ?>" value="<?php echo (!empty($results[$i]->ID)?$results[$i]->ID:''); ?>">
                                </td>
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

<div id="four" class="tabs" style="display:none">
    <div class="select_customer">
        <select id="select_user">
            <option value="">Select</option>
            <?php 
            $users = get_users();
            if($users){
                foreach($users as $user){
                    echo "<option value='$user->ID'>$user->display_name</option>";
                }
            }
            ?>
            
        </select>

        <select name="access_val" id="access_val">
            <option value="blocked">Blocked</option>
            <option value="allowed">Allowed</option>
        </select>
        
        <button class="button button-secondary" id="access_btn">Save</button>
    </div>

    <div class="table_of_blocked_users">
        <table id="table_of_blocked_users">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <tbody class="users_rows">
              <?php
                $users_arr = get_option('mt_fields_edit_access');
                
                if(is_array($users_arr)){
                    if(count($users_arr) == 0){
                        echo '<tr>
                            <td colspan="4">No user blocked for edit MT Accounts.</td>
                        </tr>';
                    }else{
                        foreach($users_arr as $userD => $status){
                            $user = get_user_by( 'ID', intval($userD) );
                            
                            ?>
                            <tr class="user-<?php echo $user->ID ?>">
                                <th scope="row"><?php echo $user->ID ?></th>
                                <td><?php echo $user->display_name ?></td>
                                <td><?php echo $user->user_email ?></td>
                                <td><?php echo $status; ?></td>
                            </tr>
                            <?php
                        }
                    }
                }else{
                    echo '<tr>
                        <td colspan="4">No user blocked for edit MT Accounts.</td>
                    </tr>';
                }
              ?>
          </tbody>
        </table>
    </div>
</div>

<script>
    jQuery(function ($) {
        let ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ) ?>"
        $('#select_user').select2({
            placeholder: 'Type Name'
        });

        $('#access_btn').on('click', function(){
            let user_id = $('#select_user').val()
            let access_value = $('#access_val').val()
            $.ajax({
                type: "post",
                url: ajaxurl,
                data: {
                    action: "mt_id_edit_access",
                    user_id: user_id,
                    value: access_value
                },
                dataType: "json",
                success: function (response) {
                    if(response.blocked){
                        $('.users_rows').append(response.blocked)
                    }
                    if(response.allowed){
                        $('.users_rows').find(response.allowed).remove()
                        if($('.users_rows').children().length == 0){
                            $('.users_rows').html('<tr><td colspan="4">No user blocked for edit MT Accounts.</td></tr>')
                        }
                    }
                }
            });
        });
        
    });
    function tabs_items(elem) {
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