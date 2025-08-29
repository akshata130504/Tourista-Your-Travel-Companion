<?php
session_start(); // Start the session
$con = mysqli_connect('localhost', 'root', '', 'travel');

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: index.html");
    exit;
}

// Get the logged-in user's email
$user_email = $_SESSION['user_email'];

// Handle delete request
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM `booking` WHERE id = $id AND femail = '$user_email'";
    mysqli_query($con, $sql);
    header("Location: booked_details.php");
    exit;
}

// Handle edit request
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $firstname = $_POST['ffirst'];
    $lastname = $_POST['flast'];
    $city = $_POST['city'];
    $phone = $_POST['fphone'];
    $destination = $_POST['fdesti'];
    $date = $_POST['fdate'];

    $sql = "UPDATE `booking` 
            SET `ffirst`='$firstname', `flast`='$lastname', 
                `city`='$city', `fphone`='$phone', 
                `fdesti`='$destination', `fdate`='$date' 
            WHERE `id`=$id AND `femail`='$user_email'";
    mysqli_query($con, $sql);
    header("Location: booked_details.php");
    exit;
}

// Fetch bookings for the logged-in user's email
$result = mysqli_query($con, "SELECT * FROM `booking` WHERE `femail` = '$user_email'");
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Booking Details</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        background-color: #f9f9f9;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 18px;
        background-color: white;
    }

    table th,
    table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }

    table th {
        background-color: #f2f2f2;
    }

    .btn {
        padding: 5px 10px;
        margin: 5px;
        color: white;
        background-color: #007bff;
        border: none;
        cursor: pointer;
    }

    .btn-danger {
        background-color: #dc3545;
    }

    .form-inline {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
    }

    .form-inline input[type="text"],
    .form-inline input[type="date"] {
        width: 200px;
        padding: 5px;
    }
    </style>
</head>

<body>
    <h1>Welcome, <?php echo htmlspecialchars($user_email); ?>!</h1>
    <h2>Your Booking Details</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>City</th>
            <th>Phone</th>
            <th>Destination</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr data-id="<?php echo $row['id']; ?>">
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['ffirst']); ?></td>
            <td><?php echo htmlspecialchars($row['flast']); ?></td>
            <td><?php echo htmlspecialchars($row['city']); ?></td>
            <td><?php echo htmlspecialchars($row['fphone']); ?></td>
            <td><?php echo htmlspecialchars($row['fdesti']); ?></td>
            <td><?php echo htmlspecialchars($row['fdate']); ?></td>
            <td>
                <!-- Delete Button -->
                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                <!-- Edit Button (Trigger Form) -->
                <button class="btn" onclick="editBooking(<?php echo $row['id']; ?>)">Edit</button>
            </td>
        </tr>
        <?php } ?>
    </table>

    <div id="editForm" style="display:none;">
        <h3>Edit Booking</h3>
        <form method="POST" class="form-inline">
            <input type="hidden" name="id" id="editId">
            <input type="text" name="ffirst" id="editFirstName" placeholder="First Name" required>
            <input type="text" name="flast" id="editLastName" placeholder="Last Name" required>
            <input type="text" name="city" id="editCity" placeholder="City" required>
            <input type="tel" name="fphone" id="editPhone" placeholder="Phone" pattern="[0-9]{10}"
                title="Enter 10-digit phone number" required>
            <input type="text" name="fdesti" id="editDestination" placeholder="Destination" required>
            <input type="date" name="fdate" id="editDate" required>
            <button type="submit" name="edit" class="btn">Save Changes</button>
        </form>
    </div>

    <script>
    // Edit booking form display
    function editBooking(id) {
        var row = document.querySelector('tr[data-id="' + id + '"]');
        document.getElementById('editId').value = id;
        document.getElementById('editFirstName').value = row.cells[1].innerText;
        document.getElementById('editLastName').value = row.cells[2].innerText;
        document.getElementById('editCity').value = row.cells[3].innerText;
        document.getElementById('editPhone').value = row.cells[4].innerText;
        document.getElementById('editDestination').value = row.cells[5].innerText;
        document.getElementById('editDate').value = row.cells[6].innerText;
        document.getElementById('editForm').style.display = 'block';
    }
    </script>
</body>

</html>