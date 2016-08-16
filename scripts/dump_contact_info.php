<?php

// Dump a file containing image submitter contact information for specified image numbers.
// Note that the image numbers should be stored in the database but they are currently not,
// so there is a workaround in place.

$options = array(
	// Configuration file for database connection parameters
	'c:' => "config-file:",
	'h'  => "help",
	// Image number from the list display page
	'n:' => 'image-number:',
	// Output file
	'o:' => 'output-file:'
);

// Dump a single image rather than an entire year?
$optionValues = array(
	'config-file' => "../config/casc.ini",
	// The default output file
	'output-file' => "php://stdout",
	// List of image numbers to export
	'image-numbers' => array()
);

$args = getopt(implode("", array_keys($options)));  // , $options);
foreach ( $args as $arg => $value ) {
	switch ($arg) {
		case 'c':
		case 'config-file':
			if ( ! is_file($value) ) {
				usage_and_exit("Config file not found: '$value'");
			}
			$optionValues['config-file'];
		break;

		case 'n':
		case 'image-number':
			$value = ( ! is_array($value) ? array($value) : $value );
			$optionValues['image-numbers'] = $value;
		break;

		case 'o':
		case 'output-file':
			$optionValues['output-file'] = trim($value);
		break;

		case 'h':
		case 'help':
			usage_and_exit();
		break;

		default:
		break;
	}
}  // foreach ( $args as $arg => $value )

// Verify arguments

if ( ! is_file($optionValues['config-file']) ) {
	usage_and_exit("Config file not found: '" . $optionValues['config-file'] ."'");
}

if ( 0 == count($optionValues['image-numbers']) ) {
	exit();
}

$config = parse_ini_file($optionValues['config-file'], true);
$dbHost = $config['database']['host'];
$dbName = $config['database']['name'];
$dbUser = $config['database']['user'];
$dbPasswd = $config['database']['password'];

$dbh = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPasswd);
$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

$query = "
select 
    name, phone, email,
    researcher_name, researcher_phone, researcher_email, researcher_institution,
    description
from images
";

try {
  $sth = $dbh->prepare($query);
  $sth->execute();
} catch (PDOException $e) {
  $msg = "Query error in {$e->getFile()}:{$e->getLine()} {$e->getMessage()}";
  exit($msg);
}

$outFd = fopen($optionValues['output-file'], 'w');

fputcsv($outFd, array(
	"image_number",
	"submitter_name",
	"submitter_phone",
	"submitter_email",
	"researcher_name",
	"researcher_phone",
	"researcher_email",
	"researcher_institution",
	"description"
));

$imageNumber = 0;
while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
  $imageNumber++;
  if ( ! in_array($imageNumber, $optionValues['image-numbers']) ) {
    continue;
  }
  array_unshift($row, $imageNumber);
  fputcsv($outFd, $row);
}

fclose($outFd);

// --------------------------------------------------------------------------------


function usage_and_exit($msg = NULL)
{
  if ( NULL !== $msg ) { fwrite(STDERR, "\n$msg\n\n"); }

  $str = "Usage: " . $_SERVER['argv'][0] . " \\\n" .
    "[-h | --help] Display this help \n" .
    "[-c | --config-file] Configuration file for database parameters (default: " . $GLOBALS['optionValues']['config-file'] . ")\n" .
    "[-n | --image-number] One or more image numbers to export\n" .
    "[-o | --output-file] Output file\n\n";
  fwrite(STDERR, $str);
  exit(1);
}  // usage_and_exit()
