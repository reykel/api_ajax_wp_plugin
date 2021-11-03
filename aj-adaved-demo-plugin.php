<?php
/*
Plugin Name: AJAX Cocktails Demo
Plugin URI: https://www.adaved.com/ajax-cocktails-wordpress-demo/
Description: Ajax demo for Cocktail's API handle. It works placing a shortcode like this [ajax_demo] in a WP page
Author: Adaved 
Version: 1.0

*/

function aj_ajax_demo_shortcode() {

    return '<label for="select">Contenido:</label><select name="select" size="1" id="select">
    <option value="Non alcoholic">Non alcoholic</option>
    <option value="Optional alcohol">Optional alcohol</option>
    <option value="Alcoholic">Alcoholic</option>
    </select>
    <br><br>
    <label for="nombre">Criterio: </label><input type="text" name="str_criteria" id="str_criteria"></br><div id="data_table"><br></div><br>';
}


add_shortcode('ajax_demo', 'aj_ajax_demo_shortcode');


add_action( 'wp_enqueue_scripts', 'aj_enqueue_scripts' );
function aj_enqueue_scripts() {
    wp_enqueue_script( 'aj-demo', plugin_dir_url( __FILE__ ). 'aj-ajax-code.js', array('jquery') );

    wp_localize_script( 'aj-demo', 'aj_ajax_demo', array(
                        'ajax_url' => admin_url( 'admin-ajax.php' ),
                        'aj_demo_nonce' => wp_create_nonce('aj-demo-nonce') 
    ));
}


add_action( 'wp_ajax_nopriv_aj_ajax_demo_get_cocktails', 'aj_ajax_demo_process' );
add_action( 'wp_ajax_aj_ajax_demo_get_cocktails', 'aj_ajax_demo_process' );

function aj_ajax_demo_process() {
    $str_table = '';

    check_ajax_referer( 'aj-demo-nonce', 'nonce' );
    
    $_valor = sanitize_text_field($_POST['valor']);

    $response     = wp_remote_request('https://www.thecocktaildb.com/api/json/v1/1/search.php?s='.$_valor);
    $json_a      = json_decode( wp_remote_retrieve_body( $response ), true );
    
    $i = 0;

    foreach ($json_a as $k) {
        while ($i <= count($json_a['drinks']) - 1){
            if($k[$i]['strAlcoholic'] == $_POST['contenido']){
                $str_table = $str_table.'<tr><td>'.$k[$i]['strDrink'].'</td><td>'.$k[$i]['strInstructions'].'</td><td><button name="btn_criteria" id="btn_criteria">Detalles</button></td></tr>';
            }

            $i++;
        }
    }        

    wp_send_json('<br><table><tr><th scope="col">Trago</th><th scope="col">Preparación</th><th scope="col"></th></tr>'.$str_table.'</table>');

    wp_send_json_error();
    wp_die();
}
?>