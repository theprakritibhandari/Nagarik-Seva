<?php

session_start();

require_once "../database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "citizen") {
    header("Location: ../login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $child_name = trim($_POST["child_name"]);
    $date_of_birth = $_POST["date_of_birth"];
    $gender = $_POST["gender"];
    $place_of_birth = trim($_POST["place_of_birth"]);
    $father_name = trim($_POST["father_name"]);
    $mother_name = trim($_POST["mother_name"]);
    $province = trim($_POST["province"]);
    $district = trim($_POST["district"]);
    $municipality = trim($_POST["municipality"]);
    $ward = trim($_POST["ward"]);

    if (
        empty($child_name) ||
        empty($date_of_birth) ||
        empty($gender) ||
        empty($place_of_birth) ||
        empty($father_name) ||
        empty($mother_name) ||
        empty($province) ||
        empty($district) ||
        empty($municipality) ||
        empty($ward)
    ) {

        $message = "Please fill in all fields.";

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO birth_registrations
            (child_name, date_of_birth, gender, place_of_birth,
             father_name, mother_name, province, district,
             municipality, ward)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssssssssss",
            $child_name,
            $date_of_birth,
            $gender,
            $place_of_birth,
            $father_name,
            $mother_name,
            $province,
            $district,
            $municipality,
            $ward
        );

        if ($stmt->execute()) {

            $registration_id = $stmt->insert_id;

            $application_no = "BR-" . date("Y") . "-" . str_pad(
                $registration_id,
                6,
                "0",
                STR_PAD_LEFT
            );

            $type = "Birth";
            $status = "Pending";
            $user_id = $_SESSION["user_id"];

            $app = $conn->prepare(
                "INSERT INTO applications
                (user_id, type, registration_id, status, application_no)
                VALUES (?, ?, ?, ?, ?)"
            );

            $app->bind_param(
                "isiss",
                $user_id,
                $type,
                $registration_id,
                $status,
                $application_no
            );

            if ($app->execute()) {

                $message =
                    "Birth registration submitted successfully! " .
                    "Application No: " . $application_no;

            } else {

                $message = "Registration saved, but application creation failed.";

            }

            $app->close();

        } else {

            $message = "Birth registration failed. Please try again.";

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

    <title>Birth Registration - Nagarik Seva</title>

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

            <h1>Birth Registration</h1>

            <h2>Enter Birth Details</h2>

            <?php if (!empty($message)): ?>

                <p>
                    <?php echo htmlspecialchars($message); ?>
                </p>

            <?php endif; ?>


            <form method="POST">

                <p>
                    <input
                        type="text"
                        name="child_name"
                        placeholder="Child Name"
                        required
                    >
                </p>

                <p>
                    <input
                        type="date"
                        name="date_of_birth"
                        required
                    >
                </p>

                <p>
                    <select name="gender" required>

                        <option value="">
                            Select Gender
                        </option>

                        <option value="Male">
                            Male
                        </option>

                        <option value="Female">
                            Female
                        </option>

                        <option value="Other">
                            Other
                        </option>

                    </select>
                </p>

                <p>
                    <input
                        type="text"
                        name="place_of_birth"
                        placeholder="Place of Birth"
                        required
                    >
                </p>

                <p>
                    <input
                        type="text"
                        name="father_name"
                        placeholder="Father's Name"
                        required
                    >
                </p>

                <p>
                    <input
                        type="text"
                        name="mother_name"
                        placeholder="Mother's Name"
                        required
                    >
                </p>

                <p>
                    <input
                        type="text"
                        name="province"
                        placeholder="Province"
                        required
                    >
                </p>

                <p>
                    <input
                        type="text"
                        name="district"
                        placeholder="District"
                        required
                    >
                </p>

                <p>
                    <input
                        type="text"
                        name="municipality"
                        placeholder="Municipality"
                        required
                    >
                </p>

                <p>
                    <input
                        type="text"
                        name="ward"
                        placeholder="Ward Number"
                        required
                    >
                </p>

                <button type="submit" class="btn">
                    Submit Birth Registration
                </button>

            </form>

        </div>

    </section>


    <footer class="footer">

        <p>
            &copy; 2026 Nagarik Seva. Academic Project.
        </p>

    </footer>

</body>

</html>