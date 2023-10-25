Dash by Role : Dashboard by role
--

[![Moodle Plugin CI](https://github.com/call-learning/moodle-local_dash_by_role/actions/workflows/ci.yml/badge.svg)](https://github.com/call-learning/moodle-local_dash_by_role/actions/workflows/ci.yml)

The aim of this plugin is to enhance the usual dashboard by helping admin to define general layout for different system role. A system role is a role that can be assigned system wide
(like Manager).
For this to work, the global system config parameter $CFG->forcedefaultmymoodle should be set so dashboard are defined globally instead of per user. 

Usage
--

Once the plugin has been installed, and set up (see initial install and requirements), you can go as an administrator to Appearance > Default Dashboard.
You will see a new drop down menu at the top of the page, that will allow you to select a role (the list of roles is taken from roles that are assignable system wide).

Once you have selected a role, you can add and remove blocks as you would do for the default dashboard page. Each role will have a different set of block, and include
blocks defined when selecting the "All roles" options.

Initial install and requirements
--

This plugin uses two global flags that need to be added into the config.php file.
This means that you have direct access to the Moodle installation. 

* $CFG->forcedefaultmymoodle: this will force the dashboard to be globally defined and then editable through Appearance > Default Dashboard page. Set it to 1.
* $CFG->customscripts: this is used to the local/dash_by_role/customscripts folder

So in summary, add the following to your config.php just before the "require_once(__DIR__ . '/lib/setup.php');" line.

    $CFG->forcedefaultmymoodle=1;
    $CFG->customscripts = __DIR__ .'/local/dash_by_role/customscripts';


Implementation notes
--

For ease of development, we used the custom context which are marked as "unofficial".
See : https://tracker.moodle.org/browse/MDL-20045

So this is not sure current method will be supported in further version (looks that it is still in use in 4.0).