<?php
session_start();

// Check if the admin is logged in, if not redirect to admin login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: adminlogin.php");
    exit;
}

// Check if logout request is received
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session.
    session_destroy();

    // Redirect to login page
    header("location: adminlogin.php");
    exit;
}

// Include config file
require_once "config.php";

// Function to delete user by ID
function deleteUser($user_id, $mysqli) {
    $sql = "DELETE FROM users WHERE id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
        return true;
    } else {
        return false;
    }
}

// Function to delete listing by ID
function deleteListing($listing_id, $mysqli) {
    $sql = "DELETE FROM listing WHERE listing_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $listing_id);
        $stmt->execute();
        $stmt->close();
        return true;
    } else {
        return false;
    }
}

// Handle user deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $user_id = $_POST['delete_user'];
    if (deleteUser($user_id, $mysqli)) {
        // Redirect to refresh the page after deletion
        header("location: admindashboard.php");
        exit;
    }
}

// Handle listing deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_listing'])) {
    $listing_id = $_POST['delete_listing'];
    if (deleteListing($listing_id, $mysqli)) {
        // Redirect to refresh the page after deletion
        header("location: admindashboard.php");
        exit;
    }
}

// Handle form submission for updating user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editUserId'])) {
    // Extract user details from the form
    $editUserId = $_POST['editUserId'];
    $editEmail = $_POST['editEmail'];
    $editUserName = $_POST['editUserName'];
    $editFirstName = $_POST['editFirstName'];
    $editLastName = $_POST['editLastName'];
    $editDob = $_POST['editDob'];
    $editUserAdd = $_POST['editUserAdd'];

    // Check if a new profile picture file is uploaded
    if ($_FILES['editProfilePicture']['error'] == UPLOAD_ERR_OK) {
        // Get the file extension
        $extension = pathinfo($_FILES['editProfilePicture']['name'], PATHINFO_EXTENSION);
        // Generate a unique filename
        $profile_picture = 'uploads/' . uniqid() . '.' . $extension;
        // Move the uploaded file to the desired location
        move_uploaded_file($_FILES['editProfilePicture']['tmp_name'], $profile_picture);
    }

    // Update the user in the database
    $sql = "UPDATE users SET email_address = ?, user_name = ?, first_name = ?, last_name = ?, dob = ?, user_add = ?, profile_picture = ? WHERE id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("sssssssi", $editEmail, $editUserName, $editFirstName, $editLastName, $editDob, $editUserAdd, $profile_picture, $editUserId);
        if ($stmt->execute()) {
            // Redirect to refresh the page after successful update
            header("location: admindashboard.php");
            exit;
        } else {
            echo "Error updating user details: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $mysqli->error;
    }
}

// Handle form submission for updating listing
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editListingId'])) {
    // Extract listing details from the form
    $editListingId = $_POST['editListingId'];
    $editListingName = $_POST['editListingName'];
    $editListingPrice = $_POST['editListingPrice'];
    $editListingDesc = $_POST['editListingDesc'];

    // Check if a new listing image file is uploaded
    if ($_FILES['editListingImage']['error'] == UPLOAD_ERR_OK) {
        // Get the file extension
        $extension = pathinfo($_FILES['editListingImage']['name'], PATHINFO_EXTENSION);
        // Generate a unique filename
        $listing_image = 'uploads/' . uniqid() . '.' . $extension;
        // Move the uploaded file to the desired location
        move_uploaded_file($_FILES['editListingImage']['tmp_name'], $listing_image);
    }

    // Update the listing in the database
    $sql = "UPDATE listing SET listing_name = ?, listing_price = ?, listing_desc = ?, listing_image = ? WHERE listing_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ssssi", $editListingName, $editListingPrice, $editListingDesc, $listing_image, $editListingId);
        if ($stmt->execute()) {
            // Redirect to refresh the page after successful update
            header("location: admindashboard.php");
            exit;
        } else {
            echo "Error updating listing details: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $mysqli->error;
    }
}

// Define variables to store user details
$user_data = array();
$listing_data = array();

// Fetch user details from the database
$sql_users = "SELECT id, email_address, user_name, first_name, last_name, dob, profile_picture, user_add FROM users";
if ($result_users = $mysqli->query($sql_users)) {
    while ($row = $result_users->fetch_assoc()) {
        $user_data[] = $row;
    }
    $result_users->free();
}

// Fetch listing details from the database
$sql_listings = "SELECT listing_id, listing_name, listing_price, listing_desc, listing_image FROM listing";
if ($result_listings = $mysqli->query($sql_listings)) {
    while ($row = $result_listings->fetch_assoc()) {
        $listing_data[] = $row;
    }
    $result_listings->free();
}

// Close connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REPS - Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1>REPS - Admin Panel</h1>
        <h2>Welcome, <?php echo $_SESSION["admin_name"]; ?>!</h2>
        <p>This is your admin dashboard.</p>

        <!-- Users Table -->
        <div class="row">
            <div class="col-md-12">
                <h3>User Table</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email Address</th>
                            <th>User Name</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Date of Birth</th>
                            <th>Profile Picture</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($user_data as $user): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo $user['email_address']; ?></td>
                                <td><?php echo $user['user_name']; ?></td>
                                <td><?php echo $user['first_name']; ?></td>
                                <td><?php echo $user['last_name']; ?></td>
                                <td><?php echo $user['dob']; ?></td>
                                <td><img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture" style="max-width: 100px;"></td>
                                <td><?php echo $user['user_add']; ?></td>
                                <td>
                                    <!-- Edit button -->
                                    <button type="button" class="btn btn-primary edit-user-btn" data-toggle="modal" data-target="#editUserModal" data-id="<?php echo $user['id']; ?>" data-email="<?php echo $user['email_address']; ?>" data-username="<?php echo $user['user_name']; ?>" data-firstname="<?php echo $user['first_name']; ?>" data-lastname="<?php echo $user['last_name']; ?>" data-dob="<?php echo $user['dob']; ?>" data-address="<?php echo $user['user_add']; ?>">Edit</button>
                                    <!-- Delete button -->
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                        <input type="hidden" name="delete_user" value="<?php echo $user['id']; ?>">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Listings Table -->
        <div class="row">
            <div class="col-md-12">
                <h3>Listings Table</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Listing Name</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listing_data as $listing) : ?>
                            <tr>
                                <td><?php echo $listing['listing_id']; ?></td>
                                <td><?php echo $listing['listing_name']; ?></td>
                                <td><?php echo $listing['listing_price']; ?></td>
                                <td><?php echo $listing['listing_desc']; ?></td>
                                <!-- Display image -->
                                <td><img src="<?php echo $listing['listing_image']; ?>" alt="Listing Image" style="max-width: 100px;"></td>
                                <td>
                                    <!-- Edit button -->
                                    <button type="button" class="btn btn-primary edit-listing-btn" data-toggle="modal" data-target="#editListingModal" data-id="<?php echo $listing['listing_id']; ?>" data-name="<?php echo $listing['listing_name']; ?>" data-price="<?php echo $listing['listing_price']; ?>" data-description="<?php echo $listing['listing_desc']; ?>">Edit</button>
                                    <!-- Delete button -->
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                        <input type="hidden" name="delete_listing" value="<?php echo $listing['listing_id']; ?>">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sign out form -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="submit" name="logout" class="btn btn-primary" value="Sign Out">
        </form>
    </div>

    <!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="editUserId" name="editUserId">
                    <div class="form-group">
                        <label for="editEmail">Email Address</label>
                        <input type="email" class="form-control" id="editEmail" name="editEmail" required>
                    </div>
                    <div class="form-group">
                        <label for="editUserName">User Name</label>
                        <input type="text" class="form-control" id="editUserName" name="editUserName" required>
                    </div>
                    <div class="form-group">
                        <label for="editFirstName">First Name</label>
                        <input type="text" class="form-control" id="editFirstName" name="editFirstName" required>
                    </div>
                    <div class="form-group">
                        <label for="editLastName">Last Name</label>
                        <input type="text" class="form-control" id="editLastName" name="editLastName" required>
                    </div>
                    <div class="form-group">
                        <label for="editDob">Date of Birth</label>
                        <input type="date" class="form-control" id="editDob" name="editDob" required>
                    </div>
                    <div class="form-group">
                        <label for="editUserAdd">Address</label>
                        <input type="text" class="form-control" id="editUserAdd" name="editUserAdd" required>
                    </div>
                    <div class="form-group">
                        <label for="editProfilePicture">Profile Picture</label>
                        <input type="file" class="form-control-file" id="editProfilePicture" name="editProfilePicture">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Edit Listing Modal -->
<div class="modal fade" id="editListingModal" tabindex="-1" role="dialog" aria-labelledby="editListingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editListingModalLabel">Edit Listing</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editListingForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="editListingId" name="editListingId">
                    <div class="form-group">
                        <label for="editListingName">Listing Name</label>
                        <input type="text" class="form-control" id="editListingName" name="editListingName" required>
                    </div>
                    <div class="form-group">
                        <label for="editListingPrice">Price</label>
                        <input type="text" class="form-control" id="editListingPrice" name="editListingPrice" required>
                    </div>
                    <div class="form-group">
                        <label for="editListingDesc">Description</label>
                        <textarea class="form-control" id="editListingDesc" name="editListingDesc" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="editListingImage">Listing Image</label>
                        <input type="file" class="form-control-file" id="editListingImage" name="editListingImage">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Fill Edit User Modal with data when Edit button is clicked
        $(document).on("click", ".edit-user-btn", function () {
            var id = $(this).data('id');
            var email = $(this).data('email');
            var username = $(this).data('username');
            var firstname = $(this).data('firstname');
            var lastname = $(this).data('lastname');
            var dob = $(this).data('dob');
            var address = $(this).data('address');

            $("#editUserId").val(id);
            $("#editEmail").val(email);
            $("#editUserName").val(username);
            $("#editFirstName").val(firstname);
            $("#editLastName").val(lastname);
            $("#editDob").val(dob);
            $("#editUserAdd").val(address);
        });

        // Fill Edit Listing Modal with data when Edit button is clicked
        $(document).on("click", ".edit-listing-btn", function () {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var price = $(this).data('price');
            var description = $(this).data('description');

            $("#editListingId").val(id);
            $("#editListingName").val(name);
            $("#editListingPrice").val(price);
            $("#editListingDesc").val(description);
        });
    </script>
</body>

</html>


