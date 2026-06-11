<?php
require_once 'includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Collect and Sanitize Basic Data
    $name = mysqli_real_escape_string($con, $_POST['full_name']);
    $mobile = mysqli_real_escape_string($con, $_POST['mobile']);
    $location = mysqli_real_escape_string($con, $_POST['location_text']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $lat = mysqli_real_escape_string($con, $_POST['latitude']);
    $lng = mysqli_real_escape_string($con, $_POST['longitude']);

    // 2. Generate Reference Code
    $ref_code = "FIRE-" . mt_rand(100000, 999999);

    // 3. Handle Media Uploads (Images & Videos)
    $media_paths = [];
    $upload_dir = "uploads/";

    // Check if files were uploaded
    if (!empty($_FILES['photos']['name'][0])) {
        foreach ($_FILES['photos']['name'] as $key => $val) {
            $file_name = $_FILES['photos']['name'][$key];
            $tmp_name = $_FILES['photos']['tmp_name'][$key];
            $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            // Allowed extensions for high-tech reporting
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'webm', 'mov'];

            if (in_array($extension, $allowed)) {
                $new_name = "MEDIA_" . time() . "_" . mt_rand(100, 999) . "." . $extension;
                $target_path = $upload_dir . $new_name;

                if (move_uploaded_file($tmp_name, $target_path)) {
                    $media_paths[] = $new_name;
                }
            }
        }
    }
    
    // Encode the array of paths into a JSON string for the DB
    $json_media = mysqli_real_escape_string($con, json_encode($media_paths));

    /**
     * 4. INSERT QUERY
     * Matching your exact DB structure
     */
    $sql = "INSERT INTO tblfirereport (
        fullName, 
        mobileNumber, 
        location, 
        message, 
        latitude, 
        longitude, 
        photo_paths,
        status
    ) VALUES (
        '$name', 
        '$mobile', 
        '$location', 
        '$description', 
        '$lat', 
        '$lng', 
        '$json_media',
        'Reported'
    )";

    $query = mysqli_query($con, $sql);

    if ($query) {
        echo "<script>alert('EMERGENCY BROADCASTED SUCCESSFULLY. Ref: $ref_code');</script>";
        echo "<script>window.location.href ='reporting.php?success=1&ref=$ref_code'</script>";
    } else {
        $error = mysqli_error($con);
        echo "<script>alert('SYSTEM UPLINK FAILED: $error');</script>";
        // echo "<script>window.location.href ='reporting.php'</script>"; // Uncomment after debugging
    }
}
?>