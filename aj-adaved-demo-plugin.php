<?php
/*
Plugin Name: AJAX Users Demo
Description: Ajax demo for Users's API handle. It works placing a shortcode like this [ajax_demo] in a WP page
Author: Reykel 
Version: 1.0
*/

    function aj_ajax_demo_shortcode() {
        return '<div id="data_details"></div><div id="data_table"></div>';
    }

    add_shortcode('ajax_demo', 'aj_ajax_demo_shortcode');
    add_action( 'wp_enqueue_scripts', 'aj_enqueue_scripts' );

    function aj_enqueue_scripts() {
        wp_enqueue_script( 'aj-demo', plugin_dir_url( __FILE__ ). 'aj-ajax-code.js', array('jquery') );

        wp_localize_script( 
            'aj-demo', 'aj_ajax_demo', array(
                'ajax_url'      => admin_url( 'admin-ajax.php' ),
                'aj_demo_nonce' => wp_create_nonce('aj-demo-nonce')
            )
        );
    }

    add_action( 'wp_ajax_nopriv_aj_ajax_demo_get_users', 'aj_ajax_demo_process' );
    add_action( 'wp_ajax_aj_ajax_demo_get_users', 'aj_ajax_demo_process' );

    function aj_ajax_demo_process() {
        $str_table = '';

        check_ajax_referer( 'aj-demo-nonce', 'nonce' );
        
        $response     = wp_remote_request('https://jsonplaceholder.typicode.com/users');
        $json_a       = json_decode( wp_remote_retrieve_body( $response ), true );
        
        foreach ($json_a as $k) {
            $str_table = $str_table.'<tr><td><a class="Tlink" id="'.$k['id'].'" href="#">'.$k['id'].'</a></td><td><a class="Tlink" id="'.$k['id'].'" href="#">'.$k['name'].'</a></td><td><a class="Tlink" id="'.$k['id'].'" href="#">'.$k['username'].'</a></td><td></td></tr>';
        }        

        wp_send_json('<br><table id="css_table">
            <thead>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Username</th>
                <th scope="col"></th>
            </thead><tbody>'.$str_table.'</tbody></table>'
        );

        wp_send_json_error();
        wp_die();
    }

    add_action( 'wp_ajax_nopriv_aj_ajax_demo_get_user_details', 'aj_ajax_demo_process_id' );
    add_action( 'wp_ajax_aj_ajax_demo_get_user_details', 'aj_ajax_demo_process_id' );

    function aj_ajax_demo_process_id() {

        check_ajax_referer( 'aj-demo-nonce', 'nonce' );

        $_valor = sanitize_text_field($_POST['valor']);        
        
        $response     = wp_remote_request('https://jsonplaceholder.typicode.com/users/'.$_valor.' ');
        $json_a       = json_decode( wp_remote_retrieve_body( $response ), true );
        
        wp_send_json('
            <input type="button" value="Back to list" id="back_button">
            <table>
              <tr>
                <td>ID</td>
                <td>'.$json_a['id'].'</td>
              </tr>
              <tr>
                <td>Name</td>
                <td>'.$json_a['name'].'</td>
              </tr>
              <tr>
                <td>Username</td>
                <td>'.$json_a['username'].'</td>
              </tr>
              <tr>
                <td>Email</td>
                <td>'.$json_a['email'].'</td>
              </tr>
              <tr>
                <td>Street</td>
                <td>'.$json_a['address']['street'].'</td>
              </tr>
              <tr>
                <td>Suite</td>
                <td>'.$json_a['address']['suite'].'</td>
              </tr>
              <tr>
                <td>City</td>
                <td>'.$json_a['address']['city'].'</td>
              </tr>
              <tr>
                <td>Zip Code</td>
                <td>'.$json_a['address']['zipcode'].'</td>
              </tr>
              <tr>
                <td>Lat</td>
                <td>'.$json_a['address']['geo']['lat'].'</td>
              </tr>
              <tr>
                <td>Lng</td>
                <td>'.$json_a['address']['geo']['lng'].'</td>
              </tr>
              <tr>
                <td>Phone</td>
                <td>'.$json_a['phone'].'</td>
              </tr>
              <tr>
                <td>Website</td>
                <td>'.$json_a['website'].'</td>
              </tr>
              <tr>
                <td>Company</td>
                <td>'.$json_a['company']['name'].'</td>
              </tr>
              <tr>
                <td>Catch Phrase</td>
                <td>'.$json_a['company']['catchPhrase'].'</td>
              </tr>
              <tr>
                <td>Bs</td>
                <td>'.$json_a['company']['bs'].'</td>
              </tr>
            </table'
        );

        wp_send_json_error();
        wp_die();
    }    
?>
