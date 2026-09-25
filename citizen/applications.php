<?php

session_start();

require_once "../database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "citizen") {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare(
    "SELECT application_no, type, status, created_at
     FROM applications
     WHERE user_id = ?
     ORDER BY application_id DESC"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Applications - Nagarik Seva</title>

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


    <section class="services">

        <h2>My Applications</h2>

        <?php if ($result->num_rows > 0): ?>

            <div style="overflow-x:auto;">

                <table border="1" cellpadding="10" cellspacing="0"
                       style="margin: auto; background: white;">

                    <tr>

                        <th>Application No.</th>

                        <th>Type</th>

                        <th>Status</th>

                        <th>Submitted Date</th>

                    </tr>


                    <?php while ($application = $result->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $application["application_no"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $application["type"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $application["status"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $application["created_at"]
                                );
                                ?>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                </table>

            </div>

        <?php else: ?>

            <p>
                You have not submitted any applications yet.
            </p>

        <?php endif; ?>

    </section>


    <footer class="footer">

        <p>
            &copy; 2026 Nagarik Seva. Academic Project.
        </p>

    </footer>

</body>

</html>

<?php

$stmt->close();

?>