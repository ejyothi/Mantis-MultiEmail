# Mantis MultiEmail Plugin

This plugin allows user to report issues from multiple email addresses.

IMPORTANT:

The plugin may not work without the latest version of Mantis EmailReporting plugin. Also make sure that you have enabled *mail_save_from* configuration (Write the sender of the email into the issue report/note).

## Prerequisites
* MultiEmail v0.5 and later works with MantisBT v2.0 and later
* MultiEmail v0.4 works only from MantisBT v2.0 to v2.10.x
* MultiEmail v0.3 and earlier works only with MantisBT v1.2 and v1.3

## How to Use the plugin

1. Install the plugin from *Manage Plugins*
2. Go to *Manage* --> *Manage Multiple Email*
3. Add additional email address.

The next time you run EmailReporting plugin it will use the correct user instead of using the fallback user

## TODO

1. Allow users to selet the preferred email address
2. Send the issue created notification to the email address from which the report is sent. Currently, notifications are send only to primary email address (email address in user table).

