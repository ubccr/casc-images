<?php

$response = NULL;
$imageDirectory = 'images/current';
$dumpScript = '/var/www/scripts/dump_raw_images.php';
$logFile = '/tmp/casc_image_dump.out';

$mimeTypeToExtension = array(
    'image/tiff' => 'tiff',
    'image/jpeg' => 'jpg',
    'image/pjpeg' => 'jpg',
    'image/png' => 'png',
    'image/gif' => 'gif',
    'image/x-png' => 'png',
    'image/x-eps' => 'eps'
);

// --------------------------------------------------------------------------------
// Handle errors

if ( ! array_key_exists('imagefile', $_FILES) ||
     UPLOAD_ERR_NO_FILE == $_FILES['imagefile']['error'] ) {
    $response = array(
        'success' => false,
        'msg'     => "File not provided"
    );
}
elseif ( UPLOAD_ERR_INI_SIZE == $_FILES['imagefile']['error'] ) {
    $response = array(
        'success' => false,
        'msg'     => "Image exceeded maximim size of " . ini_get('upload_max_filesize')
    );
}
elseif ( 0 !== strpos($_FILES['imagefile']['type'], "image") ) {
    $response = array(
        'success' => false,
        'msg'     => "Invalid image type: '" . $_FILES['imagefile']['type'] . "'"
    );
}

if ( NULL !== $response ) {
    print json_encode($response);
    exit();
}

// --------------------------------------------------------------------------------
// Save the data

$memberId = $_POST['casc_member_id'];
$description = $_POST['description'];
$contactName = $_POST['name'];
$contactPhone = "(" . $_POST['phone_ac'] . ") " . $_POST['phone_3'] . "-" . $_POST['phone_4']
    . ( isset($_POST['phone_ext']) && ! empty($_POST['phone_ext']) ? " x" . $_POST['phone_ext'] : "" );
$contactEmail = $_POST['email'];
$researcherName = $_POST['researcher'];
$researcherPhone = "(" . $_POST['r_phone_ac'] . ") " . $_POST['r_phone_3'] . "-" . $_POST['r_phone_4']
    . ( isset($_POST['r_phone_ext']) && ! empty($_POST['r_phone_ext']) ? " x" . $_POST['r_phone_ext'] : "" );
$researcherEmail = $_POST['r_email'];
// Add credit information for researcher institution: just text field
// JMS, April 2014
$researcherInstitution = ( isset($_POST['r_institution']) ? $_POST['r_institution'] : NULL );
$researcherAddress = ( isset($_POST['r_address']) ? $_POST['r_address'] : NULL );

// Add credit information for visualization and computation contributors and institutions: just text fields
// JMS, April 2014
$vizName = ( isset($_POST['viz_name']) ? $_POST['viz_name'] : NULL );
$vizInstitution = ( isset($_POST['viz_institution']) ? $_POST['viz_institution'] : NULL );

$computeName = ( isset($_POST['compute_name']) ? $_POST['compute_name'] : NULL );
$computeSystem = ( isset($_POST['compute_system']) ? $_POST['compute_system'] : NULL );
$computeInstitution = ( isset($_POST['compute_institution']) ? $_POST['compute_institution'] : NULL );
$dateUploaded = exec("date +%s");

// --------------------------------------------------------------------------------
// Determine image size and mime type

$imageInfo = getimagesize($_FILES['imagefile']['tmp_name']);
$imageResolution = $imageInfo[0] . 'x' . $imageInfo[1];
$imageMimeType = ( array_key_exists('mime', $imageInfo) && ! empty($imageInfo['mime'])
                   ? $imageInfo['mime']
                   : $_FILES['imagefile']['type']);

if ( array_key_exists($imageMimeType, $mimeTypeToExtension) ) {
    $imageExt = $mimeTypeToExtension[$imageMimeType];
} else {
    $response = array(
        'success' => false,
        'msg'     => "Unsupported image type: '$imageMimeType'"
    );
    print json_encode($response);
    exit();
}

$config = parse_ini_file("../config/casc.ini", true);
$dbHost = $config['database']['host'];
$dbName = $config['database']['name'];
$dbUser = $config['database']['user'];
$dbPasswd = $config['database']['password'];

$dbh = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPasswd);
$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

$sql =
    "INSERT INTO images (member_id, description, name, phone, email,
   researcher_name, researcher_phone, researcher_email, researcher_institution,
   researcher_address, viz_name, viz_institution,
   compute_name, compute_system, compute_institution,
   date_uploaded, imagetype, image_resolution, image_ext, image)
   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, from_unixtime(?), ?, ?, ?, ?)";

try
{
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(1, $memberId);
    $stmt->bindParam(2, $description);
    $stmt->bindParam(3, $contactName);
    $stmt->bindParam(4, $contactPhone);
    $stmt->bindParam(5, $contactEmail);
    $stmt->bindParam(6, $researcherName);
    $stmt->bindParam(7, $researcherPhone);
    $stmt->bindParam(8, $researcherEmail);
    $stmt->bindParam(9, $researcherInstitution);
    $stmt->bindParam(10, $researcherAddress);
    $stmt->bindParam(11, $vizName);
    $stmt->bindParam(12, $vizInstitution);
    $stmt->bindParam(13, $computeName);
    $stmt->bindParam(14, $computeSystem);
    $stmt->bindParam(15, $computeInstitution);
    $stmt->bindParam(16, $dateUploaded);
    $stmt->bindParam(17, $imageMimeType);
    $stmt->bindParam(18, $imageResolution);
    $stmt->bindParam(19, $imageExt);
    $fp = fopen($_FILES['imagefile']['tmp_name'], 'rb');
    $stmt->bindParam(20, $fp, PDO::PARAM_LOB);

    $dbh->beginTransaction();
    $stmt->execute();
    $imageId = $dbh->lastInsertId();
    $dbh->commit();
    $result = array('success' => true,
                    'msg'     => "Uploaded '" . basename($_FILES['imagefile']['name']) . "' (" .
                    number_format($_FILES['imagefile']['size'], 0) . " bytes)");

    // Call the dump image script with the particulars to create the image files for display.
    $options="-i $imageId -d $imageDirectory";
    $command = "/usr/bin/php {$dumpScript} {$options} >> {$logFile} 2>&1 &";
    exec($command);
}
catch ( PDOException $e )
{
    $result = array(
        'success' => false,
        'msg'     => "Error uploading '" . basename($_FILES['imagefile']['name'])
        . ", size=" . filesize($_FILES['imagefile']['tmp_name']) . "': " . $e->getMessage()
    );
}

print json_encode($result);

?>
