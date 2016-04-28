<?php
//require( "faq_api.php" );
//require( "css_faq.php" );
html_page_top1();
  html_page_top2();
access_ensure_global_level( MANAGER );
?>

<div align="center">
<?php

$f_user_id	  = gpc_get_int( 'f_user_id' );
$f_email	  = gpc_get_string( 'f_email' );
$f_email = strtolower($f_email);

if($f_user_id) {
    //$f_project_id = gpc_get_string( 'project_id' );
    //$f_poster_id  = auth_get_current_user_id();

    /*if (ON == plugin_config_get('faq_view_check') ){
      $f__view_level = gpc_get_string( 'faq_view_threshold' );
      } else {
      $f_view_level =plugin_config_get('faq_view_threshold');
      }*/
    //    $result 	= faq_add_query( $f_project_id, $f_poster_id, $f_question, $f_answere ,$f_view_level);
    if (filter_var($f_email, FILTER_VALIDATE_EMAIL)) {
        if(user_get_id_by_email($f_email) === FALSE) {

            $t_plugin_table =  plugin_table('emails');
            $query = "INSERT
                INTO $t_plugin_table
                ( id, user_id, email)
                VALUES
                ( null, '$f_user_id', '$f_email' )";
            //       print $query; exit;
            $result = db_query( $query );
            if ( $result ) {			# SUCCESS
                PRINT lang_get( 'operation_successful' ) . '<p>';
            } else {					# FAILURE
                print_sql_error( $query );
            }
        } else {
            print "<h3>Email already added</h3>";
        }
    } else {

        print "<h3>Invalid Email</h3>";
    }
} else {
    print "<h3>User not selected</h3>";
}

/*$f_question = string_display( $f_question );
  $f_answere 	= string_display( $f_answere );*/
?>

<?php

		print_bracket_link( plugin_page( 'manage_email.php' ), lang_get( 'proceed' ) );
?>
</div>
<?php
html_page_bottom1();
