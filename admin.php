<?php
// admin.php - Admin Dashboard
include 'config.php';
include 'functions.php';
redirectIfNotLoggedIn();
redirectIfNotRole('admin');
$pageTitle = 'Admin Dashboard';

$result = $conn->query("SELECT id, username, role, email FROM users");

include 'header.php';
?>
<h2>Manage Users</h2>
<table class="table table-striped animate__animated animate__fadeIn">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr class="fade-in-section">
                <td><?php echo $row['id']; ?></td>
                <td><?php echo sanitizeInput($row['username']); ?></td>
                <td><?php echo $row['role']; ?></td>
                <td><?php echo sanitizeInput($row['email']); ?></td>
                <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>">Edit</button>
                    <button class="btn btn-sm btn-danger">Delete</button>
                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Placeholder for edit form -->
                                    <p>Edit user details (form to be implemented).</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php include 'footer.php'; ?>