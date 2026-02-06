<?php

if($_SERVER["REQUEST_METHOD"]==="POST")
{
 $firstname=isset($_POST["firstname"]) ? trim($_POST["firstname"]) : "";
 $lastname=isset($_POST["lastname"]) ? trim($_POST["lastname"]) : "";
 $birthdateSwitcher=isset($_POST["birthdateSwitcher"]) ? trim($_POST["birthdateSwitcher"]) : "";
 $birthdate=isset($_POST["birthdate"]) ? trim($_POST["birthdate"]) : "";
 $day=isset($_POST["day"]) ? trim($_POST["day"]) : "";
 $month=isset($_POST["month"]) ? trim($_POST["month"]) : "";
 $year=isset($_POST["year"]) ? trim($_POST["year"]) : "";
 $countrySwitcher=isset($_POST["countrySwitcher"]) ? trim($_POST["countrySwitcher"]) : "";
 $countrySelect=isset($_POST["countrySelect"]) ? trim($_POST["countrySelect"]) : "";
 $countryText=isset($_POST["countryText"]) ? trim($_POST["countryText"]) : "";
 $countryCode=isset($_POST["countryCode"]) ? trim($_POST["countryCode"]) : "";
 $citySwitcher=isset($_POST["citySwitcher"]) ? trim($_POST["citySwitcher"]) : "";
 $citySelect=isset($_POST["citySelect"]) ? trim($_POST["citySelect"]) : "";
 $cityText=isset($_POST["cityText"]) ? trim($_POST["cityText"]) : "";
 $postalCode=isset($_POST["postalCode"]) ? trim($_POST["postalCode"]) : "";
 $address=isset($_POST["address"]) ? trim($_POST["address"]) : "";
 $phoneSwitcher=isset($_POST["phoneSwitcher"]) ? trim($_POST["phoneSwitcher"]) : "";
 $phoneCountryCode=isset($_POST["phoneCountryCode"]) ? trim($_POST["phoneCountryCode"]) : "";
 $phone=isset($_POST["phone"]) ? trim($_POST["phone"]) : "";

 if(!$phoneSwitcher)
 {
  $phone=$phoneCountryCode.' '.$phone;
 }

 try
 {
  $errors=[];

  require_once __DIR__.'/../default/db.php';
  require_once __DIR__.'/../default/session.php';
 
  if(emptyInputSignup($firstname,$lastname,$birthdateSwitcher,$birthdate,$day,$month,$year,$countrySwitcher,$countrySelect,$countryText,$countryCode,$citySwitcher,$citySelect,$cityText,$postalCode,$address,$phone,$email,$password,$passwordRepeat))
   $errors["emptyInput"]="Fill in all fields!";

  if(invalidName($firstname,0))
   $errors["invalidFirstName"]="Choose a proper first name!";

  if(invalidName($lastname,0))
   $errors["invalidLastName"]="Choose a proper last name!";

  if($birthdateSwitcher)
  {
   if(invalidDateParts($year,$month,$day))
    $errors["invalidBirthdateParts"]="Choose a proper birthday!";
  }
  else
  {
   if(invalidDate($birthdate))
    $errors["invalidBirthdate"]="Choose a proper birthday!";
  }

  if($countrySwitcher==="1")
  {
   if(invalidName($countryText,0))
   $errors["invalidCountry"]="Choose a proper country!";

   if(invalidCountryCode($countryCode))
    $errors["invalidCountryCode"]="Choose a proper country code!";

   if(invalidName($cityText,1))
   $errors["invalidCity"]="Choose a proper city!";

   if(invalidPostalCode($postalCode))
    $errors["invalidPostalCode"]="Choose a proper postal code!";
  }
  elseif($citySwitcher==="1")
  {
   if(invalidName($cityText,1))
   $errors["invalidCity"]="Choose a proper city!";

   if(invalidPostalCode($postalCode))
    $errors["invalidPostalCode"]="Choose a proper postal code!";
  }
  else
  {
   if(invalidName($countrySelect,0))
    $errors["invalidCountrySelect"]="Choose a proper country!";

   if(invalidName($citySelect,1))
    $errors["invalidCitySelect"]="Choose a proper city!";
  }

  if(invalidAddress($address))
   $errors["invalidAddress"]="Choose a proper address!";

  if(invalidPhone($phone))
   $errors["invalidPhone"]="Choose a proper phone number!";

  $country=!empty($countrySelect) ? $countrySelect : $countryText;
  $city=!empty($citySelect) ? $citySelect : $cityText;

  if($errors)
  {
   $localNumber=$phone;

   if(strpos($phone,$phoneCountryCode)===0)
    $localNumber=trim(substr($phone,strlen($phoneCountryCode)));

   $_SESSION["errorsAtProfile"]=$errors;
   header("Location: ../../pages/profile.php");
   exit();

 }

  if($birthdateSwitcher==="1")
  {
   $birthdate=sprintf('%04d-%02d-%02d',$year,$month,$day);
  }

  $id=$_SESSION["id"];

  if($countrySwitcher==="1")
   $answer=changeUser($firstname,$lastname,$birthdate,$country,$countryCode,$city,$postalCode,$address,$phone,$id);

  else if($citySwitcher==="1")
   $answer=changeUser($firstname,$lastname,$birthdate,$country,'',$city,$postalCode,$address,$phone,$id);

  else
   $answer=changeUser($firstname,$lastname,$birthdate,$country,'',$city,'',$address,$phone,$id);

  if($answer)
  {
   header("Location: ../../pages/profile.php?edit=success");
   exit();
  }
  else
  {
   header("Location: ../../pages/profile.php?edit=failedToChangeUser");
   exit();
  }
 }
 catch(PDOException $e)
 {
  exit("Query failed: ".$e->getMessage());
 }
}
else
{
 header("Location: ../../pages/profile.php");
 exit();
}
function emptyInputSignup($firstname,$lastname,$birthdateSwitcher,$birthdate,$day,$month,$year,$countrySwitcher,$countrySelect,$countryText,$countryCode,$citySwitcher,$citySelect,$cityText,$postalCode,$address,$phone,$email,$password,$passwordRepeat)
{
 $result=false;
 
 if($birthdateSwitcher==="1")
 {
  if(empty($day) || empty($month) || empty($year))
   $result=true;
 }
 else
 {
  if(empty($birthdate))
   $result=true;
 }

 if($countrySwitcher==="1")
 {
  if(empty($countryText))
   $result=true;

  if(empty($countryCode))
   $result=true;

  if(empty($cityText))
   $result=true;

  if(empty($postalCode))
   $result=true;
 }
 else if($citySwitcher==="1")
 {
  if(empty($cityText))
   $result=true;

  if(empty($postalCode))
   $result=true;
 }
 else
 {
  if(empty($countrySelect))
   $result=true;

  if(empty($citySelect))
   $result=true;
 }

 if(empty($firstname) || empty($lastname) || empty($address) || empty($phone))
  $result=true;
 
 return $result;
}

function invalidName($name,$flag)
{
 $result=true;

 if($flag)
 {
  if(!(preg_match("/^[\p{L}0-9 .'\-]{3,64}$/u",$name)))
   $result=true;
  else
   $result=false;
 }
 else
 {
  if(!(preg_match("/^[\p{L} \'-]{3,32}$/u",$name)))
   $result=true;
  else
   $result=false;
 }
 return $result;
}

function invalidDate($date)
{
 $format='Y-m-d';
 $dateTimeObject=DateTime::createFromFormat($format,$date);

 if($dateTimeObject && $dateTimeObject->format($format)===$date)
 {
  $result=false;
 }
 else
 {
  $result=true;
 }
 return $result;
}

function invalidDateParts($year,$month,$day)
{
 $result=false;

 $month=(int)$month;
 $day=(int)$day;
 $year=(int)$year;

 if($year<1905 || $year>(date('Y')-18))
    $result=true;

   if($month<1 || $month>12)
    $result=true;

   if($day<1 || $day>31)
    $result=true;

   if($month===2)
   {
    $isLeap=($year%4===0 && $year%100!==0) || ($year%400===0);

    if($isLeap && $day>29)
     $result=true;

    else if(!$isLeap && $day>28)
     $result=true;
   }

   if(($month===4 || $month===6 || $month===9 || $month===11) && $day>30)
    $result=true;

 return $result;
}

function invalidPostalCode($postalCode)
{
 $result=true;

 if($postalCode==="none")
 {
  return false;
 }

 if (!preg_match("/^(none|[A-Z0-9][A-Z0-9\s\-]{1,20})$/i", $postalCode))
  $result=true;
 else
  $result=false;

 return $result;
}

function invalidAddress($address)
{
 $result=true;

 if (!preg_match("/^[\p{L}0-9\s,.'\-\/#]+$/u",$address) || (strlen($address)<=5 || strlen($address)>=128))
  $result=true;
 else
  $result=false;

 return $result;
}

function invalidCountryCode($code)
{
 $result=true;

 if($code==="none")
 {
  return false;
 }

 if(!(preg_match("/^\+(?:\d{1,3})(?:-\d{1,4})?$/",$code)))
  $result=true;
 else
  $result=false;

 return $result;
}

function invalidPhone($phone)
{
 $result=true;

 if(!(preg_match("/^(?:\+?\d{1,3}[-\s]?)?(?:\d[-\s]?){7,15}\d$/",$phone)))
  $result=true;
 else
  $result=false;

 return $result;
}

function changeUser($firstname,$lastname,$birthdate,$country,$countryCode,$city,$postalCode,$address,$phone,$id)
{
 $countryCode=ltrim($countryCode,'+');

 if($countryCode==="" && $postalCode==="")
 {
  $sql='SELECT countryID FROM countries WHERE countryName=:countryName LIMIT 1;';
  $stmt=$GLOBALS["pdo"]->prepare($sql);
  $stmt->bindParam(":countryName",$country);
  $stmt->execute();
  $row=$stmt->fetch();
  $countryID= $row ? $row['countryID']: 0;

  $sql='SELECT cityID FROM cities WHERE cityName=:cityName LIMIT 1;';
  $stmt=$GLOBALS["pdo"]->prepare($sql);
  $stmt->bindParam(":cityName",$city);
  $stmt->execute();
  $row=$stmt->fetch();
  $cityID= $row ? $row['cityID']: 0;
 }
 else if($countryCode==="" && $postalCode!=="")
 {
  $sql='SELECT countryID FROM countries WHERE countryName=:countryName LIMIT 1;';
  $stmt=$GLOBALS["pdo"]->prepare($sql);
  $stmt->bindParam(":countryName",$country);
  $stmt->execute();
  $row=$stmt->fetch();
  $countryID= $row ? $row['countryID']: 0;

  $sql="SELECT cityID FROM cities WHERE cityName=:cityName AND postalCode=:postalCode LIMIT 1;";
  $stmt=$GLOBALS["pdo"]->prepare($sql);
  $stmt->bindParam(":cityName",$city);
  $stmt->bindParam(":postalCode",$postalCode);
  $stmt->execute();
  $existingCity=$stmt->fetch();

  if($existingCity)
  {
   $cityID=$existingCity['cityID'];
  }
  else
  {
   $sql="INSERT INTO cities (cityName, postalCode, countryID) VALUES (:cityName,:postalCode,:countryID)";
   $stmt=$GLOBALS["pdo"]->prepare($sql);
   $stmt->bindParam(":cityName",$city);
   $stmt->bindParam(":postalCode",$postalCode);
   $stmt->bindParam(":countryID",$countryID);
   $stmt->execute();
   $cityID=$GLOBALS["pdo"]->lastInsertId();
  }
 }
 else
 {
  $sql="SELECT countryID FROM countries WHERE countryName=:countryName LIMIT 1;";
  $stmt=$GLOBALS["pdo"]->prepare($sql);
  $stmt->bindParam(":countryName",$country);
  $stmt->execute();
  $existingCountry=$stmt->fetch();

  if($existingCountry)
  {
   $countryID=$existingCountry['countryID'];
  }
  else
  {
   $sql="INSERT INTO countries (countryFlag,countryName,countryCode) VALUES ('🏴‍☠️',:countryName,:countryCode)";
   $stmt=$GLOBALS["pdo"]->prepare($sql);
   $stmt->bindParam(":countryName",$country);
   $stmt->bindParam(":countryCode",$countryCode);
   $stmt->execute();
   $countryID=$GLOBALS["pdo"]->lastInsertId();
  }

  $sql="SELECT cityID FROM cities WHERE cityName=:cityName AND postalCode=:postalCode LIMIT 1;";
  $stmt=$GLOBALS["pdo"]->prepare($sql);
  $stmt->bindParam(":cityName",$city);
  $stmt->bindParam(":postalCode",$postalCode);
  $stmt->execute();
  $existingCity=$stmt->fetch();

  if($existingCity)
  {
   $cityID=$existingCity['cityID'];
  }
  else
  {
   $sql="INSERT INTO cities (cityName, postalCode, countryID) VALUES (:cityName,:postalCode,:countryID)";
   $stmt=$GLOBALS["pdo"]->prepare($sql);
   $stmt->bindParam(":cityName",$city);
   $stmt->bindParam(":postalCode",$postalCode);
   $stmt->bindParam(":countryID",$countryID);
   $stmt->execute();
   $cityID=$GLOBALS["pdo"]->lastInsertId();
  }

  if(!$countryID)
   $countryID=0;

  if(!$cityID)
   $cityID=0;
 }

 $sql="UPDATE users SET userFirstname=:firstname, userLastname=:lastname, userBirthDate=:birthdate, userPhone=:phone, userAddress=:address, countryID=:countryID, cityID=:cityID WHERE userID=:id;";
 
 $stmt=$GLOBALS["pdo"]->prepare($sql);
 $stmt->bindParam(":firstname",$firstname);
 $stmt->bindParam(":lastname",$lastname);
 $stmt->bindParam(":birthdate",$birthdate);
 $stmt->bindParam(":phone",$phone);
 $stmt->bindParam(":address",$address);
 $stmt->bindParam(":countryID",$countryID);
 $stmt->bindParam(":cityID",$cityID);
 $stmt->bindParam(":id",$id);

 if($stmt->execute())
 {
  $result=true;
  $sql='SELECT * FROM users WHERE userID=:id LIMIT 1;';

  $stmt=$GLOBALS["pdo"]->prepare($sql);
  $stmt->bindParam(":id",$id);
  $stmt->execute();
  $result=$stmt->fetch();

  $_SESSION["firstname"]=htmlspecialchars($result["userFirstname"]);
  $_SESSION["lastname"]=htmlspecialchars($result["userLastname"]);
  $_SESSION["birthdate"]=htmlspecialchars($result["userBirthDate"]);
  list($_SESSION["year"],$_SESSION["month"],$_SESSION["day"])=explode('-',$_SESSION["birthdate"]);
  $_SESSION["countryID"]=htmlspecialchars($result["countryID"]);
  $_SESSION["cityID"]=htmlspecialchars($result["cityID"]);
  $_SESSION["address"]=htmlspecialchars($result["userAddress"]);
  $_SESSION["phone"]=htmlspecialchars($result["userPhone"]);
 }
 else
  $result=false;
 
 return $result;
}

