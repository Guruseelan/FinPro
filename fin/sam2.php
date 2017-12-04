<?php
$conn = new mysqli('localhost','root','','rc_test');
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
} ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
  body {
      position: relative; 
  }
  .affix {
      top:0;
      width: 100%;
      z-index: 9999 !important;
  }
  .navbar {
      margin-bottom: 0px;

  }

  .affix ~ .container-fluid {
     position: relative;
     top: 50px;
  }
  #section1 {padding-top:50px;color: #fff; background-color: #1E88E5;}
  #section2 {padding-top:50px;color: #fff; background-color: #673ab7}
  #section3 {padding-top:50px;color: #fff; background-color: #F44336;}
   </style>
</head>
<body data-spy="scroll" data-target=".navbar" data-offset="50">


<nav class="navbar navbar-inverse" data-spy="affix" data-offset-top="0">
  <div class="container-fluid">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#">Welcome</a>
    </div>
    <div>
      <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav">
          <li><a href="#section1">Search Stock Name</a></li>
          <li><a href="#section2">Search by date</a></li>
          <li><a href="#section3">Search by Past</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>    


<div id="section1" class="container-fluid">
<div class="row">
<div class="col-sm-push-1 col-sm-4">
<form method="post">
   <h2>Current Stock</h2>
  <div class="form-group">
  <label for="stock_name">StockName:</label>
  <input type="text" class="form-control" name ="stkname" id="stock_name"  placeholder="StockName">
  </div>

  <button type="submit" class="btn btn-default" name="chk">Submit</button>
</form>
</div>


<div class="col-sm-push-1 col-sm-4">
<?php
if(isset($_POST['chk']))
{
$name=$_POST['stkname'];
if (empty($name))
{
echo"enter a valid name No stock to display ";
}
else
{
 $sql ="SELECT `Date`,`Open_Price`, `High_Price`, `Low_Price`,
 `Close_Price` FROM `rc_stk_dt_values` WHERE Stock_Name ='$name' and date=(select max(date) FROM `rc_stk_dt_values` WHERE Stock_Name ='$name') ";
 $result = $conn->query($sql);
if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) 
{
	?><pre><?php
	
echo "Latest Stock Date ".$row['Date']."<br>";

$open= $row['Open_Price'];
echo "Open_Price    ".$open."<br>";
	
$high = $row["High_Price"];
echo "High_Price     ".$high."<br>";
	
$low= $row["Low_Price"];
echo "Low_Price      ".$low."<br>";
	
$close = $row['Close_Price'];
echo "Close_Price    ".$close."<br>";

$change =(($close-$open)/$close)*100;
echo "Percentage Change ".$change."<br>";

if($change==0)
{
	?><span class="glyphicon glyphicon-resize-horizontal">No change</span>
<?php
}
else if($change>0)
{
	?>
	<span class="glyphicon glyphicon-arrow-up"> Upwards</span>
<?php
}
else
{	?>
<span class="glyphicon glyphicon-arrow-down">Downwards</span>
<?php
}
    }
} else {
    echo "No stock";
		?></pre><?php
}
}
}
?></div>
</div><br><br></div>


<div id="section2" class="container-fluid">
<div class="row">
<div class="col-sm-push-1 col-sm-4">

<form method="post">
     <h2>Stock difference by Date</h2>
  <div class="form-group">
  
  <label for="stock_name">Search StockName:</label>
  <input type="text" class="form-control" name ="stkname" id="stock_name"  placeholder="StockName">
  
  <label for="frmdt">Past date:</label>
  <input type="date" name="frmdt" class="form-control" id="frmdt">
  
  <label for="todt">Recent date:</label>
  <input type="date" name="endt" class="form-control" id="todt">
  
  </div>

  <button type="submit" class="btn btn-default" name="dtchk">Submit</button>
</form>
</div>
<div class="col-sm-push-1 col-sm-4">
<?php
if(isset($_POST['dtchk']))
{
$close1=0;
$close2=0;
$name=$_POST['stkname'];
$dt=date_create($_POST['frmdt']);
$fdt=date_format($dt,"Y-m-d");

$dt=date_create($_POST['endt']);
$edt=date_format($dt,"Y-m-d");
//echo $fdt;
if (empty($fdt)|empty($edt)|empty($name))
{
echo"enter a valid name No stock to display ";
}
else
{
	?><pre><?php
 $sql ="SELECT `Close_Price` FROM `rc_stk_dt_values` WHERE Stock_Name ='$name' and date='$fdt'";
 $result = $conn->query($sql);
if ($result->num_rows > 0)
{
while($row = $result->fetch_assoc()) 
{
$close1 =$row['Close_Price'];
}
$sql ="SELECT `Close_Price` FROM `rc_stk_dt_values` WHERE Stock_Name ='$name' and date='$edt'";
$result = $conn->query($sql);
if ($result->num_rows > 0)
{
while($row = $result->fetch_assoc()) 
{
$close2 =$row['Close_Price'];
echo $fdt." Close_Price    ".$close1."<br>";
echo $edt." Close_Price    ".$close2."<br>";
}
$change=(($close2-$close1)/$close1)*100;
echo $change."  Stock ";

if($change==0)
{
	?><span class="glyphicon glyphicon-resize-horizontal"> No change</span>
<?php
}
else if($change>0)
{
	?>
	<span class="glyphicon glyphicon-arrow-up"> Upwards</span>
<?php
}
else
{	?>
<span class="glyphicon glyphicon-arrow-down"> Downwards</span>
<?php
}

}
}
else
{
	 echo "No stock to dispaly";
}
}
?></pre><?php
}
?></div></div><br><br></div>

<div id="section3" class="container-fluid">
<div class="row">
<div class="col-sm-push-1 col-sm-4">

<form method="post">
   <h2>Stock difference by Date/Month/Year</h2>
  <div class="form-group">
  
  <label for="stock_name">StockName:</label>
  <input type="text" class="form-control" name ="stkname" id="stock_name"  placeholder="StockName">

 </div>
  <div class="form-group">
   <label for="mdt">Before in:</label>
   <div class="radio">
    <label><input type="radio" name="optype" value="days" required>Day</label>

  <label><input type="radio" name="optype" value="months">Month</label>

  <label><input type="radio" name="optype" value="years">Year</label>
</div>
  <input type="text" required class="form-control" name ="mdt" id="mdt"  placeholder="before period">

  <button type="submit" class="btn btn-default" name="dchk">Submit</button>
  </div>
</form>
  </div>
<div class="col-sm-push-1 col-sm-4">

<?php
if(isset($_POST['dchk']))
{
$close1=0;
$close2=0;
$ot=$_POST['optype'];
$md=$_POST['mdt'];
$name=$_POST['stkname'];
if (empty($name))
{
	echo"enter a valid name No stock to display ";
}
else
{
	?><pre><?php
	//selecting latest date
	$sql ="SELECT `date`,`Close_Price` FROM `rc_stk_dt_values` WHERE Stock_Name ='$name' and date=(select max(date) FROM `rc_stk_dt_values` WHERE Stock_Name ='$name')";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) 
	{
		while($row = $result->fetch_assoc()) 
		{
			$date1=$row['date'];
			$close1= $row['Close_Price'];
		}
		
		$dt=date_create($date1);
		date_sub($dt,date_interval_create_from_date_string($md." ".$ot));//subtracting
		$date2=date_format($dt,"Y-m-d");
		$sql ="SELECT `date`,`Close_Price` FROM `rc_stk_dt_values` WHERE Stock_Name ='$name' and date='$date2'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{
			while($row = $result->fetch_assoc()) 
			{
				$date2=$row['date'];
				$close2= $row['Close_Price'];
			}
		}
		else//getting min date if not present for tat date
		{
		$sql ="SELECT `date`,`Close_Price` FROM `rc_stk_dt_values` WHERE Stock_Name ='$name' and date=(select min(date) FROM `rc_stk_dt_values` WHERE Stock_Name ='$name')";
		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{
			while($row = $result->fetch_assoc()) 
			{
				$date2=$row['date'];
				$close2 =$row['Close_Price'];
			}
		}
		}
		echo "Current date: ".$date1." Closing: ".$close1."<br>";
		echo "Before date: ".$date2." Closing: ".$close2."<br>";
		$change=(($close1-$close2)/$close2)*100;
		echo $change."  Stock ";

if($change==0)
{
	?><span class="glyphicon glyphicon-resize-horizontal"> No change</span>
<?php
}
else if($change>0)
{
	?>
	<span class="glyphicon glyphicon-arrow-up"> Upwards</span>
<?php
}
else
{	?>
<span class="glyphicon glyphicon-arrow-down"> Downwards</span>
<?php
}

}
else
{
	 echo "No stock to dispaly";
}

?></pre><?php
}
}
?></div></div><br><br>
</div>
</body>
</html>