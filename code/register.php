
<!DOCTYPE html>
<html>
<head>
		<meta charset="utf-8">
		<link href="css/style2.css" rel='stylesheet' type='text/css' />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
		<!--webfonts-->
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text.css'/>
		<!--//webfonts-->
		<style>
.btn {
  background-color: #4CAF50;
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}
</style>
</head>
<body>
	<div class="main">
		<div class="header" >
			<h1>Horizon 2k19</h1>
<?php
require_once('lib/dbolib.php');

require_once('lib/fpdf.php');




$flag=true;
$db=new DBManager;


/***************************************************************************************************/
if(isset($_POST['name'])&&!empty($_POST['name']))
{	$name=$_POST['name'];
    if(preg_match("/[a-zA-Z]$/",$name)!=1)
		$flag=false;
}
else
	$flag=false;



if(isset($_POST['email'])&&!empty($_POST['email']))	
{	$email=$_POST['email'];
    if(preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix",$email)!=1)
		$flag=false;
}
else
	$flag=false;



if(isset($_POST['clgname'])&&!empty($_POST['clgname']))
{	$clgname=$_POST['clgname'];
    //if(preg_match("/[a-zA-Z]$/",$clgname)!=1)
		//$flag=false;
}
else
	$flag=false;



if(isset($_POST['dept'])&&!empty($_POST['dept']))
{	$dept=$_POST['dept'];
    //if(preg_match("/[a-zA-Z]$/",$dept)!=1)
		//$flag=false;
}
else
	$flag=false;



if(isset($_POST['clgroll'])&&!empty($_POST['clgroll']))
{	$clgroll=$_POST['clgroll'];
    if(preg_match("/[0-9]$/",$clgroll)!=1)
		$flag=false;
}
else
	$flag=false;


if(isset($_POST['pno'])&&!empty($_POST['pno']))
{	$pno=$_POST['pno'];
    if(preg_match("/^[6-9][0-9]{9}$/",$pno)!=1)
		$flag=false;
}
else
	$flag=false;



if(isset($_POST['tsize'])&&!empty($_POST['tsize']))
	$tsize=$_POST['tsize'];
else
	$flag=false;


$events=array("Cinekstra","WannaCode","Ignitia","Robotrix","Treasure_Hunt",
              "Web_D","Mech_Quiz","Circuitrix","Scholar","Tech_Mela",
			  "X_posed","Game_thrones","Bohemian_Colour","B_plan","Tech_Quiz",
			  "AutoCad","Andromeda","Brug_it");
$opted=array();
foreach($events as $v)
{
	if(isset($_POST[$v]))
		array_push($opted,$v);
}
/*************************************************************************************************/


if($flag)
{

$db->preParams($name,$email,$clgname,$dept,$clgroll,$pno,$tsize,$opted);
//echo $db->getPNO();

if($db->checkuser())
{
	echo "<h2>Already Registered</h2>";
}
else
{   
    if($db->registerfrm())
	{
		echo "<h2>Registration completed</h2>";
		echo "<h1>Unique ID: HRZN19-".$db->getUID()."</h1>";
		echo "<h1>Do NOT loose this ID. It's for your future reference.</h1>";
	}
    	else
    		echo "<h2>ERROR occured, Please try again</h2>";
    }

}
else
{
	echo "<h2>Wrong or Insufficient credentials</h2>";
	echo "<h1>Please retry</h1>";
	
}

?>
<br>
Please take a Snap of your Unique ID.
<br>
<a class='btn' href='http://bcrechorizon.in/login.html'>Go Back</a>
</div>
		
			
			
		</div>
			<!-----start-copyright---->
   					<div class="copy-right">
						<p> Developed by <a href= "https://www.facebook.com/eviil.lennard">Chandan Gorai</a></p> 
					</div>
				<!-----//end-copyright---->

	
</body>
</html>