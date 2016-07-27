<?php
//require( "faq_api.php" );
//require( "css_faq.php" );
access_ensure_global_level( MANAGER );
html_page_top1();
html_page_top2();
print_manage_menu();
# Select the faq posts

/*
$minimum_level = access_get_global_level();
$t_where_clausole = "view_access <= $minimum_level";
$p_project_id = helper_get_current_project();

if( $p_project_id != 0 ) {
    $t_where_clausole .= " and ((project_id='".$p_project_id."' OR project_id=0)";
	$t_project_ids = project_hierarchy_get_subprojects( $p_project_id );
	foreach ($t_project_ids as $value) {
		$t_where_clausole .= " or project_id='".$value."'";
	}
	$t_where_clausole .= ")";
}
$f_search = $_POST["f_search"];
if( !isset( $f_search ) ) {
	$f_search = "";
	$f_search3 = "";
	$f_search2 = "";
} else {
	$f_search3 = "";
	$f_search2 = "";
    if( $t_where_clausole != "" ){
        $t_where_clausole = $t_where_clausole . " AND ";
	}

	$f_search=trim($f_search);
	$what = " ";
	$pos = strpos($f_search, $what);

	$search_string = $_POST["search_string"];
	if (($pos === false) or (isset( $search_string ))){
		$t_where_clausole = $t_where_clausole . " ( (question LIKE '%".addslashes($f_search)."%')
				OR (answere LIKE '%".addslashes($f_search)."%') ) ";
	} else {
		$pos1 = strpos($f_search, $what, $pos+1);
		if ($pos1 === false) {
			$f_search2 = substr($f_search, $pos);
		} else {
			$len1=$pos1-$pos;
			$f_search2 = substr($f_search, $pos1,$len1);
		}
		$f_search3 = substr($f_search,0, $pos);
		$f_search3=trim($f_search3);
		$f_search2=trim($f_search2);
		$t_where_clausole = $t_where_clausole . " ((question LIKE '%".addslashes($f_search3)."%') and (question LIKE '%".addslashes($f_search2)."%'))
					OR ((answere LIKE '%".addslashes($f_search3)."%') and (answere LIKE '%".addslashes($f_search2)."%')) ";
	}
}
*/

$t_plugin_table =  plugin_table('emails');
$t_user_table = db_get_table( 'mantis_user_table' );

$query = "SELECT e.id, e.user_id, e.email as extra_email, u.username, u.realname, u.email FROM $t_plugin_table AS e LEFT JOIN $t_user_table AS u ON e.user_id=u.id ORDER BY u.realname";

$result = db_query( $query );
$user_count = db_num_rows( $result );
$records = array();
for ($i=0;$i<$user_count;$i++) 
{
	$row = db_fetch_array($result);
	extract( $row);
	//$records[$user_id]['id'] = $id;
	$records[$user_id]['realname'] = $realname;
	$records[$user_id]['username'] = $username;
	$records[$user_id]['email'] = $email;
	$records[$user_id]['emails'][$id] = $extra_email;

}  # end for loop

?>
<p>
<table  class="width100" cellspacing="0" border="0">
<tr>
<td class="category">Real Name</td>
<td class="category">Username</td>
<td class="category">Primary Email</td>
<td class="category">Additional Email</td>
</tr>
<?php
foreach ($records as $record) 
{
    print "<tr ".helper_alternate_class()." >";
    print "<td>{$record['realname']}</td>";
    print "<td>{$record['username']}</td>";
    print "<td>{$record['email']}</td>";
    print "<td>";
	foreach($record['emails'] as $id => $email) {
		print $email."&nbsp;";
		print_bracket_link( plugin_page( 'del_email.php' ) . "&id={$id}", 'Delete' );
		print "<br>";
	}
    print "</td>";
    print "</tr>";

}  # end for loop
?>
</table>
<br>
<form method="post" action="<?php echo plugin_page( 'add_email.php' ); ?>">
<table class="width100" cellspacing="0" border="0">
<tr class="row-category2">
<td class="small-caption" colspan="3">
<?php print plugin_lang_get( 'add_email'); ?>
</td>
<td class="small-caption"></td>
</tr>
<tr>
<td>
<select name="f_user_id">
<option value="0">Select User</option>
<?php print_user_option_list(0) ?>
</select>
</td>
<td class="small-caption">
<input type="text" size="25" maxlength="255" name="f_email" value="<?php echo $f_email; ?>">
<!-- <input  type="checkbox" name="f_preferred" id="f_preferred" > <label for="f_preferred"><?php echo plugin_lang_get( 'preferred' ) ?></label> -->
</td>
<td class="right">
   <input type="submit" name="f_save" value="<?php echo plugin_lang_get( 'save') ?>">
</td>
</form>
</table>

<?php
html_page_bottom1();
