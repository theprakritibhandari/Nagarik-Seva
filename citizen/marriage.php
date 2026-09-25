<?php

session_start();

require_once "../database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "citizen") {
    header("Location: ../login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $groom_name = trim($_POST["groom_name"]);
    $groom_dob = $_POST["groom_dob"];
    $groom_citizenship_no = trim($_POST["groom_citizenship_no"]);

    $bride_name = trim($_POST["bride_name"]);
    $bride_dob = $_POST["bride_dob"];
    $bride_citizenship_no = trim($_POST["bride_citizenship_no"]);

    $marriage_date = $_POST["marriage_date"];
    $marriage_place = trim($_POST["marriage_place"]);

    $province = trim($_POST["province"]);
    $district = trim($_POST["district"]);
    $municipality = trim($_POST["municipality"]);
    $ward = trim($_POST["ward"]);


    if (
        empty($groom_name) ||
        empty($groom_dob) ||
        empty($groom_citizenship_no) ||
        empty($bride_name) ||
        empty($bride_dob) ||
        empty($bride_citizenship_no) ||
        empty($marriage_date) ||
        empty($marriage_place) ||
        empty($province) ||
        empty($district) ||
        empty($municipality) ||
        empty($ward)
    ) {

        $message = "Please fill in all fields.";

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO marriage_registrations
            (groom_name, groom_dob, groom_citizenship_no,
             bride_name, bride_dob, bride_citizenship_no,
             marriage_date, marriage_place,
             province, district, municipality, ward)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssssssssssss",
            $groom_name,
            $groom_dob,
            $groom_citizenship_no,
            $bride_name,
            $bride_dob,
            $bride_citizenship_no,
            $marriage_date,
            $marriage_place,
            $province,
            $district,
            $municipality,
            $ward
        );


        if ($stmt->execute()) {

            $registration_id = $stmt->insert_id;

            $application_no = "MR-" . date("Y") . "-" . str_pad(
                $registration_id,
                6,
                "0",
                STR_PAD_LEFT
            );

            $type = "Marriage";
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
                    "Marriage registration submitted successfully! " .
                    "Application No: " . $application_no;

            } else {

                $message =
                    "Registration saved, but application creation failed.";

            }

            $app->close();

        } else {

            $message =
                "Marriage registration failed. Please try again.";

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

    <title>Marriage Registration - Nagarik Seva</title>

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

            <h1>Marriage Registration</h1>

            <h2>Enter Marriage Details</h2>


            <?php if (!empty($message)): ?>

                <p>
                    <?php echo htmlspecialchars($message); ?>
                </p>

            <?php endif; ?>


            <form method="POST">

                <h3>Groom Details</h3>

                <p>
                    <input
                        type="text"
                        name="groom_name"
                        placeholder="Groom Name"
                        required
                    >
                </p>

                <p>
                    <input
                        type="date"
                        name="groom_dob"
                        required
                    >
                </p>

                <p>
                    <input
                        type="text"
                        name="groom_citizenship_no"
                        placeholder="Groom Citizenship Number"
                        required
                    >
                </p>


                <h3>Bride Details</h3>

                <p>
                    <input
                        type="text"
                        name="bride_name"
                        placeholder="Bride Name"
                        required
                    >
                </p>

                <p>
                    <input
                        type="date"
                        name="bride_dob"
                        required
                    >
                </p>

                <p>
                    <input
                        type="text"
                        name="bride_citizenship_no"
                        placeholder="Bride Citizenship Number"
                        required
                    >
                </p>


                <h3>Marriage Details</h3>

                <p>
                    <input
                        type="date"
                        name="marriage_date"
                        required
                    >
                </p>

                <p>
                    <input
                        type="text"
                        name="marriage_place"
                        placeholder="Marriage Place"
                        required
                    >
                </p>


                <h3>Address Details</h3>

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
                    Submit Marriage Registration
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