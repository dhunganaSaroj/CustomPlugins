    <pre>
        <?php
            $options_value=get_option('my_setting_field');
            //print_r($options_value);
        ?>
    </pre>
    <label for="my-input"><?php _e( 'My Input' ); ?></label>

    <table class="tbl_identify">
        <tr>
            <th>Base Value($)</th>
            <th>SpendTier($)</th>
            <th>Margin(%)</th>
            <th>Markup(%)</th>
        </tr>
        <?php 
            if($options_value){
                $count=0;
                foreach($options_value as $value){
            
        ?>
        <tr class="margin_table_row" id="margin_repeater_row">
            <td class="class_retrieve">
                 <input type="number" class="margin_input_fields margin_base_value" id="my_setting_field" name="my_setting_field[<?php echo $count ?>][base_value]" value="<?php echo $value['base_value']; ?>">
            </td class="class_retrieve">
            <td>
                <input type="number" class="margin_input_fields margin_spend_tier" id="my_setting_field" name="my_setting_field[<?php echo $count ?>][spend_tier]" value="<?php echo $value['spend_tier']; ?>">
            </td class="class_retrieve">
            <td class="class_retrieve">
               <input type="number" class="margin_input_fields margin_margin" id="my_setting_field" step="any" name="my_setting_field[<?php echo $count ?>][margin]" value="<?php echo $value['margin']; ?>">
            </td>
            <td class="class_retrieve">
                <input type="number" class="margin_input_fields margin_markup" id="my_setting_field" step="any" name="my_setting_field[<?php echo $count ?>][markup]" value="<?php echo $value['markup']; ?>">
            </td>
            <td class="class_retrive">
                <a href="javascript:void(0)" class="remove_row">
                    <i class="fa fa-close"></i>
                </a>
            </td>

        </tr>

        <?php
            $count++;
                }//foreach
    }else{
    ?>
        <tr class="margin_table_row" id="margin_repeater_row">
            <td class="class_retrieve">
                 <input type="number" class="margin_input_fields" id="my_setting_field" step="any" name="my_setting_field[0][base_value]" value="">
            </td>
            <td class="class_retrieve">
                <input type="number" class="margin_input_fields" id="my_setting_field" step="any" name="my_setting_field[0][spend_tier]" value="">
            </td>
            <td class="class_retrieve">
               <input type="number" class="margin_input_fields" id="my_setting_field" step="any" name="my_setting_field[0][margin]" value="">
            </td>
            <td class="class_retrieve">
                <input type="number" class="margin_input_fields" id="my_setting_field" step="any" name="my_setting_field[0][markup]" value="">
            </td>
        </tr>
        

    <?php
    } //endif
    ?>
    
    </table>
    <a href="javascript:void(0)" class="btn" id="margin_row">Add Row</a>
    

    

<!-- //         jQuery('#margin_repeater_row').after(html); -->