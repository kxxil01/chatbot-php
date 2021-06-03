<?php 
$dhost="DB";
$duser="app";
$dpass="komcad2021";
$dname="hankam";

$dhostcrm="172.16.250.10:3310";
$dusercrm="telcrm";
$dpasscrm="rahasia";
$dnamecrm="teleasycrm";
$dportcrm="3310";
 

$con = mysqli_connect($dhost,$duser,$dpass,$dname);
if (mysqli_connect_errno()) {
  tulis_log("gagal konek MySQL: " . mysqli_connect_error());
  exit();
}

$con2 = mysqli_connect($dhostcrm,$dusercrm,$dpasscrm,$dnamecrm,$dportcrm);
if (mysqli_connect_errno()) {
  tulis_log("gagal konek MySQL: " . mysqli_connect_error());
  exit();
}

?>
