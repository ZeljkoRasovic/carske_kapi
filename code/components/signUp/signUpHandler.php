<?php

require __DIR__.'/../libraries/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail=new PHPMailer(true);

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
 $email=isset($_POST["email"]) ? trim($_POST["email"]) : "";
 $password=isset($_POST["password"]) ? trim($_POST["password"]) : "";
 $passwordRepeat=isset($_POST["passwordRepeat"]) ? trim($_POST["passwordRepeat"]) : "";
 $token=md5(rand());

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

  if(invalidEmail($email))
   $errors["invalidEmail"]="Choose a proper email!";

  if(existingEmail($email))
   $errors["emailTaken"]="Email is already taken!";

  if(invalidPassword($password))
   $errors["Invalidpassword"]="Choose a proper password!";

  if(passwordNotMatching($password,$passwordRepeat))
   $errors["passDoNotMatch"]="Passwords do not match!";

  $country=!empty($countrySelect) ? $countrySelect : $countryText;
  $city=!empty($citySelect) ? $citySelect : $cityText;

  if($errors)
  {
   $localNumber=$phone;

   if(strpos($phone,$phoneCountryCode)===0)
    $localNumber=trim(substr($phone,strlen($phoneCountryCode)));

   $_SESSION["errorsAtSignup"]=$errors;
   $_SESSION["signupData"]=
   [
    "firstname"=>$firstname,
    "lastname"=>$lastname,
    "birthdateSwitcher"=>$birthdateSwitcher,
    "birthdate"=>$birthdate,
    "day"=>$day,
    "month"=>$month,
    "year"=>$year,
    "countrySwitcher"=>$countrySwitcher,
    "country"=>$country,
    "countryCode"=>$countryCode,
    "citySwitcher"=>$citySwitcher,
    "city"=>$city,
    "postalCode"=>$postalCode,
    "address"=>$address,
    "phoneSwitcher"=>$phoneSwitcher,
    "phone"=>$localNumber,
    "email"=>$email
   ];
   header("Location: ../../pages/signup.php");
   exit();
  }

  if($birthdateSwitcher==="1")
  {
   $birthdate=sprintf('%04d-%02d-%02d',$year,$month,$day);
  }

  if($countrySwitcher==="1")
   $answer=createUser($firstname,$lastname,$birthdate,$country,$countryCode,$city,$postalCode,$address,$phone,$email,$password,$token,$mail);

  else if($citySwitcher==="1")
   $answer=createUser($firstname,$lastname,$birthdate,$country,'',$city,$postalCode,$address,$phone,$email,$password,$token,$mail);

  else
   $answer=createUser($firstname,$lastname,$birthdate,$country,'',$city,'',$address,$phone,$email,$password,$token,$mail);

  if($answer)
  {
   header("Location: ../../pages/signup.php?signup=pending");
   exit();
  }
  else
  {
   header("Location: ../../pages/signup.php?signup=failedToCreateANewUser");
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
 header("Location: ../../pages/signup.php");
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

 if(empty($firstname) || empty($lastname) || empty($address) || empty($phone) || empty($email) || empty($password) || empty($passwordRepeat))
  $result=true;
 
 return $result;
}

function invalidName($name,$flag)
{
 $result=true;

 if($flag)
 {
  if(!(preg_match("/^[\p{L} \'-]{3,64}$/u",$name)))
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

function invalidEmail($email)
{
 $result=true;
 
 if(!(filter_var($email,FILTER_VALIDATE_EMAIL)))
  $result=true;
 else
  $result=false;

 return $result;
}

function existingEmail($email)
{
 $result=false;

 $sql="SELECT userID FROM users WHERE userEmail=:email LIMIT 1;";

 $stmt=$GLOBALS["pdo"]->prepare($sql);
 $stmt->bindParam(":email",$email);
 $stmt->execute();
 $answer=$stmt->fetch();

 if($answer)
  $result=true;
 else
  $result=false;

 return $result;
}

function invalidPassword($password)
{
 $result=true;
 
 if(!preg_match("/^[\S]{8,64}$/u",$password))
  $result=true;
 else
  $result=false;
 
 return $result;
}

function passwordNotMatching($password,$passwordRepeat)
{
 $result=true;

 if($password!==$passwordRepeat)
  $result=true;
 else
  $result=false;
 
 return $result;
}

function sendVerificationEmail($email,$firstname,$lastname,$token,$mail)
{
 $result=false;
 $subject="Email verification";
 $url="http://127.0.0.1/touristAgency/code/components/signUp/verifyEmail.php?token=";
 $HTMLContent=
  '
   <div style="background:#18191f;">
    <br>
    <div style="background:#3d3f4f;">
     <div style="display:flex;justify-content:center;">
      <h3 style="color:#e9e9e9;padding:1rem;">Hi '.htmlspecialchars($firstname).' '.htmlspecialchars($lastname).', you have signed up.</h3>
     </div>
     <br>
     <div style="display:flex;justify-content:center;">
      <p style="color:#e9e9e9;">Please, verify your email address with the link given below.</p>
     </div>
     <br>
     <div style="display:flex;justify-content:center;">
      <a href="'.$url.$token.'" style="color:gold;">Activation link</a>
     </div>
     <br>
    </div>
    <br>
   </div>
  ';
 $content="Hi ".$firstname." ".$lastname.", you have signed up.\r\nPlease, verify your email address with the link given below.\r\n\r\nActivation link: ".$url.$token;
 try
 {
  $mail->isSMTP();
  $mail->Host='smtp.gmail.com';
  $mail->SMTPAuth=true;
  $mail->SMTPSecure=PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port=587;
  $mail->Username='zeljkorasovicskola@gmail.com';
  $mail->Password='vzps icea dkvm ffkz';
  $mail->Priority=1;
  $mail->CharSet='UTF-8';
  $mail->Subject=$subject;
  $mail->setFrom('zeljkorasovicskola@gmail.com',$firstname." ".$lastname);
  $mail->addAddress($email);
  $mail->isHTML(true);
  $mail->Body=$HTMLContent;
  $mail->AltBody=$content;
  $mail->send();
  $result=true;
  return $result;
 }
 catch(Exception $e)
 {
  $result=false;
  header("Location: ../../pages/signup.php?signup=failedToSendAEmail");
  exit();
 }
}

function createUser($firstname,$lastname,$birthdate,$country,$countryCode,$city,$postalCode,$address,$phone,$email,$password,$token,$mail)
{
 $countryCode=ltrim($countryCode,'+');

 if($countryCode==="" && $postalCode==="")
 {
  $sql='SELECT countryID FROM countries WHERE countryName=:countryName LIMIT 1;';
  $stmt=$GLOBALS["pdo"]->prepare($sql);
  $stmt->bindParam(":countryName",$country);
  $stmt->execute();
  $row=$stmt->fetch();
  $countryID= $row ? $row['countryID']: NULL;

  $sql='SELECT cityID FROM cities WHERE cityName=:cityName LIMIT 1;';
  $stmt=$GLOBALS["pdo"]->prepare($sql);
  $stmt->bindParam(":cityName",$city);
  $stmt->execute();
  $row=$stmt->fetch();
  $cityID= $row ? $row['cityID']: NULL;
 }
 else if($countryCode==="" && $postalCode!=="")
 {
  $sql='SELECT countryID FROM countries WHERE countryName=:countryName LIMIT 1;';
  $stmt=$GLOBALS["pdo"]->prepare($sql);
  $stmt->bindParam(":countryName",$country);
  $stmt->execute();
  $row=$stmt->fetch();
  $countryID= $row ? $row['countryID']: NULL;

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
   $countryID=NULL;

  if(!$cityID)
   $cityID=NULL;
 }

 $sql="INSERT INTO users(userEmail,userPassword,userToken,userFirstname,userLastname,userBirthDate,userPhone,userAddress,countryID,cityID) VALUES (:email,:password,:token,:firstname,:lastname,:birthdate,:phone,:address,:countryID,:cityID);";

 $options=['cost'=>12];
 $hashedPass=password_hash($password,PASSWORD_BCRYPT,$options);

 $stmt=$GLOBALS["pdo"]->prepare($sql);
 $stmt->bindParam(":email",$email);
 $stmt->bindParam(":password",$hashedPass);
 $stmt->bindParam(":token",$token);
 $stmt->bindParam(":firstname",$firstname);
 $stmt->bindParam(":lastname",$lastname);
 $stmt->bindParam(":birthdate",$birthdate);
 $stmt->bindParam(":phone",$phone);
 $stmt->bindParam(":address",$address);
 $stmt->bindParam(":countryID",$countryID);
 $stmt->bindParam(":cityID",$cityID);
 $result=$stmt->execute();

 if($result)
  $userID=$GLOBALS["pdo"]->lastInsertId();

 else
  return false;

 $sql="INSERT INTO profileImages(userID) VALUES (:id)";

 $stmt=$GLOBALS["pdo"]->prepare($sql);
 $stmt->bindParam(":id",$userID);
 $answer=$stmt->execute();

 if($answer)
 {
  $send=sendVerificationEmail($email,$firstname,$lastname,$token,$mail);

  if($send)
   return true;

  else
   return false;
 }
 else
  return false;
}
?>
