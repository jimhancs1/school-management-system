<?php
require 'connect.php';
include 'navbar.php';

// Initialize variables to avoid undefined variable warnings
$club_name = '';
$club_description = '';
$establishment_year = '';
$contact_email = '';
$club_status = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $club_name = $_POST['club_name'] ?? '';
    $club_description = $_POST['club_description'] ?? '';
    $establishment_year = $_POST['establishment_year'] ?? '';
    $contact_email = $_POST['contact_email'] ?? '';
    $club_status = isset($_POST['club_status']) ? (int)$_POST['club_status'] : null;

    // Check for missing required fields
    $missing = [];
    if (empty($club_name)) $missing[] = 'club_name';
    if (empty($club_description)) $missing[] = 'club_description';
    if (empty($establishment_year)) $missing[] = 'establishment_year';
    if (empty($contact_email)) $missing[] = 'contact_email';
    if ($club_status === null) $missing[] = 'club_status';

    if ($missing) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Please fill all required fields: ' . implode(', ', $missing) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    } elseif (!filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Invalid email format.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    } elseif ($establishment_year < 1900 || $establishment_year > date('Y')) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Establishment year must be between 1900 and ' . date('Y') . '.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    } elseif ($club_status !== 0 && $club_status !== 1) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Club status must be Active (1) or Inactive (0).<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    } else {
        if (!$conn) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Database connection failed.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        } else {
            $stmt = $conn->prepare("INSERT INTO clubs (club_name, club_description, establishment_year, contact_email, club_status) VALUES (?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("ssisi", $club_name, $club_description, $establishment_year, $contact_email, $club_status);
                if ($stmt->execute()) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Club added successfully!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                } else {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error: ' . htmlspecialchars($stmt->error) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                }
                $stmt->close();
            } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Failed to prepare statement.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Club</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Club</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Club Name</label>
                <input type="text" name="club_name" class="form-control" value="<?php echo htmlspecialchars($club_name); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Club Description</label>
                <textarea name="club_description" class="form-control" rows="4" required><?php echo htmlspecialchars($club_description); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Establishment Year</label>
                <input type="number" name="establishment_year" class="form-control" min="1900" max="<?php echo date('Y'); ?>" value="<?php echo htmlspecialchars($establishment_year); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contact Email</label>
                <input type="email" name="contact_email" class="form-control" value="<?php echo htmlspecialchars($contact_email); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Club Status</label>
                <select name="club_status" class="form-control" required>
                    <option value="">Select Status</option>
                    <option value="1" <?php echo $club_status === 1 ? 'selected' : ''; ?>>Active</option>
                    <option value="0" <?php echo $club_status === 0 ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Club</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>