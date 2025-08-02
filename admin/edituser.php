<?php
session_start();
if (!isset($_SESSION['islogin'])) {
    header("location:../login.php");
    exit();
}
include '../config.php';

if (isset($_POST['sabt'])) {
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $pass = mysqli_real_escape_string($conn, $_POST['pass']);
    $fid = intval($_GET['fid']);
    $query = "UPDATE `users` SET 
        `fname`= '$fname', 
        `lname`= '$lname', 
        `email`= '$email', 
        `user`= '$user', 
        `pass`= '$pass' 
        WHERE id=$fid";
    mysqli_query($conn, $query);
}

$row = null;
if (isset($_GET['fid'])) {
    $fid = intval($_GET['fid']);
    $sql = "SELECT * FROM `users` WHERE id=$fid";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);
}
?>
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8" />
    <title>ویرایش کاربر</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="../pic/khabar1.png" rel="icon" />
    <link rel="stylesheet" href="../assets/font-awesome.min.css" />
    <style>
        @charset "utf-8";
        * {
            margin: 0; padding: 0; box-sizing: border-box; direction: rtl;
            font-family: Tahoma, sans-serif;
        }
        body {
            background: linear-gradient(to left, #f2f2f2, #dfe6e9);
            padding: 20px 0;
        }
        .topmenu {
            background-color: #2d3436;
            padding: 15px 0;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        }
        .topmenu ul {
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }
        .topmenu ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        .topmenu ul li a:hover {
            color: #00cec9;
        }
        .container {
            max-width: 1100px;
            margin: auto;
            padding: 20px;
        }
        .tilearea {
            background-color: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            margin-bottom: 25px;
        }
        h3 {
            color: #2d3436;
            margin-bottom: 20px;
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 600px;
            margin: 0 auto;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            padding: 12px;
            font-size: 15px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
            width: 100%;
            font-family: Tahoma, sans-serif;
        }
        #btn-send {
            background-color: #0984e3;
            color: #fff;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
            border-radius: 8px;
            padding: 12px 25px;
            max-width: 200px;
            margin: 0 auto;
        }
        #btn-send:hover {
            background-color: #0652DD;
        }
        @media (max-width: 600px) {
            form {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
<div class="topmenu">
    <ul>
        <li><a href="../index.php">مشاهده سایت</a></li>
        <li><a href="users.php">کاربران</a></li>
        <li><a href="index.php">خبرها</a></li>
        <li><a href="cats.php">دسته‌بندی‌ها</a></li>
        <li><a href="../logout.php">خروج</a></li>
    </ul>
</div>
<div class="container">
    <div class="tilearea">
        <h3>فرم ویرایش کاربر</h3>
        <?php if ($row): ?>
        <form action="" method="post">
            <input name="fname" type="text" placeholder="نام" required value="<?php echo htmlspecialchars($row['fname']); ?>" />
            <input name="lname" type="text" placeholder="نام خانوادگی" required value="<?php echo htmlspecialchars($row['lname']); ?>" />
            <input name="email" type="email" placeholder="ایمیل" required value="<?php echo htmlspecialchars($row['email']); ?>" dir="ltr" />
            <input name="user" type="text" placeholder="نام کاربری" required value="<?php echo htmlspecialchars($row['user']); ?>" dir="ltr" />
            <input name="pass" type="password" placeholder="رمز عبور" required value="<?php echo htmlspecialchars($row['pass']); ?>" dir="ltr" />
            <input type="submit" name="sabt" id="btn-send" value="ویرایش کاربر" />
        </form>
        <?php else: ?>
            <p style="text-align:center; color: red;">کاربر مورد نظر یافت نشد.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
