<?php
class MultiEmailPlugin extends MantisPlugin {

    function register() {
        $this->name        = 'MultiEmail';
        $this->description = 'Allow users to report issues from multiple emails';
        $this->version     = '0.2';
        $this->requires    = array(
				'MantisCore' => '>1.2.0',
				'EmailReporting' => '0.9',
			);
        $this->author      = 'Manilal K M';
        $this->contact     = 'manilal@ejyothi.com';
        $this->url         = 'http://www.ejyothi.com';
        $this->page        = 'config';
    }

    /**
     * Default plugin configuration.
     */
    function config() {
        /*return array(
                'merge_issue_threshold' => DEVELOPER,
                );*/
    }

    function init() {
        //plugin_event_hook( 'EVENT_MENU_MAIN', 'mainmenu' );
        //plugin_event_hook( 'EVENT_MENU_ISSUE', 'faqmenu' );
    }

    function mainmenu() {
        return array( '<a href="'. plugin_page( 'faq_menu_page.php' ) . '">' . lang_get( 'menu_mergeissue_link' ) . '</a>' );
    }

    function hooks() {
        $hooks = array(
                'EVENT_ERP_REPORT_BUG_DATA' => 'lookup_email_bug',
                'EVENT_ERP_BUGNOTE_DATA'  => 'lookup_email_note',
                'EVENT_MENU_MANAGE'  => 'multiemail_manage_menu',
                );

        return $hooks;
    }

    function schema() {
        return array(
                array( 'CreateTableSQL', array( plugin_table( 'emails' ), "
                        id I UNSIGNED NOTNULL PRIMARY AUTOINCREMENT,
                        user_id I UNSIGNED NOTNULL,
                        email C(255) NOTNULL
                        " )
                    ),
                array( 'CreateIndexSQL', array( 'idx_me_emails_email', plugin_table( 'emails' ), 'email', array( 'UNIQUE' ) ) ),
                );
    }

    function lookup_email_bug($p_event, $p_bug_data)
    {
        //$p_bug_data->additional_information = 'PROCESSED ISSUE';
        $t_description = $p_bug_data->description;
        // Email Reporting plugin writes the email address as the first line in description.
        $t_firstline = explode('\r\n', $t_description);
        $regex = '<(.*?)>';

        // Need to process the email only if there is a valid email.
        if(preg_match_all ("/".$regex."/is", $t_firstline[0], $matches))
        {
            $t_email = $matches[1][0];
            // TODO Check the validity of email.
            $query = "SELECT user_id FROM ". plugin_table('emails')." WHERE email=" . db_param();
            $t_user_id = db_result( db_query_bound( $query, array( $t_email ), 1 ) );
            if($t_user_id!== FALSE)
            {
                $p_bug_data->reporter_id =  $t_user_id;
                $t_user_name = user_get_field( $t_user_id, 'username' );
                $t_authattemptresult = auth_attempt_script_login( $t_user_name );
                if ( $t_authattemptresult === TRUE )
                {
                    user_update_last_visit( $t_user_id );
                }
            }
        }
        return  $p_bug_data;
    }

    function lookup_email_note($p_event, $p_note, $p_bug_id)
    {
        $t_firstline = explode('\r\n', $p_note);
        $regex = '<(.*?)>';

        // Need to process the email only if there is a valid email.
        if(preg_match_all ("/".$regex."/is", $t_firstline[0], $matches))
        {
            $t_email = $matches[1][0];
            // TODO Check the validity of email.
            $query = "SELECT user_id FROM ". plugin_table('emails')." WHERE email=" . db_param();
            $t_user_id = db_result( db_query_bound( $query, array( $t_email ), 1 ) );
            if($t_user_id!== FALSE)
            {
                $t_user_name = user_get_field( $t_user_id, 'username' );
                $t_authattemptresult = auth_attempt_script_login( $t_user_name );
                if ( $t_authattemptresult === TRUE )
                {
                    user_update_last_visit( $t_user_id );
                }
            }
        }
        return  $p_note;
    }

    function multiemail_manage_menu($p_event)
    {
        return array( '<a href="' . plugin_page( 'manage_email' ) . '">' . plugin_lang_get( 'manage' ) . ' ' . plugin_lang_get( 'plugin_title' ) . '</a>', );
    
    }
}
