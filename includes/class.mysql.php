<?php
/**
 * class.mysql.php
 *
 * Class used to interact with the mysql database.
 * 
 * If the database does not exist it will be created.   
 *
 * @package    class.mysql.php
 * @link       /includes/class.mysql.php
 * @author     Dan Ward <dpw989@gmail.com>
 * @copyright  2014
 * @version    1.0
 * @since      1.0
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License v3
 */

define("SQL_HOST", "localhost");    //replace with your database server. 
define("SQL_USER", "[user]");       //replace with user name of an admin user
define("SQL_PW", "[password]");     //replace with admin user password.
define("DATABASE", "sysprefs");     //name of the database to create. 
define("TABLE", "preferences");     //name of the database to create.

/**
 * MySql Connection Class..
 */
class mysql {

    private $dbconn = null;
    private $host   = SQL_HOST;
    private $user   = SQL_USER;
    private $pass   = SQL_PW;
    private $db     = DATABASE;
    private $table  = TABLE;

    /**
     * Class constructor
     */
    public function __construct() {
        $this->dbconn = $this->dbconnect();
    }

    /**
     * Class destructor
     */
    public function __destruct() {
        $this->close();
    }

    /**
     * Class Sleep
     * @return type
     */
    public function __sleep() {
        return array('host', 'user', 'pass');
    }

    /**
     * Class Wake up
     */
    public function __wakeup() {
        $this->dbconnect();
    }

    /**
     * Connects to Database
     * 
     * @return mySQL connection
     */
    private function dbconnect() {
        $link = new PDO('mysql:host=' . $this->host . ';charset=utf8', $this->user, $this->pass);
        $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $link->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $link->setAttribute(PDO::ATTR_PERSISTENT, true);
        $this->mkDatabase($link);
        $link->exec("USE " . $this->db);
        return $link;
    }

    /**
     * check if database exists, if not create it, then use it. 
     * 
     * @param PDO $link
     */
    private function mkDatabase(&$link) {
        try {

            $link->exec("CREATE DATABASE IF NOT EXISTS `$this->db`;"
                    . " CREATE USER '$this->user'@'localhost' IDENTIFIED BY '$this->pass';"
                    . " GRANT ALL ON `$this->db`.* TO '$this->user'@'localhost';"
                    . " FLUSH PRIVILEGES;");

            $link->exec("USE " . $this->db);

            $link->exec("CREATE TABLE IF NOT EXISTS `".$this->table."` ("
                    . " `int_id` int(11) NOT NULL AUTO_INCREMENT,"
                    . " `pref_name` varchar(50) NOT NULL,"
                    . " `pref_value` text NOT NULL,"
                    . " PRIMARY KEY (`int_id`)"
                    . ") ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
        } catch (PDOException $e) {
            echo ("DB ERROR: " . $e->getMessage());
        }
    }

    /**
     * Get the last error message.
     */
    public function getLastError() {
        return $this->dbconn->errorInfo();
    }

    /**
     * Get the last error number.
     */
    public function getLastErrorNo() {
        return $this->dbconn->errorCode();
    }

    /**
     * Closes the SQL connetion.
     *
     * @return boolean
     */
    public function close() {
        return $this->dbconn = null;
    }

    /**
     * Selects a preference from the database.
     * 
     * If no preference is passed, return everything. 
     * 
     * @param string $pref - name of preference
     * @return array
     */
    public function getSystemPreferences($pref = null) {
        $sql = "SELECT "
                . "pref_name, "
                . "pref_value "
                . "FROM ".$this->table." ";

        if ($pref != null) { //if preference is included, return just this value. 
            $sql .= "WHERE pref_name='$pref'";
        }
        $sql .= ";";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Update Preferences
     * 
     * @param string $pref - name of preference. 
     * @param string $value - value of preference.
     * @return boolean
     */
    public function updateSystemPreferences($pref, $value) {
        $sql = "UPDATE ".$this->table." SET pref_value='$value' "
                . "WHERE pref_name =:prefname;";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->bindParam(':prefname', $pref);
        return $stmt->execute();
    }

    /**
     * insert preference
     * 
     * @param string $pref - name of preference. 
     * @param string $value - value of preference.
     * @return int
     */
    public function insertSystemPreferences($pref, $value) {
        $sql = "INSERT INTO ".$this->table." "
                . "(pref_name, "
                . "pref_value) "
                . "VALUES ( "
                . "'" . $pref . "', "
                . "'" . $value . "'); ";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->execute();
        return $this->dbconn->lastInsertId();
    }

    /**
     * delete Preferences
     * 
     * @param string $pref - name of preference. 
     * @return boolean
     */
    public function deleteSystemPreferences($pref) {
        $stmt = $this->dbconn->prepare("DELETE FROM ".$this->table." WHERE pref_name = ?;");
        $stmt->bindValue(1, "$pref", PDO::PARAM_STR);
        return $stmt->execute();
    }
}