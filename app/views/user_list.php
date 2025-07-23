<?php require_once __DIR__ . '/includes/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Users List</h2>
    <a href="index.php?action=create" class="btn btn-primary">Add New User</a>
</div>

<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                    <td>
                        <a href="index.php?action=edit&id=<?php echo htmlspecialchars($user['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="index.php?action=delete&id=<?php echo htmlspecialchars($user['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">No users found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/includes/footer.php'; ?>