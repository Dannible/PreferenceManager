<?php

/**
 * class.sysprefs.php
 *
 * This class will handle the System Preferences.  
 *
 * @package    class.sysprefs.php
 * @link       /includes/class.sysprefs.php
 * @author     Dan Ward <dpw989@gmail.com>
 * @copyright  2014
 * @version    1.0
 * @since      1.0
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License v3
 */
include_once 'class.mysql.php';

/**
 * 
 * System Preferences Class
 * 
 * Handle all system preferences. 
 * 
 * <b>Example usage:<b>
 *
 * <p>
 * include '../includes/sysprefs.php';
 *
 * $bob = new system_prefs();                                   //init class
 * echo $bob->getSystemPref("name");                     //get preference
 * echo $bob->getSystemPref("uncle", 123);             //get/adds preference with default value.           Returns 123
 * $bob->updateSystemPref("phil", "moooo");           //update or insert with a single method.
 * echo $bob->getSystemPref("phil");                        //get preference with just a string.                Returns “moooo”
 * $bob->deleteSystemPref("phil");                            //even delete a preference
 * <p>
 */
class system_prefs {

    private $mysql = "";

    /**
     * Class Constructor.
     */
    public function __construct() {
        //init MYSQL
        $this->mysql = new mySql();
    }

    /**
     * Get all preferences.
     * 
     * @return Array
     */
    public function getAllSystemPreferences() {
        return $this->mysql->getSystemPreferences();
    }

    /**
     * Get a System Preference.
     * 
     * If a default value is defined and preference does not exist, we add a new preference. 
     * 
     * @param String $pref - preference name
     * @param String $default - default value. 
     * @return String Value.
     */
    public function getSystemPref($pref, $default = null) {
        $valuesys = $this->mysql->getSystemPreferences($pref);    //query for pref
        if ($default != null) {   //check if there is a value for default
            if (count($valuesys) == 0) { //If there is a default value and a pref does not exist. add a new one. 
                $this->updateSystemPref($pref, $default); //insert or update. 
                return $default;  //return the value just inserted. 
            }
        }
        if (empty($valuesys)) {//if pref is not found and we don't have a default value to insert, then retun null. 
            return null;
        } else {
            return $valuesys[0]["pref_value"]; //value found. could be null if nothing exists.
        }
    }

    /**
     * Update or insert a System Preference.
     * 
     * @param string $pref - preference name
     * @param string $value - preference value
     */
    public function updateSystemPref($pref, $value) {
        $valuesys = $this->mysql->getSystemPreferences($pref); //query for preferences
        if (count($valuesys) > 0) {  //if we find the preferences, update it with new value.
            return $this->mysql->updateSystemPreferences($pref, $value);
        } else {  //otherwise insert a new value. 
            return $this->mysql->insertSystemPreferences($pref, $value);
        }
        return false;
    }

    /**
     * Deletes a System Preference.
     * 
     * @param String $pref - preference name.
     * @return boolean
     */
    public function deleteSystemPref($pref) {
        return $this->mysql->deleteSystemPreferences($pref);   //delete
    }
}