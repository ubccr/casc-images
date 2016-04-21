<?php

/* There's no unique ID on the images table so we can't get at 
   an individual image easily. Boo to that. For now we'll use the
   member_id + date_uploaded. Consider changing the database design!!!
*/

function notfound() {
    header('Content-Type: image/png');
    echo @file_get_contents('images/image_not_found.png');
    exit;
}

$types = array(
    'image/tiff' => 'tiff',
    'image/jpeg' => 'jpg',
    'image/pjpeg' => 'jpg',
    'image/png' => 'png',
    'image/x-png' => 'png'
);

$member_id  = isset($_GET['i']) ? $_GET['i'] : null;
$date_uploaded  = isset($_GET['d']) ? $_GET['d'] : null;
$is_thumb  = isset($_GET['t']) ? true : false;

if(!is_numeric($member_id) || !is_numeric($date_uploaded)) {
    notfound();
}

$config = parse_ini_file("../config/casc.ini", true);
$dbHost = $config['database']['host'];
$dbName = $config['database']['name'];
$dbUser = $config['database']['user'];
$dbPasswd = $config['database']['password'];

$dbh = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPasswd);
$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

$query = "
select 
    member_id,
    unix_timestamp(date_uploaded) uploaded,
    imagetype,
    image
from 
    images
where 
    member_id = ? and unix_timestamp(date_uploaded) = ?
";

try {
  $sth = $dbh->prepare($query);
  $sth->execute(array($member_id, $date_uploaded));
} catch (PDOException $e) {
  $msg = "Query error in {$e->getFile()}:{$e->getLine()} {$e->getMessage()}";
  exit("<pre>$msg</pre>");
}

$row = $sth->fetch(PDO::FETCH_ASSOC);

if(!$row) {
    notfound();
}

$base = 'images/';
$base .= $is_thumb ? '180x' : '600x';
$name = "${member_id}-${date_uploaded}";
$path = "${base}/${name}.png";
$type = $types[$row['imagetype']];

if(file_exists($path)) {
    header('Content-Type: image/png');
    echo @file_get_contents($path);
    exit;
}

$raw = "images/raw/${name}.${type}";
$thumb = "images/180x/${name}.png";
$full = "images/600x/${name}.png";
file_put_contents($raw, $row['image']);

# This can cause the machine to swap
#exec("convert -resize 180x180 $raw $thumb");
#exec("convert -resize 600x $raw $full");

if(file_exists($path)) {
    header('Content-Type: image/png');
    echo @file_get_contents($path);
    exit;
}

notfound();

?>
