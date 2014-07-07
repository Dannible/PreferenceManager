<?php
/**
 * Example usage of the system preference class.  
 * 
 * The purpose of this class is to the easily manage preferences.   This class gives us many options when
 * adding, updating or inserting preferences.  
 * 
 * For example if we need to get a preference we can get it like this:
 * 
 * <em>$system_prefs->getSystemPref("special_number");</em>
 *
 * But what if "special_number" does not exist?  We can roll getting the value, and setting it, all in one call
 * like this:
 * 
 *  <em>$system_prefs->getSystemPref("special_number",123456);</em>
 * 
 * The above example will get the value for "special_number" if it exist, or set the default value of 
 * 123456.  The can be helpful on an applications first run.  It will ensure that there is always a value 
 * to get when asking for one. 
 * 
 * The same can be done when calling updateSystemPref(), this method will not only update the
 * specified value, but if it does not exist it will add the value.  
 * 
 *
 * @author     Dan Ward <dpw989@gmail.com>
 * @copyright  2014
 * @version    1.0
 * @since      1.0
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License v3
 */
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

include "includes/class.sysprefs.php";

$system_prefs = new system_prefs();// init preferences class.

$special = $system_prefs->getSystemPref("special_number",123456); //example get value

//POST call to add/update preferences.
if(isset($_POST["addpref"])){
    $system_prefs->updateSystemPref($_POST["pref"],$_POST["value"]);
}

//POST call delete preferences.
if(isset($_POST["delpref"])){
    $system_prefs->deleteSystemPref($_POST["delpref"]);
}
?>

<html>
    <head>
        <title>Preference Manager</title>
        <link rel="stylesheet" href="css/style.css" />
    </head>
    <body>
        <div class="content">
            <h2>Preference Manager</h2>
            <hr>
            <div class="inputblock">
                <form action="#" method="POST">
                    <input type="hidden" id="addpref" name="addpref">
                    <input class="txtinput" placeholder="Preference Name" type="text" id="pref" name="pref" value=""/> <br>
                    <textarea id="value" name="value" cols="35" rows="10"placeholder="Preference Value"></textarea><br> 
                    <input class="btn" type="submit" value="Add Preference">
                </form>
                <pre>
When we call <em>"$special = $system_prefs->getSystemPref("special_number",123456);"</em> a row gets added to the database with a name of "special_number" and a value of "<?php echo $special; ?>."

Next try adding "special_number" to the Preference Name field and a new value in to the Preference Value. Now "specail_number" should have a new value. 

Now try deleting "special_number," next time we load the this page it should recreate "special_number" with it's default value. 
                </pre>
            </div>
            <div class="inputblock">
                <table cellspacing="5" cellpadding="5">
                    <thead>
                        <tr><th>Name</th><th>Value</th><th>&nbsp;</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        $allprefs = $system_prefs->getAllSystemPreferences();
                        foreach ($allprefs as $value) {
                            echo "<tr><td>".$value["pref_name"]."</td><td>".$value["pref_value"]."</td><td><form action='#' method='POST'><input id='delpref' name='delpref' type='hidden' value='".$value["pref_name"]."'><input type='submit' class='btn' value='Delete'></form></td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>