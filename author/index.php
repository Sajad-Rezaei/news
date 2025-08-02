<?php
date_default_timezone_set('Asia/Tehran');
session_start();
if(!isset($_SESSION['islogin'])){
    header("location:../login.php");
    exit;
}
include '../config.php';

if (isset($_POST['sabt'])) {
    $userid = $_SESSION['userid'];
    $title = $_POST['title'];
    $matn = $_POST['matn'];
    $catid = $_POST['catid'];

    if ((isset($_FILES["image"])) && ($_FILES["image"]["size"] > 0)) {
        $tmpName = $_FILES["image"]["tmp_name"];
        $fp = fopen($tmpName, 'r');
        $image = fread($fp, filesize($tmpName));
        fclose($fp);

        $image = mysqli_real_escape_string($conn, $image);
    } else {
        $image = null;
    }

    $title = mysqli_real_escape_string($conn, $title);
    $matn = mysqli_real_escape_string($conn, $matn);
    $catid = mysqli_real_escape_string($conn, $catid);
    $date = $_POST['date'];

    $query = "INSERT INTO `news` (`title`,`matn`,`date`,`catid`,`pic`,`userid`, `status`) 
              VALUES ('$title','$matn','$date','$catid', " . ($image ? "'$image'" : "NULL") . ", '$userid', 'pending')";
    mysqli_query($conn, $query);

    // پاک‌سازی فرم با ریدایرکت
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$selectedCatid = isset($_POST['catid']) ? $_POST['catid'] : '';
?>
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>خبرها</title>
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
        h3, h1 {
            color: #2d3436;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-grid {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .form-group {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group input[type="file"],
        .form-group select,
        .form-group textarea {
            flex: 1 1 45%;
            min-width: 250px;
            padding: 12px;
            font-size: 15px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
        }
        .form-group textarea {
            width: 100%;
            height: 120px;
        }
        .form-group.center {
            justify-content: center;
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
        }
        #btn-send:hover {
            background-color: #0652DD;
        }
        .news-cards {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .news-card {
            display: flex;
            flex-wrap: wrap;
            background-color: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s;
        }
        .news-card:hover {
            transform: translateY(-5px);
        }
        .news-image {
            flex: 1 1 180px;
            max-width: 200px;
            height: 100%;
            object-fit: cover;
        }
        .news-content {
            flex: 2 1 300px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .news-content h4 {
            font-size: 18px;
            color: #2d3436;
            margin-bottom: 10px;
        }
        .news-content p {
            font-size: 14px;
            color: #636e72;
            margin-bottom: 8px;
            word-break: break-word;
        }
        .news-meta {
            font-size: 13px;
            color: #888;
            margin-bottom: 10px;
        }
        .card-actions {
            display: flex;
            justify-content: flex-start;
            gap: 10px;
            margin-top: 10px;
        }
        .card-actions a {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #dfe6e9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2d3436;
            text-decoration: none;
            transition: background 0.3s ease;
        }
        .card-actions a:hover {
            background-color: #b2bec3;
        }
        .card-actions .fa-remove {
            color: #d63031;
            background-color: #f8d7da;
            box-shadow: 0 0 5px rgba(214, 48, 49, 0.6);
        }
        .card-actions .fa-remove:hover {
            background-color: #f5b7b1;
        }
        .card-actions .fa-edit { color: #0984e3; }
        .card-actions .fa-check { color: #00b894; }
        @media (max-width: 600px) {
            .form-group input,
            .form-group select,
            .form-group textarea {
                flex: 1 1 100%;
            }
            .news-card {
                flex-direction: column;
            }
            .news-image {
                width: 100%;
                height: auto;
            }
        }
        #selectedCatName {
            text-align: center;
            margin-top: 8px;
            font-weight: bold;
            color: #2d3436;
        }
    </style>
</head>
<body>
<div class="topmenu">
    <ul>
        <li><a href="../index.php">مشاهده سایت</a></li>
        <li><a href="../logout.php">خروج از حساب</a></li>
    </ul>
</div>

<div class="container">

    <div class="tilearea">
        <h1>خبرهای ارسال‌شده</h1>
        <div class="news-cards">
            <?php
            $userid = $_SESSION['userid'];
            $sqlNews = "SELECT * FROM news WHERE userid = $userid ORDER BY id DESC";
            $resNews = mysqli_query($conn, $sqlNews);
            if (mysqli_num_rows($resNews) > 0) {
                while ($row = mysqli_fetch_assoc($resNews)) {
                    $catid = (int)$row['catid'];
                    $catQuery = mysqli_query($conn, "SELECT name FROM cats WHERE id = $catid");
                    $catName = mysqli_fetch_assoc($catQuery)['name'] ?? '---';

                    $shortMatn = mb_substr(strip_tags($row['matn']), 0, 100, 'UTF-8') . '...';

                    $imgSrc = $row['pic'] ? "data:image/jpeg;base64," . base64_encode($row['pic']) : "../pic/default-news.png";
                    ?>
                    <div class="news-card">
                        <img class="news-image" src="<?php echo $imgSrc; ?>" alt="تصویر خبر">
                        <div class="news-content">
                            <h4><?php echo htmlspecialchars($row['title']); ?></h4>
                            <p><?php echo htmlspecialchars($shortMatn); ?></p>
                            <div class="news-meta">
                                <span>تاریخ: <?php echo htmlspecialchars($row['date']); ?></span> |
                                <span>دسته: <?php echo htmlspecialchars($catName); ?></span> |
                                <span>وضعیت: <?php echo htmlspecialchars($row['status']); ?></span>
                            </div>
                            <div class="card-actions">
                                <a href="edit.php?fid=<?php echo $row['id']; ?>" class="fa fa-edit" title="ویرایش"></a>
                                <a href="delete.php?fid=<?php echo $row['id']; ?>" class="fa fa-remove" title="حذف"></a>
                            </div>
                        </div>
                    </div>
                <?php }
            } else {
                echo "<p style='text-align:center; color:#666;'>خبری از سمت شما وارد نشده.</p>";
            }
            ?>
        </div>
    </div>

    <div class="tilearea">
        <h3>ارسال خبر جدید</h3>
        <form action="" method="post" enctype="multipart/form-data" class="form-grid" id="newsForm">
            <div class="form-group">
                <input name="title" type="text" placeholder="تیتر خبر" required value="">
                <input type="date" name="date" required value="">
            </div>
            <div class="form-group">
                <select name="catid" id="catSelect" required>
                    <option value="">دسته بندی را انتخاب کنید</option>
                    <?php
                    $sql = "SELECT * FROM cats";
                    $res = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($res)) {
                        echo "<option value='{$row['id']}'>" . htmlspecialchars($row['name']) . "</option>";
                    }
                    ?>
                </select>
                <input name="image" type="file" accept="image/*">
            </div>
            <div class="form-group">
                <textarea name="matn" placeholder="متن خبر" required></textarea>
            </div>
            <div class="form-group center">
                <input type="submit" name="sabt" value="ثبت خبر" id="btn-send">
            </div>
        </form>
        <div id="selectedCatName">
            <?php
            if ($selectedCatid) {
                $catNameQ = mysqli_query($conn, "SELECT name FROM cats WHERE id = $selectedCatid");
                $catNameR = mysqli_fetch_assoc($catNameQ);
                echo htmlspecialchars($catNameR['name']);
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>
