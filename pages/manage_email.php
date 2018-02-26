<?php
access_ensure_global_level( MANAGER );
layout_page_header( plugin_lang_get( 'plugin_title' ) );
layout_page_begin();
print_manage_menu();
$t_plugin_table =  plugin_table('emails');
$t_user_table = db_get_table( 'mantis_user_table' );

$t_query = "SELECT e.id, e.user_id, e.email as extra_email, u.username, u.realname, u.email FROM $t_plugin_table AS e LEFT JOIN $t_user_table AS u ON e.user_id=u.id ORDER BY u.realname";
$t_result = db_query( $t_query );

$t_records = array();
while($t_row = db_fetch_array($t_result))
{
	$t_records[$t_row['user_id']]['realname'] = $t_row['realname'];
	$t_records[$t_row['user_id']]['username'] = $t_row['username'];
	$t_records[$t_row['user_id']]['email'] = $t_row['email'];
	$t_records[$t_row['user_id']]['emails'][$t_row['id']] = $t_row['extra_email'];

}

?>
<div class="col-md-12 col-xs-12">
<div class="space-10"></div>
<form method="post" action="<?php echo plugin_page( 'add_email.php' ); ?>">
Add Alternate Email for:&nbsp;
<select name="f_user_id">
<option value="0">Select User</option>
<?php print_user_option_list(0) ?>
</select>
<input type="text" size="25" maxlength="255" name="f_email" placeholder="Enter Email Address (Required)" required>
   <input type="submit" name="f_save" value="<?php echo plugin_lang_get( 'save') ?>">
</form>

<div class="table-responsive">
<table class="table table-bordered table-condensed table-hover table-striped">
<tr class="row-category">
<td class="category">Real Name</td>
<td class="category">Username</td>
<td class="category">Primary Email</td>
<td class="category">Additional Email</td>
</tr>
<?php
foreach ($t_records as $t_record) 
{
    print "<tr ".helper_alternate_class()." >";
    print "<td>{$t_record['realname']}</td>";
    print "<td>{$t_record['username']}</td>";
    print "<td>{$t_record['email']}</td>";
    print "<td>";
	foreach($t_record['emails'] as $id => $email) {
		print $email."&nbsp;";
		print_link( plugin_page( 'del_email.php' ) . "&id={$id}", '<i class="fa fa-trash-o"></i>' );
		print "<br>";
	}
    print "</td>";
    print "</tr>";

}  # end for loop
?>
</table>
<br>
</div> <!-- table-responsive -->
</div> <!-- col-md -->
<?php
//html_page_bottom1();
layout_page_end();
