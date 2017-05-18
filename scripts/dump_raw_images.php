<?php
// Dump uploaded images from the database and convert them into the sizes that we will need to
// display hem using the lightbox viewer.  Images are stored in the casc database "images" table
// for the current year and manually renamed to "<year>_images" tables at the start of a new year
// (e.g., "2013_images").  By default the current "images" table is used but you can specify an
// alternate year - "2011" for "2011_images" for example.
//
// By default images will be processed in an "images" directory relative to the working directory
// unless another directory is specified.  Raw images will be dumped to "images/raw" and converted
// images will be placed into "images/180x" (preview) and "images/600x" (lightbox viewer) for use
// with the image viewer page.  Note that currently images for all years are placed in to the same
// images directory.
//
// NOTE: The current list.php page will display images for the current year. For archival purposes a
// new image view page will need to be created.  Copy the previous year image view page (e.g.,
// 2012_list.php) and change the database table to the correct one for the current year.  The table
// may need to be copied from the default "images" first.

$options = array(
    'h'  => "help",
    // Casc member id
    'm:' => 'member-id:',
    // Image id
    'i:' => 'image-id:',
    // Optional image directory
    'd:' => 'image-dir:',
    // Optional year
    'y:' => 'year:'
);

// Image to dump
$imageId = null;

// CASC member for single images
$memberId = null;

// current year unless specified
$year = null;

// Base directory for exported images: must be writable by apache
$imageDir = "./images";

// By default, process entire year's images
$single_image = false;

$args = getopt(implode("", array_keys($options)), $options);

foreach ( $args as $arg => $value )
{
    switch ($arg)
    {
        case 'i':
        case 'image-id':
            $imageId = trim($value);
            $single_image = true;
            break;

        case 'd':
        case 'image-dir':
            $imageDir = trim($value);
            break;

        case 'y':
        case 'year':
            $year = trim($value);
            break;

        case 'm':
        case 'member-id':
            $memberId = trim($value);
            break;

        case 'h':
        case 'help':
            usage_and_exit();
            break;

        default:
            break;
    }
}  // foreach ( $args as $arg => $value )

// Create image directories if they don't already exist

$rawImageDir = $imageDir . "/raw";
$thumbImageDir = $imageDir . "/180x";
$fullImageDir = $imageDir . "/600x";
$tableName = ( null !== $year ? $year . "_" : "" ) . "images";

if ( ! is_dir($imageDir) ) {
    mkdir($imageDir, 0755);
}
if ( ! is_dir($rawImageDir) ) {
    mkdir($rawImageDir, 0755);
}
if ( ! is_dir($thumbImageDir) ) {
    mkdir($thumbImageDir, 0755);
}
if ( ! is_dir($fullImageDir) ) {
    mkdir($fullImageDir, 0755);
}

print "Using image directory '$imageDir' (thumbnails in '$thumbImageDir', full images in '$fullImageDir')\n";
print "Using database table '$tableName'\n";

$config = parse_ini_file("../config/casc.ini", true);
$dbHost = $config['database']['host'];
$dbName = $config['database']['name'];
$dbUser = $config['database']['user'];
$dbPasswd = $config['database']['password'];

$dbh = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPasswd);
$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

$criteria = array();
$parameters = array();

if ( null !== $imageId ) {
    $criteria[] = "image_id = ?";
    $parameters[] = $imageId;
}
if ( null !== $memberId ) {
    $criteria[] = "memberId = ?";
    $parameters[] = $memberId;
}

$query = "
select
    image_id, member_id, unix_timestamp(date_uploaded) as uploaded,
    imagetype, image, image_ext
from $tableName";

if ( 0 != count($criteria) ) {
    $query .= " where " . implode(" and ", $criteria);
}

try {
    $sth = $dbh->prepare($query);
    $sth->execute($parameters);
} catch (PDOException $e) {
    $msg = "Query error in {$e->getFile()}:{$e->getLine()} {$e->getMessage()}";
    exit($msg);
}

print "Processing " . $sth->rowCount() . " images\n";

// Save the file names in a list and convert them later. Otherwise, trying to fork the process
// containing a large result set may fail.

$fileNameList = array();

while ($row = $sth->fetch(PDO::FETCH_ASSOC))
{
    $name = $row['image_id'] . "-" . $row['member_id'] . "-" . $row['uploaded'];
    $ext = $row['image_ext'];
    $rawPath = "{$rawImageDir}/${name}.${ext}";
    print "Save image from database: $rawPath\n";

    if ( false === file_put_contents($rawPath, $row['image']) ) {
        $err = error_get_last();
        print sprintf("  Error saving image '%s': %s", $name, $err['message']) . "\n";
        continue;
    }

    if ( false === @chmod($rawPath, 0644) ) {
        $err = error_get_last();
        print sprintf("  Error changing permissions on '%s': %s", $rawPath, $err['message']) . "\n";
        continue;
    }

    $fileNameList[] = array($name, $rawPath);

}

// Clean up the database results to free memory before we start forking

$sth->closeCursor();
$sth = null;
$dbh = null;

foreach ( $fileNameList as $imageInfo ) {
    list($name, $rawPath) = $imageInfo;
    $thumbnail = $thumbImageDir . "/${name}.png";

    // Note the [0] notation for convert, this selects the first frame and is useful when
    // someone uploads an animated gif.

    $exe = "convert -resize 180x180 {$rawPath}[0] $thumbnail && chmod 644 $thumbnail";
    print "$name: $exe\n";
    system($exe);

    $full = $fullImageDir . "/${name}.png";
    $exe = "convert -resize 600x {$rawPath}[0] $full && chmod 644 $full";
    print "$name: $exe\n";
    system($exe);
}

exit(0);

// --------------------------------------------------------------------------------


function usage_and_exit($msg = NULL)
{
    if ( NULL !== $msg ) { fwrite(STDERR, "\n$msg\n\n"); }

    $str = "Usage: " . $_SERVER['argv'][0] . " \\\n" .
        "[-h | --help] Display this help \n" .
        "[-i | --image-id] Database image identifier\n" .
        "[-d | --image-dir] Base image directory (default: " . $GLOBALS['imageDir'] . ")\n" .
        "[-y | --year] Optional year if not the current one\n" .
        "[-m | --member-id] CASC member institution id, if processing a single image\n";
    fwrite(STDERR, $str);
    exit(1);
}  // usage_and_exit()
