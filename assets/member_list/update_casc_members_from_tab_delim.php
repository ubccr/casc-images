<?php
;
// --------------------------------------------------------------------------------
// Update the member list using a tab-delimited member file
// --------------------------------------------------------------------------------

$filename = NULL;
$skipLines = 1;
$truncateTable = FALSE;

$options = array("h"  => "help",
		 "s:" => "skip:",
                 "f:" => "file:",
                 "t"  => "truncate");

$args = getopt(implode("", array_keys($options)));  // , $options);
foreach ( $args as $arg => $value )
{
  switch ($arg)
  {
  case 'f':
  case 'file':
    $filename = trim($value);
    break;

  case 's':
  case 'skip':
    $skipLines = trim($value);
    break;

  case 'h':
  case 'help':
    usage_and_exit();
    break;

  case 't':
  case 'truncate':
    $truncateTable = TRUE;
    break;

  default:
    break;
  }
}  // foreach ( $args as $arg => $value )

if ( NULL === $filename || ! is_file($filename) ) {
  usage_and_exit("Cannot open input file '$filename'");
}

$lines = file($filename);

if ( 0 == count($lines) ) {
  usage_and_exit("File contains no data: '$filename'");
} else if ( 4 != count(explode("\t", $lines[0])) ) {
  usage_and_exit("Expected 4 columns, found " . count(explode("\t", $lines[0])));
}

// Skip the required number of lines

for ( $i = 0; $i < $skipLines; $i++ ) {
  array_shift($lines);
}

$config = parse_ini_file("../../config/casc.ini", true);
$dbHost = $config['database']['host'];
$dbName = $config['database']['name'];
$dbUser = $config['database']['user'];
$dbPasswd = $config['database']['password'];

$dbh = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPasswd);
$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

$sql = "insert into members (name, organization, city, state) values (:name, :organization, :city, :state)";
$sth = $dbh->prepare($sql);

if ( $truncateTable ) {
  $sql = "TRUNCATE TABLE members";
  $dbh->query($sql);
}

foreach ( $lines as $line )
{

  list($name, $organization, $city, $state) = array_map("trim", explode("\t", $line));
  print "Adding: $name, $organization, $city, $state\n";
  
  $params = array(":name" => $name,
		  ":organization" => ( "" == $organization ? NULL : $organization ),
		  ":city" => $city,
		  ":state" => $state);

  try {
    $sth->execute($params);
  } catch ( PPDOException $e ) {
    print "Error inserting ($organization, $name, $city, $state): " . $e->getMessage() . "\n";
    continue;
  }

}

exit(0);

// --------------------------------------------------------------------------------

function usage_and_exit($msg = NULL)
{
  if ( NULL !== $msg ) { fwrite(STDERR, "\n$msg\n\n"); }

  $str = "Usage: " . $_SERVER['argv'][0] . " \\\n" .
    "[-h | --help] Display this help \n" .
    "[-f | --file] Tab delimited member file\n" .
    "[-s | --skip] Number of lines to skip (default " . $GLOBALS['skipLines'] . ")\n";
  fwrite(STDERR, $str);
  exit(1);
}  // usage_and_exit()

?>
