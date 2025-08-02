<?php
date_default_timezone_set('Asia/Tehran');
session_start();
if (!isset($_SESSION['islogin'])) {
    header("location:../login.php");
    exit();
}
include '../config.php';
?>
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>مدیریت خبرها</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../pic/khabar1.png" rel="icon">
    <link rel="stylesheet" href="../assets/font-awesome.min.css">
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

        /* طراحی کارت‌های اخبار ثبت شده */
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

        .card-actions .fa-times {
            color: white;
            background-color: #ff7f50;
            font-weight: bold;
            font-size: 18px;
            box-shadow: 0 0 6px rgba(255, 127, 80, 0.8);
        }

        .card-actions .fa-times:hover {
            background-color: #ff5722;
        }

        .card-actions .fa-edit { color: #0984e3; }
        .card-actions .fa-check { color: #00b894; }

        @media (max-width: 600px) {
            .news-card {
                flex-direction: column;
            }

            .news-image {
                width: 100%;
                height: auto;
            }
        }
    </style>
</head>
<body>
<div class="topmenu">
    <ul>
        <li><a href="../index.php">مشاهده سایت</a></li>
        <li><a href="users.php">کاربران</a></li>
        <li><a href="cats.php">دسته‌بندی‌ها</a></li>
        <li><a href="comments.php">مدیریت دیدگاه‌ها</a></li>
        <li><a href="../logout.php">خروج</a></li>
    </ul>
</div>

<div class="container">

    <div class="tilearea">
        <h1>مدیریت اخبار</h1>
        <div class="news-cards">
            <?php
            $sql = "SELECT * FROM news ORDER BY id DESC";
            $res = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($res)) {
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
                            <a href="approve.php?id=<?php echo $row['id']; ?>&status=approved" class="fa fa-check" title="تأیید"></a>
                            <a href="approve.php?id=<?php echo $row['id']; ?>&status=rejected" class="fa fa-times" title="رد"></a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

</div>
</body>
</html>
