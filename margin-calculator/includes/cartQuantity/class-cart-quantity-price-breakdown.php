<?php
    class CartQuantityPriceBreakDown{
       public static function init(){
            add_action('woocommerce_before_add_to_cart_quantity', __CLASS__.'::get_variation_data');
            add_action('woocommerce_before_calculate_totals',__CLASS__.'::custom_saleprice_accoring_to_variation');
            add_action('wp_enqueue_scripts',__CLASS__.'::additional_styles');
        }

        public static function additional_styles(){
            wp_enqueue_script( 'breakdown', plugin_dir_url( __FILE__ ) . 'js/demo.js',array(),'1.0.0',true);
        }
        /**
         * Margin markup calculations on detail page with discounted price
         */
        public static function get_variation_data() {
            $margin_markup=get_option('my_setting_field');
            global $product;
            
            if ( $product->is_type('variable') ) {
                
                ?>
                <script>
                    jQuery(document).ready(function($) {
                        var price_data_max;
                        var global_discount_html;
                        jQuery('input.variation_id').change( function(){
                            jQuery(".table_element").remove();
                            if( '' != jQuery('input.variation_id').val() ) {
                                
                                var var_id = jQuery('input.variation_id').val();
                                var selected_variation_key=jQuery('#pa_decor :selected').val();
                                var variation_data=jQuery(".variations_form").attr("data-product_variations");
                                var parsed_datas=JSON.parse(variation_data);
                                const filtered_data=parsed_datas.filter( element => element.attributes.attribute_pa_decor ==selected_variation_key);
                                if(filtered_data){
                                    var custom_field_repeat_data=filtered_data[0]['custom_field_repeat'];

                                    var setup_cost=filtered_data[0]['custom_field_setup_cost'];
        
                                        
                                        //Appending Custom Data to table

                                        /**
                                         * Quantity Row
                                         */
                                        var tablerows = new Array();
                                        
                                        tablerows.push('<th>Quantity</th>');
                                        //quantity in the heading row
                                        jQuery.each(custom_field_repeat_data,function(key, value){
                                            tablerows.push('<td>' + value.quantity + '</td>');
                                        });

                                        var table_heading_row =  jQuery('<tr/>', {
                                            html:tablerows
                                        });

                                        var thead=jQuery('<thead>',{
                                            html:table_heading_row
                                        });
                                        
                                        /**
                                         * Price Row
                                         */
                                        var price_rows=  new Array();

                                        price_rows.push('<th>Price(Each)</th>');
                                        var main_price="";
                                        var margin_markup_settings= <?php echo json_encode($margin_markup); ?>;
                                        jQuery.each(custom_field_repeat_data,function(key, value){
                                            //creating array of prices to get the closest key value from base value in the margin markup calculator settings
                                            
                                            var new_price=calculate_final_price(value);

                                            price_rows.push('<td> $' + new_price.toFixed(2) + '</td>');
                                            main_price=new_price;
                                            
                                        });

                                        price_row_wrap=jQuery('<tr>',{
                                            html:price_rows
                                        }); 

                                    

                                        /**
                                         * Selection
                                         */
                                        //max sell price
                                        var sellPriceArray=new Array();
                                        jQuery.each(custom_field_repeat_data,function(key,value){
                                            var sellPrice=calculate_final_price(value);
                                            sellPriceArray.push(sellPrice);
                                        });
                                        var max_sell_price= Math.max.apply(Math, sellPriceArray);
                                        
                                            

                                        var quantitySelected= new Array();

                                        quantitySelected.push('<th>Select</th>');

                                        jQuery.each(custom_field_repeat_data,function(key, value){
                                            var discount=discounted_price_with_percentage(max_sell_price,value);
                                            var new_price=calculate_final_price(value);
                                            quantitySelected.push('<td><div class="form-check"><input class="form-check-input selected-quantity" type="radio"name="selected-quantity"data-maxprice="'+max_sell_price.toFixed(2)+'" data-discount="'+discount+'" data-price="'+ new_price.toFixed(2) +'" value="'+value.quantity+'" id="selected-quantity"></div></td>');
                                        });

                                        var select_wrap=jQuery('<tr>',{
                                            html:quantitySelected
                                        });
                                        

                                        /**
                                         * Discount Save
                                         */
                                        var discountedAmount=new Array();
                                        
                                        discountedAmount.push('<th>You Save</th>');
                                        
                                        //max sell price
                                        var sellPriceArray=new Array();
                                        jQuery.each(custom_field_repeat_data,function(key,value){
                                            var sellPrice=calculate_final_price(value);
                                            sellPriceArray.push(sellPrice);
                                        });
                                        var max_sell_price= Math.max.apply(Math, sellPriceArray);
                                        
                                        price_data_max=max_sell_price;

                                        //discount field on quantity breakdown
                                        jQuery.each(custom_field_repeat_data,function(key,value){
                                            var discount=discounted_price_with_percentage(max_sell_price,value);

                                            discountedAmount.push('<td>'+ discount +'</td>');
                                        });

                                        var discount_wrap=jQuery('<tr>',{
                                            html:discountedAmount
                                        });



                                        var table_body=jQuery('<tbody>',{
                                            html:[price_row_wrap,discount_wrap,select_wrap]
                                        });

                                        var table=jQuery('<table>',{
                                            class:'table',
                                            html:[thead,table_body]
                                        })
                                        
                                        var table_wrapper= jQuery('<div>',{
                                            class: 'table-responsive bg-white table_element',
                                            html:table 
                                        })

                                        jQuery('.quanity_table').append(table_wrapper);
                                        var table_wrapper=" ";
                                    // end of appending data to table

                                   function discounted_price_with_percentage(max_sell_price,value){
                                       var sell_price=calculate_final_price(value);

                                        var max_price=max_sell_price;
                                       var discounted_price=(max_price-sell_price).toFixed(2);
                                     
                                       var discount_percent=(discounted_price/max_price)*100;
                                       var discounthtml='$'+discounted_price+'('+discount_percent.toFixed(2) +'%)';
                                       if(discounted_price==0.00){
                                        discounthtml='-';
                                       }
                                    //    console.log(discounthtml);
                                       return discounthtml;
                                    
                                   }

                                    /**
                                     * Function to calcualte the price for detail page display
                                     */
                                    function calculate_final_price(value){
                                        //cost price calculaions
                                            var price="";

                                            if(value.special_price){
                                                price=parseFloat(value.special_price);
                                            }else{
                                                price=parseFloat(value.price);
                                            }

                                            var cost_price=parseFloat((setup_cost/value.quantity));
                                            //console.log(cost_price);
                                            var  unit_cp=parseFloat(price+cost_price);

                                            var qty_cp=value.quantity*unit_cp;

                                            //desired price calculation
                                            var priceArray= new Array();
                                            jQuery.each(margin_markup_settings,function(ind,variable){
                                                priceArray.push(variable.base_value);
                                            });

                                            //closest element
                                            var goal=qty_cp;


                                            var new_arr = priceArray.filter(function(x) {
                                                return x < goal;
                                            });

                                            var closest = Math.max.apply(Math, new_arr);



                                            var desired_row=margin_markup_settings.filter(element => element.base_value==closest);

                                            var desired_markup=desired_row[0].markup;
                                            //final selling price calculations
                                            var final_sellprice=unit_cp*(1+(desired_markup/100));
                                            return final_sellprice;
                                    }

                                }
                                }
                            }); 

                            jQuery('.input-qty').on('change',function(){
                                if( '' != jQuery('input.variation_id').val() ) {
                                var qty=jQuery(this).val();
                                //console.log(qty);
                                var var_id = jQuery('input.variation_id').val();
                                var selected_variation_key=jQuery('#pa_decor :selected').val();
                                var variation_data=jQuery(".variations_form").attr("data-product_variations");
                                var parsed_datas=JSON.parse(variation_data);
                                const filtered_data=parsed_datas.filter( element => element.attributes.attribute_pa_decor ==selected_variation_key);
                                if(filtered_data){
                                    var custom_field_repeat_data=filtered_data[0]['custom_field_repeat'];
                                        //console.log(custom_field_repeat_data);
                                        var setup_cost=filtered_data[0]['custom_field_setup_cost'];

                                        //quantity array to determine min quantity
                                        var quantityArray=new Array();
                                        
                                        jQuery.each(custom_field_repeat_data,function(key, value){
                                            quantityArray.push(value.quantity);
                                        });

                                        //minquantity 
                                        var min_quantity=Math.min.apply(Math, quantityArray);
                                        
                                        var max_value=" ";
                                        //console.log(qty);
                                        if(min_quantity>qty){
                                            qty=min_quantity;
                                        }
                                        //console.log(qty);
                                        //filtering data less than goal
                                        const required_row=custom_field_repeat_data.filter( element => parseFloat(element.quantity) <= qty);
                                        //console.log("Required row");
                                        //console.log(required_row);
                                        
                                        //creating quantity array from filered data;
                                        newPriceArray=new Array();
                                        jQuery.each(required_row,function(key,value){
                                            newPriceArray.push(value.quantity);
                                        });
                                       
                                        
                                        //max value among less than value
                                        max_value=Math.max.apply(Math, newPriceArray);
                                        
                                        var cal_price=required_row.filter(element=>parseFloat(element.quantity)==max_value);
                                        //console.log(cal_price);
                                        //console.log(cal_price);
                                        var price=" ";
                                        if(cal_price[0].special_price){
                                            price=cal_price[0].special_price;
                                        }else{
                                            price=cal_price[0].price;
                                        }
                                       
                                        var main_price=calculate_quantity_final_price(price,qty,setup_cost).toFixed(2);
                                        var max_price_data=price_data_max.toFixed(2);
                                        var save_discounted_price=(max_price_data-main_price).toFixed(2);
                                     
                                       var save_discount_percent=(save_discounted_price/max_price_data)*100;
                                       var save_discounthtml='$'+save_discounted_price+'('+save_discount_percent.toFixed(2) +'%)';
                                       if(save_discounted_price==0.00){
                                        save_discounthtml='-';
                                       }

                                        jQuery(this).parents('.calculate-price-wrap ').find('.dat-wooprice').text(main_price);
                                        if(main_price==max_price_data){
                                            // console.log("no disc");
                                            // jQuery(this).parents('.calculate-price-wrap').find('.discount-price-strike').text(' ');
                                            jQuery(this).parents('.calculate-price-wrap').find('.you-save').text(' ');
                                        }else{
                                            // jQuery(this).parents('.calculate-price-wrap').find('.discount-price-strike').text('$'+max_price_data);
                                            jQuery(this).parents('.calculate-price-wrap').find('.you-save').text('You save: '+save_discounthtml+' from $'+max_price_data);
                                        }

                                       
                                    /**
                                     * Function to calcualte the price for detail page display
                                     */
                                    function calculate_quantity_final_price(price,quantity,setup_cost){
                                        //cost price calculaions


                                            var margin_markup_settings= <?php echo json_encode($margin_markup); ?>;
                                           

                                            var cost_price=parseFloat((setup_cost/quantity));
                                            //console.log(cost_price);
                                           
                                            var  unit_cp=parseFloat(price)+parseFloat(cost_price);
                                             
                                            
                                            
                                            var qty_cp=quantity*unit_cp;

                                            

                                            //desired price calculation
                                            var priceArray= new Array();
                                            jQuery.each(margin_markup_settings,function(ind,variable){
                                                priceArray.push(variable.base_value);
                                            });

                                            //closest element
                                            var goal=qty_cp;


                                            var new_arr = priceArray.filter(function(x) {
                                                return x < goal;
                                            });

                                           
                                            var closest = Math.max.apply(Math, new_arr);

                                            


                                            var desired_row=margin_markup_settings.filter(element => element.base_value==closest);
                                          
                                            var desired_markup=desired_row[0].markup;
                                            //final selling price calculations
                                            var final_sellprice=unit_cp*(1+(desired_markup/100));
                                            return final_sellprice;
                                    }

                                }
                                }
                                else{
                                    //console.log("Simple Product");
                                }
                            });
                        }); 
                
                </script>
                
                <?php
            }else{
                $price="";
                $regular_price= $product->get_regular_price();
                $sale_price=$product->get_sale_price();
                if($sale_price){
                    $price=$sale_price;
                }else{
                    $price=$regular_price;
                }
                ?>
                    <script>
                        jQuery(document).ready(function(){
                            var price=<?php echo $price; ?>;
                            //console.log(price);
                            jQuery('.dat-wooprice').text(price); 
                            
                        });
                    </script>
                <?php
            }
            
        }

        /**
         * Margin Markup callcuation and implementation on cart page
         */
        public static function custom_saleprice_accoring_to_variation($cart){
            $margin_markup=get_option('my_setting_field');
                if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;
                if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) return;
                foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
                    if($cart_item['variation']){
                        $quantity=$cart_item['quantity'];
                        $variation_id=$cart_item['variation_id'];
                        $variation_settings=get_post_meta( $variation_id,'custom_field_repeat',false);
                        $setup_cost=get_post_meta( $variation_id,'custom_field_setup_cost',false);

                        //quantity variable Array
                        $quantity_array=array();
                        //creating an array to store all the quantity
                        foreach($variation_settings[0] as $data){
                            $quantity_array[]=$data['quantity'];
                        } 
                        
                        $min_quantity=min($quantity_array);
                        if($min_quantity>$quantity){
                            $quantity=$min_quantity;
                        }

                        $filtered =array_filter($quantity_array, function($element) use ( $quantity) {
                            return ($element <=  $quantity);
                         });

                         $quantity_key=max($filtered);
                        //getting the price from the closest Key
                        $calculated_price;
                        foreach($variation_settings[0] as $data){
                            if($quantity_key==$data['quantity']){
                                if($data['special_price']){
                                    $calculated_price=$data['special_price'];
                                }else{
                                    $calculated_price=$data['price'];
                                }
                            }
                        }

                        $cost_price=$calculated_price+($setup_cost[0]/$quantity);

                        $quantity_price=$quantity*$cost_price;

    
                        $price = round( $calculated_price, 2 );
                        $price_array=array();
                        if($margin_markup){
                         foreach($margin_markup as $markup){
                                  $price_array[]=$markup['base_value'];
                         }
                        }
                        $priceFiltered = array_filter($price_array, function($element) use ($quantity_price) {
                            return ($element <= $quantity_price);
                         });
                         $price_key=max($priceFiltered);
                        //$price_key=getClosest($price,$price_array);
                        $desired_markup;
                        if($margin_markup){
                         foreach($margin_markup as $marks){
                              if($marks['base_value']==$price_key){
                                   $desired_markup=$marks['markup'];
                              }
                         }
                        }
                        
                        
                       //$cost_price=$calculated_price($setup_cost[0]/$quantity);
                       
                       $final_price=round($cost_price*(1+($desired_markup/100)),2);
                       //testPrint($final_price);
                        $cart_item['data']->set_price( $final_price );
                    }
                }
        }
    }
    CartQuantityPriceBreakDown::init();
    

