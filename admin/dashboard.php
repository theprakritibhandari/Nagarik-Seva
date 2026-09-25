<?php

session_start();

require_once "../database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}


/* Total Applications */

$total_query = $conn->query(
    "SELECT COUNT(*) AS total FROM applications"
);

$total_applications = $total_query->fetch_assoc()["total"];


/* Pending Applications */

$pending_query = $conn->query(
    "SELECT COUNT(*) AS total
     FROM applications
     WHERE status = 'Pending'"
);

$pending_applications = $pending_query->fetch_assoc()["total"];


/* Approved Applications */

$approved_query = $conn->query(
    "SELECT COUNT(*) AS total
     FROM applications
     WHERE status = 'Approved'"
);

$approved_applications = $approved_query->fetch_assoc()["total"];


/* Rejected Applications */

$rejected_query = $conn->query(
    "SELECT COUNT(*) AS total
     FROM applications
     WHERE status = 'Rejected'"
);

$rejected_applications = $rejected_query->fetch_assoc()["total"];


/* Total Citizens */

$citizen_query = $conn->query(
    "SELECT COUNT(*) AS total
     FROM users
     WHERE role = 'citizen'"
);

$total_citizens = $citizen_query->fetch_assoc()["total"];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard - Nagarik Seva</title>

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


    <!-- Dashboard -->

    <section class="services">

        <h2>Admin Dashboard</h2>

        <p>
            Welcome,
            <?php echo htmlspecialchars($_SESSION["name"]); ?>!
        </p>


        <div class="service-container">


            <!-- Total Applications -->

            <div class="service-card">

                <h3>Total Applications</h3>

                <p style="font-size: 32px; font-weight: bold;">

                    <?php echo $total_applications; ?>

                </p>

            </div>


            <!-- Pending -->

            <div class="service-card">

                <h3>Pending</h3>

                <p style="font-size: 32px; font-weight: bold;">

                    <?php echo $pending_applications; ?>

                </p>

            </div>


            <!-- Approved -->

            <div class="service-card">

                <h3>Approved</h3>

                <p style="font-size: 32px; font-weight: bold;">

                    <?php echo $approved_applications; ?>

                </p>

            </div>


            <!-- Rejected -->

            <div class="service-card">

                <h3>Rejected</h3>

                <p style="font-size: 32px; font-weight: bold;">

                    <?php echo $rejected_applications; ?>

                </p>

            </div>


            <!-- Citizens -->

            <div class="service-card">

                <h3>Registered Citizens</h3>

                <p style="font-size: 32px; font-weight: bold;">

                    <?php echo $total_citizens; ?>

                </p>

            </div>


        </div>


        <br>


        <a href="applications.php" class="btn">
            Manage Applications
        </a>

        <a href="users.php" class="btn">
            View Citizens
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