<?php
include 'config.php';
session_start();
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="utf-8">
    <title>خبرنو</title>
    <link rel="stylesheet" href="assets/site1.css">
    <link href="pic/khabar1.png" rel="icon">
    <meta name="author" content="H.Ebrahimi">
    <link href="assets/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/owl.carousel.css">
    <link rel="stylesheet" href="assets/owl.theme.css">
    <link rel="stylesheet" href="./assets/style4.css">
    <script src="js/jquery-1.9.1.min.js"></script>
    <script src="js/owl.carousel.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/fs.js"></script>
</head>
<body>
<div class="topmenu">
    <div class="container">
        <div class="socialicons fbicon">
            <img src="pic/tabarnews1.png" width="160" height="100" alt="خبر">
        </div>
        <ul>
            <li style="margin-top: 5px;"><img src="pic/tabarnews1.png" width="158" height="58" alt="خبر"></li>
            <li style="margin-top: 10px; margin-end:auto;"><a href="index.php">صفحه اصلی</a></li>
            <?php
            if (!isset($_SESSION['islogin'])) {
                echo "<li style=\"margin-top: 10px;\"><a href=\"login.php\" style=\"float: left;\">ورود</a></li>";
            } else {
                if ($_SESSION['username'] == 'admin') {
                    echo "<li style=\"margin-top: 10px;\"><a href=\"admin/index.php\" style=\"float: left;\">پنل مدیریت</a></li>";
                } else {
                    echo "<li style=\"margin-top: 10px;\"><a href=\"author/index.php\" style=\"float: left;\">خبرهای من</a></li>";
                    echo "<li style=\"margin-top: 10px;\"><a href=\"author/comments.php\" style=\"float: left;\">دیدگاه‌های من</a></li>";
                }
                echo "<li style=\"margin-top: 10px;\"><a href=\"logout.php\" style=\"float: left;\">خروج</a></li>";
            }
            ?>
        </ul>
    </div>
    <div class="clear"></div>
</div>

<div class="container">
    <div class="titlezone" style="margin-top: 30px">
        <h3>اخبار تازه</h3>
        <div class="mainmenus">
            <ul>
                <?php
                $sql = "SELECT * FROM cats";
                $res = mysqli_query($conn , $sql);
                while ($row = mysqli_fetch_assoc($res)) {
                    echo "<li><a href='category.php?id={$row['id']}'>{$row['name']}</a></li>";
                }
                ?>
            </ul>
        </div>
        <div class="clear"></div>
    </div>

    <!-- بخش نمایش کارت‌های ۴ خبر تازه افقی -->
    <div class="news-cards-container">
        <?php
        $sql = "
            SELECT news.*, cats.name AS cat_name, users.user AS username 
            FROM news 
            LEFT JOIN cats ON news.catid = cats.id 
            LEFT JOIN users ON news.userid = users.id 
            WHERE news.status = 'approved' 
            ORDER BY news.id DESC 
            LIMIT 4
        ";
        $res = mysqli_query($conn , $sql);
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
                    <span><i class="fa fa-user"></i> <?php echo htmlspecialchars($row['username'] ?? 'ناشناخته'); ?></span>
                    <span><i class="fa fa-calendar-o"></i> <?php echo htmlspecialchars($row['date']); ?></span>
                    <span><i class="fa fa-newspaper-o"></i> <?php echo htmlspecialchars($row['cat_name'] ?? ''); ?></span>
                </div>
                <p class="news-excerpt"><?php echo mb_strimwidth(strip_tags($row['matn']), 0, 90, '...'); ?></p>
            </div>
        </div>
        <?php } ?>
    </div>

    <div class="im" >
        <p style="padding: 20px">نام اعضا : سجاد رضایی -سید محمد قاسمی</p>
    </div>
</div>

</body>
</html>
