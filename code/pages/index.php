<?php
 $title="Home page";
 $slider=1;
 require __DIR__.'/../components/head/theme.php';
 require __DIR__.'/../components/head/head.php';
/*
 require __DIR__.'/../components/default/db.php';
 require __DIR__.'/../components/libraries/vendor/autoload.php';

 use Detection\MobileDetect;

 $url="index.php?";
 
 $detect=new MobileDetect();
 
 $ip=getIpAddress();

 $os="Unknown";
 $os=stripos($_SERVER['HTTP_USER_AGENT'],"iPod")?"IOS":$os;
 $os=stripos($_SERVER['HTTP_USER_AGENT'],"iPhone")?"IOS":$os;
 $os=stripos($_SERVER['HTTP_USER_AGENT'],"iPad")?"IOS":$os;
 $os=stripos($_SERVER['HTTP_USER_AGENT'],"Android")?"Android":$os;
 $os=stripos($_SERVER['HTTP_USER_AGENT'],"webOS")?"webOS":$os;
 $os=stripos($_SERVER['HTTP_USER_AGENT'],"Windows")?"Windows":$os;
 $os=stripos($_SERVER['HTTP_USER_AGENT'],"OSX")?"OSX":$os;
 $os=stripos($_SERVER['HTTP_USER_AGENT'],"Linux")?"Linux":$os;
 $os=stripos($_SERVER['HTTP_USER_AGENT'],"FreeBSD")?"FreeBSD":$os;
 $os=stripos($_SERVER['HTTP_USER_AGENT'],"OpenBSD")?"OpenBSD":$os;

 $deviceType=($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');

 $ua=$detect->getUserAgent();

 insertDetectData($ip,$os,$deviceType,$ua);

function getIpAddress()
{
 if(!empty($_SERVER['HTTP_CLIENT_IP']))
  $ip = $_SERVER['HTTP_CLIENT_IP'];

 elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
  $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

 else
  $ip = $_SERVER['REMOTE_ADDR'];

 if(!filter_var($ip, FILTER_VALIDATE_IP)) 
  $ip = "unknown";

 return $ip;
}

function insertDetectData($ip_address,$operation_system,$device_type,$http_user_agent)
{
 $handle=curl_init();
 $api="http://ip-api.com/json/";
 curl_setopt($handle,CURLOPT_URL,$api);
 curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
 curl_setopt($handle,CURLOPT_HTTPHEADER,array('Content-Type:application/json'));

 $content=curl_exec($handle);

 $countryID=NULL;
 $cityID=NULL;
 $timezone="";
 $isp="";

 if($content!==false)
 {
  $result=json_decode($content,1);
  $country=$result["country"];
  $city=$result["city"];
  $timezone=$result["timezone"];
  $isp=$result["isp"];
 }

 if($city==="Belgrade")
  $city="Beograd";

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

 $query='INSERT INTO detects(ip_address,operation_system,device_type,countryID,cityID,timeZone,isp,http_user_agent) VALUES (:ip_address,:operation_system,:device_type,:countryID,:cityID,:timeZone,:isp,:http_user_agent);';

 $stmt=$GLOBALS['pdo']->prepare($query);
 $stmt->bindParam(':ip_address',$ip_address,PDO::PARAM_STR);
 $stmt->bindParam(':operation_system',$operation_system,PDO::PARAM_STR);
 $stmt->bindParam(':device_type',$device_type,PDO::PARAM_STR);
 $stmt->bindParam(':countryID',$countryID,PDO::PARAM_INT);
 $stmt->bindParam(':cityID',$cityID,PDO::PARAM_STR);
 $stmt->bindParam(':timeZone',$timezone,PDO::PARAM_STR);
 $stmt->bindParam(':isp',$isp,PDO::PARAM_STR);
 $stmt->bindParam(':http_user_agent',$http_user_agent,PDO::PARAM_STR);
 $stmt->execute();
}
*/
?>
 <body>
 <?php require __DIR__.'/../components/nav/nav.php';?>
  <main class="bg">
   <section class="element_bg">
    <h2>Welcome to Yugo Travel Agency</h2>
    <p>Discover the world with us tailored trips, guided tours, and unforgettable experiences.</p>
   </section>
   <br>
   <section class="element_bg">
    <?php require __DIR__.'/../components/slider/slider.php';?>
   </section>
  </main>
 </body>
</html>
