<?php
require 'views/admin/components/header.php';
?>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f4f4f4;
    }

    .current-promotion {
        color: #ff6b6b;
        font-weight: bold;
    }

    .current-promotion-current {
        color: #28a745;
        font-weight: bold;
    }
</style>

<div class="section-title">
    <h2>Manage Products for Promotion: <?php echo htmlspecialchars($promotion['promotion_name']); ?></h2>
</div>

<div class="promotion-manage-products">
    <form method="post" action="">
        <table>
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Current Promotion</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="products[]" value="<?php echo $product['id']; ?>"
                                <?php echo in_array($product['id'], $assignedProducts) ? 'checked' : ''; ?>>
                        </td>
                        <td><?php echo $product['id']; ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td>
                            <?php
                            if (in_array($product['id'], $assignedProducts)) {
                                echo '<span class="current-promotion-current">' . htmlspecialchars($promotion['promotion_name']) . '</span>';
                            } elseif (isset($allAssignedProducts[$product['id']])) {
                                echo '<span class="current-promotion">' . htmlspecialchars($allAssignedProducts[$product['id']]) . '</span>';
                            } else {
                                echo 'None';
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" class="btn">Save Changes</button>
    </form>
</div>

<?php
require 'views/admin/components/admin_footer.php';
?>