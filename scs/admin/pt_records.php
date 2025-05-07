<?php
include_once "../templates/header.php";
include_once "includes/connect.php";

$stmt = $pdo->query("SELECT * FROM patients");
$patients = $stmt->fetchAll();
?>

<main id="main" class="main">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Patients Table</h5>
      <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addPatientModal">
        Add Patient
      </button>

      <!-- Table -->
      <table class="table datatable">
        <thead>
          <tr>
            <th>#</th>
            <th>Full Name</th>
            <th>DOB</th>
            <th>Gender</th>
            <th>Contact</th>
            <th>Email</th>
            <th>Academic Year</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($patients as $patient): ?>
            <tr>
              <td><?= htmlspecialchars($patient['id']) ?></td>
              <td><?= htmlspecialchars($patient['full_name']) ?></td>
              <td><?= htmlspecialchars($patient['dob']) ?></td>
              <td><?= htmlspecialchars($patient['gender']) ?></td>
              <td><?= htmlspecialchars($patient['contact_number']) ?></td>
              <td><?= htmlspecialchars($patient['email']) ?></td>
              <td><?= htmlspecialchars($patient['academic_year']) ?></td>
              <td>
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editPatientModal<?= $patient['id'] ?>">Edit</button>
                <a href="functions/pt_actions.php?action=delete&id=<?= $patient['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?')">Delete</a>
              </td>
            </tr>

            <!-- Edit Patient Modal -->
            <div class="modal fade" id="editPatientModal<?= $patient['id'] ?>" tabindex="-1" aria-labelledby="editPatientLabel<?= $patient['id'] ?>" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <form action="functions/pt_actions.php" method="POST">
                  <input type="hidden" name="action" value="update">
                  <input type="hidden" name="id" value="<?= $patient['id'] ?>">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Patient</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body row g-3">
                      <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="full_name" value="<?= $patient['full_name'] ?>" required>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" name="dob" value="<?= $patient['dob'] ?>" required>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-control" required>
                          <option <?= $patient['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                          <option <?= $patient['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Contact Number</label>
                        <input type="text" class="form-control" name="contact_number" value="<?= $patient['contact_number'] ?>" required>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?= $patient['email'] ?>" required>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Academic Year</label>
                        <input type="text" class="form-control" name="academic_year" value="<?= $patient['academic_year'] ?>" required>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Add Patient Modal -->
  <div class="modal fade" id="addPatientModal" tabindex="-1" aria-labelledby="addPatientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form action="functions/pt_actions.php" method="POST">
        <input type="hidden" name="action" value="add">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add New Patient</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body row g-3">
            <div class="col-md-6">
              <label class="form-label">Full Name</label>
              <input type="text" class="form-control" name="full_name" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Date of Birth</label>
              <input type="date" class="form-control" name="dob" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Gender</label>
              <select name="gender" class="form-control" required>
                <option>Male</option>
                <option>Female</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Contact Number</label>
              <input type="text" class="form-control" name="contact_number" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" name="email" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Academic Year</label>
              <input type="text" class="form-control" name="academic_year" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success">Add Patient</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</main>

<?php include_once "../templates/footer.php"; ?>
