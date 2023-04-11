<?php
/*
Plugin Name: adnan plugin
Plugin URI: http://example.com
Description: first adnan plugin solicode 
Version: 1.0
Author: adnan bennasare
Author URI: https://github.com/AdnanBennasare
*/


// include upgrade.php to be able to use dbDelta function to run SQL queries
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

function bootstrap_cdn_scripts() {
    // Enqueue all styles
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js');
  }
  add_action('wp_enqueue_scripts', 'bootstrap_cdn_scripts');
  
  
  #######################################################################
  ################ END registering activation hook ######################
  #######################################################################
    function Plugin_Activation_Hook() {

      global $wpdb;
      $table_name = $wpdb->prefix . 'contact_form';

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
          `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
          `FirstName` varchar(100) NOT NULL,
          `LastName` varchar(100) NOT NULL,
          `Email` varchar(255) NOT NULL,
          `Subject` varchar(255) NOT NULL,
          `Message` text NOT NULL,
          `DateSent` timestamp NOT NULL DEFAULT current_timestamp()
        );
        ";
          dbDelta($sql);
        }
        register_activation_hook( __FILE__, 'Plugin_Activation_Hook' );
      
      
      
      ##################################################################################
      #################### START registering Deactivation hook #########################
      ########### DELETE wp_contact_form WHEN THE PLUGIN IS DESACTIVATED  ##############
      ##################################################################################
        function Plugin_Deactivation_Hook() {
          global $wpdb;
          $table_name = $wpdb->prefix . 'contact_form';
          $sql = "DROP TABLE IF EXISTS $table_name";
          $wpdb->query($sql);
        }
        register_deactivation_hook(__FILE__, 'Plugin_Deactivation_Hook');





        function contact_form_admin_menu_8(){
          $page_title = 'adnan contact form';
          $menu_title = 'adnan contact form';
          $capability = 'manage_options';
          $menu_slug = 'Contact-Form-8';
          $icon_url = 'https://cdn-icons-png.flaticon.com/24/9862/9862681.png';
          function Menu_Page_Callback(){
                  include(dirname(__FILE__). '/view_responses.php');
              
          }
  
   add_menu_page(  $page_title ,  $menu_title,  $capability,  $menu_slug, 'Menu_Page_Callback' ,  $icon_url,  $position = 2 );
   
      }
  
      add_action( "admin_menu", 'contact_form_admin_menu_8');
      







    function html_form_code() {
    echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post"> ';
    echo '<p>';

    echo 'Your Name (required) <br />';
    echo '<input type="text" name="cf-name" class="form-control" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["cf-name"] ) ? esc_attr( $_POST["cf-name"] ) : '' ) . '" size="40" />';
    echo '</p>';

    echo '<p>';
    echo 'Your lastname (required) <br />';
    echo '<input type="text" name="cf-lastname" class="form-control" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["cf-lastname"] ) ? esc_attr( $_POST["cf-lastname"] ) : '' ) . '" size="40" />';
    echo '</p>';
    
    echo '<p>';
    echo 'Your Email (required) <br />';
    echo '<input type="email" name="cf-email" class="form-control" value="' . ( isset( $_POST["cf-email"] ) ? esc_attr( $_POST["cf-email"] ) : '' ) . '" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Subject (required) <br />';
    echo '<input type="text" name="cf-subject" class="form-control" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["cf-subject"] ) ? esc_attr( $_POST["cf-subject"] ) : '' ) . '" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Your Message (required) <br />';
    echo '<textarea rows="10" cols="35" class="form-control" name="cf-message">' . ( isset( $_POST["cf-message"] ) ? esc_attr( $_POST["cf-message"] ) : '' ) . '</textarea>';
    echo '</p>';
    echo '<p><input type="submit" class="btn btn-primary" name="cf-submitted" value="Send"/></p>';
    echo '</form>';
}





function STORE_MAIL() {
  if (isset( $_POST['cf-submitted'])){
          // if the submit button is clicked, send the email
          if ( isset( $_POST['cf-name'] ) && !empty($_POST['cf-name']) 
          && isset( $_POST['cf-lastname'] ) && !empty($_POST['cf-lastname'])
          && isset( $_POST['cf-email'] ) && !empty($_POST['cf-email'])
          && isset( $_POST['cf-subject'] ) && !empty($_POST['cf-subject'])
          && isset( $_POST['cf-message'] ) && !empty($_POST['cf-message'])
      
            ) {
      
              // sanitize form values
             
              $FirstName   = sanitize_text_field( $_POST["cf-name"] );
              $LastName    = sanitize_text_field( $_POST["cf-lastname"] );
              $Email   = sanitize_email( $_POST["cf-email"] );
              $Subject = sanitize_text_field( $_POST["cf-subject"] );
              $Message = esc_textarea( $_POST["cf-message"] );
              // , current_timestamp()
                  // insert message into table 
                  global $wpdb;
                  $sql = "
                  INSERT INTO `wp_contact_form` (`id`, `FirstName`, `LastName`, `Email`, `Subject`, `Message`) 
                  VALUES (NULL, 
                  '$FirstName', 
                  '$LastName',
                  '$Email', 
                  '$Subject', 
                  '$Message'
            )
              ";
                  if ($wpdb->query($sql)){
                      echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                      <strong>message sent! </strong> Your message has been recieved thanks for contacting us .
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
  
                  } else {
                      echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <strong>message failed! </strong> Your message has not recieved please try again .
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                  }
  
      
  
             
          } else {
              echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>missing input fields </strong> please fill all the required inputs .
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
          }
  
      }
  
  
      }




    
    
    function cf_shortcode() {

  STORE_MAIL();
	ob_start();
	html_form_code();
  
	return ob_get_clean();
}

add_shortcode( 'sitepoint_contact_form', 'cf_shortcode' );



