<style>
    .about-form .form-group {
        margin-bottom: 20px;
    }

    .about-form .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #333;
    }

    .about-form .form-group input[type="text"],
    .about-form .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    .about-form .form-group textarea {
        resize: vertical;
        min-height: 300px;
        font-family: inherit;
    }

    .about-form .form-group img {
        max-width: 400px;
        border-radius: 5px;
        margin-top: 10px;
    }

    .about-form button {
        padding: 12px 30px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    .about-form button:hover {
        background-color: #0056b3;
    }

    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .btn-secondary {
        padding: 10px 20px;
        background-color: #6c757d;
        color: white;
        text-decoration: none;
        border-radius: 4px;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
</style>

<div class="admin-header">
    <h1>Edit About Page Content</h1>
    <a href="/index.php?controller=about&action=index" class="btn-secondary" target="_blank">View Page</a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<form method="post" class="about-form" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Page Title</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($aboutContent['Title']); ?>" required>
    </div>

    <div class="form-group">
        <label for="content">Content</label>
        <textarea id="content" name="content" required><?php echo htmlspecialchars($aboutContent['Content']); ?></textarea>
    </div>

    <div class="form-group">
        <label for="image">Image (optional)</label>
        <?php if ($aboutContent['Image']): ?>
            <div>
                <img src="/<?php echo htmlspecialchars($aboutContent['Image']); ?>" alt="Current Image">
                <p><small>Current image - Upload a new image to replace it</small></p>
            </div>
        <?php endif; ?>
        <input type="file" id="image" name="image" accept="image/*">
    </div>

    <button type="submit">Update Content</button>
</form>
