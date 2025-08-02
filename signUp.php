<?php
session_start();
include 'config.php';

if (isset($_POST['register'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $check = mysqli_query($conn, "SELECT * FROM users WHERE user='$username' OR email='$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "<p style='color:red; text-align:center;'>❌ این نام کاربری یا ایمیل قبلاً ثبت شده است.</p>";
    } else {
        $query = "INSERT INTO `users` (`fname`,`lname`,`email`,`user`,`pass`) VALUES ('$fname','$lname','$email','$username','$password')";
        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = "✅ ثبت‌نام با موفقیت انجام شد.";
            header("Location: login.php");
            exit();
        } else {
            echo "<p style='color:red; text-align:center;'>❌ خطا در ثبت‌نام رخ داد.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ثبت‌نام نویسنده</title>
    <link href="pic/khabar1.png" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Vazirmatn', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to left, rgba(0, 121, 107, 0.9), rgba(0, 77, 64, 0.9)),
                        url('https://images.unsplash.com/photo-1503264116251-35a269479413?auto=format&fit=crop&w=1600&q=80') no-repeat center center fixed;
            background-size: cover;
        }

        .topmenu {
            background-color: rgba(192, 190, 190, 0.95);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 12px 0;
            direction: rtl;
        }

        .topmenu .container {
            width: 90%;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: row-reverse;
        }

        .topmenu ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }

        .topmenu ul li {
            margin: 0 12px;
            display: flex;
            align-items: center;
        }

        .topmenu ul li a {
            text-decoration: none;
            color: #004d40;
            font-weight: bold;
            font-size: 15px;
            transition: color 0.3s ease;
        }

        .topmenu ul li a:hover {
            color: #00251a;
        }

        .container {
            max-width: 500px;
            background-color: rgba(255, 255, 255, 0.95);
            margin: 80px auto;
            padding: 35px 40px;
            
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h3 {
            text-align: center;
            color: #004d40;
            margin-bottom: 25px;
        }

        form input[type="text"],
        form input[type="email"],
        form input[type="password"] {
            width: 100%;
            padding: 13px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 15px;
        }

        form input[type="submit"] {
            width: 100%;
            background-color: #00796b;
            color: white;
            border: none;
            padding: 13px;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #004d40;
        }

        .clear {
            clear: both;
        }

        .socialicons img {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="topmenu">
    <div class="container">
        <ul>
            <li><a href="index.php">صفحه اصلی</a></li>
            <?php
            if (!isset($_SESSION['islogin'])) {
                echo "<li><a href=\"login.php\">ورود</a></li>";
            } else {
                echo "<li><a href=\"logout.php\">خروج</a></li>";
            }
            ?>
        </ul>
    </div>
</div>

<div class="container">
    <h3>ثبت نام خبرنگار</h3>
    <form method="post">
        <input name="fname" type="text" placeholder="نام شما" required>
        <input name="lname" type="text" placeholder="نام خانوادگی" required>
        <input name="email" type="email" placeholder="ایمیل" required>
        <input name="username" type="text" placeholder="نام کاربری" required>
        <input name="password" type="password" placeholder="رمز عبور" required>
        <input type="submit" name="register" value="ایجاد حساب کاربری">
    </form>
</div>

</body>
</html>
