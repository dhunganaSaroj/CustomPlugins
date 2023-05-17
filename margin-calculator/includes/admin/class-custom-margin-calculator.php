<?php 
class Margin_Markup_Calculator_Settings{

   public static function init(){
            add_action( 'admin_menu', __CLASS__ . '::my_admin_menu' );
            add_action( 'admin_init', __CLASS__ . '::my_settings_init' );
            add_action( 'admin_init', __CLASS__ . '::additional_styles' );
            
        }
    public static function additional_styles(){
        wp_enqueue_script('margin-calculator-scrpits',plugin_dir_url( __FILE__ ) . 'js/custom.js',array(),'1.0.0',true);
        wp_enqueue_style('margin-calculator-styles',plugin_dir_url( __FILE__ ) . 'css/custom.css',array(),'1.0.0','all');
    } 

    /**
     * Adding Menu In the WP Dashboard
     */
    public static function my_admin_menu() {
        add_menu_page(
            __( 'Sample page', 'my-textdomain' ),
            __( 'Margin Markup Calculator', 'my-textdomain' ),
            'manage_options',
            'sample-page',
            __CLASS__ . '::my_admin_page_contents',
            'dashicons-schedule',
            3
        );
    }
    
    
    public static function my_admin_page_contents() {
        ?>
       
        <form method="POST" action="options.php">
        <?php
        settings_fields( 'sample-page' );
        do_settings_sections( 'sample-page' );
        submit_button();
        ?>
        </form>
        <?php
    }
    
    /**
     * 
     * Settings Initialize 
     */
    public static function my_settings_init() {
    
        add_settings_section(
            'sample_page_setting_section',
            __( 'Margin Markup Calculator', 'my-textdomain' ),
            __CLASS__ . '::my_setting_section_callback_function',
            'sample-page'
        );
    
            add_settings_field(
               'my_setting_field',
               __( 'Calculation Field', 'my-textdomain' ),
               __CLASS__ . '::my_setting_markup',
               'sample-page',
               'sample_page_setting_section'
            );
    

           
            
            register_setting( 
                'sample-page',
                 'my_setting_field',
                 array(
                    'show_in_rest' => array(
                        'name' => 'images_slide',
                        'schema' => array(
                            'type'  => 'array',
                            'items' => array(
                                'id'    => 'string',
                                'order' => 'string',
                            ),
                        ),
                    ),
                    'type' => 'array',
                   //'sanitize_callback' => array(__CLASS__ . '::my_setting_markup' ),
                )
                );
            // register_setting( 'sample-page', 'my_setting_fiel' );
    }
    
    
    public static function my_setting_section_callback_function() {
        echo '<p>The Margin Markup calculator relating with base value and spend tier.</p>';
    }
    
    /**
     * Settings Function Call Back(Settings View Template)
     */
    public static function my_setting_markup() {
        require_once trailingslashit(WP_PLUGIN_DIR) . 'margin-calculator/includes/partials/custom_setting_callback.php';
    }
}
Margin_Markup_Calculator_Settings::init();
