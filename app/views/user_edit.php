<?php require_once __DIR__ . '/includes/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h2>Edit User</h2>
    </div>
    <div class="card-body">
        <form action="index.php?action=edit&id=<?php echo htmlspecialchars($user['id']); ?>" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required value="<?php echo htmlspecialchars($_POST['username'] ?? $user['username']); ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? $user['email']); ?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">New Password (Leave blank to keep current)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm New Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
            </div>
            <button type="submit" class="btn btn-success">Update User</button>
            <a href="index.php?action=index" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>