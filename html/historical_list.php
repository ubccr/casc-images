<?php
/*
 * Note: The researcher_institution field was added in 2014
 */

$year = ( isset($_GET['year']) && is_numeric($_GET['year']) ? $_GET['year'] : 2011 );

?>
<html>
<head>
  <title>CASC Image Submissions</title>
  <style type="text/css">
    img, body, html { border: 0 none; }
    body, html { margin: 0; padding: 0}
    #wrapper { margin: 5px auto; width: 1100; border: 0px solid black }
    .desc { font-size: x-small; }
    .thumbnail {
        background-color: #eee;
        border: 1px solid #ccc;
        width: 250px;
        height: 400px;
        float: left;
        margin-bottom: 10px;
        margin-right: 10px;
        padding: 3px 3px 8px;
        text-align: center;
    }
    #outside-page {
        background: url("images/bg_page.jpg") repeat-x scroll left top transparent;
    }
  </style>
  <link rel="stylesheet" href="css/lightbox.css" type="text/css" media="screen" />
  <script type="text/javascript" src="js/lightbox/prototype.js"></script>
  <script type="text/javascript" src="js/lightbox/scriptaculous.js?load=effects,builder"></script>
  <script type="text/javascript" src="js/lightbox/lightbox.js"></script>
</head>
<body>
<div id="outside-page">
<div align="center" id="header"> <a href="http://www.casc.org/"><img src="images/logo.png"/></a></div>

<div align="center" id="wrapper">
<h2><?= $year ?> Image Submissions</h2>
<p><a href="/">Back</a></p>

<?php
        $config = parse_ini_file("../config/casc.ini", true);
$dbHost = $config['database']['host'];
$dbName = $config['database']['name'];
$dbUser = $config['database']['user'];
$dbPasswd = $config['database']['password'];

$dbh = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPasswd);
$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
$year = trim($dbh->quote($year), "'");

$fields = array(
    "i.image_id",
    "m.name as member_name",
    "m.organization as member_org",
    "i.researcher_name",
    "i.member_id",
    "i.description",
    "unix_timestamp(i.date_uploaded) uploaded",
    "i.image_resolution",
    "i.image_ext"
);

if ( $year >= 2014 ) {
    $additionalFields = array(
        "i.researcher_institution",
        "i.viz_name",
        "i.viz_institution",
        "i.compute_name",
        "i.compute_institution"
    );
    $fields = array_merge($fields, $additionalFields);
}

$query = "select " . implode(", ", $fields) . "
from {$year}_images i
join {$year}_members m on i.member_id = m.member_id
order by i.date_uploaded asc
";

try {
    $sth = $dbh->prepare($query);
    $sth->execute();
} catch (PDOException $e) {
    $msg = "Query error in {$e->getFile()}:{$e->getLine()} {$e->getMessage()}";
    exit("<pre>$msg</pre>");
}

while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
    $imageName = $row['image_id'] . '-' . $row['member_id'] . '-' . $row['uploaded']. '.png';
    $rawImageName = $row['image_id'] . '-' . $row['member_id'] . '-' . $row['uploaded']. '.' . $row['image_ext'];
    $thumb = 'images/' . $year . '/180x/' . $imageName;
    $full = 'images/' . $year . '/600x/' . $imageName;
    $cleanedDescription = strtr($row['description'], array("\n" => "", "\r" => ""));
    $description = '<b>Image #' . $row['image_id'] . '</b><br/><br/>'
        . $cleanedDescription . '<br/><br/>'
        . '<a href="download_image.php?year=' . $year . '&name=' . $rawImageName . '">Download Full Resolution Image</a><br/><br/>';
    $displayOrg = $row['member_org'] . ( ! empty($row['member_name']) ? ", " . $row['member_name'] : "" );

    print '<div class="thumbnail">'
        . '<a rel="lightbox[casc]" title="'. htmlentities($description, ENT_COMPAT|ENT_HTML401|ENT_SUBSTITUTE) .'" href="'.$full.'"><img src="'.$thumb.'"/></a>';
    print '<p class="desc"><b>Image #' . $row['image_id'] . '<b> (' . $row['image_resolution'] . ')'
        . '<br/><br/>Researcher: ' . $row['researcher_name'];
    if ( $year >= 2014 ) {
        print '<br/>' . $row['researcher_institution'];
        print '<br/>Visualization: ' . $row['viz_name'] . '<br/>' . $row['viz_institution'];
        print '<br/>Computation: ' . $row['compute_name'] . '<br/>' . $row['compute_institution'] . '<br/>';
    }
    print '</p>';
    print '<p class="desc"><b>' . $displayOrg . '</b>';
    print '</div>';
}
?>

</div>

<div align="center" style="clear: both">
<img src="/images/ccr_logo.png">
<div>
</div>
</body>
</html>
