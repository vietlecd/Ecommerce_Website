<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Chi tiết bài viết">
    <meta name="keywords" content="tin tức, bài viết, chi tiết tin tức">
    <meta name="author" content="Ecommerce Website">
    <title>Chi tiết bài viết</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        /* Responsive Design */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .news-detail img {
            width: 100%;
            height: auto;
            margin-bottom: 20px;
        }
        .news-detail h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .news-detail p {
            font-size: 16px;
            line-height: 1.6;
            color: #333;
        }
        .comments {
            margin-top: 40px;
        }
        .comments h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }
        .comments form textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .comments form button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .comments form button:hover {
            background-color: #0056b3;
        }
        .comment {
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .comment p {
            margin: 0;
        }
        .comment .author {
            font-weight: bold;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <?php include '../../views/components/header.php'; ?>

    <div class="container">
        <div class="news-detail">
            <?php
            if ($news) {
                echo '<img src="/assets/images/news/' . $news['image'] . '" alt="' . $news['title'] . '">';
                echo '<h1>' . $news['title'] . '</h1>';
                echo '<p>' . $news['content'] . '</p>';
            } else {
                echo '<p>Bài viết không tồn tại.</p>';
            }
            ?>
        </div>

        <div class="comments">
            <h3>Bình luận</h3>
            <form method="POST" action="/index.php?controller=news&action=addComment">
                <textarea name="comment" placeholder="Viết bình luận..."></textarea>
                <input type="hidden" name="news_id" value="<?php echo $news['id']; ?>">
                <button type="submit">Gửi bình luận</button>
            </form>

            <?php
            foreach ($comments as $comment) {
                echo '<div class="comment">';
                echo '<p class="author">' . $comment['author'] . ':</p>';
                echo '<p>' . $comment['content'] . '</p>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <?php include '../../views/components/footer.php'; ?>
</body>
</html>