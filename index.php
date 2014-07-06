<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

include "includes/class.sysprefs.php";

$system_prefs = new system_prefs();

if(isset($_POST["addpref"])){
    $system_prefs->updateSystemPref($_POST["pref"],$_POST["value"]);
}

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
                    <input class="txtinput" placeholder="Preference Name" maxlength="5" type="text" id="pref" name="pref" value=""/> <br>
                    <textarea id="value" name="value" cols="35" rows="10"placeholder="Preference Value"></textarea><br> 
                    <input class="btn" type="submit" value="Add Preference">
                </form>
                <pre>
help Text!
Goes Here!
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