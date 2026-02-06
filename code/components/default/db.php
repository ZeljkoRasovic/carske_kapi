<?php
const PARAMS=
[
 'DRIVER'=>'mysql',
 'HOST'=>'localhost',
 'DB'=>'carske_kapi',
 'USER'=>'root',
 'PASSWORD'=>'',
 'CHARSET'=>'utf8mb4'
];

$dsn=PARAMS['DRIVER'].":host=".PARAMS['HOST'].";dbname=".PARAMS['DB'].";charset=".PARAMS['CHARSET'];

$pdoOptions=
[
 PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
 PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
 PDO::ATTR_EMULATE_PREPARES=>false
];

if(!isset($GLOBALS["pdo"]))
{
 try
 {
  $GLOBALS["pdo"]=new PDO($dsn,PARAMS['USER'],PARAMS['PASSWORD'],$pdoOptions);
 }
 catch(\PDOException $e)
 {
  throw new \PDOException($e->getMessage());
 }
}
?>
