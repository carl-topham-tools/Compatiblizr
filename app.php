<?php
/*
    Plugin Name: Compatiblizr
    Plugin URI: http://carl-topham.com/wordpress/plugins/compatiblizr
    Description: Plugin for patching old versions of IE (7 &amp; 8)to work with CSS3 selectors and Media Queries.
    Author: C. Topham
    Version: 0.2
    Author URI: http://carl-topham.com
    */

//For safety - Think of the children!!!
defined('ABSPATH') or die("No script kiddies please!");


function detect_ie($ie7_check = true, $ie8_check = true) {
    $ie7 = ($ie7_check == true) ? strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0') : false;
    $ie8 = ($ie8_check == true) ? strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0') : false;
    if ($ie7 !== false || $ie8 !== false) {
        return true;
    } else {
        return false;
    }
}

function compatiblizr_register_scripts() {
    if(!is_admin()) {
        //register
        wp_register_script( 'ie9-js', plugins_url( '/js/ie9.js', __FILE__ ) );
        wp_register_script( 'respond-js', plugins_url( '/js/respond.js', __FILE__ ) );
        wp_register_script( 'selectivizr-js', plugins_url( '/js/selectivizr.js', __FILE__ ) );
        wp_register_script( 'borderbox-js', plugins_url( '/js/border-box.js', __FILE__ ) , '', '', true);
    }
}
add_action('init', 'compatiblizr_register_scripts');


function compatiblizr_enqueue_scripts()
{   

    // IE7 & IE8
    if( detect_ie() ) { 
      //enqueue
      $options = get_option( 'compatiblizr_options' );
      if ( $options['select'] ) {
        wp_enqueue_script( 'selectivizr-js' );
      }
      if ( $options['respond'] ) {
        wp_enqueue_script( 'respond-js' );
      }
      if ( $options['ie9'] ) {
        wp_enqueue_script( 'ie9-js');
      }
    }

    // IE7
    if( detect_ie(true, false) ) { 
      if ( $options['borderbox'] ) {
        wp_enqueue_script( 'borderbox-js' );
      }
    }
}
add_action( 'wp_enqueue_scripts', 'compatiblizr_enqueue_scripts' );


//options

function global_custom_options()
{
?>
    <div class="wrap">
        <h2>Compatiblizr</h2>
        <p>Patch IE 7 &amp; 8 to work with CSS3 and Media Queries.</p>
        <p>Scripts will only be included for IE7 &amp; 8.</p>
        <form method="post" action="options.php">
            <?php wp_nonce_field('update-options') ?>
            <?php $options = get_option( 'compatiblizr_options' ); ?>
            <h3>CSS3 and Media Queries</h3>
            <p><input type="checkbox" name="compatiblizr_options[select]" value="1"<?php checked( 1 == $options['select'] ); ?> /><strong>Selectivizr.js </strong>- selectivizr is a JavaScript utility that emulates CSS3 pseudo-classes and attribute selectors in Internet Explorer 6-8. </strong><br />
                
            </p>

            <p><input type="checkbox" name="compatiblizr_options[respond]" value="1"<?php checked( 1 == $options['respond'] ); ?> /><strong>Respond.js * </strong>- A fast &amp; lightweight polyfill for min/max-width CSS3 Media Queries (for IE 6-8)<br />
                
            </p>

             <p><input type="checkbox" name="compatiblizr_options[ie9]" value="1"<?php checked( 1 == $options['ie9'] ); ?> /><strong>IE9.js *</strong>- Upgrade MSIE5.5-8 to be compatible with modern browsers.<br />

            </p>
            <p>* Please note that there are reports of respond.js &amp; IE.js not working well together. Use both at your own risk.</p>
            
            <h3>Box Sizing model</h3>
            <p>Already supported by IE8.</p>
            <p>For IE7 you can either use the <a href="https://github.com/Schepp/box-sizing-polyfill">box sizing polyfill</a>. You will need to update your CSS and server settings.</p>
            <p>OR...</p>
            <p><input type="checkbox" name="compatiblizr_options[borderbox]" value="1"<?php checked( 1 == $options['borderbox'] ); ?> /><strong>borderBoxModel.js </strong>- Border-box model support for Internet Explorer 6-7. Currently it works only if padding and border are set in px. Yey for IE7!</strong><br />

            <p><input type="submit" name="Submit" value="Save" /></p>
            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="page_options" value="compatiblizr_options" />

            
        </form>
    </div>


<?php
}


function add_global_custom_options()
{
    add_options_page('Compatiblizr', 'Compatiblizr', 'manage_options', 'compatiblizr','global_custom_options');
}

add_action('admin_menu', 'add_global_custom_options');

?>