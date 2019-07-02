<?php
/**
 * Format the list of members for consumption by an ExtJS JsonStore (e.g., an array of objects).
 */

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
    organization,
    name
from members
order by organization ASC, name ASC
";

try {
    $sth = $dbh->prepare($query);
    $sth->execute();
} catch (PDOException $e) {
    exit(sprintf("<pre>Query error in %s:$d %s</pre>", $e->getFile(), $e->getLine(), $e->getMessage()));
}

$results = array();
while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
    $results[] = (object) $row;
}

$retval = array(
    'success' => true,
    'num'     => count($results),
    'data' => $results
);

header("Content-Type", "application/json");
print json_encode($retval);
