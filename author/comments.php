<?php
include '../config.php';
session_start();

if (!isset($_SESSION['userid'])) {
    header("Location: ../login.php");
    exit;
}

$userId = $_SESSION['userid'];

if (isset($_GET['delete'])) {
    $comment_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM comments WHERE id = $comment_id AND user_id = $userId");
    header("Location: comments.php");
    exit;
}

if (isset($_POST['edit_comment'])) {
    $comment_id = intval($_POST['comment_id']);
    $new_text = mysqli_real_escape_string($conn, $_POST['new_text']);
    mysqli_query($conn, "UPDATE comments SET comment_text='$new_text', status='pending' WHERE id=$comment_id AND user_id=$userId");
    header("Location: comments.php");
    exit;
}

$sql = "SELECT c.id, c.comment_text, c.created_at, c.status, n.title AS news_title
        FROM comments c
        JOIN news n ON c.news_id = n.id
        WHERE c.user_id = $userId
        ORDER BY c.created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>دیدگاه‌های من</title>
    <style>
        @charset "utf-8";
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Tahoma, sans-serif;
            direction: rtl;
        }
        body {
            background: linear-gradient(to left, #f2f2f2, #dfe6e9);
            padding: 20px 0 50px;
        }
        nav {
            background-color: #2d3436;
            padding: 15px 0;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        }
        nav .container {
            max-width: 1100px;
            margin: auto;
            display: flex;
            justify-content: flex-end;
            gap: 20px;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            padding: 8px 16px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }
        nav a:hover {
            background-color: #00cec9;
            color: #fff;
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
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
            padding: 0 15px;
        }
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            transition: transform 0.2s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .news-title {
            font-weight: bold;
            font-size: 18px;
            color: #0984e3;
            border-bottom: 2px solid #00cec9;
            padding-bottom: 6px;
        }
        .comment-text {
            background-color: #f1f2f6;
            padding: 12px;
            border-radius: 8px;
            min-height: 70px;
            font-size: 15px;
            color: #333;
            white-space: pre-wrap;
            overflow-wrap: break-word;
        }
        .comment-info {
            font-size: 14px;
            color: #2d3436;
        }
        .status {
            font-weight: bold;
            padding: 5px 12px;
            border-radius: 8px;
            display: inline-block;
            width: fit-content;
        }
        .status-approved {
            background-color: #00b894;
            color: #fff;
        }
        .status-pending {
            background-color: #fdcb6e;
            color: #2d3436;
        }
        .status-rejected {
            background-color: #d63031;
            color: #fff;
        }
        form.edit-form {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }
        form.edit-form input[type="text"] {
            flex-grow: 1;
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #b2bec3;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        form.edit-form input[type="text"]:focus {
            border-color: #00cec9;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 206, 201, 0.5);
        }
        form.edit-form button {
            background-color: #0984e3;
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        form.edit-form button:hover {
            background-color: #0652DD;
        }
        .btn-delete {
            background-color: #d63031;
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
            align-self: flex-start;
        }
        .btn-delete:hover {
            background-color: #b02728;
        }
        @media (max-width: 400px) {
            .cards-container {
                grid-template-columns: 1fr;
            }
            form.edit-form {
                flex-direction: column;
            }
            form.edit-form input[type="text"], form.edit-form button {
                width: 100%;
            }
            .btn-delete {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<nav>
    <div class="container">
        <a href="../index.php">بازگشت به سایت</a>
        <a href="index.php">پنل نویسنده</a>
        <a href="../logout.php">خروج</a>
    </div>
</nav>

<h2>دیدگاه‌های من</h2>

<div class="cards-container">
    <?php if (mysqli_num_rows($result) == 0): ?>
        <p style="text-align:center; color:#555; font-size:18px;">شما هنوز هیچ دیدگاهی ثبت نکرده‌اید.</p>
    <?php else: ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="card">
                <div class="news-title"><?= htmlspecialchars($row['news_title']) ?></div>
                <div class="comment-text"><?= nl2br(htmlspecialchars($row['comment_text'])) ?></div>
                <div class="comment-info">
                    تاریخ ارسال: <?= htmlspecialchars($row['created_at']) ?>
                </div>
                <div class="comment-info">
                    وضعیت: 
                    <span class="status 
                        <?= $row['status'] === 'approved' ? 'status-approved' : ($row['status'] === 'rejected' ? 'status-rejected' : 'status-pending') ?>">
                        <?= $row['status'] == 'pending' ? 'در انتظار تأیید' : ($row['status'] == 'approved' ? 'تأیید شده' : 'رد شده') ?>
                    </span>
                </div>

                <form class="edit-form" method="post">
                    <input type="hidden" name="comment_id" value="<?= $row['id'] ?>">
                    <input type="text" name="new_text" value="<?= htmlspecialchars($row['comment_text']) ?>" required>
                    <button type="submit" name="edit_comment">ویرایش</button>
                </form>

                <form method="get" onsubmit="return confirm('آیا مطمئن هستید؟');">
                    <input type="hidden" name="delete" value="<?= $row['id'] ?>">
                    <button type="submit" class="btn-delete">حذف</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

</body>
</html>
