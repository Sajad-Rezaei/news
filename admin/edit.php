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
    $query = "UPDATE `news` SET `title`= '$title', `matn`='$matn', `status`='pending', `date`='$date', `catid`='$catid' $img_sql WHERE id=" . intval($_GET['fid']);
    mysqli_query($conn, $query);
}

$t = '';
$m = '';
$selectedCat = '';
$dateValue = '';
if (isset($_GET['fid'])) {
    $fid = intval($_GET['fid']);
    $sql = "SELECT * FROM `news` WHERE id=$fid";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);
    if ($row) {
        $t = $row['title'];
        $m = $row['matn'];
        $selectedCat = $row['catid'];
        $dateValue = $row['date'];
    }
}
?>
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8" />
    <title>ویرایش خبر</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="../pic/khabar1.png" rel="icon" />
    <link rel="stylesheet" href="../assets/font-awesome.min.css" />
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
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
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
        }
        input[type="text"],
        input[type="date"],
        input[type="file"],
        select,
        textarea {
            padding: 12px;
            font-size: 15px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            font-family: Tahoma, sans-serif;
        }
        textarea {
            height: 120px;
            resize: vertical;
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
            input[type="text"],
            input[type="date"],
            input[type="file"],
            select,
            textarea,
            #btn-send {
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
        <h3>فرم ویرایش خبر</h3>
        <form action="" method="post" enctype="multipart/form-data">
            <input name="title" type="text" placeholder="تیتر خبر" required value="<?php echo htmlspecialchars($t); ?>" />
            <textarea name="matn" placeholder="متن خبر" required><?php echo htmlspecialchars($m); ?></textarea>
            <select name="catid" required>
                <?php
                $sqlCats = "SELECT * FROM cats";
                $resCats = mysqli_query($conn, $sqlCats);
                while ($rowCat = mysqli_fetch_assoc($resCats)) {
                    $selected = ($rowCat['id'] == $selectedCat) ? 'selected' : '';
                    echo "<option value='{$rowCat['id']}' $selected>" . htmlspecialchars($rowCat['name']) . "</option>";
                }
                ?>
            </select>
            <input name="image" type="file" accept="image/*" />
            <input name="date" type="date" required value="<?php echo htmlspecialchars($dateValue); ?>" />
            <input type="submit" name="sabt" id="btn-send" value="ویرایش خبر" />
        </form>
    </div>
</div>
</body>
</html>
