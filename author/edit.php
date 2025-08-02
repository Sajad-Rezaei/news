<?php
date_default_timezone_set('Asia/Tehran');
session_start();
if (!isset($_SESSION['islogin'])) {
    header("location:../login.php");
    exit();
}
include '../config.php';

if (isset($_POST['sabt'])) {
    $userid = $_SESSION['userid'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $matn = mysqli_real_escape_string($conn, $_POST['matn']);
    $catid = mysqli_real_escape_string($conn, $_POST['catid']);

    if ((isset($_FILES["image"])) && ($_FILES["image"]["size"] > 0)) {
        $tmpName = $_FILES["image"]["tmp_name"];
        $fp = fopen($tmpName, 'r');
        $image = fread($fp, filesize($tmpName));
        $image = mysqli_real_escape_string($conn, $image);
        fclose($fp);
        $img_sql = ", `pic`='$image'";
    } else {
        $img_sql = "";
    }

    $date = $_POST['date'];
    $fid = intval($_GET['fid']);
    $query = "UPDATE `news` SET `title`= '$title', `matn`='$matn', `status`='pending', `date`='$date', `catid`='$catid' $img_sql WHERE id=$fid";
    mysqli_query($conn, $query);
}

$t = '';
$m = '';
$currentCat = '';
$currentDate = '';
if (isset($_GET['fid'])) {
    $fid = intval($_GET['fid']);
    $sql = "SELECT * FROM `news` WHERE id=$fid";
    $res = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($res)) {
        $t = $row['title'];
        $m = $row['matn'];
        $currentCat = $row['catid'];
        $currentDate = $row['date'];
    }
}
?>
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="utf-8">
    <title>ویرایش خبر</title>
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
        .topmenu ul {
            list-style: none;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
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
            background-color: #ffffff;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        h3 {
            color: #2d3436;
            margin-bottom: 20px;
            text-align: center;
        }
        form input[type="text"],
        form input[type="file"],
        form input[type="date"],
        form textarea,
        form select {
            display: block;
            width: 80%;
            margin: 10px auto;
            padding: 10px 15px;
            border: 1px solid #b2bec3;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
        }
        form input[type="text"]:focus,
        form input[type="file"]:focus,
        form input[type="date"]:focus,
        form textarea:focus,
        form select:focus {
            border-color: #00cec9;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 206, 201, 0.5);
        }
        #btn-send {
            background-color: #0984e3;
            color: #fff;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
            border-radius: 8px;
            padding: 10px 25px;
            margin: 15px auto;
            display: block;
        }
        #btn-send:hover {
            background-color: #0652DD;
        }
    </style>
</head>
<body>
<div class="topmenu">
    <div class="container">
        <ul>
            <li style="margin-top: 10px;"><a href="../index.php">مشاهده سایت</a></li>
            <li style="margin-top: 10px;"><a href="../logout.php">خروج از حساب</a></li>
        </ul>
    </div>
</div>

<div class="container">
    <div class="tilearea">
        <h3>فرم ویرایش خبر</h3>
        <form action="" method="post" enctype="multipart/form-data">
            <input name="title" type="text" placeholder="تیتر خبر" required value="<?php echo htmlspecialchars($t); ?>">
            <textarea name="matn" placeholder="متن خبر" style="height: 80px" required><?php echo htmlspecialchars($m); ?></textarea>
            <select name="catid" required>
                <option value="">دسته‌بندی را انتخاب کنید</option>
                <?php
                $cats = mysqli_query($conn, "SELECT * FROM cats");
                while ($cat = mysqli_fetch_assoc($cats)) {
                    $selected = ($cat['id'] == $currentCat) ? 'selected' : '';
                    echo "<option value='{$cat['id']}' $selected>{$cat['name']}</option>";
                }
                ?>
            </select>
            <input name="image" type="file">
            <input type="date" name="date" required value="<?php echo htmlspecialchars($currentDate); ?>">
            <input type="submit" name="sabt" value="ویرایش خبر" id="btn-send">
        </form>
    </div>
</div>
</body>
</html>
