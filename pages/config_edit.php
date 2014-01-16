<?php
auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );
$f_merge_issue_threshold= gpc_get_string( 'merge_issue_threshold', VIEWER );

plugin_config_set( 'merge_issue_threshold', $f_merge_issue_threshold );

print_successful_redirect( plugin_page( 'config',TRUE ) );
