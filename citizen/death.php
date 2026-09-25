<?php

session_start();

require_once "../database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "citizen") {
    header("Location: ../login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $deceased_name = trim($_POST["deceased_name"]);
    $date_of_birth = $_POST["date_of_birth"];
    $date_of_death = $_POST["date_of_death"];
    $gender = $_POST["gender"];
    $place_of_death = trim($_POST["place_of_death"]);
    $father_name = trim($_POST["father_name"]);
    $mother_name = trim($_POST["mother_name"]);
    $spouse_name = trim($_POST["spouse_name"]);
    $province = trim($_POST["province"]);
    $district = trim($_POST["district"]);
    $municipality = trim($_POST["municipality"]);
    $ward = trim($_POST["ward"]);

    if (
        empty($deceased_name) ||
        empty($date_of_birth) ||
        empty($date_of_death) ||
        empty($gender) ||
        empty($place_of_death) ||
        empty($father_name) ||
        empty($mother_name) ||
        empty($spouse_name) ||
        empty($province) ||
        empty($district) ||
        empty($municipality) ||
        empty($ward)
    ) {

        $message = "Please fill in all fields.";

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO death_registrations
            (deceased_name, date_of_birth, date_of_death, gender,
             place_of_death, father_name, mother_name, spouse_name,
             province, district, municipality, ward)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssssssssssss",
            $deceased_name,
            $date_of_birth,
            $date_of_death,
            $gender,
            $place_of_death,
            $father_name,
            $mother_name,
            $spouse_name,
            $province,
            $district,
            $municipality,
            $ward
        );

        if ($stmt->execute()) {

            $registration_id = $stmt->insert_id;

            $application_no = "DR-" . date("Y") . "-" . str_pad(
                $registration_id,
                6,
                "0",
                STR_PAD_LEFT
            );

            $type = "Death";
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
                    "Death registration submitted successfully! " .
                    "Application No: " . $application_no;

            } else {

                $message =
                    "Registration saved, but application creation failed.";

            }

            $app->close();

        } else {

            $message =
                "Death registration failed. Please try again.";

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

    <title>Death Registration - Nagarik Seva</title>

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

            <h1>Death Registration</h1>

            <h2>Enter Death Details</h2>

            <?php if (!empty($message)): ?>

                <p>
                    <?php echo htmlspecialchars($message); ?>
                </p>

            <?php endif; ?>


            <form method="POST">

                <p>
                    <input
                        type="text"
                        name="deceased_name"
                        placeholder="Deceased Name"
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
                    <input
                        type="date"
                        name="date_of_death"
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
                        name="place_of_death"
                        placeholder="Place of Death"
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
                        name="spouse_name"
                        placeholder="Spouse Name"
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
                    Submit Death Registration
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