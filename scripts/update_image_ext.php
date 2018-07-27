<?php

$mimeTypeToExtension = array(
  'image/tiff' => 'tiff',
  'image/jpeg' => 'jpg',
  'image/pjpeg' => 'jpg',
  'image/png' => 'png',
  'image/gif' => 'gif',
  'image/x-png' => 'png',
  'image/x-eps' => 'eps'
);

// Update the image table to set the image extension based on the image on disk.

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

$query = "select image_id, imagetype from $tableName";

try {
  $sth = $dbh->prepare($query);
  $sth->execute();
} catch (PDOException $e) {
  $msg = "Query error in {$e->getFile()}:{$e->getLine()} {$e->getMessage()}";
  exit($msg);
}

print "Found " . $sth->rowCount() . " images\n";

$sql = "UPDATE $tableName SET image_ext=? WHERE image_id=?";
$update = $dbh->prepare($sql);

while ($row = $sth->fetch(PDO::FETCH_ASSOC))
{
  $imageId = $row['image_id'];
  print "Processing image id = $imageId";
  $imageExt = $mimeTypeToExtension[$row['imagetype']];

  print " set extension = $imageExt";

  try {
    $update->bindParam(1, $imageExt);
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
