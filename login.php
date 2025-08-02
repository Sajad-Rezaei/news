<?php
session_start();
include 'config.php';

$err = '';

if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    if ($user === 'admin' && $pass === 'admin') {
        $_SESSION['islogin'] = true;
        $_SESSION['username'] = 'admin';
        $_SESSION['userid'] = $row['id'];
        header("Location: admin/index.php");
        exit();
    }

    $sql = "SELECT * FROM users WHERE user='$user' AND pass='$pass'";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
        $_SESSION['islogin'] = true;
        $_SESSION['username'] = $row['user'];
        $_SESSION['fname'] = $row['fname'];
        $_SESSION['lname'] = $row['lname'];
        $_SESSION['userid'] = $row['id'];
        header("Location: index.php");
        exit();
    } else {
        $err = "نام کاربری یا رمز عبور اشتباه است";
    }
}
?>


<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title> ورود به عنوان : ادمین / خبرنگار </title>
    <link href="pic/khabar1.png" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Vazirmatn', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to left, rgba(0, 121, 107, 0.8), rgba(0, 77, 64, 0.85)),
                        url('https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1950&q=80') no-repeat center center fixed;
            background-size: cover;
        }

        .topmenu {
            background-color: rgba(24, 20, 20, 0.4);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 12px 0;
        
        }

        .topmenu .container {
            width: 90%;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: space-around;
            gap:300px;
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
        }

        .topmenu ul li a {
            text-decoration: none;
            color: #e6edecff;
            font-weight: bold;
            font-size: 15px;
            transition: color 0.3s ease;
        }

        .topmenu ul li a:hover {
            color: #818684ff;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 35px 45px;
            border-radius: 20px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 420px;
            margin: 80px auto;
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-container h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #004d40;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 13px;
            margin-bottom: 18px;
            border: 1px solid #bbb;
            border-radius: 12px;
            font-size: 15px;
            direction: rtl;
        }

        .login-container input[type="submit"] {
            width: 100%;
            background-color: #00796b;
            color: white;
            border: none;
            padding: 13px;
            border-radius: 12px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-container input[type="submit"]:hover {
            background-color: #004d40;
        }

        .login-container .error {
            margin-top: 10px;
            color: red;
            text-align: center;
            font-size: 14px;
        }

        .register-link {
            display: block;
            text-align: center;
            margin-top: 25px;
            color: #004d40;
            text-decoration: none;
            font-size: 14px;
        }

        .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="topmenu">
    <div class="container">
        <ul>
            <li><a href="index.php"> بازگشت به صفحه اصلی</a></li>
            <li><a href="signUp.php"> ثبت نام خبرنگار جدید</a></li>
            
        </ul>
    </div>
</div>

<div class="login-container">
    <h1> ورود به عنوان : ادمین / خبرنگار </h1>
    <form method="post">
        <input type="text" name="username" placeholder="نام کاربری" required>
        <input type="password" name="password" placeholder="رمز عبور" required>
        <input type="submit" name="login" value="ورود">
        <?php if ($err != ''): ?>
            <div class="error"><?php echo $err; ?></div>
        <?php endif; ?>
    </form>
    <a class="register-link" href="signUp.php">هنوز ثبت‌نام نکرده‌اید؟ کلیک کنید</a>
</div>

</body>
</html>
