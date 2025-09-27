<style>
    .promotion-shoes {
        margin-top: 20px;
    }

    .shoe-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }

    .shoe-item {
        text-align: center;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
    }

    .shoe-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
    }

    .shoe-item h3 {
        font-size: 1.2rem;
        margin: 10px 0;
        color: #333;
    }

    .shoe-item p {
        margin: 5px 0;
        color: #666;
    }

    .pagination {
        margin-top: 30px;
        text-align: center;
    }

    .pagination a {
        display: inline-block;
        padding: 10px 18px;
        margin: 0 5px;
        border: 1px solid #ddd;
        text-decoration: none;
        color: #333;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .pagination a.active {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    .pagination a:hover:not(.active) {
        background-color: #f1f1f1;
    }
</style>

<div class="section-title">
    <h2><?php echo htmlspecialchars($promotion['promotion_name']); ?></h2>
</div>

<div class="promotion-shoes">
    <div class="shoe-list">
        <?php if (empty($shoes)): ?>
            <p>No products found for this promotion.</p>
        <?php else: ?>
            <?php foreach ($shoes as $shoe): ?>
                <div class="shoe-item">
                    <a href="/index.php?controller=shoes&action=detail&id=<?php echo $shoe['ShoesID']; ?>">
                        <img src="/<?php echo htmlspecialchars($shoe['Image']); ?>" alt="<?php echo htmlspecialchars($shoe['Name']); ?>" loading="lazy">
                    </a>
                    <h3><?php echo htmlspecialchars($shoe['Name']); ?></h3>
                    <p>Price: <?php echo number_format($shoe['Price'], 0, ',', '.'); ?> VNĐ</p>
                    <?php if ($promotion['discount_percentage']): ?>
                        <p>Discounted Price: <?php echo number_format($shoe['Price'] * (1 - $promotion['discount_percentage'] / 100), 0, ',', '.'); ?> VNĐ</p>
                    <?php elseif ($promotion['fixed_price']): ?>
                        <p>Fixed Price: <?php echo number_format($promotion['fixed_price'], 0, ',', '.'); ?> VNĐ</p>
                    <?php elseif ($promotion['buy_quantity'] && $promotion['get_quantity']): ?>
                        <p>Buy <?php echo $promotion['buy_quantity']; ?> Get <?php echo $promotion['get_quantity']; ?> Free</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="pagination">
        <?php if ($totalPages > 1): ?>
            <?php if ($page > 1): ?>
                <a href="/index.php?controller=news&action=promotion&promotion_id=<?php echo $promotion_id; ?>&page=<?php echo $page - 1; ?>">Trang trước</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="/index.php?controller=news&action=promotion&promotion_id=<?php echo $promotion_id; ?>&page=<?php echo $i; ?>" <?php echo $i === $page ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="/index.php?controller=news&action=promotion&promotion_id=<?php echo $promotion_id; ?>&page=<?php echo $page + 1; ?>">Trang sau</a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'views/components/footer.php'; ?>