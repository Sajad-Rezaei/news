<?php
session_start();
if (!isset($_SESSION['islogin'])) {
    header("location:../login.php");
    exit;
}
include '../config.php';

if (isset($_POST['sabt'])) {
    $name = $_POST['name'];
    if (!isset($_GET['fid'])) {
        $query = "INSERT INTO `cats` (`name`) VALUES ('$name')";
    } else {
        $query = "UPDATE `cats` SET `name`='$name' WHERE id=" . (int)$_GET['fid'];
    }
    mysqli_query($conn, $query);
    header("location:cats.php");
    exit;
}

$catname = '';
if (isset($_GET['fid'])) {
    $sql = "SELECT * FROM cats WHERE id=" . (int)$_GET['fid'];
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);
    $catname = $row['name'];
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>مدیریت دسته‌بندی‌ها</title>
    <link href="../pic/khabar1.png" rel="icon">
    <link href="../assets/font-awesome.min.css" rel="stylesheet">
    <style>
        @charset "utf-8";
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            direction: rtl;
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
        .topmenu .container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 40px;
        }
        .topmenu ul {
            list-style: none;
            display: flex;
            gap: 30px;
            margin: 0;
            padding: 0;
        }
        .topmenu ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
            padding: 8px 12px;
            border-radius: 6px;
        }
        .topmenu ul li a:hover {
            color: #00cec9;
            background-color: rgba(0, 206, 201, 0.1);
            transition: all 0.3s ease;
        }
        .container {
            max-width: 900px;
            margin: auto;
            padding: 20px;
        }
        .form-box {
            background-color: #fff;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 35px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        .form-box h3 {
            text-align: center;
            color: #2d3436;
            margin-bottom: 20px;
        }
        .form-box input[type="text"] {
            width: 90%;
            margin: 10px auto;
            display: block;
            padding: 12px 15px;
            font-size: 16px;
            border: 1px solid #b2bec3;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
        }
        .form-box input[type="text"]:focus {
            border-color: #00cec9;
            outline: none;
            box-shadow: 0 0 6px rgba(0,206,201,0.6);
        }
        .form-box input[type="submit"] {
            background-color: #0984e3;
            color: #fff;
            font-weight: bold;
            border: none;
            cursor: pointer;
            border-radius: 8px;
            padding: 12px 0;
            margin: 15px auto 0;
            width: 90%;
            font-size: 18px;
            transition: background 0.3s ease;
            display: block;
        }
        .form-box input[type="submit"]:hover {
            background-color: #0652DD;
        }
        .cards-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            width: 250px;
            padding: 20px;
            position: relative;
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.15);
        }
        .card h4 {
            margin-bottom: 15px;
            color: #2d3436;
            font-size: 20px;
            text-align: center;
        }
        .card .actions {
            display: flex;
            justify-content: center;
            gap: 25px;
        }
        .card .fa {
            font-size: 22px;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }
        .card .fa-edit {
            color: #0984e3;
        }
        .card .fa-edit:hover {
            background-color: #d0e8ff;
        }
        .card .fa-remove {
            color: #d63031;
        }
        .card .fa-remove:hover {
            background-color: #ffd6d6;
        }
    </style>
</head>
<body>

<div class="topmenu">
    <div class="container">
        <img src="../pic/tabarnews1.png" width="158" height="100" alt="خبر" style="vertical-align: middle;">
        <ul>
            <li><a href="../index.php">مشاهده سایت</a></li>
            <li><a href="index.php">خبرها</a></li>
            <li><a href="users.php">کاربران</a></li>
            <li><a href="../logout.php">خروج از حساب</a></li>
        </ul>
    </div>
</div>

<div class="container">
    <div class="form-box">
        <h3>دسته‌بندی جدید</h3>
        <form method="post" autocomplete="off">
            <input type="text" name="name" placeholder="نام دسته‌بندی" value="<?php echo htmlspecialchars($catname); ?>" required>
            <input type="submit" name="sabt" value="ثبت دسته">
        </form>
    </div>

    <div class="cards-container">
        <?php
        $sql = "SELECT * FROM cats ORDER BY id DESC";
        $res = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($res)) {
            $id = (int)$row['id'];
            $name = htmlspecialchars($row['name']);
            echo "
            <div class='card'>
                <h4>$name</h4>
                <div class='actions'>
                    <a href='cats.php?fid=$id' title='ویرایش' class='fa fa-edit'></a>
                    <a href='deletecat.php?fid=$id' title='حذف' class='fa fa-remove' onclick='return confirm(\"آیا از حذف این دسته‌بندی مطمئن هستید؟\")'></a>
                </div>
            </div>
            ";
        }
        ?>
    </div>
</div>

</body>
</html>
