<?php
include("scripts/dbconnect.php");
include("scripts/header_l2.php");

//if the file has been passed correctly
if (isset($_FILES['file'])) {

    $file = $_FILES['file'];

    $name = $file['name'];
    $tmp_name = $file['tmp_name'];


    //Check that the uploaded file is not anytihng other than an image to protect from potential attacks
    if (!getimagesize($tmp_name)) {
        ?>
        <div class="alert alert-warning">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>Sorry, only images can be uploaded which must have a suitable file size</strong>
        </div>
        <?php
    } else {


        //split the string
        $extension = explode('.', $name);
        //convert the extension to lower case
        $extension = strtolower(end($extension));


        //file details
        //encrypt the extension
        $key = md5(uniqid());
        $tmp_file_name = "{$key}.{$extension}";
        //name the path for the photo to be saved to on the server
        $file_path = "../html/TempPhotos/{$tmp_file_name}";


        var_dump($tmp_name);
        var_dump($file_path);

        move_uploaded_file($tmp_name, $file_path);
        //var_dump($photo_name);

        $username = $_SESSION['username'];
        // escape the input, removing everything that could be (html/javascript-) code
        $photo_name = $db->real_escape_string(strip_tags($_POST["photo_name"], ENT_QUOTES));
        $price = $db->real_escape_string(strip_tags($_POST["price"], ENT_QUOTES));
        $meta_data = $db->real_escape_string(strip_tags($_POST["meta_data"], ENT_QUOTES));


        if (!class_exists('S3')) require_once 's3.php';


// AWS access info
        if (!defined('awsAccessKey')) define('awsAccessKey', 'MyKey');
        if (!defined('awsSecretKey')) define('awsSecretKey', 'SecretKey');
        $uploadFile = $file_path; // File to upload, we'll use the S3 class since it exists
        $bucketName = uniqid('photoshare1303'); // Temporary bucket. After upload to this "Temporary bucket" we can move the file to any bucket from s3 control panel.


// Check if our upload file exists
        if (!file_exists($uploadFile) || !is_file($uploadFile))
            exit("\nERROR: No such file: $uploadFile\n\n");

// Check for CURL. CURL is must if not installed please install and try again.
        if (!extension_loaded('curl') && !@dl(PHP_SHLIB_SUFFIX == 'so' ? 'curl.so' : 'php_curl.dll'))
            exit("\nERROR: CURL extension not loaded\n\n");

// Pointless without your keys!
        if (awsAccessKey == 'change-this' || awsSecretKey == 'change-this')
            exit("\nERROR: AWS access information required\n\nPlease edit the following lines in this file:\n\n" .
                "define('awsAccessKey', 'change-me');\ndefine('awsSecretKey', 'change-me');\n\n");

// Instantiate the class
        $s3 = new S3(awsAccessKey, awsSecretKey);



// Create a bucket with public read access
        if ($s3->putBucket($bucketName, S3::ACL_PUBLIC_READ)) {
            //  echo "Created bucket {$bucketName}".PHP_EOL;

            // Put our file (also with public read access)
            if ($s3->putObjectFile($uploadFile, $bucketName, baseName($uploadFile), S3::ACL_PUBLIC_READ)) {
                // echo "S3::putObjectFile(): File copied to {$bucketName}/".baseName($uploadFile).PHP_EOL;


                // Get the contents of our bucket
                $contents = $s3->getBucket($bucketName);
                //  echo "S3::getBucket(): Files in bucket {$bucketName}: ".print_r($contents, 1);


                // Get object info
                $info = $s3->getObjectInfo($bucketName, baseName($uploadFile));
                echo "S3::getObjectInfo(): Info for {$bucketName}/" . baseName($uploadFile) . ': ' . print_r($info, 1);

                $s3Path = "{$bucketName}/" . baseName($uploadFile);
                var_dump($s3Path);


                // Get the access control policy for a bucket:
                $acp = $s3->getAccessControlPolicy($bucketName);
                //  echo "S3::getAccessControlPolicy(): {$bucketName}: ".print_r($acp, 1);

                // Update an access control policy ($acp should be the same as the data returned by S3::getAccessControlPolicy())
                $s3->setAccessControlPolicy($bucketName, '', $acp);
                $acp = $s3->getAccessControlPolicy($bucketName);
                //  echo "S3::getAccessControlPolicy(): {$bucketName}: ".print_r($acp, 1);


                // Enable logging for a bucket:
                $s3->setBucketLogging($bucketName, 'logbucket', 'prefix');

                if (($logging = $s3->getBucketLogging($bucketName)) !== false) {
                    echo "S3::getBucketLogging(): Logging for {$bucketName}: " . print_r($contents, 1);
                } else {
                    echo "S3::getBucketLogging(): Logging for {$bucketName} not enabled\n";
                }
                
                // Delete our file

            } else {
                echo "S3::putObjectFile(): Failed to copy file\n";
            }
        } else {
            echo "S3::putBucket(): Unable to create bucket (it may already exist and/or be owned by someone else)\n";
        }


        if (!empty($price)) {
            $for_sale = 1;

        } else {
            $for_sale = 0;
            $price = 0;
        }

        //Insert the editted comment into the database using a prepared statement to protect from SQL injections
        $stmt = new mysqli_stmt($db, "INSERT INTO photos (username, photo_name, meta_data, for_sale, price, s3_ext) VALUES (?,?,?,?,?,?);");
        $stmt->bind_param("sssiis", $username, $photo_name, $meta_data, $for_sale, $price, $s3Path);
        $stmt->execute();


        $sql = "SELECT id FROM photos WHERE username='$username'";
        $userResult = $db->query($sql);
        while ($row = $userResult->fetch_array()) {
            $photo_id = $row['id'];
        }

        //remove the temporary file from the ubuntu server
        unlink($file_path);

        //take the user to the photo location
     $str = 'location:photo/' . $photo_id;
       header($str);


    }
}
?>

<div class="col-md-4"></div>
<div class="col-md-4">
    <form data-toggle="validator" role="form" action="../uploadPhoto" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="inputPhotoName" class="control-label">Enter a name for the photo</label>
            <input type="text" class="form-control" name="photo_name" placeholder="Photo name" required>
        </div>
        <div class="form-group">
            <label for="inputMetaData" class="control-label">If you wish to add meta data such as where & when the photo was taken please add it below
                </label>
            <input type="text" class="form-control" name="meta_data"  placeholder="">
        </div>
        <div class="form-group">
            <label for="inputPrice" class="control-label">If you wish to sell this photo please enter the number of pounds you wish to sell it for
                below</label>
            <input type="text" class="form-control" name="price"  placeholder="">
        </div>
        <div class="form-group">
            <input type="file" name="file" placeholder="Select image" required>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Upload</button>
        </div>
    </form>
</div>
<div class="col-md-4"></div>


