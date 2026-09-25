<?php

session_start();

require_once "../database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "citizen") {
    header("Location: ../login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Citizen Dashboard - Nagarik Seva</title>

    <link rel="stylesheet" href="../style.css">

</head>

<body>

    <nav class="navbar">

        <div class="logo">
            Nagarik Seva
        </div>

        <ul class="nav-links">

            <li>
                <a href="dashboard.php">Dashboard</a>
            </li>

            <li>
                <a href="applications.php">My Applications</a>
            </li>

            <li>
                <a href="../logout.php">Logout</a>
            </li>

        </ul>

    </nav>


    <section class="hero">

        <div class="hero-content">

            <h1>Citizen Dashboard</h1>

            <h2>
                Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?>!
            </h2>

            <p>
                You can apply for Birth, Death and Marriage Registration
                from this dashboard.
            </p>

            <div>

                <a href="birth.php" class="btn">
                    Birth Registration
                </a>

                <a href="death.php" class="btn">
                    Death Registration
                </a>

                <a href="marriage.php" class="btn">
                    Marriage Registration
                </a>

            </div>

        </div>

    </section>


    <footer class="footer">

        <p>
            &copy; 2026 Nagarik Seva. Academic Project.
        </p>

    </footer>

</body>

</html>