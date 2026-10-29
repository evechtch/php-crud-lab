<?php
require_once __DIR__ . '/crud_bootstrap.php';

page_start('Manage Skills');

$pid = profile_id($conn);
$sql = 'SELECT id, name, level FROM skills WHERE profile_id = ? ORDER BY id DESC';
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $pid);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
?>

<p class="text-muted mb-4">Existing resume skill records for your profile.</p>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold m-0">Skills List</h5>
    <a href="skill_create.php" class="btn btn-navy btn-sm">+ Add Skill</a>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle border">
        <thead class="table-light">
            <tr>
                <th style="width: 10%;">ID</th>
                <th style="width: 45%;">Skill Name</th>
                <th style="width: 25%;">Level</th>
                <th style="width: 20%;" class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($res) === 0): ?>
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">No skills found. Click "+ Add Skill" to create one.</td>
                </tr>
            <?php else: ?>
                <?php while ($row = mysqli_fetch_assoc($res)): ?>
                    <tr>
                        <td><?= h($row['id']) ?></td>
                        <td class="fw-semibold"><?= h($row['name']) ?></td>
                        <td><span class="badge bg-secondary"><?= h($row['level']) ?></span></td>
                        <td class="text-center">
                            <a href="skill_edit.php?id=<?= h($row['id']) ?>" class="btn btn-outline-primary btn-sm me-1">Edit</a>
                            <a href="skill_delete.php?id=<?= h($row['id']) ?>" class="btn btn-outline-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
mysqli_stmt_close($stmt);
page_end();
