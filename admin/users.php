<?php

session_start();

require_once "../database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}


$stmt = $conn->prepare(
    "SELECT id, name, email, phone
     FROM users
     WHERE role = 'citizen'
     ORDER BY id DESC"
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

    <title>Citizens - Nagarik Seva</title>

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


    <!-- Citizens -->

    <section class="services">

        <h2>Registered Citizens</h2>


        <?php if ($result->num_rows > 0): ?>

            <div style="overflow-x:auto;">

                <table
                    border="1"
                    cellpadding="10"
                    cellspacing="0"
                    style="
                        margin: auto;
                        background: white;
                        min-width: 700px;
                    "
                >

                    <tr>

                        <th>ID</th>

                        <th>Name</th>

                        <th>Email</th>

                        <th>Phone</th>

                    </tr>


                    <?php while ($user = $result->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $user["id"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $user["name"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $user["email"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $user["phone"]
                                );
                                ?>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                </table>

            </div>

        <?php else: ?>

            <p>
                No citizens registered yet.
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