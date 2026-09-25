<?php

session_start();

require_once "../database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}


if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: applications.php");
    exit();
}

$application_id = intval($_GET["id"]);

$message = "";


/* =========================
   APPROVE / REJECT
   ========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $new_status = $_POST["status"];

    if ($new_status == "Approved" || $new_status == "Rejected") {

        $update = $conn->prepare(
            "UPDATE applications
             SET status = ?
             WHERE application_id = ?"
        );

        $update->bind_param(
            "si",
            $new_status,
            $application_id
        );

        if ($update->execute()) {

            $message =
                "Application status updated to " .
                $new_status . ".";

        } else {

            $message =
                "Failed to update application status.";

        }

        $update->close();
    }
}


/* =========================
   GET APPLICATION
   ========================= */

$stmt = $conn->prepare(
    "SELECT
        applications.application_id,
        applications.application_no,
        applications.type,
        applications.registration_id,
        applications.status,
        applications.created_at,
        users.name,
        users.email,
        users.phone
     FROM applications
     INNER JOIN users
     ON applications.user_id = users.id
     WHERE applications.application_id = ?"
);

$stmt->bind_param("i", $application_id);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows != 1) {

    header("Location: applications.php");
    exit();

}

$application = $result->fetch_assoc();

$stmt->close();


/* =========================
   GET REGISTRATION DETAILS
   ========================= */

$type = $application["type"];

$registration_id = $application["registration_id"];

$details = null;


if ($type == "Birth") {

    $detail_stmt = $conn->prepare(
        "SELECT *
         FROM birth_registrations
         WHERE id = ?"
    );

    $detail_stmt->bind_param(
        "i",
        $registration_id
    );

    $detail_stmt->execute();

    $detail_result = $detail_stmt->get_result();

    $details = $detail_result->fetch_assoc();

    $detail_stmt->close();

}


elseif ($type == "Death") {

    $detail_stmt = $conn->prepare(
        "SELECT *
         FROM death_registrations
         WHERE id = ?"
    );

    $detail_stmt->bind_param(
        "i",
        $registration_id
    );

    $detail_stmt->execute();

    $detail_result = $detail_stmt->get_result();

    $details = $detail_result->fetch_assoc();

    $detail_stmt->close();

}


elseif ($type == "Marriage") {

    $detail_stmt = $conn->prepare(
        "SELECT *
         FROM marriage_registrations
         WHERE id = ?"
    );

    $detail_stmt->bind_param(
        "i",
        $registration_id
    );

    $detail_stmt->execute();

    $detail_result = $detail_stmt->get_result();

    $details = $detail_result->fetch_assoc();

    $detail_stmt->close();

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Application Details - Nagarik Seva</title>

    <link rel="stylesheet" href="../style.css">

</head>

<body>


    <!-- Navigation -->

    <nav class="navbar">

        <div class="logo">
            Nagarik Seva - Admin
        </div>

        <ul class="nav-links">

            <li>
                <a href="dashboard.php">
                    Dashboard
                </a>
            </li>

            <li>
                <a href="applications.php">
                    Applications
                </a>
            </li>

            <li>
                <a href="users.php">
                    Citizens
                </a>
            </li>

            <li>
                <a href="../logout.php">
                    Logout
                </a>
            </li>

        </ul>

    </nav>


    <!-- Application Details -->

    <section class="services">

        <h2>Application Details</h2>


        <?php if (!empty($message)): ?>

            <p>
                <?php echo htmlspecialchars($message); ?>
            </p>

        <?php endif; ?>


        <!-- Application Information -->

        <div class="service-card"
             style="width: 90%; max-width: 800px; margin: 20px auto;">

            <h3>Application Information</h3>

            <p>
                <strong>Application No:</strong>
                <?php
                echo htmlspecialchars(
                    $application["application_no"]
                );
                ?>
            </p>

            <p>
                <strong>Type:</strong>
                <?php
                echo htmlspecialchars(
                    $application["type"]
                );
                ?>
            </p>

            <p>
                <strong>Status:</strong>
                <?php
                echo htmlspecialchars(
                    $application["status"]
                );
                ?>
            </p>

            <p>
                <strong>Submitted:</strong>
                <?php
                echo htmlspecialchars(
                    $application["created_at"]
                );
                ?>
            </p>

        </div>


        <!-- Citizen Information -->

        <div class="service-card"
             style="width: 90%; max-width: 800px; margin: 20px auto;">

            <h3>Citizen Information</h3>

            <p>
                <strong>Name:</strong>
                <?php
                echo htmlspecialchars(
                    $application["name"]
                );
                ?>
            </p>

            <p>
                <strong>Email:</strong>
                <?php
                echo htmlspecialchars(
                    $application["email"]
                );
                ?>
            </p>

            <p>
                <strong>Phone:</strong>
                <?php
                echo htmlspecialchars(
                    $application["phone"]
                );
                ?>
            </p>

        </div>


        <!-- Registration Details -->

        <?php if ($details): ?>

            <div class="service-card"
                 style="width: 90%; max-width: 800px; margin: 20px auto;">

                <h3>
                    <?php
                    echo htmlspecialchars($type);
                    ?>
                    Registration Details
                </h3>


                <?php if ($type == "Birth"): ?>

                    <p>
                        <strong>Child Name:</strong>
                        <?php echo htmlspecialchars($details["child_name"]); ?>
                    </p>

                    <p>
                        <strong>Date of Birth:</strong>
                        <?php echo htmlspecialchars($details["date_of_birth"]); ?>
                    </p>

                    <p>
                        <strong>Gender:</strong>
                        <?php echo htmlspecialchars($details["gender"]); ?>
                    </p>

                    <p>
                        <strong>Place of Birth:</strong>
                        <?php echo htmlspecialchars($details["place_of_birth"]); ?>
                    </p>

                    <p>
                        <strong>Father's Name:</strong>
                        <?php echo htmlspecialchars($details["father_name"]); ?>
                    </p>

                    <p>
                        <strong>Mother's Name:</strong>
                        <?php echo htmlspecialchars($details["mother_name"]); ?>
                    </p>

                    <p>
                        <strong>Province:</strong>
                        <?php echo htmlspecialchars($details["province"]); ?>
                    </p>

                    <p>
                        <strong>District:</strong>
                        <?php echo htmlspecialchars($details["district"]); ?>
                    </p>

                    <p>
                        <strong>Municipality:</strong>
                        <?php echo htmlspecialchars($details["municipality"]); ?>
                    </p>

                    <p>
                        <strong>Ward:</strong>
                        <?php echo htmlspecialchars($details["ward"]); ?>
                    </p>


                <?php elseif ($type == "Death"): ?>

                    <p>
                        <strong>Deceased Name:</strong>
                        <?php echo htmlspecialchars($details["deceased_name"]); ?>
                    </p>

                    <p>
                        <strong>Date of Birth:</strong>
                        <?php echo htmlspecialchars($details["date_of_birth"]); ?>
                    </p>

                    <p>
                        <strong>Date of Death:</strong>
                        <?php echo htmlspecialchars($details["date_of_death"]); ?>
                    </p>

                    <p>
                        <strong>Gender:</strong>
                        <?php echo htmlspecialchars($details["gender"]); ?>
                    </p>

                    <p>
                        <strong>Place of Death:</strong>
                        <?php echo htmlspecialchars($details["place_of_death"]); ?>
                    </p>

                    <p>
                        <strong>Father's Name:</strong>
                        <?php echo htmlspecialchars($details["father_name"]); ?>
                    </p>

                    <p>
                        <strong>Mother's Name:</strong>
                        <?php echo htmlspecialchars($details["mother_name"]); ?>
                    </p>

                    <p>
                        <strong>Spouse Name:</strong>
                        <?php echo htmlspecialchars($details["spouse_name"]); ?>
                    </p>

                    <p>
                        <strong>Province:</strong>
                        <?php echo htmlspecialchars($details["province"]); ?>
                    </p>

                    <p>
                        <strong>District:</strong>
                        <?php echo htmlspecialchars($details["district"]); ?>
                    </p>

                    <p>
                        <strong>Municipality:</strong>
                        <?php echo htmlspecialchars($details["municipality"]); ?>
                    </p>

                    <p>
                        <strong>Ward:</strong>
                        <?php echo htmlspecialchars($details["ward"]); ?>
                    </p>


                <?php elseif ($type == "Marriage"): ?>

                    <p>
                        <strong>Groom Name:</strong>
                        <?php echo htmlspecialchars($details["groom_name"]); ?>
                    </p>

                    <p>
                        <strong>Groom DOB:</strong>
                        <?php echo htmlspecialchars($details["groom_dob"]); ?>
                    </p>

                    <p>
                        <strong>Groom Citizenship No:</strong>
                        <?php echo htmlspecialchars($details["groom_citizenship_no"]); ?>
                    </p>

                    <p>
                        <strong>Bride Name:</strong>
                        <?php echo htmlspecialchars($details["bride_name"]); ?>
                    </p>

                    <p>
                        <strong>Bride DOB:</strong>
                        <?php echo htmlspecialchars($details["bride_dob"]); ?>
                    </p>

                    <p>
                        <strong>Bride Citizenship No:</strong>
                        <?php echo htmlspecialchars($details["bride_citizenship_no"]); ?>
                    </p>

                    <p>
                        <strong>Marriage Date:</strong>
                        <?php echo htmlspecialchars($details["marriage_date"]); ?>
                    </p>

                    <p>
                        <strong>Marriage Place:</strong>
                        <?php echo htmlspecialchars($details["marriage_place"]); ?>
                    </p>

                    <p>
                        <strong>Province:</strong>
                        <?php echo htmlspecialchars($details["province"]); ?>
                    </p>

                    <p>
                        <strong>District:</strong>
                        <?php echo htmlspecialchars($details["district"]); ?>
                    </p>

                    <p>
                        <strong>Municipality:</strong>
                        <?php echo htmlspecialchars($details["municipality"]); ?>
                    </p>

                    <p>
                        <strong>Ward:</strong>
                        <?php echo htmlspecialchars($details["ward"]); ?>
                    </p>

                <?php endif; ?>

            </div>

        <?php endif; ?>


        <!-- Approve / Reject -->

        <?php if ($application["status"] == "Pending"): ?>

            <div style="margin: 30px;">

                <form method="POST"
                      style="display: inline;">

                    <input
                        type="hidden"
                        name="status"
                        value="Approved"
                    >

                    <button
                        type="submit"
                        class="btn"
                    >
                        Approve Application
                    </button>

                </form>


                <form method="POST"
                      style="display: inline;">

                    <input
                        type="hidden"
                        name="status"
                        value="Rejected"
                    >

                    <button
                        type="submit"
                        class="btn"
                    >
                        Reject Application
                    </button>

                </form>

            </div>

        <?php else: ?>

            <p>
                This application has already been
                <?php
                echo strtolower(
                    htmlspecialchars($application["status"])
                );
                ?>.
            </p>

        <?php endif; ?>


        <br>

        <a href="applications.php" class="btn">
            Back to Applications
        </a>


    </section>


    <!-- Footer -->

    <footer class="footer">

        <p>
            &copy; 2026 Nagarik Seva. Academic Project.
        </p>

    </footer>


</body>

</html>