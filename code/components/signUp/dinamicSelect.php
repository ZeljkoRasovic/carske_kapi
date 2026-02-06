<?php
 require_once __DIR__.'/../default/db.php';

 function getCountries()
 {
  $sql="SELECT countryFlag,countryName FROM countries ORDER BY countryName";

  $stmt=$GLOBALS["pdo"]->prepare($sql);
  $stmt->execute();

  $countries=$stmt->fetchAll();
  return $countries;
 }
 function getCountryCodes()
 {
  $sql="SELECT countryFlag,countryCode FROM countries ORDER BY countryCode;";

  $stmt=$GLOBALS["pdo"]->prepare($sql);
  $stmt->execute();

  $countryCodes=$stmt->fetchAll();
  return $countryCodes;
 }
 if(isset($_GET['countryName']))
 {
  $countryName=$_GET['countryName'];
  $countryName=htmlspecialchars($countryName);
  $sql='SELECT cities.cityName FROM cities INNER JOIN countries ON cities.countryID=countries.countryID WHERE countries.countryName=:countryName ORDER BY cities.cityName;';
  $stmt=$GLOBALS["pdo"]->prepare($sql);
  $stmt->bindParam(":countryName",$countryName);
  $stmt->execute();
  $cities=$stmt->fetchAll();

  $sql='SELECT countryCode FROM countries WHERE countryName=:countryName LIMIT 1';
  $stmt=$GLOBALS["pdo"]->prepare($sql);
  $stmt->bindParam(":countryName",$countryName);
  $stmt->execute();
  $countryCode=$stmt->fetch();

  if($countryCode)
   $countryCode='+'.$countryCode['countryCode'];
  else
   $countryCode=null;

  header('Content-Type:application/json;charset=utf-8');
  echo json_encode(['cities'=>$cities,'countryCode'=>$countryCode],JSON_UNESCAPED_UNICODE);
  exit;
 }
 if(isset($_GET['cityName']))
 {
  $cityName=$_GET['cityName'];

  $sql='SELECT postalCode FROM cities WHERE cityName=:cityName LIMIT 1';
  $stmt=$GLOBALS["pdo"]->prepare($sql);
  $stmt->bindParam(":cityName",$cityName);
  $stmt->execute();
  $result=$stmt->fetch();

  if($result)
   $result=$result['postalCode'];
  else
   $result=null;

  header('Content-Type:application/json;charset=utf-8');
  echo json_encode(['postalCode'=>$result],JSON_UNESCAPED_UNICODE);
  exit;
 }
?>
