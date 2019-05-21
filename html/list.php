<?php
// Set the current year
$year = "2019";
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
<br>
<div align="center" id="wrapper">
<h2>Current Image Submissions (<?= $year ?>)</h2>
<p><a href="/">Back</a></p>

<?php
$config = parse_ini_file("../config/casc.ini", true);
$dbHost = $config['database']['host'];
$dbName = $config['database']['name'];
$dbUser = $config['database']['user'];
$dbPasswd = $config['database']['password'];

$dbh = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPasswd);
$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

$query = "
select
    i.image_id,
    m.name as member_name,
    m.organization as member_org,
    i.researcher_name,
    i.researcher_institution,
    i.viz_name,
    i.viz_institution,
    i.compute_name,
    i.compute_institution,
    i.member_id,
    i.description,
    unix_timestamp(i.date_uploaded) uploaded,
    i.image_resolution,
    i.image_ext
from images i
join members m
    on i.member_id = m.member_id
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
    // grab exported images for display
    $imageName = $row['image_id'] . '-' . $row['member_id'] . '-' . $row['uploaded'].'.png';
    $rawImageName = $row['image_id'] . '-' . $row['member_id'] . '-' . $row['uploaded']. '.' . $row['image_ext'];
    $thumb = "images/current/180x/$imageName";
    $full = "images/current/600x/$imageName";
    $cleanedDescription = strtr($row['description'], array("\n" => "", "\r" => ""));
    $description = '<b>Image #' . $row['image_id'] . '</b><br/><br/>' . $cleanedDescription . '<br/><br/>'
        . '<a href="download_image.php?year=current&name=' . $rawImageName . '">Download Full Resolution Image</a><br/><br/>';

    print '<div class="thumbnail">'
        . '<a rel="lightbox[casc]" title="'.htmlentities($description, ENT_COMPAT|ENT_HTML401|ENT_SUBSTITUTE).'" href="'.$full.'"><img src="'.$thumb.'"/></a>';
    print '<p class="desc"><b>Image #' . $row['image_id'] . '</b> (' . $row['image_resolution'] . ')<br/>'
        . 'Researcher: ' . $row['researcher_name'].'<br/>' . $row['researcher_institution'];

    if (null != $row['viz_name'] || null != $row['viz_institution']) {
        print '<br/>Visualization: ' . $row['viz_name'] . '<br/>' . $row['viz_institution'] . '<br/>';
    }
    if (null != $row['compute_name'] || null != $row['compute_institution']) {
        print '<br/>Computation: ' . $row['compute_name'] . '<br/>' . $row['compute_institution'] . '<br/>';
    }
    print '<p class="desc"><b>' . $row['member_name'] . '</b>';
    if ( ! empty($row['member_org']) ) {
        print ' (' . $row['member_org'] . ')';
    }
    print '</p></div>' . PHP_EOL;
}
?>

</div>


<div align="center" style="clear: both">
<img src="/images/ccr_logo.png">
</div>
</body>
</html>
