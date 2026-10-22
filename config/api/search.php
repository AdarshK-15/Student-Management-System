<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';$dept = isset($_GET['dept']) ? trim($_GET['dept']) : 'All';
$degree = isset($_GET['degree']) ? trim($_GET['degree']) : 'All';$year = isset($_GET['year']) ? trim($_GET['year']) : 'All';

try {
    $sql = "SELECT * FROM students WHERE 1=1";
    $params = [];

    if ($dept !== 'All' && $dept !== '') {$sql .= " AND department = :dept";
        $params['dept'] =$dept;
    }

    if ($degree !== 'All' && $degree !== '') {$sql .= " AND degree_level = :degree";
        $params['degree'] =$degree;
    }

    if ($year !== 'All' && $year !== '') {$sql .= " AND academic_year = :year";
        $params['year'] = (int)$year;
    }

    if ($q !== '') {$sql .= " AND (full_name LIKE :q OR roll_number LIKE :q OR email LIKE :q)";
        $params['q'] = "\%$q%";
    }

    $sql .= " ORDER BY academic_year ASC, cgpa DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $records =$stmt->fetchAll();

    $metrics =$pdo->query("
        SELECT 
            COUNT(*) as total_students,
            COALESCE(ROUND(AVG(cgpa), 2), 0.00) as avg_cgpa,
            COALESCE(ROUND(AVG(attendance_pct), 1), 0.0) as avg_attendance,
            COALESCE(SUM(CASE WHEN academic_status = 'Dean\'s List' THEN 1 ELSE 0 END), 0) as deans_list_count
        FROM students
    ")->fetch();

    echo json_encode([
        'status' => 'success',
        'data' => $records,
        'metrics' => $metrics
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
