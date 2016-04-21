<?php
// Download the file
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="casc_submissions.csv"');

$config = parse_ini_file("../config/casc.ini", true);
$dbHost = $config['database']['host'];
$dbName = $config['database']['name'];
$dbUser = $config['database']['user'];
$dbPasswd = $config['database']['password'];

$dbh = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPasswd);
$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

$cols = array(
    'name',
    'organization',
    'city',
    'state',
    'name',
    'phone',
    'email',
    'researcher_name',
    'researcher_phone',
    'researcher_email',
    'researcher_address',
    'date_uploaded',
    'imagetype',
    'description'
);

$types = array(
    'image/tiff' => 'tiff',
    'image/jpeg' => 'jpg',
    'image/pjpeg' => 'jpg',
    'image/png' => 'png',
    'image/x-png' => 'png',
    'image/x-eps' => 'eps'
);

$query = "
select 
    m.member_id,
    m.name,
    m.organization,
    m.city,
    m.state,
    i.member_id,
    i.description,
    i.name,
    i.phone,
    i.email,
    i.researcher_name,
    i.researcher_phone,
    i.researcher_email,
    i.researcher_address,
    i.date_uploaded,
    i.imagetype,
    unix_timestamp(i.date_uploaded) uploaded
from images i
join members m
    on i.member_id = m.member_id
";

$sth = $dbh->prepare($query);
$sth->execute();

$fp = fopen('php://output', 'r');
$header = $cols;
$header[] = 'imageurl';
fputcsv($fp, $header);
while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
    $url = 'http://apps.ccr.buffalo.edu/images/raw/'.$row['member_id'].'-'.$row['uploaded'].'.'.$types[$row['imagetype']];
    $data = array();
    foreach($cols as $c) {
        $data[] = $row[$c];
    }

    $data[] = $url;
    fputcsv($fp, $data);
}

?>
