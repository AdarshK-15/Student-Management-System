<?php
header('Content-Type: application/json');
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$id = isset($input['id']) ? (int)$input['id'] : 0;
$roll = trim($input['roll_number'] ?? '');
$name = trim($input['full_name'] ?? '');
$email = trim($input['email'] ?? '');
$dept = trim($input['department'] ?? '');
$degree = trim($input['degree_level'] ?? '');
$year = (int)($input['academic_year'] ?? 1);
$cgpa = floatval($input['cgpa'] ?? 0.0);
$attendance = floatval($input['attendance_pct'] ?? 85.0);

if (!$roll || !$name || !$email || !$dept || !$degree) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'All required fields must be filled.']);
    exit;
}

$status = 'Good Standing';
if ($cgpa >= 8.50) {
    $status = "Dean's List";
} elseif ($cgpa < 6.50) {
    $status = 'Academic Probation';
}

try {
    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE students SET 
            roll_number = :roll,
            full_name = :name,
            email = :email,
            department = :dept,
            degree_level = :degree,
            academic_year = :year,
            cgpa = :cgpa,
            attendance_pct = :attendance,
            academic_status = :status
            WHERE id = :id");
        $params = [
            'roll' => $roll, 'name' => $name, 'email' => $email,
            'dept' => $dept, 'degree' => $degree, 'year' => $year,
            'cgpa' => $cgpa, 'attendance' => $attendance,
            'status' => $status, 'id' => $id
        ];
        $stmt->execute($params);
        echo json_encode(['status' => 'success', 'message' => 'Record updated successfully']);
    } else {
        $stmt = $pdo->prepare("INSERT INTO students 
            (roll_number, full_name, email, department, degree_level, academic_year, cgpa, attendance_pct, academic_status)
            VALUES (:roll, :name, :email, :dept, :degree, :year, :cgpa, :attendance, :status)");
        $params = [
            'roll' => $roll, 'name' => $name, 'email' => $email,
            'dept' => $dept, 'degree' => $degree, 'year' => $year,
            'cgpa' => $cgpa, 'attendance' => $attendance,
            'status' => $status
        ];
        $stmt->execute($params);
        echo json_encode(['status' => 'success', 'message' => 'Student enrolled successfully']);
    }
} catch (PDOException $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
