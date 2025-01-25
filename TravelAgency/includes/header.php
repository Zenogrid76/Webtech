<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travello</title>
    <link rel="stylesheet" href="assets/css/headerstyle.css">
</head>

<body>
    <header>
        <div class="logo">
            <img src="assets/Images/logo.png" alt="Logo">
        </div>
        <nav class="navbar">
            <a href="index.php">Home</a>
            <a href="#special-deals">Packages</a>
            <a href="pages/auth/login.php">Booking</a>
        </nav>
        <div class="auth-buttons">
            <button class="login" onclick="window.location.href='pages/auth/login.php';">Login</button>
            <button class="signup" onclick="window.location.href='pages/auth/signup.php';">Sign Up</button>
        </div>
    </header>

    <script>
        window.addEventListener("scroll", function() {
            const header = document.querySelector("header");
            if (window.scrollY > 0) {
                header.classList.add("scrolled");
            } else {
                header.classList.remove("scrolled");
            }
        });
    </script>

</body>

</html>