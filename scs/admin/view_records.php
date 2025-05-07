<?php 

include('../database/config.php'); 
include_once "../templates/header.php";

if (isset($_SESSION['username'])) { 
    $username = $_SESSION['username']; 
} else { 
    header("Location: login.php"); 
    exit(); 
} 

// Initialize an array to hold patient data 
$patients = []; 

// Fetch total number of patients
$totalPatientsQuery = "SELECT COUNT(*) as total FROM patients";
$totalResult = $conn->query($totalPatientsQuery);
$totalRow = $totalResult->fetch_assoc();
$totalPatients = $totalRow['total'];

// Pagination variables
$limit = 8; // Number of entries per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$offset = ($page - 1) * $limit; // Offset for SQL query

// Fetch patient data from the database with limit and offset
$sql = "SELECT * FROM patients LIMIT $limit OFFSET $offset"; 
$result = $conn->query($sql); 

if ($result->num_rows > 0) { 
    while ($row = $result->fetch_assoc()) { 
        $patients[] = $row; 
    } 
} 

// Sort patients array by full_name 
usort($patients, function($a, $b) { 
    return strcmp($a['full_name'], $b['full_name']); 
}); 

// Close the database connection 
$conn->close(); 
?>

<main id="main" class="main">
    <div class="pagetitle">
      <h1>Patient Records</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Patient Records</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Patient Records</h5>
              <table id="patientTable" class="table datatable">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Course</th>
                    <th>Year Level</th>
                    <th>Section</th>
                    <th>Semester</th>
                    <th>Academic Year</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($patients as $patient): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($patient['full_name']); ?></td>
                      <td><?php echo htmlspecialchars($patient['course']); ?></td>
                      <td><?php echo htmlspecialchars($patient['year_level']); ?></td>
                      <td><?php echo htmlspecialchars($patient['section']); ?></td>
                      <td><?php echo htmlspecialchars($patient['semester']); ?></td>
                      <td><?php echo htmlspecialchars($patient['academic_year']); ?></td>
                      <td class="actions">
                        <a href="update_records.php?id=<?php echo $patient['id']; ?>" class="edit"><i class="fa-solid fa-edit"></i> Edit</a>
                        <a href="delete_patient.php?id=<?php echo $patient['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this record?');"><i class="fa-solid fa-trash"></i> Delete</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>

                <div class="pagination">
                    <nav aria-label="Page navigation">
                  <ul class="pagination">
                    <?php if ($page > 1): ?>
                      <li class="page-item">
                        <a class="page-link" href="?page=1" aria-label="First">
                          <span aria-hidden="true">«</span>
                        </a>
                      </li>
                      <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                          <span aria-hidden="true">‹</span>
                        </a>
                      </li>
                    <?php endif; ?>

                    <?php
                    $totalPages = ceil($totalPatients / $limit);
                    for ($i = 1; $i <= $totalPages; $i++): ?>
                      <li class="page-item <?php echo ($i === $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                      </li>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                      <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                          <span aria-hidden="true">›</span>
                        </a>
                      </li>
                      <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $totalPages; ?>" aria-label="Last">
                          <span aria-hidden="true">»</span>
                        </a>
                      </li>
                    <?php endif; ?>
                  </ul>
                    </nav>
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>
  </main><!-- End #main -->

<?php
include_once "../templates/footer.php";
?>

<script>
    function filterTable() {
        const input = document.getElementById('searchInput');
        const courseFilter = document.getElementById('courseFilter').value;
        const yearLevelFilter = document.getElementById('yearLevelFilter').value;
        const sectionFilter = document.getElementById('sectionFilter').value;
        const semesterFilter = document.getElementById('semesterFilter').value;
        const academicYearFilter = document.getElementById('academicYearFilter').value;

        const filter = input.value.toLowerCase();
        const table = document.getElementById('patientTable');
        const tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            const tdFullName = tr[i].getElementsByTagName('td')[0];
            const tdCourse = tr[i].getElementsByTagName('td')[1];
            const tdYearLevel = tr[i].getElementsByTagName('td')[2];
            const tdSection = tr[i].getElementsByTagName('td')[3];
            const tdSemester = tr[i].getElementsByTagName('td')[4];
            const tdAcademicYear = tr[i].getElementsByTagName('td')[5];

            const fullNameMatch = tdFullName && tdFullName.textContent.toLowerCase().indexOf(filter) > -1;
            const courseMatch = courseFilter === "" || (tdCourse && tdCourse.textContent === courseFilter);
            const yearLevelMatch = yearLevelFilter === "" || (tdYearLevel && tdYearLevel.textContent === yearLevelFilter);
            const sectionMatch = sectionFilter === "" || (tdSection && tdSection.textContent === sectionFilter);
            const semesterMatch = semesterFilter === "" || (tdSemester && tdSemester.textContent === semesterFilter);
            const academicYearMatch = academicYearFilter === "" || (tdAcademicYear && tdAcademicYear.textContent === academicYearFilter);

            if (fullNameMatch && courseMatch && yearLevelMatch && sectionMatch && semesterMatch && academicYearMatch) {
                tr[i].style.display = ""; // Show the row
            } else {
                tr[i].style.display = "none"; // Hide the row
            }
        }
    }

    // JavaScript to toggle submenu visibility
    const toggles = document.querySelectorAll('.toggle');

    toggles.forEach(toggle => {
        toggle.addEventListener('click', () => {
            // Close all submenus
            toggles.forEach(t => {
                const submenu = t.nextElementSibling;
                if (submenu) {
                    submenu.classList.remove('show'); // Remove show class to close
                }
            });

            // Open the clicked submenu
            const submenu = toggle.nextElementSibling;
            if (submenu) {
                submenu.classList.toggle('show'); // Toggle show class to open/close
            }
        });
    });

    document.querySelector(".toggle.dashboard").addEventListener("click", function() {
        window.location.href = "dashboard.php";
    });
</script>
</body>
</html>