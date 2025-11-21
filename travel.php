<?php
session_start();

// Simple in-memory user storage (replace with a database for real use)
if (!isset($_SESSION['users'])) {
    $_SESSION['users'] = [];
}

// Registration handler
if (isset($_POST['register'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        echo "<p style='color:red;'>Username and password are required.</p>";
    } elseif (isset($_SESSION['users'][$username])) {
        echo "<p style='color:red;'>Username already exists. Try another.</p>";
    } else {
        $_SESSION['users'][$username] = $password;
        echo "<p style='color:green;'>Registration successful! Please log in.</p>";
    }
}

// Login handler
if (isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (isset($_SESSION['users'][$username]) && $_SESSION['users'][$username] === $password) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
    } else {
        echo "<p style='color:red;'>Invalid login. Try again.</p>";
    }
}

// Logout handler
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Booking handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])) {
    $city    = $_POST['city'] ?? '';
    $country = $_POST['country'] ?? '';
    $start   = $_POST['start_date'] ?? '';
    $end     = $_POST['end_date'] ?? '';
    $payment = $_POST['payment'] ?? '';
    $review  = $_POST['review'] ?? '';

    $errors = [];
    if ($city === '')    $errors[] = "Please select a sponsored city.";
    if ($country === '') $errors[] = "Please select a country.";
    if ($start === '')   $errors[] = "Please select a start date.";
    if ($end === '')     $errors[] = "Please select an end date.";
    if ($payment === '') $errors[] = "Please select a payment method.";

    if (!empty($errors)) {
        echo "<div style='color:red;'><ul>";
        foreach ($errors as $e) {
            echo "<li>" . htmlspecialchars($e) . "</li>";
        }
        echo "</ul></div>";
    } else {
        echo "<h2>Booking Confirmation</h2>";
        echo "User: " . htmlspecialchars($_SESSION['username'] ?? 'Guest') . "<br>";
        echo "Sponsored City: " . htmlspecialchars($city) . "<br>";
        echo "Country: " . htmlspecialchars($country) . "<br>";
        echo "Start Date: " . htmlspecialchars($start) . "<br>";
        echo "End Date: " . htmlspecialchars($end) . "<br>";
        echo "Payment Method: " . htmlspecialchars($payment) . "<br>";
        echo "Review: " . nl2br(htmlspecialchars($review)) . "<br>";
        echo "<p><strong>Thank you! Your booking has been recorded.</strong></p>";
        echo "<p><a href='?logout=1'>Logout</a></p>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ClubTravalia Booking</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; }
        h1 { margin-bottom: 8px; }
        .card { border: 1px solid #ddd; padding: 16px; border-radius: 8px; margin-bottom: 20px; max-width: 540px; }
        .row { margin-bottom: 12px; }
        label { display: inline-block; width: 160px; }
        input[type="text"], input[type="password"], input[type="date"], select, textarea { width: 60%; padding: 6px; }
        textarea { width: 60%; }
        .actions { margin-top: 12px; }
        .actions input { padding: 8px 14px; }
    </style>
</head>
<body>
    <h1>ClubTravalia Booking</h1>

    <?php if (!isset($_SESSION['loggedin'])) { ?>
        <div class="card">
            <h2>Register</h2>
            <form method="post" action="">
                <div class="row">
                    <label for="reg_username">Username</label>
                    <input id="reg_username" type="text" name="username" required>
                </div>
                <div class="row">
                    <label for="reg_password">Password</label>
                    <input id="reg_password" type="password" name="password" required>
                </div>
                <div class="actions">
                    <input type="submit" name="register" value="Register">
                </div>
            </form>
        </div>

        <div class="card">
            <h2>Login</h2>
            <form method="post" action="">
                <div class="row">
                    <label for="log_username">Username</label>
                    <input id="log_username" type="text" name="username" required>
                </div>
                <div class="row">
                    <label for="log_password">Password</label>
                    <input id="log_password" type="password" name="password" required>
                </div>
                <div class="actions">
                    <input type="submit" name="login" value="Login">
                </div>
            </form>
        </div>
    <?php } else { ?>
        <div class="card">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <form method="post" action="">
                <div class="row">
                    <label for="city">Sponsored city</label>
                    <select id="city" name="city" required>
                        <option value="">-- Select City --</option>
                        <option value="Paris">Paris</option>
                        <option value="Dubai">Dubai</option>
                        <option value="Singapore">Singapore</option>
                        <option value="New York">New York</option>
                        <option value="Bengaluru">Bengaluru</option>
                    </select>
                </div>

                <div class="row">
                    <label for="country">Country</label>
                    <select id="country" name="country" required>
                        <option value="">-- Select Country --</option>
                        <option value="France">France</option>
                        <option value="UAE">UAE</option>
                        <option value="Singapore">Singapore</option>
                        <option value="USA">USA</option>
                        <option value="India">India</option>
                    </select>
                </div>

                <div class="row">
                    <label for="start_date">Start date</label>
                    <input id="start_date" type="date" name="start_date" required>
                </div>

                <div class="row">
                    <label for="end_date">End date</label>
                    <input id="end_date" type="date" name="end_date" required>
                </div>

                <div class="row">
                    <label for="payment">Payment method</label>
                    <select id="payment" name="payment" required>
                        <option value="">-- Select Payment --</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="Debit Card">Debit Card</option>
                        <option value="UPI">UPI</option>
                        <option value="Net Banking">Net Banking</option>
                    </select>
                </div>

                <div class="row">
                    <label for="review">Review</label>
                    <textarea id="review" name="review" rows="4" placeholder="Write your review here..."></textarea>
                </div>

                <div class="actions">
                    <input type="submit" name="book" value="Book Now">
                    <a href="?logout=1" style="margin-left:12px;">Logout</a>
                </div>
            </form>
        </div>
    <?php } ?>
</body>
</html>