<?php
session_start();
if (!isset($_SESSION['islogin'])) {
    header("location:../logout.php");
    exit;
}
include '../config.php';
?>
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>کاربران</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../pic/2.png" rel="icon">
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
        .topmenu .container {
            max-width: 1100px;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .topmenu .socialicons img {
            height: 58px;
        }
        .topmenu ul {
            list-style: none;
            display: flex;
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
        h1 {
            color: #2d3436;
            margin-bottom: 20px;
            text-align: center;
        }
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit,minmax(280px,1fr));
            gap: 20px;
        }
        .user-card {
            background-color: #fefefe;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s ease;
        }
        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .user-info {
            margin-bottom: 15px;
        }
        .user-info p {
            font-size: 15px;
            color: #2d3436;
            margin-bottom: 8px;
            line-height: 1.3;
            word-break: break-word;
        }
        .user-actions {
            margin-top: auto;
            display: flex;
            justify-content: flex-start;
            gap: 15px;
        }
        .user-actions a {
            text-decoration: none;
            font-size: 20px;
            width: 38px;
            height: 38px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }
        .user-actions a.fa-edit {
            color: #0984e3;
            background-color: #dfe9fc;
        }
        .user-actions a.fa-edit:hover {
            background-color: #74a9f7;
            color: #fff;
        }
        .user-actions a.fa-remove {
            color: #d63031;
            background-color: #fcdada;
        }
        .user-actions a.fa-remove:hover {
            background-color: #e74c3c;
            color: #fff;
        }
        @media (max-width: 400px) {
            .user-card {
                padding: 15px;
            }
            .user-actions a {
                width: 34px;
                height: 34px;
                font-size: 18px;
            }
        }
    </style>
</head>
<body>

<div class="topmenu">
    <div class="container">
        <div class="socialicons">
            <img src="../pic/tabarnews1.png" width="158" height="100" alt="خبر">
        </div>
        <ul>
            <li><a href="../index.php">مشاهده سایت</a></li>
            <li><a href="index.php">خبرها</a></li>
            <li><a href="cats.php">دسته‌بندی‌ها</a></li>
            <li><a href="../logout.php">خروج</a></li>
        </ul>
    </div>
</div>

<div class="container">
    <div class="tilearea">
        <h1>خبرنگاران وب سایت</h1>
        <div class="cards-container">
            <?php
            $sql = "SELECT * FROM users ORDER BY id ASC";
            $res = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($res)) {
                ?>
                <div class="user-card">
                    <div class="user-info">
                        <p><strong>نام:</strong> <?php echo htmlspecialchars($row['fname']); ?></p>
                        <p><strong>نام خانوادگی:</strong> <?php echo htmlspecialchars($row['lname']); ?></p>
                        <p><strong>ایمیل:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                        <p><strong>نام کاربری:</strong> <?php echo htmlspecialchars($row['user']); ?></p>
                    </div>
                    <div class="user-actions">
                        <a href="edituser.php?fid=<?php echo $row['id']; ?>" class="fa fa-edit" title="ویرایش"></a>
                        <a href="deleteuser.php?fid=<?php echo $row['id']; ?>" class="fa fa-remove" title="حذف"></a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

</body>
</html>
