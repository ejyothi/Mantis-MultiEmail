<?php
html_page_top1();
html_page_top2();
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
	if ( $result ) {            # SUCCESS
		PRINT "<h2>Deleted email address</h2>";
	} else {                    # FAILURE
		print_sql_error( $query );
	}
} else {
	if($f_id) {
		$t_user_table = db_get_table( 'mantis_user_table' );
		$query = "SELECT e.email, u.username FROM ". plugin_table('emails')." AS e LEFT JOIN $t_user_table AS u ON e.user_id=u.id WHERE e.id=" . db_param();
		$t_username = db_result( db_query_bound( $query, array( $f_id ), 1 ) );
		$t_email = db_result( db_query_bound( $query, array( $f_id ), 2 ) );

		if ( $t_email ) {			# SUCCESS
			//PRINT lang_get( 'operation_successful' ) . '<p>';
			print "<h2>Are you sure you want to remove {$t_email} from the account {$username}</h2>";
			print '<input type="hidden" name="id" value="'.$f_id.'">';
			print '<input type="submit" name="submit" value="Confirm Deletion">';
		} else {					# FAILURE
			print_sql_error( $query );
		}
	} else {
		print "<h3>User not selected</h3>";
	}

}
?>

<?php
		print "<br>";
		print_bracket_link( plugin_page( 'manage_email.php' ), lang_get( 'proceed' ) );
?>
</div>
</form>
<?php
html_page_bottom1();
