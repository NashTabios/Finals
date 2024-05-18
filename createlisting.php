<?php
session_start();

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$listing_name = $listing_price = $listing_desc = "";
$listing_name_err = $listing_price_err = $listing_desc_err = $listing_image_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate listing name
    $input_listing_name = trim($_POST["listing_name"]);
    if (empty($input_listing_name)) {
        $listing_name_err = "Please enter a listing name.";
    } else {
        $listing_name = $input_listing_name;
    }
    
    // Validate listing price
    $input_listing_price = trim($_POST["listing_price"]);
    if (empty($input_listing_price)) {
        $listing_price_err = "Please enter the listing price.";
    } elseif (!ctype_digit($input_listing_price)) {
        $listing_price_err = "Please enter a valid listing price.";
    } else {
        $listing_price = $input_listing_price;
    }

    // Validate listing description
    $input_listing_desc = trim($_POST["listing_desc"]);
    if (empty($input_listing_desc)) {
        $listing_desc_err = "Please enter a listing description.";
    } else {
        $listing_desc = $input_listing_desc;
    }

    // Validate and process listing image
    $targetDirectory = "uploads/";
    $targetFile = $targetDirectory . basename($_FILES["listing_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["listing_image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $listing_image_err = "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check file size
    if ($_FILES["listing_image"]["size"] > 500000) {
        $listing_image_err = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $listing_image_err = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $listing_image_err = "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["listing_image"]["tmp_name"], $targetFile)) {
            // File uploaded successfully, proceed with other data insertion
            $status = "Available"; // Set the status
            $sql = "INSERT INTO listing (listing_name, listing_price, listing_desc, listing_image, user_name, status) VALUES (?, ?, ?, ?, ?, ?)";

            if ($stmt = $mysqli->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("ssssss", $param_listing_name, $param_listing_price, $param_listing_desc, $param_listing_image, $param_user_name, $param_status);

                // Set parameters
                $param_listing_name = $listing_name;
                $param_listing_price = $listing_price;
                $param_listing_desc = $listing_desc;
                $param_listing_image = $targetFile;
                $param_user_name = $_SESSION["user_name"];
                $param_status = $status;

                // Attempt to execute the prepared statement
                if ($stmt->execute()) {
                    // Records created successfully. Redirect to listing page
                    header("location: listing.php");
                    exit();
                } else {
                    echo "Something went wrong. Please try again later.";
                }

                // Close statement
                $stmt->close();
            }
        } else {
            $listing_image_err = "Sorry, there was an error uploading your file.";
        }
    }

    // Close connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Listing</title>
    <link rel="stylesheet" href="createlisting.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
    <div class="row">
            <div class="listing-content mb-3">
                <div class="listing-img">
                    <img class="product_image" src="" alt="Listing Image">
                </div>

                <div class="listing-info">
                <h2 class="title text-center">Create Listing</h2>
                <br>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Item Name</label>
                        <input type="text" name="listing_name" class="form-control <?php echo (!empty($listing_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $listing_name; ?>">
                        <span class="invalid-feedback"><?php echo $listing_name_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label> Price</label>
                        <input type="text" name="listing_price" class="form-control <?php echo (!empty($listing_price_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $listing_price; ?>">
                        <span class="invalid-feedback"><?php echo $listing_price_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label> Description</label>
                        <textarea name="listing_desc" class="form-control <?php echo (!empty($listing_desc_err)) ? 'is-invalid' : ''; ?>"><?php echo $listing_desc; ?></textarea>
                        <span class="invalid-feedback"><?php echo $listing_desc_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Upload Image</label>
                        <input type="file" name="listing_image" class="form-control-file <?php echo (!empty($listing_image_err)) ? 'is-invalid' : ''; ?>">
                        <span class="invalid-feedback"><?php echo $listing_image_err; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Submit" name="submit">
                        <a href="listing.php" class="btn btn-secondary ml-2">Cancel</a>
                    </div>
                </form>
                </div>
            </div>
    </div>
    </div>
</body>
<script>
  const listingImageInput = document.querySelector('input[type="file"]');
  const productImage = document.querySelector('.product_image');

  listingImageInput.addEventListener('change', function(event) {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function(event) {
      productImage.src = event.target.result;
    };

    reader.readAsDataURL(file);
  });
</script>
</html>
