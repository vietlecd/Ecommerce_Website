<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Danh sách các bài viết và tin tức mới nhất">
    <meta name="keywords" content="tin tức, bài viết, tin tức mới, bài viết mới">
    <meta name="author" content="Ecommerce Website">
    <title>Danh sách bài viết</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        /* Responsive Design */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .search-bar {
            margin-bottom: 20px;
        }

        .search-bar input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .news-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .news-item {
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .news-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .news-item .content {
            padding: 15px;
        }

        .news-item h3 {
            font-size: 18px;
            margin: 0 0 10px;
        }

        .news-item p {
            font-size: 14px;
            color: #555;
        }

        .news-item a {
            display: inline-block;
            margin-top: 10px;
            color: #007BFF;
            text-decoration: none;
        }

        .news-item a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <?php include '../../views/components/header.php'; ?>

    <div class="container">
        <h1>Danh sách bài viết</h1>
        <div class="search-bar">
            <form method="GET" action="/index.php?controller=news&action=list">
                <input type="text" name="keyword" placeholder="Tìm kiếm bài viết...">
            </form>
        </div>
        <div class="news-list">
            <?php echo $newsList; ?>
            <?php
            foreach ($newsList as $news) {
                echo '<div class="news-item">';
                echo '<img src="/assets/images/news/' . $news['image'] . '" alt="' . $news['title'] . '">';
                echo '<div class="content">';
                echo '<h3>' . $news['title'] . '</h3>';
                echo '<p>' . substr($news['content'], 0, 100) . '...</p>';
                echo '<a href="/index.php?controller=news&action=detail&id=' . $news['id'] . '">Đọc thêm</a>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <?php include '../../views/components/footer.php'; ?>
</body>

</html>