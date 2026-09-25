<?php

session_start();

require_once "database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {

        $message = "Please enter your email and password.";

    } else {

        $stmt = $conn->prepare(
            "SELECT id, name, email, password, role
             FROM users
             WHERE email = ?"
        );

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user["password"])) {

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["name"] = $user["name"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["role"] = $user["role"];

                if ($user["role"] == "admin") {

                    header("Location: admin/dashboard.php");
                    exit();

                } else {

                    header("Location: citizen/dashboard.php");
                    exit();
                }

            } else {

                $message = "Invalid email or password.";

            }

        } else {

            $message = "Invalid email or password.";

        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Nagarik Seva</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <nav class="navbar">

        <div class="logo">
            Nagarik Seva
        </div>

        <ul class="nav-links">

            <li>
                <a href="index.php">Home</a>
            </li>

            <li>
                <a href="register.php">Register</a>
            </li>

        </ul>

    </nav>


    <section class="hero">

        <div class="hero-content">

            <h1>Login</h1>

            <h2>Welcome to Nagarik Seva</h2>

            <?php if (!empty($message)): ?>

                <p>
                    <?php echo htmlspecialchars($message); ?>
                </p>

            <?php endif; ?>


            <form method="POST">

                <p>
                    <input
                        type="email"
                        name="email"
                        placeholder="Email Address"
                        required
                    >
                </p>

                <p>
                    <input
                        type="password"
                        name="password"
                        placeholder="Password"
                        required
                    >
                </p>

                <button type="submit" class="btn">
                    Login
                </button>

            </form>


            <p>
                Don't have an account?
                <a href="register.php">Create an account</a>
            </p>

        </div>

    </section>


    <footer class="footer">

        <p>
            &copy; 2026 Nagarik Seva. Academic Project.
        </p>

    </footer>

</body>

</html>