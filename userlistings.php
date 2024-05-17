<?php
session_start();

// Include config file
require_once "config.php";
include 'navbar2.php';

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Get the username of the currently logged-in user
$user_name = $_SESSION["user_name"];

// Initialize an empty array to store user listings
$user_listings = [];

// Retrieve listings uploaded by the current user from the database
$sql = "SELECT listing_id, listing_name, listing_price, listing_image, status FROM listing WHERE user_name = ?";
if ($stmt = $mysqli->prepare($sql)) {
    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("s", $user_name);

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        // Store result
        $stmt->store_result();

        // Check if user has uploaded any listings
        if ($stmt->num_rows > 0) {
            // Bind result variables
            $stmt->bind_result($listing_id, $listing_name, $listing_price, $listing_image, $status);
            // Fetch result rows into array
            while ($stmt->fetch()) {
                $user_listings[] = array(
                    'listing_id' => $listing_id,
                    'listing_name' => $listing_name,
                    'listing_price' => $listing_price,
                    'listing_image' => $listing_image,
                    'status' => $status
                );
            }
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    $stmt->close();
}

// Check if form data has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form submission is for updating a listing
    if (isset($_POST["edit_listing_name"]) && isset($_POST["edit_listing_price"]) && isset($_POST["edit_listing_status"]) && isset($_POST["listing_id"])) {
        // Get the form data
        $listing_id = $_POST["listing_id"];
        $edit_listing_name = $_POST["edit_listing_name"];
        $edit_listing_price = $_POST["edit_listing_price"];
        $edit_listing_status = $_POST["edit_listing_status"];

        // Check if a new image file has been uploaded
        if (!empty($_FILES["edit_listing_image"]["name"])) {
            // Process the uploaded image file
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["edit_listing_image"]["name"]);
            if (move_uploaded_file($_FILES["edit_listing_image"]["tmp_name"], $target_file)) {
                // Update the listing in the database with the new image file path
                $sql = "UPDATE listing SET listing_name=?, listing_price=?, listing_image=?, status=? WHERE listing_id=? AND user_name=?";
                if ($stmt = $mysqli->prepare($sql)) {
                    // Bind variables to the prepared statement as parameters
                    $stmt->bind_param("ssssss", $edit_listing_name, $edit_listing_price, $target_file, $edit_listing_status, $listing_id, $_SESSION["user_name"]);

                    // Attempt to execute the prepared statement
                    if ($stmt->execute()) {
                        // Redirect to the same page to reflect the changes
                        header("location: userlistings.php");
                        exit;
                    } else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }

                    // Close statement
                    $stmt->close();
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            // Update the listing in the database without changing the image file path
            $sql = "UPDATE listing SET listing_name=?, listing_price=?, status=? WHERE listing_id=? AND user_name=?";
            if ($stmt = $mysqli->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("sssss", $edit_listing_name, $edit_listing_price, $edit_listing_status, $listing_id, $_SESSION["user_name"]);

                // Attempt to execute the prepared statement
                if ($stmt->execute()) {
                    // Redirect to the same page to reflect the changes
                    header("location: userlistings.php");
                    exit;
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close statement
                $stmt->close();
            }
        }
    }

    // Check if the form submission is for deleting a listing
    if (isset($_POST["delete_listing_id"])) {
        // Get the listing ID to be deleted
        $delete_listing_id = $_POST["delete_listing_id"];

        // Prepare a delete statement
        $sql_delete = "DELETE FROM listing WHERE listing_id = ? AND user_name = ?";

        if ($stmt_delete = $mysqli->prepare($sql_delete)) {
            // Bind variables to the prepared statement as parameters
            $stmt_delete->bind_param("ss", $delete_listing_id, $_SESSION["user_name"]);

            // Attempt to execute the prepared statement
            if ($stmt_delete->execute()) {
                // Redirect to the same page to reflect the changes
                header("location: userlistings.php");
                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt_delete->close();
        }
    }
}

// Close connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Listings</title>
    <link rel="stylesheet" href="userlistings.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>

<body>
<main>
    <div class="nero">
      <div class="nero__heading">
        <span class="nero__bold">Your </span> Listings
      </div>
      <p class="nero__text">
      </p>
    </div>
  </main>
    <div class="container">
        <?php if (empty($user_listings)) : ?>
            <script>
                alert("No listings uploaded by the user.");
                window.location.href = 'listing.php';
            </script>
        <?php else : ?>
            <div class="row">
                <?php foreach ($user_listings as $listing) : ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="<?php echo htmlspecialchars($listing['listing_image']); ?>" class="card-img-top" alt="Listing Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($listing['listing_name']); ?></h5>
                                <p class="card-text">Price: ₱<?php echo htmlspecialchars($listing['listing_price']); ?></p>
                                <p class="card-text">Status: <?php echo htmlspecialchars($listing['status']); ?></p>
                                <div class="btn-group" role="group">
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                        <input type="hidden" name="delete_listing_id" value="<?php echo $listing['listing_id']; ?>">
                                        <button type="submit" class="btn btn-danger mr-2" onclick="return confirm('Are you sure you want to delete this listing?');">
                                            Delete
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editListingModal_<?php echo $listing['listing_id']; ?>">
                                        Edit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Edit Listing Modal -->
                    <div class="modal fade" id="editListingModal_<?php echo $listing['listing_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editListingModalLabel_<?php echo $listing['listing_id']; ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editListingModalLabel_<?php echo $listing['listing_id']; ?>">Edit Listing</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true"></span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <!-- Form for editing the listing -->
                                    <form id="editForm_<?php echo $listing['listing_id']; ?>" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="listing_id" value="<?php echo $listing['listing_id']; ?>">
                                        <!-- Add your form fields here -->
                                        <div class="form-group">
                                            <label for="edit_listing_name_<?php echo $listing['listing_id']; ?>">Listing Name</label>
                                            <input type="text" class="form-control" id="edit_listing_name_<?php echo $listing['listing_id']; ?>" name="edit_listing_name" value="<?php echo htmlspecialchars($listing['listing_name']); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_listing_price_<?php echo $listing['listing_id']; ?>">Listing Price</label>
                                            <input type="text" class="form-control" id="edit_listing_price_<?php echo $listing['listing_id']; ?>" name="edit_listing_price" value="<?php echo htmlspecialchars($listing['listing_price']); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_listing_status_<?php echo $listing['listing_id']; ?>">Status</label>
                                            <select class="form-control" id="edit_listing_status_<?php echo $listing['listing_id']; ?>" name="edit_listing_status">
                                                <option value="available" <?php if ($listing['status'] === 'available') echo 'selected'; ?>>Available</option>
                                                <option value="sold" <?php if ($listing['status'] === 'sold') echo 'selected'; ?>>Sold</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_listing_image_<?php echo $listing['listing_id']; ?>">Upload Image</label>
                                            <input type="file" class="form-control-file" id="edit_listing_image_<?php echo $listing['listing_id']; ?>" name="edit_listing_image" accept="image/*">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>