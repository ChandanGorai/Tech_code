<?php
class DBManager
{
	
	
	
	private $connection;
	private $name;
    private $email;
	private $clgname;
	private $dept;
	private $clgroll;
	private $pno;
	private $tsize;
	private $event;
    private $uid;
	
	
	
    function __construct()
    {
		
	
		$dbhost="localhost";
	    $dbuser="id8602980_horizon";
	    $dbpass="horizon";
	    $dbname="id8602980_horizon";
		
		$this->connection=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
		$this->event=array();
		
	}
	
	private function sanitizeStrings($str)
	{
	   
	   $str=stripslashes($str);
       $str=htmlentities($str);
	   $str=strip_tags($str);
       
       return $str;	   
	}
	
	public function preParams($name,$email,$clgname,$dept,$clgroll,$pno,$tsize,$event)
	{   

		$name=$this->sanitizeStrings($name);
		$name=$this->connection->real_escape_string($name);
		$this->name=$name;
		
		$email=$this->sanitizeStrings($email);
		$email=$this->connection->real_escape_string($email);
		$this->email=$email;
		
		$clgname=$this->sanitizeStrings($clgname);
		$clgname=$this->connection->real_escape_string($clgname);
		$this->clgname=$clgname;
		
		$dept=$this->sanitizeStrings($dept);
		$dept=$this->connection->real_escape_string($dept);
		$this->dept=$dept;
		
		$clgroll=$this->sanitizeStrings($clgroll);
		$clgroll=$this->connection->real_escape_string($clgroll);
		$this->clgroll=$clgroll;
		
		$pno=$this->sanitizeStrings($pno);
		$pno=$this->connection->real_escape_string($pno);
		$this->pno=$pno;
		
		$tsize=$this->sanitizeStrings($tsize);
		$tsize=$this->connection->real_escape_string($tsize);
		$this->tsize=$tsize;
		
		
		$this->event=array_merge($this->event,$event);

		
	}
	
	public function showParams()
	{
		    echo '<br>Server Data<br>';
			echo $this->name.'<br>';
			echo $this->email.'<br>';
			echo $this->clgname.'<br>';
			echo $this->dept.'<br>';
			echo $this->clgroll.'<br>';
			echo $this->pno.'<br>';
			echo $this->tsize.'<br>';
			print_r($this->event);
			
	}
	
	public function registerfrm()
	{
		  $flag=false;
		  if(($q=$this->connection->query("INSERT INTO studentsdb (name,email,clgname,dept,clgroll,pno,tsize) VALUES('".$this->name."','".$this->email."','".$this->clgname."','".$this->dept."','".$this->clgroll."','".$this->pno."','".$this->tsize."')")))
          {
			 foreach($this->event as $v)
			 {
				 $q=$this->connection->query("UPDATE studentsdb SET ".$v."='Y' WHERE email='".$this->email."'");
			 }
			 $this->setUID();
			 //$this->sendSMS();
			 $flag=true;
		  }
          else
          {
			  echo "Registration Query Error!";
			
		  }
		  
		  return $flag;
	}
	
	
	public function checkuser()
	{
         $flag=true;
		 
	      if($q=$this->connection->query("SELECT uid FROM studentsdb WHERE email='".$this->email."'"))
          {
			 if($q->num_rows<1)
				$flag=false;  
		  }  
          else
          {
			  echo "User Check Error!";

		  } 
		  return $flag;
	}
	
	
	private function genUID()
	{
		$a=chr(rand(65,90)).rand(0,9).chr(rand(65,90)).rand(0,9).chr(rand(65,90)).rand(0,9);
		return $a;
	}
	
	private function setUID()
	{ 
	  for($i=0;$i<6;$i++)
	  {
		$uid=$this->genUID();
		if($q=$this->connection->query("UPDATE studentsdb SET uid='".$uid."' WHERE email='".$this->email."'"))
		{
			$this->uid=$uid;
			break;
		}
	  }
	}
	
	public function getUID()
	{
		return $this->uid;
	}
	
	public function getPNO()
	{
	    return $this->pno;
	}
	
	private function sendSMS()
	{
	    $msg="[BCREC]\r\nWelcome to HORIZON 2K19\r\n".
	         "Dear ".$this->name.",\r\n".
	         "You have been successfully registerd.\r\n".
	         "Your Horizon ID is HRZN19-".$this->uid."\r\n".
	         "KINDLY, submit your registration fee at our help desk for completing your registration process.\r\n".
	         "Thank you for being a part of HORIZON 2K19.\r\n\r\n-";
	    
	    $client = new WAY2SMSClient();
        $client->login('7098948581', 'DAVEbourneway2sms');
        $result = $client->send($this->pno, $msg);
        $client->logout();
        //return $result;
	}

	function __destruct()
	{   
		$this->connection->close();
	}
	
}



class WAY2SMSClient
{

    var $curl;
    var $timeout = 30;
    var $jstoken;
    var $way2smsHost;
    var $refurl;

    /**
     * @param $username
     * @param $password
     * @return bool|string
     */
    function login($username, $password)
    {
        $this->curl = curl_init();
        $uid = urlencode($username);
        $pwd = urlencode($password);

        // Go where the server takes you :P
        curl_setopt($this->curl, CURLOPT_URL, "http://way2sms.com");
        curl_setopt($this->curl, CURLOPT_HEADER, true);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);
        $a = curl_exec($this->curl);
        if (preg_match('#Location: (.*)#', $a, $r))
            $this->way2smsHost = trim($r[1]);

        // Setup for login
        curl_setopt($this->curl, CURLOPT_URL, $this->way2smsHost . "Login1.action");
        curl_setopt($this->curl, CURLOPT_POST, 1);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, "username=" . $uid . "&password=" . $pwd . "&button=Login");
        curl_setopt($this->curl, CURLOPT_COOKIESESSION, 1);
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, "cookie_way2sms");
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->curl, CURLOPT_MAXREDIRS, 20);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36");
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($this->curl, CURLOPT_REFERER, $this->way2smsHost);
        $text = curl_exec($this->curl);

        // Check if any error occured
        if (curl_errno($this->curl))
            return "access error : " . curl_error($this->curl);

        // Check for proper login
        $pos = stripos(curl_getinfo($this->curl, CURLINFO_EFFECTIVE_URL), "main.action");
        if ($pos === "FALSE" || $pos == 0 || $pos == "")
            return "invalid login";

        // Set the home page from where we can send message
        $this->refurl = curl_getinfo($this->curl, CURLINFO_EFFECTIVE_URL);
        /*$newurl = str_replace("ebrdg.action?id=", "main.action?section=s&Token=", $this->refurl);
        curl_setopt($this->curl, CURLOPT_URL, $newurl);*/

        // Extract the token from the URL
        $tokenLocation = strpos($this->refurl, "Token");
        $this->jstoken = substr($this->refurl, $tokenLocation + 6, 37);
        //Go to the homepage
        //$text = curl_exec($this->curl);

        return true;
    }


    /**
     * @param $phone
     * @param $msg
     * @return array
     */
    function send($phone, $msg)
    {
        $result = array();

        // Check the message
        if (trim($msg) == "" || strlen($msg) == 0)
            return "invalid message";

        // Take only the first 140 characters of the message
        $msg = substr($msg, 0, 140);
        // Store the numbers from the string to an array
        $pharr = explode(",", $phone);

        // Send SMS to each number
        foreach ($pharr as $p) {
            // Check the mobile number
            if (strlen($p) != 10 || !is_numeric($p) || strpos($p, ".") != false) {
                $result[] = array('phone' => $p, 'msg' => $msg, 'result' => "invalid number");
                continue;
            }

            // Setup to send SMS
            curl_setopt($this->curl, CURLOPT_URL, $this->way2smsHost . 'smstoss.action');
            curl_setopt($this->curl, CURLOPT_REFERER, curl_getinfo($this->curl, CURLINFO_EFFECTIVE_URL));
            curl_setopt($this->curl, CURLOPT_POST, 1);

            curl_setopt($this->curl, CURLOPT_POSTFIELDS, "ssaction=ss&Token=" . $this->jstoken . "&mobile=" . $p . "&message=" . $msg . "&button=Login");
            $contents = curl_exec($this->curl);

            //Check Message Status
            $pos = strpos($contents, 'Message has been submitted successfully');
            $res = ($pos !== false) ? true : false;
            $result[] = array('phone' => $p, 'msg' => $msg, 'result' => $res);
        }
        return $result;
    }


    /**
     * logout of current session.
     */
    function logout()
    {
        curl_setopt($this->curl, CURLOPT_URL, $this->way2smsHost . "LogOut");
        curl_setopt($this->curl, CURLOPT_REFERER, $this->refurl);
        $text = curl_exec($this->curl);
        curl_close($this->curl);
    }

}


?>
