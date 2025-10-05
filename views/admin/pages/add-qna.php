<style>
    .qna-form .form-group {
        margin-bottom: 20px;
    }

    .qna-form .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #333;
    }

    .qna-form .form-group input[type="text"],
    .qna-form .form-group input[type="number"],
    .qna-form .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    .qna-form .form-group textarea {
        resize: vertical;
        min-height: 150px;
        font-family: inherit;
    }

    .qna-form .form-group input[type="checkbox"] {
        margin-right: 8px;
    }

    .qna-form button {
        padding: 12px 30px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    .qna-form button:hover {
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
    <h1>Add New Q&A</h1>
    <a href="/index.php?controller=adminQna&action=manage" class="btn-secondary">Back to List</a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<form method="post" class="qna-form">
    <div class="form-group">
        <label for="question">Question *</label>
        <input type="text" id="question" name="question" required 
               placeholder="Enter the question">
    </div>

    <div class="form-group">
        <label for="answer">Answer *</label>
        <textarea id="answer" name="answer" required 
                  placeholder="Enter the answer"></textarea>
    </div>

    <div class="form-group">
        <label for="display_order">Display Order</label>
        <input type="number" id="display_order" name="display_order" value="0" 
               min="0" placeholder="0">
        <small>Lower numbers appear first</small>
    </div>

    <div class="form-group">
        <label>
            <input type="checkbox" name="is_active" value="1" checked>
            Active (Display on Q&A page)
        </label>
    </div>

    <button type="submit">Add Q&A</button>
</form>
