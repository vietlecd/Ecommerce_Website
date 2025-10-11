<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Manage Q&A</h3>
                <p class="text-subtitle text-muted">View and manage all questions and answers</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/index.php?controller=adminDashboard&action=dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Q&A</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible show fade">
            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Q&A List</h4>
                    <a href="/index.php?controller=adminQna&action=add" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Q&A
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="get" class="mb-4">
                    <input type="hidden" name="controller" value="adminQna">
                    <input type="hidden" name="action" value="manage">
                    <div class="input-group">
                        <input type="text" 
                               class="form-control" 
                               name="search" 
                               placeholder="Search questions or answers..." 
                               value="<?php echo htmlspecialchars($search); ?>">
                        <button class="btn btn-success" type="submit">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Question</th>
                                <th>Answer Preview</th>
                                <th>Order</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($qnaList)): ?>
                                <?php foreach ($qnaList as $qna): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($qna['QnaID']); ?></td>
                                        <td><?php echo htmlspecialchars(substr($qna['Question'], 0, 80)); ?><?php echo strlen($qna['Question']) > 80 ? '...' : ''; ?></td>
                                        <td><?php echo htmlspecialchars(substr($qna['Answer'], 0, 100)); ?><?php echo strlen($qna['Answer']) > 100 ? '...' : ''; ?></td>
                                        <td><?php echo htmlspecialchars($qna['DisplayOrder']); ?></td>
                                        <td>
                                            <?php if ($qna['IsActive']): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($qna['CreatedByName'] ?? 'N/A'); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="/index.php?controller=adminQna&action=edit&id=<?php echo $qna['QnaID']; ?>" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="/index.php?controller=adminQna&action=delete&id=<?php echo $qna['QnaID']; ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Are you sure you want to delete this Q&A?');">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>No Q&A items found.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($totalPages > 1): ?>
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="/index.php?controller=adminQna&action=manage&search=<?php echo urlencode($search); ?>&page=<?php echo $page - 1; ?>">
                                        <i class="fas fa-chevron-left"></i> Previous
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="/index.php?controller=adminQna&action=manage&search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="/index.php?controller=adminQna&action=manage&search=<?php echo urlencode($search); ?>&page=<?php echo $page + 1; ?>">
                                        Next <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>
