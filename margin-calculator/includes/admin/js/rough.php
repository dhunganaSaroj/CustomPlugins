<?php 
  
  class Individual_Product_Settings{

        public static function init(){
            //var_dump($_POST['custom_field_setup_cost']);
            //die();
            add_action( 'woocommerce_save_product_variation', __CLASS__ . '::save_variation_settings_fields', 10, 2 );
            add_action( 'woocommerce_variation_options', __CLASS__ . '::variation_settings_fields', 10, 3 );
            add_filter( 'woocommerce_available_variation', __CLASS__ . '::load_variation_settings_fields' );
            add_action( 'admin_init', __CLASS__ . '::additional_styles' );
        }

             
        public static function additional_styles(){
            wp_enqueue_script('margin-calculator-detail-scripts',plugin_dir_url( __FILE__ ) . 'js/detail-product-settings.js',array(),'1.0.0',true);
            wp_enqueue_style('margin-calculator-details-styles',plugin_dir_url( __FILE__ ) . 'css/custom.css',array(),'1.0.0','all');
        } 
    
        
        function variation_settings_fields( $loop, $variation_data, $variation ) {
            $setup_cost=get_post_meta( $variation->ID,'custom_field_repeat',false);
            
            testPrint($setup_cost);
            $custom_fields=get_post_meta( $variation->ID,'custom_field_repeat',false);
            $formatted_fields=$custom_fields[0];
        ?>
        <div class="setup_cost_wrap">
            <label for="custom_field_setup_cost">Setup Cost</label>
            <input type="text" class="custom_field_setup_cost" id="custom_field_setup_cost" value="">
        </div>
        <div class="settings_repeat_wrap">
            <div class="repeat_wrap">
                <?php
                   
                    //testPrint($formatted_fields);
                    $count=0;
                    if($formatted_fields){
                        foreach($formatted_fields as $fields){
                ?>
                <div class="single-product-repeater_field" id="single-product-repeater">
                    <div class="quantity_wrapper"> 
                        <label for="custom_field_repeat">Quantity</label>
                        <input type="text" name="custom_field_repeat[<?php echo $count ?>][quantity]" class="variations_quantity_input_fields" value="<?php echo $fields['quantity'] ?>">
                    </div>
                    <div class="price_wrapper">
                        <label for="custom_field_repeat">Price</label>
                        <input type="text" name="custom_field_repeat[<?php echo $count ?>][price]" class="variations_price_input_fields" value="<?php echo $fields['price'] ?>" >
                    </div>   
                    <div class="special_price_wrapper"> 
                        <label for="custom_field_repeat">Special Price</label>
                        <input type="text" name="custom_field_repeat[<?php echo $count ?>][special_price]" class="variations_special_price_input_fields" value="<?php echo $fields['special_price'] ?>">
                    </div>
                    <div class="class_retrive">
                        <a href="javascript:void(0)" class="detail_productremove_row">
                            <i class="fa fa-close"></i>
                        </a>
                    </div>
                </div>
                <?php 
                    $count++;
                        }//foreach
                    }else{
                ?>
                <div class="single-product-repeater_field" id="single-product-repeater">
                        <div class="quantity_wrapper"> 
                            <label for="custom_field_repeat">Quantity</label>
                            <input type="text" name="custom_field_repeat[0][quantity]" class="variations_quantity_input_fields" value="">
                        </div>
                        <div class="price_wrapper">
                            <label for="custom_field_repeat">Price</label>
                            <input type="text" name="custom_field_repeat[0][price]" class="variations_price_input_fields" value="" >
                        </div>   
                        <div class="special_price_wrapper"> 
                            <label for="custom_field_repeat">Special Price</label>
                            <input type="text" name="custom_field_repeat[0][special_price]" class="variations_special_price_input_fields" value="">
                        </div>
                        <div class="class_retrive">
                            <a href="javascript:void(0)" class="detail_productremove_row">
                                <i class="fa fa-close"></i>
                            </a>
                        </div>
                </div>
                <?php
                    }
                ?>
            </div>
            <a href="javascript:void(0)" class="product-settings-repeater" id="variation_repeater_settings">Add Row</a>
        </div>
        <?php    
        }
        
        function save_variation_settings_fields( $variation_id, $loop ) { 
         //$setup_cost=$POST['custom_field_setup_cost'];
         $meta = $_POST['custom_field_repeat'];
        //  if($meta){
        //      foreach($meta as $meta_key){}
        //  }
          for($i=0;$i<count($meta);$i++){
              $meta_value[]=array(
                'price' => $meta[$i]['price'],
                'quantity'  =>$meta[$i]['quantity'],
                'special_price' =>$meta[$i]['special_price']
              );
            }
            update_post_meta( $variation_id, 'custom_field_repeat', $meta_value );
            //update_post_meta( $variation_id, 'custom_field_setup_cost', $setup_cost );
            
        }
        
        function load_variation_settings_fields( $variation ) {     
            $variation['custom_field_repeat'] = get_post_meta( $variation[ 'variation_id' ], 'custom_field_repeat', true );
            $variation['custom_field_setup_cost'] = get_post_meta( $variation[ 'variation_id' ], 'custom_field_setup_cost', true );
            return $variation;
        }     

        
        

  }
  Individual_Product_Settings::init();