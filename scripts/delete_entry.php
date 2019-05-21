<?php
// Delete an entry from the database and optionally remove image files from disk that are
// associated with that entry.

$options = array(
    'h'  => "help",
    // Clean the image files on physical storage
    'c' => 'clean-image-files',
    // Base image directory
    'd:' => 'image-dir:',
    // Casc member id
    'i:' => 'image-id:'
);

// Image to dump
$imageId = null;

// CASC member for single images
$deleteImageFiles = false;

// Base directory for images
$baseImageDir = "./images/current";

// Base table name
$tableName = 'images';

// Various image subdirectories
$imageSubDirs = array(
    'raw'       => '/raw',
    'thumbnail' => '/180x',
    'full'      => '/600x'
);

$args = getopt(implode("", array_keys($options)), $options);

foreach ( $args as $arg => $value )
{
    switch ($arg)
    {
        case 'c':
        case 'clean-image-files':
            $deleteImageFiles = true;
            break;

        case 'd':
        case 'image-dir':
            $baseImagerDir = trim($value);
            break;

        case 'i':
        case 'image-id':
            if ( ! is_numeric($value) ) {
                usage_and_exit(sprintf('Image id must be numeric, %s given: %s', get_type($value), $value));
            }
            $imageId = trim($value);
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

if ( null === $imageId ) {
    usage_and_exit("Must provide image id");
}

if ( $deleteImageFiles && ! is_dir($baseImageDir) ) {
    usage_and_exit("Image directory does not exist: $baseImageDir");
}

print "Using base image directory '$baseImageDir'\n";
print "Using database table '$tableName'\n";

$config = parse_ini_file("../config/casc.ini", true);
$dbHost = $config['database']['host'];
$dbName = $config['database']['name'];
$dbUser = $config['database']['user'];
$dbPasswd = $config['database']['password'];

$dbh = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPasswd);
$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

// Select image information that we need to construct the file name

$query = "SELECT image_id, member_id, UNIX_TIMESTAMP(date_uploaded) as ts, image_ext FROM $tableName WHERE image_id = ?";

try {
    $sth = $dbh->prepare($query);
    $sth->execute(array($imageId));
    if ( 0 == $sth->rowCount() ) {
        exit(sprintf('No image found with id = %d', $imageId) . "\n");
    }
    list($imageId, $memberId, $timestamp, $imageExt) = $sth->fetch(PDO::FETCH_NUM);
} catch (PDOException $e) {
    exit(sprintf('Error querying image id %d in %s: line %d %s', $imageId, $e->getFile(), $e->getLine(), $e->getMessage()));
}

// Delete the image from the database

$query = "DELETE FROM $tableName WHERE image_id = ?";

try {
    $sth = $dbh->prepare($query);
    $sth->execute(array($imageId));
} catch (PDOException $e) {
    exit(sprintf('Error deleting image id %d in %s: line %d %s', $imageId, $e->getFile(), $e->getLine(), $e->getMessage()));
}

// Reset the auto-increment id. Note that by setting it to 1 it will be set to the next available id if there is
// already data in the table.

$query = "ALTER TABLE $tableName AUTO_INCREMENT = 1";

try {
    $dbh->query($query);
} catch (PDOException $e) {
    exit(sprintf('Error resetting auto increment value in %s: line %d %s', $e->getFile(), $e->getLine(), $e->getMessage()));
}

// Delete the image from the phyical storage. Note that the raw file retains its uploaded extension but the thumbnails
// and smaller images are converted to .png

$imageFilenames = array(
    'raw'       => sprintf('%d-%d-%d.%s', $imageId, $memberId, $timestamp, $imageExt),
    'thumbnail' => sprintf('%d-%d-%d.%s', $imageId, $memberId, $timestamp, 'png'),
    'full'      => sprintf('%d-%d-%d.%s', $imageId, $memberId, $timestamp, 'png')
);

foreach ( $imageSubDirs as $type => $subdir ) {
    $dir = $baseImageDir . $subdir;
    $file = $dir . '/' . $imageFilenames[$type];
    if ( ! is_dir($dir) ) {
        print "Warning: $dir is not a directory, skipping\n";
    } elseif ( ! is_file($file) ) {
        print "Warning: $file does not exist, skipping\n";
    } else {
        print "Removing $file\n";
        unlink($file);
    }
}

print "Deleted " . $sth->rowCount() . " images\n";

exit(0);

// --------------------------------------------------------------------------------


function usage_and_exit($msg = NULL)
{
    if ( NULL !== $msg ) { fwrite(STDERR, "\n$msg\n\n"); }

    $str = "Usage: " . $_SERVER['argv'][0] . " \\\n" .
        "[-h | --help] Display this help \n" .
        "-i | --image-id Database image identifier\n" .
        "[-d | --image-dir] Base image directory (default: " . $GLOBALS['baseImageDir'] . ")\n" .
        "[-c | --clean-image-files] Remove the physical image files from disk (default: " . ($GLOBALS['deleteImageFiles'] ? "true" : "false" ) . ")\n";
    fwrite(STDERR, $str);
    exit(1);
}  // usage_and_exit()
