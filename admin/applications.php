<?php

session_start();

require_once "../database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}


$stmt = $conn->prepare(
    "SELECT
        applications.application_id,
        applications.application_no,
        applications.type,
        applications.status,
        applications.created_at,
        users.name,
        users.email,
        users.phone
     FROM applications
     INNER JOIN users
     ON applications.user_id = users.id
     ORDER BY applications.application_id DESC"
);

$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Applications - Nagarik Seva</title>

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


    <!-- Applications -->

    <section class="services">

        <h2>All Applications</h2>


        <?php if ($result->num_rows > 0): ?>

            <div style="overflow-x:auto;">

                <table
                    border="1"
                    cellpadding="10"
                    cellspacing="0"
                    style="
                        margin: auto;
                        background: white;
                        min-width: 900px;
                    "
                >

                    <tr>

                        <th>Application No.</th>

                        <th>Citizen Name</th>

                        <th>Email</th>

                        <th>Phone</th>

                        <th>Type</th>

                        <th>Status</th>

                        <th>Submitted Date</th>

                        <th>Action</th>

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
                                    $application["name"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $application["email"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $application["phone"]
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

                            <td>

                                <a
                                    href="view.php?id=<?php echo $application["application_id"]; ?>"
                                    class="btn"
                                >
                                    View
                                </a>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                </table>

            </div>

        <?php else: ?>

            <p>
                No applications found.
            </p>

        <?php endif; ?>


    </section>


    <!-- Footer -->

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