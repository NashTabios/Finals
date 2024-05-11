<?php
session_start();

// Include config file
require_once "config.php";
include 'navbar.php';

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
$sql = "SELECT listing_id, listing_name, listing_price, listing_desc, listing_image FROM listing WHERE user_name = ?";
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
            $stmt->bind_result($listing_id, $listing_name, $listing_price, $listing_desc, $listing_image);
            // Fetch result rows into array
            while ($stmt->fetch()) {
                $user_listings[] = array(
                    'listing_id' => $listing_id,
                    'listing_name' => $listing_name,
                    'listing_price' => $listing_price,
                    'listing_desc' => $listing_desc,
                    'listing_image' => $listing_image
                );
            }
        } else {
            echo "<p>No listings uploaded by the user.</p>";
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Listings</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .card-body {
            height: 200px;
            /* Set a fixed height for the card body */
            overflow: hidden;
            /* Hide overflow content */
        }

        .card-img-top {
            width: 100%;
            /* Ensure the image fills the container */
            height: auto;
            /* Maintain aspect ratio */
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>User Listings</h1>
        <?php if (empty($user_listings)) : ?>
            <p>No listings uploaded by the user.</p>
        <?php else : ?>
            <div class="row">
                <?php foreach ($user_listings as $listing) : ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="<?php echo htmlspecialchars($listing['listing_image']); ?>" class="card-img-top" alt="Listing Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($listing['listing_name']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($listing['listing_desc']); ?></p>
                                <p class="card-text">Price: ₱<?php echo htmlspecialchars($listing['listing_price']); ?></p>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editListingModal_<?php echo $listing['listing_id']; ?>">
                                    Edit
                                </button>
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
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <!-- Form for editing the listing -->
                                    <form id="editForm_<?php echo $listing['listing_id']; ?>" action="userlistings.php" method="POST">
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
                                            <label for="edit_listing_desc_<?php echo $listing['listing_id']; ?>">Listing Description</label>
                                            <textarea class="form-control" id="edit_listing_desc_<?php echo $listing['listing_id']; ?>" name="edit_listing_desc"><?php echo htmlspecialchars($listing['listing_desc']); ?></textarea>
                                        </div>
                                        <button type="button" class="btn btn-primary" onclick="submitForm(<?php echo $listing['listing_id']; ?>)">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function submitForm(listing_id) {
            // Submit the form with the specified listing_id
            $('#editForm_' + listing_id).submit();
        }
    </script>
</body>

</html>