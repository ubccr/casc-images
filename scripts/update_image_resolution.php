<?php

// Update the image table to set the image resolution.

$options = array("h"  => "help",
                 // Optional year
                 "y:" => 'year:');

// current year unless specified
$year = null;

$args = getopt(implode("", array_keys($options)));  // , $options);
foreach ( $args as $arg => $value )
{
  switch ($arg)
  {
  case 'y':
  case 'year':
    $year = trim($value);
    break;

  case 'h':
  case 'help':
    usage_and_exit();
    break;

  default:
    break;
  }
}  // foreach ( $args as $arg => $value )

$tableName = ( null != $year ? $year . "_" : "" ) . "images";
print "Using database table '$tableName'\n";

$config = parse_ini_file("../config/casc.ini", true);
$dbHost = $config['database']['host'];
$dbName = $config['database']['name'];
$dbUser = $config['database']['user'];
$dbPasswd = $config['database']['password'];

$dbh = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPasswd);
$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

$query = "
select
    image_id, image
from $tableName";

try {
  $sth = $dbh->prepare($query);
  $sth->execute();
} catch (PDOException $e) {
  $msg = "Query error in {$e->getFile()}:{$e->getLine()} {$e->getMessage()}";
  exit($msg);
}

print "Found " . $sth->rowCount() . " images\n";

$sql = "UPDATE $tableName SET image_resolution=? WHERE image_id=?";
$update = $dbh->prepare($sql);

while ($row = $sth->fetch(PDO::FETCH_ASSOC))
{
  print "Processing image id = " . $row['image_id'];
  $tempFile = tempnam("/tmp", "casc_image_");
  file_put_contents($tempFile, $row['image']);
  $imageInfo = getimagesize($tempFile);
  $imageResolution = $imageInfo[0] . 'x' . $imageInfo[1];
  $imageId = $row['image_id'];
  unlink($tempFile);

  print " set resolution = $imageResolution";

  try {
    $update->bindParam(1, $imageResolution);
    $update->bindParam(2, $imageId);
    $update->execute();
  } catch ( PDOException $e ) {
    exit("Query error in {$e->getFile()}:{$e->getLine()} {$e->getMessage()}");
  }

  print " Done\n";
}

exit(0);

// --------------------------------------------------------------------------------


function usage_and_exit($msg = NULL)
{
  if ( NULL !== $msg ) { fwrite(STDERR, "\n$msg\n\n"); }

  $str = "Usage: " . $_SERVER['argv'][0] . " \\\n" .
    "[-h | --help] Display this help \n" .
    "[-y | --year] Year to update\n";
  fwrite(STDERR, $str);
  exit(1);
}  // usage_and_exit()
