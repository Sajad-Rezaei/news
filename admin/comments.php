<?php
include '../config.php';
session_start();

if (isset($_GET['approve'])) {
    $comment_id = intval($_GET['approve']);
    mysqli_query($conn, "UPDATE comments SET status='approved' WHERE id=$comment_id");
    header("Location: comments.php");
    exit;
}
if (isset($_GET['reject'])) {
    $comment_id = intval($_GET['reject']);
    mysqli_query($conn, "UPDATE comments SET status='rejected' WHERE id=$comment_id");
    header("Location: comments.php");
    exit;
}

$sql = "SELECT c.id, c.comment_text, c.created_at, c.status, 
               u.fname, u.lname, u.user, n.title AS news_title
        FROM comments c
        JOIN users u ON c.user_id = u.id
        JOIN news n ON c.news_id = n.id
        ORDER BY c.created_at DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>مدیریت دیدگاه‌ها</title>
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
            padding: 20px 0 50px;
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
        h2 {
            text-align: center;
            color: #2d3436;
            margin: 30px 0 20px;
            font-size: 28px;
        }
        .cards-container {
            max-width: 1100px;
            margin: auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
            padding: 0 15px;
        }
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-header {
            font-weight: bold;
            color: #0984e3;
            margin-bottom: 8px;
            font-size: 18px;
            border-bottom: 1px solid #00cec9;
            padding-bottom: 5px;
        }
        .card-info {
            margin-bottom: 12px;
            font-size: 14px;
            color: #2d3436;
            line-height: 1.4;
        }
        .card-info strong {
            color: #00cec9;
        }
        .comment-text {
            background-color: #f1f2f6;
            border-radius: 8px;
            padding: 10px;
            font-size: 15px;
            color: #333;
            margin-bottom: 15px;
            min-height: 60px;
            overflow-wrap: break-word;
            white-space: pre-wrap;
        }
        .card-footer {
            display: flex;
            justify-content: flex-start;
            gap: 10px;
        }
        .btn {
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 14px;
            color: #fff;
            cursor: pointer;
            text-decoration: none;
            user-select: none;
            transition: background-color 0.3s ease;
            display: inline-block;
        }
        .btn-approve {
            background-color: #00b894;
        }
        .btn-approve:hover {
            background-color: #019875;
        }
        .btn-reject {
            background-color: #d63031;
        }
        .btn-reject:hover {
            background-color: #b02728;
        }
        .status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 6px;
            text-align: center;
            width: fit-content;
            color: #fff;
        }
        .status-approved {
            background-color: #00b894;
        }
        .status-pending {
            background-color: #fdcb6e;
            color: #2d3436;
        }
        .status-rejected {
            background-color: #d63031;
        }
        @media (max-width: 400px) {
            .cards-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="topmenu">
    <div class="container">
        <ul>
            <li><a href="../index.php">مشاهده سایت</a></li>
            <li><a href="users.php">کاربران</a></li>
            <li><a href="cats.php">دسته بندی ها</a></li>
            <li><a href="comments.php">مدیریت دیدگاه‌ها</a></li>
            <li><a href="../logout.php">خروج از حساب</a></li>
        </ul>
    </div>
</div>

<h2>مدیریت دیدگاه‌ها</h2>

<div class="cards-container">
    <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div class="card">
            <div class="card-header">
                <?= htmlspecialchars($row['fname']) . " " . htmlspecialchars($row['lname']) ?>  
                <span style="color:#888; font-weight:normal; font-size:13px;"> (<?= htmlspecialchars($row['user']) ?>)</span>
            </div>
            <div class="card-info">
                <strong>خبر:</strong> <?= htmlspecialchars($row['news_title']) ?><br>
                <strong>تاریخ:</strong> <?= htmlspecialchars($row['created_at']) ?>
            </div>
            <div class="comment-text"><?= nl2br(htmlspecialchars($row['comment_text'])) ?></div>
            <div class="card-info">
                وضعیت: 
                <span class="status 
                    <?= $row['status'] === 'approved' ? 'status-approved' : ($row['status'] === 'rejected' ? 'status-rejected' : 'status-pending') ?>">
                    <?= htmlspecialchars($row['status']) ?>
                </span>
            </div>
            <div class="card-footer">
                <?php if ($row['status'] !== 'approved'): ?>
                    <a class="btn btn-approve" href="?approve=<?= $row['id'] ?>">تأیید</a>
                <?php endif; ?>
                <?php if ($row['status'] !== 'rejected'): ?>
                    <a class="btn btn-reject" href="?reject=<?= $row['id'] ?>">رد</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
