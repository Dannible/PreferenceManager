PreferenceManager
=================

Example PHP class for MySql PDO connection and Preference management. 

The purpose of this class is to the easily manage preferences. This class gives us many options when adding, updating or inserting preferences.  

For example if we need to get a preference we can get it like this:

<em>$system_prefs->getSystemPref("special_number");</em>
 
But what if "special_number" does not exist?  We can roll getting the value, and setting it, all in one call like this:
 
<em>$system_prefs->getSystemPref("special_number",123456);</em>
 
The above example will get the value for "special_number" if it exist or set the default value of 123456.  The can be helpful on an applications first run.  It will ensure that there is always a value to get when asking for one. 
 
The same can be done when calling updateSystemPref(), this method will not only update the specified value, but if it does not exist it will add the value.  