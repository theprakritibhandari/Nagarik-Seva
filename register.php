<?php

require_once "database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if (empty($name) || empty($email) || empty($phone) || empty($password)) {

        $message = "Please fill in all fields.";

    } elseif ($password !== $confirm_password) {

        $message = "Passwords do not match.";

    } else {

        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $message = "An account with this email already exists.";

        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $role = "citizen";

            $stmt = $conn->prepare(
                "INSERT INTO users (name, email, phone, password, role)
                 VALUES (?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "sssss",
                $name,
                $email,
                $phone,
                $hashed_password,
                $role
            );

            if ($stmt->execute()) {

                $message = "Registration successful! You can now login.";

            } else {

                $message = "Registration failed. Please try again.";

            }

            $stmt->close();
        }

        $check->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Citizen Registration - Nagarik Seva</title>

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
                <a href="login.php">Login</a>
            </li>

        </ul>

    </nav>


    <section class="hero">

        <div class="hero-content">

            <h1>Create Account</h1>

            <h2>Citizen Registration</h2>

            <?php if (!empty($message)): ?>

                <p>
                    <?php echo htmlspecialchars($message); ?>
                </p>

            <?php endif; ?>


            <form method="POST">

                <p>
                    <input
                        type="text"
                        name="name"
                        placeholder="Full Name"
                        required
                    >
                </p>

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
                        type="text"
                        name="phone"
                        placeholder="Phone Number"
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

                <p>
                    <input
                        type="password"
                        name="confirm_password"
                        placeholder="Confirm Password"
                        required
                    >
                </p>

                <button type="submit" class="btn">
                    Create Account
                </button>

            </form>

            <p>
                Already have an account?
                <a href="login.php">Login here</a>
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