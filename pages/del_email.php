<?php
layout_page_header( plugin_lang_get( 'plugin_title' ) );
layout_page_begin();
print_manage_menu();
access_ensure_global_level( MANAGER );
?>
<form method="post" action="<?php echo plugin_page( 'del_email.php' ); ?>">
<div align="center">
<?php

$f_id	  = gpc_get_int( 'id' );

$t_plugin_table =  plugin_table('emails');

if( gpc_isset( 'submit' ) ) {
	$query = "DELETE FROM $t_plugin_table WHERE id ='$f_id'";
	$result = db_query( $query );
	if ( $result ) {
		print "<h2>Deleted email address</h2>";
		print "<br>";
		print_link( plugin_page( 'manage_email.php' ), lang_get( 'proceed' ) );
	} else {
		print_sql_error( $query );
		print "<br>";
		print_link( plugin_page( 'manage_email.php' ), lang_get( 'proceed' ) );
	}
} else {
	if($f_id) {
		$t_user_table = db_get_table( 'mantis_user_table' );
		$t_email_table = plugin_table('emails');
		
		$t_query = "SELECT e.email, u.username FROM {$t_email_table} AS e LEFT JOIN $t_user_table AS u ON e.user_id=u.id WHERE e.id=" . db_param();
		$t_result = db_query( $t_query, array( $f_id ) );

		$t_row = db_fetch_array( $t_result );
		if ( $t_row ) {
			print "<h4>Are you sure you want to remove {$t_row['email']} from the account {$t_row['username']}</h4>";
			print '<input type="hidden" name="id" value="'.$f_id.'">';
			print '<input type="submit" name="submit" value="Confirm Deletion">';
		    print "<br>";
		    print_link( plugin_page( 'manage_email.php' ), plugin_lang_get( 'cancel' ) );
		} else {
			print_sql_error( $query );
		print "<br>";
		print_link( plugin_page( 'manage_email.php' ), lang_get( 'proceed' ) );
		}
	} else {
		print "<h3>User not selected</h3>";
		print "<br>";
		print_link( plugin_page( 'manage_email.php' ), lang_get( 'proceed' ) );
	}

}
?>
</div>
</form>
<?php
layout_page_end();
