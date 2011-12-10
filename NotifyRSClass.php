<?php
/* 
 * Notify Result Class
 * @author Mfawa Alfred Onen <muffycompoqm@gmail.com>
 * @license NotifyIC Specific License
 * @version 1.0
 * @copyright NotifyIC 2010
 * 
 */
// Do not Spit out all sort of jargons
error_reporting(0);


class fetchSMS
{
    /**
     *
     * @dbHost string MySQL database Host
     */
    protected $dbHost;
    /**
     *
     * @dbUser string MySQL database User
     */
    protected $dbUser;
    /**
     *
     * @dbPass string MySQL database Password
     *
     */
    protected $dbPass;
     /**
     *
     * @dbName string MySQL database name
     */
    protected $dbName;
    /**
     *
     * @dbconn string This holds database connection
     */
    protected $dbconn;

    /**
     *
     * @results string Holds the result set of a DB query
     */
    public $results;

    /**
     *
     * @rows int Holds the number of rows from DB
     */
    public $rows;
   
    // IP SMS Gateway Authentication Details (UTIWARE)
    private $username = '';
    private $password = '';
    /**
     * Construct for instantiating Database Connection
     */
    public function  __construct() {
        if ($this->dbconn === NULL){
             $this->_dbconnect();
            } else {
                return $this->dbconn;
            }
    }

    private function _dbconnect() {
    $this->dbHost = "localhost";
    $this->dbUser = "root";
    $this->dbPass = "";
    $this->dbName = "notifykannel";


    $this->dbconn = mysql_connect($this->dbHost, $this->dbUser, $this->dbPass);
        if (!$this->dbconn)
         {
            die('Connection to database failed!');
         } else
         {
            mysql_select_db($this->dbName) or die('Can not select database!');
         }
    }

    /**
     *
     * @param string $querystring This Function will run an SQL query
     */
    public function notify_query($querystring){
        $this->str = $querystring;
        $res=mysql_query($this->str);// or die(mysql_error());
       return $res;
        //return true;
    }

    /**
     *
     * @param string $querystring This Function will fetch the result from a database
     * and return an array
     * @return array
     */
    public function notify_get_db_array($querystring){
        $dbelements = array();
        $this->str = $querystring;
        $this->results = mysql_query($this->str); // or die(mysql_error());

        while ( $dbset = mysql_fetch_assoc($this->results)) {
            $dbelements[] = $dbset;
        }
        return $dbelements;
    }

    public function notify_get_row_count($querystring){
        $this->str = $querystring;
        $this->results = mysql_query($this->str); // or die(mysql_error());
        $this->rows = mysql_num_rows($this->results);// or die(mysql_error());

        return $this->rows;
    }

    public function send_sms_to_ipsms($num, $textmsg){
        $result = '';
        $smsapiurl = 'http://209.173.133.66/smsapi/Send.aspx?UN='.$this->username.'&p='.$this->password.'&SA=NotifyIC&DA='.$num.'&M='.$textmsg;
        $h = @fopen ("$smsapiurl", 'r');
        //$v = strlen($h);
        if ($h) {
            while ($l = @fgets($h, 4096)) {
                $result .= $l;
            }
            fclose ($h);
            return $result;
        } else {
            die ('Could not send SMS, Please check the network connection or switch to another IP SMS provider');
        }

    }

}
