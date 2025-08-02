<?php
session_start();
include 'config.php';

// بررسی وجود پارامتر id دسته‌بندی
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$cat_id = intval($_GET['id']);
?>
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="utf-8">
    <title>خبرنو - دسته‌بندی</title>
    <link rel="stylesheet" href="assets/site1.css">
    <link href="pic/khabar1.png" rel="icon">
    <link href="assets/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/owl.carousel.css">
    <link rel="stylesheet" href="assets/owl.theme.css">
    <link rel="stylesheet" href="./assets/style4.css">
    <script src="js/jquery-1.9.1.min.js"></script>
    <script src="js/owl.carousel.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/fs.js"></script>

    <style>
        /* استایل کارت‌ها با چینش ردیفی و راست‌چین */
        .news-cards-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            direction: rtl; /* کارت‌ها از راست به چپ چیده شوند */
            gap: 15px;
            margin-top: 20px;
        }
        .news-card {
            width: 23%; /* چهار کارت در هر ردیف */
            box-sizing: border-box;
            direction: ltr; /* جهت متن داخل کارت */
            background: #fff;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgb(0 0 0 / 0.1);
            overflow: hidden;
            margin-bottom: 15px;
            transition: box-shadow 0.3s ease;
        }
        .news-card:hover {
            box-shadow: 0 5px 15px rgb(0 0 0 / 0.2);
        }
        .news-img-link img.news-img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: block;
        }
        .news-content {
            padding: 10px 15px;
        }
        .news-title {
            font-weight: bold;
            color: #333;
            text-decoration: none;
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
            line-height: 1.3;
        }
        .news-title:hover {
            color: #007bff;
        }
        .news-meta {
            font-size: 12px;
            color: #777;
            margin-bottom: 10px;
            display: flex;
            justify-content: flex-start;
            gap: 10px;
        }
        .news-meta i {
            margin-left: 4px;
        }
        .news-excerpt {
            font-size: 13px;
            color: #555;
            line-height: 1.4;
            min-height: 45px;
        }
    </style>

</head>
<body>
<div class="topmenu">
    <div class="container">
        <div class="socialicons fbicon">
            <img src="pic/tabarnews1.png" width="158" height="90" alt="خبر">
        </div>
        <ul>
            <li style="margin-top: 5px;"><img src="pic/tabarnews1.png" width="158" height="58" alt="خبر"></li>
            <li style="margin-top: 10px;"><a href="index.php">صفحه اصلی</a></li>
            <?php
            if (!isset($_SESSION['islogin'])) {
                echo "<li style=\"margin-top: 10px;\"><a href=\"login.php\" style=\"float: left;\">ورود</a></li>";
            } else {
                if (isset($_SESSION['username']) && $_SESSION['username'] === 'admin') {
                    echo "<li style=\"margin-top: 10px;\"><a href=\"admin/index.php\" style=\"float: left;\">پنل مدیریت</a></li>";
                } else {
                    echo "<li style=\"margin-top: 10px;\"><a href=\"author/index.php\" style=\"float: left;\">خبرهای من</a></li>";
                }
                echo "<li style=\"margin-top: 10px;\"><a href=\"logout.php\" style=\"float: left;\">خروج</a></li>";
            }
            ?>
            <li style="margin-top: 10px;"><a href="#">درباره ما</a></li>
        </ul>
    </div>
    <div class="clear"></div>
</div>

<div class="mainmenus">
    <div class="container">
        <ul>
            <?php
            $sql = "SELECT * FROM cats";
            $res = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($res)) {
                echo "<li><a href='category.php?id={$row['id']}'>{$row['name']}</a></li>";
            }
            ?>
        </ul>
    </div>
    <div class="clear"></div>
</div>

<div class="container">
    <div class="titlezone" style="margin-top: 30px">
        <?php
        $sql = "SELECT * FROM cats WHERE id = $cat_id";
        $res = mysqli_query($conn, $sql);
        $cat = mysqli_fetch_assoc($res);
        $title = $cat['name'] ?? 'دسته‌بندی نامشخص';
        ?>
        <h3><?php echo htmlspecialchars($title); ?></h3>
        <div class="clear"></div>
    </div>

    <div class="news-cards-container">
        <?php
        // گرفتن اخبار تایید شده آن دسته
        $sql = "SELECT news.*, users.user AS username, cats.name AS cat_name 
                FROM news 
                LEFT JOIN users ON news.userid = users.id 
                LEFT JOIN cats ON news.catid = cats.id 
                WHERE news.catid = $cat_id AND news.status = 'approved' 
                ORDER BY news.id DESC";
        $res = mysqli_query($conn, $sql);

        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                ?>
                <div class="news-card">
                    <a href="Display.php?id=<?php echo $row['id']; ?>" class="news-img-link">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($row['pic']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" class="news-img" />
                    </a>
                    <div class="news-content">
                        <a href="Display.php?id=<?php echo $row['id']; ?>" class="news-title" title="<?php echo htmlspecialchars($row['title']); ?>">
                            <?php echo mb_strimwidth($row['title'], 0, 35, '...'); ?>
                        </a>
                        <div class="news-meta">
                            <span><i class="fa fa-user"></i> <?php echo htmlspecialchars($row['username'] ?? 'کاربر نامشخص'); ?></span>
                            <span><i class="fa fa-calendar-o"></i> <?php echo htmlspecialchars($row['date']); ?></span>
                            <span><i class="fa fa-newspaper-o"></i> <?php echo htmlspecialchars($row['cat_name'] ?? ''); ?></span>
                        </div>
                        <p class="news-excerpt"><?php echo mb_strimwidth(strip_tags($row['matn']), 0, 90, '...'); ?></p>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>خبری در این دسته‌بندی موجود نیست.</p>";
        }
        ?>
    </div>

    <div class="clear"></div>

    <div class="titlezone">
        <h3>تبلیغات</h3>
        <div class="clear"></div>
    </div>
    <div class="tabliq">
        <li><a href="#">با کوین‌نکس، سرمایه‌گذاری امن در بازار ارز دیجیتال</a></li>
        <li><a href="#">خرید بلیط هواپیما از معتبرترین ایرلاین‌ها با تخفیف ویژه</a></li>
        <li><a href="#">لپ‌تاپ قسطی با ارسال فوری، فقط در دیجیتال‌مارکت</a></li>
        <li><a href="#">با 50% تخفیف، بلیط اتوبوس به سراسر ایران</a></li>
        <li><a href="#">یادگیری زبان انگلیسی در 3 ماه، تضمینی! همین حالا شروع کن</a></li>
    </div>

    <div class="im">
        <p style="padding: 20px">نام اعضا : سجاد رضایی - سید محمد قاسمی</p>
    </div>
</div>

</body>
</html>
