<?php

// Dump uploaded images from the database and convert into the sizes that we will need to display
// them using Andrew's image viewer.  Images are stored in the casc database in the "images" table
// for the current year and manually renamed to "<year>_images" tables at the start of a new year
// (e.g., "2013_images").  By default the current "images" table is used but you can specify an
// alternate year - "2011" for "2011_images" for example.
//
// By default images will be processed in an "images" directory local to the working directory
// unless another directory is specified.  Raw images will be dumped to "images/raw" and converted
// images will be placed into "images/180x" (preview) and "images/600x" (lightbox viewer) for use
// with the image viewer page.  Note that currently images for all years are placed in to the same
// images directory.
//
// NOTE: The current list.php page will display images for the current year. For archival purposes a
// new image view page will need to be created.  Copy the previous year image view page (e.g.,
// 2012_list.php) and change the database table to the correct one for the current year.  The table
// may need to be copied from the default "images" first.

$options = array("h"  => "help",
                 // Timestamp
                 "t:" => 'timestamp:',
                 // Casc member id
                 "m:" => 'member:',
                 // Optional image directory
                 "i:" => 'image-dir:',
                 // Optional year
                 "y:" => 'year:');

// Dump a single image rather than an entire year?
$single_image = false;

// CASC member for single images
$member = null;

// Image timestamp for single images
$timestamp = null;

// current year unless specified
$year = "";

// Base directory for exported images: must be writable by apache
$imageDir = "./images";

// By default, process entire year's images
$single_image = false;

// Supported image types
$types = array('image/tiff' => 'tiff',
               'image/jpeg' => 'jpg',
               'image/pjpeg' => 'jpg',
               'image/png' => 'png',
               'image/gif' => 'gif',
               'image/x-png' => 'png',
               'image/x-eps' => 'eps');

$args = getopt(implode("", array_keys($options)));  // , $options);
foreach ( $args as $arg => $value )
{
  switch ($arg)
  {
  case 'i':
  case 'image-dir':
    $imageDir = trim($value);
    break;

  case 'y':
  case 'year':
    $year = trim($value);
    break;

  case 't':
  case 'timestamp':
    $timestamp = trim($value);
    $single_image = true;
    break;

  case 'm':
  case 'member':
    $member = trim($value);
    $single_image = true;
    break;

  case 'h':
  case 'help':
    usage_and_exit();
    break;

  default:
    break;
  }
}  // foreach ( $args as $arg => $value )

print("Timestamp: $timestamp (" . date("Y-m-d H:m:s", $timestamp) . ")\n");

// If a single image to upload, exit unless
// both member and timestamp were received.
if (true == $single_image) {
  if (null == $timestamp || null == $member ){
    usage_and_exit();
  }
}

// Create image directories if they don't already exist

$rawImageDir = $imageDir . "/raw";
$thumbImageDir = $imageDir . "/180x";
$fullImageDir = $imageDir . "/600x";
$tableName = ( "" != $year ? "${year}_" : "" ) . "images";

if ( ! is_dir($imageDir) ) mkdir($imageDir, 0755);
if ( ! is_dir($rawImageDir) ) mkdir($rawImageDir, 0755);
if ( ! is_dir($thumbImageDir) ) mkdir($thumbImageDir, 0755);
if ( ! is_dir($fullImageDir) ) mkdir($fullImageDir, 0755);

print "Using image directory '$imageDir' (thumbnails in '$thumbImageDir', full images in '$fullImageDir')\n";
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
    image_id, member_id, unix_timestamp(date_uploaded) as uploaded,
    imagetype, image
from $tableName";

if (true == $single_image) {
    $query .= " where member_id = $member and unix_timestamp(date_uploaded) = $timestamp";
}

try {
  $sth = $dbh->prepare($query);
  $sth->execute();
} catch (PDOException $e) {
  $msg = "Query error in {$e->getFile()}:{$e->getLine()} {$e->getMessage()}";
  exit($msg);
}

print "\n";
while ($row = $sth->fetch(PDO::FETCH_ASSOC))
{
  $name = $row['member_id'] . "-" . $row['uploaded'];
  $type = $types[$row['imagetype']];
  $raw = "{$rawImageDir}/${name}.${type}";
  print "Save image from database '$raw'\n";
  if ( FALSE !== file_put_contents($raw, $row['image']) )
  {
    $thumbnail = $thumbImageDir . "/${name}.png";
    $exe = "convert -resize 180x180 $raw $thumbnail && chmod 644 $thumbnail";
    print "  $exe\n";
    system($exe);

    $full = $fullImageDir . "/${name}.png";
    $exe = "convert -resize 600x $raw $full && chmod 644 $full";
    print "  $exe\n";
    system($exe);
  }
}

print("\n");

// --------------------------------------------------------------------------------


function usage_and_exit($msg = NULL)
{
  if ( NULL !== $msg ) { fwrite(STDERR, "\n$msg\n\n"); }

  $str = "Usage: " . $_SERVER['argv'][0] . " \\\n" .
    "[-h | --help] Display this help \n" .
    "[-i | --image-dir] Base image directory (default: " . $GLOBALS['imageDir'] . ")\n" .
    "[-y | --year] Optional year if not the current one\n" . 
    "[-m | --member] CASC member institution id, if processing a single image\n" .
    "[-t | --timestamp] UNIX timestamp of image, if processing a single image\n";
  fwrite(STDERR, $str);
  exit(1);
}  // usage_and_exit()
