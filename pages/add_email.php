<?php
layout_page_header( plugin_lang_get( 'plugin_title' ) );
layout_page_begin();
print_manage_menu();
access_ensure_global_level( MANAGER );
?>

<div align="center">
<?php

$f_user_id	  = gpc_get_int( 'f_user_id' );
$f_email	  = gpc_get_string( 'f_email' );
$f_email = strtolower($f_email);

if($f_user_id) {
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
?>
<?php

		print_bracket_link( plugin_page( 'manage_email.php' ), lang_get( 'proceed' ) );
?>
</div>
<?php
layout_page_end();
