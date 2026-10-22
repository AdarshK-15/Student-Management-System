<?php
require_once 'config/db.php';

$metrics = $pdo->query("
    SELECT 
        COUNT(*) as total_students,
        ROUND(AVG(cgpa), 2) as avg_cgpa,
        ROUND(AVG(attendance_pct), 1) as avg_attendance,
        SUM(CASE WHEN academic_status = 'Dean\'s List' THEN 1 ELSE 0 END) as deans_list_count
    FROM students
")->fetch();
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nagaland University | Academic Registry & Departmental SIS</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
    body { font-family: 'Inter', sans-serif; }
    .font-mono { font-family: 'JetBrains Mono', monospace; }
  </style>
</head>
<body class="h-full flex overflow-hidden text-slate-800 antialiased bg-slate-100">

  <!-- Enterprise Clean Institutional Sidebar -->
  <aside class="w-64 bg-[#1e293b] border-r border-slate-700/80 flex flex-col justify-between shrink-0 text-slate-300 select-none">
    <div>
      <!-- Institution Header -->
      <div class="h-16 flex items-center px-5 border-b border-slate-700 bg-[#0f172a] gap-3">
        <div class="w-8 h-8 rounded bg-blue-700 flex items-center justify-center font-bold text-white text-xs border border-blue-500">
          NU
        </div>
        <div class="leading-tight">
          <div class="text-xs font-bold text-slate-100 uppercase tracking-wide">Nagaland University</div>
          <div class="text-[11px] text-slate-400">School of Engg & Tech</div>
        </div>
      </div>

      <!-- Navigation Tree -->
      <nav class="p-3 space-y-1 text-xs">
        <div class="px-3 pt-3 pb-1 text-[10px] uppercase font-semibold text-slate-400 tracking-wider">
          Main Navigation
        </div>

        <!-- Student Cohort -->
        <button onclick="switchView('cohort'); setDept('All')" 
                class="main-nav-btn w-full flex items-center gap-3 px-3 py-2 rounded text-left font-medium bg-blue-600 text-white transition" 
                data-view="cohort">
          <i class="fa-solid fa-users text-xs w-4 text-center"></i>
          <span>Student Directory</span>
        </button>

        <!-- Department Dropdown Accordion -->
        <div class="pt-1">
          <button onclick="toggleDeptAccordion()" 
                  id="deptAccordionBtn" 
                  class="w-full flex items-center justify-between px-3 py-2 rounded text-left text-slate-300 hover:bg-slate-700/50 hover:text-white transition">
            <div class="flex items-center gap-3">
              <i class="fa-solid fa-sitemap text-xs w-4 text-center text-slate-400"></i>
              <span class="font-medium">Departments</span>
            </div>
            <i id="deptChevron" class="fa-solid fa-angle-down text-xs text-slate-400 transition-transform duration-200"></i>
          </button>

          <!-- Department Submenu -->
          <div id="deptSubMenu" class="mt-1 pl-3 space-y-0.5 border-l-2 border-slate-700 ml-5">
            <button onclick="switchView('cohort'); setDept('Agriculture Engineering & Technology')" 
                    class="dept-sub-btn w-full text-left px-2.5 py-1.5 rounded text-[11px] text-slate-300 hover:bg-slate-700/60 hover:text-white transition truncate" 
                    data-dept="Agriculture Engineering & Technology">
              Agri Engineering (AET)
            </button>
            <button onclick="switchView('cohort'); setDept('Biotechnology Engineering')" 
                    class="dept-sub-btn w-full text-left px-2.5 py-1.5 rounded text-[11px] text-slate-300 hover:bg-slate-700/60 hover:text-white transition truncate" 
                    data-dept="Biotechnology Engineering">
              Biotechnology (BT)
            </button>
            <button onclick="switchView('cohort'); setDept('Computer Science & Engineering')" 
                    class="dept-sub-btn w-full text-left px-2.5 py-1.5 rounded text-[11px] text-slate-300 hover:bg-slate-700/60 hover:text-white transition truncate" 
                    data-dept="Computer Science & Engineering">
              Computer Science (CSE)
            </button>
            <button onclick="switchView('cohort'); setDept('Electronics & Communication Engineering')" 
                    class="dept-sub-btn w-full text-left px-2.5 py-1.5 rounded text-[11px] text-slate-300 hover:bg-slate-700/60 hover:text-white transition truncate" 
                    data-dept="Electronics & Communication Engineering">
              Electronics (ECE)
            </button>
            <button onclick="switchView('cohort'); setDept('Information Technology')" 
                    class="dept-sub-btn w-full text-left px-2.5 py-1.5 rounded text-[11px] text-slate-300 hover:bg-slate-700/60 hover:text-white transition truncate" 
                    data-dept="Information Technology">
              Information Tech (IT)
            </button>
          </div>
        </div>

        <div class="px-3 pt-4 pb-1 text-[10px] uppercase font-semibold text-slate-400 tracking-wider">
          Academic Services
        </div>

        <!-- Notifications -->
        <button onclick="switchView('notifications')" 
                class="main-nav-btn w-full flex items-center justify-between px-3 py-2 rounded text-left text-slate-300 hover:bg-slate-700/50 hover:text-white transition" 
                data-view="notifications">
          <div class="flex items-center gap-3">
            <i class="fa-solid fa-bell text-xs w-4 text-center text-slate-400"></i>
            <span>Circulars & Notices</span>
          </div>
          <span class="px-1.5 py-0.2 rounded bg-slate-700 border border-slate-600 text-[10px] text-slate-200 font-mono">3</span>
        </button>

        <!-- Syllabus -->
        <button onclick="switchView('syllabus')" 
                class="main-nav-btn w-full flex items-center gap-3 px-3 py-2 rounded text-left text-slate-300 hover:bg-slate-700/50 hover:text-white transition" 
                data-view="syllabus">
          <i class="fa-solid fa-book text-xs w-4 text-center text-slate-400"></i>
          <span>Curriculum & Syllabi</span>
        </button>

        <!-- Audit Trail -->
        <button onclick="switchView('audit')" 
                class="main-nav-btn w-full flex items-center gap-3 px-3 py-2 rounded text-left text-slate-300 hover:bg-slate-700/50 hover:text-white transition" 
                data-view="audit">
          <i class="fa-solid fa-list-check text-xs w-4 text-center text-slate-400"></i>
          <span>System Logs</span>
        </button>
      </nav>
    </div>

    <!-- User / Session Footer -->
    <div class="p-3 border-t border-slate-700 bg-[#0f172a] flex items-center justify-between">
      <div class="flex items-center gap-2.5 overflow-hidden">
        <div class="w-7 h-7 rounded bg-slate-700 text-white font-semibold text-xs flex items-center justify-center shrink-0">
          A
        </div>
        <div class="truncate text-xs">
          <div class="font-medium text-slate-200 truncate">Adarsh Kanaujiya</div>
          <div class="text-[10px] text-emerald-400 flex items-center gap-1 font-mono">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Connected
          </div>
        </div>
      </div>
      <a href="logout.php" title="Sign Out" class="text-slate-400 hover:text-rose-400 text-xs p-1.5 transition">
        <i class="fa-solid fa-arrow-right-from-bracket"></i>
      </a>
    </div>
  </aside>

  <!-- Main Workspace -->
  <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
    <!-- Top Utility Bar -->
    <header class="h-16 border-b border-slate-200 bg-white px-8 flex items-center justify-between shrink-0 sticky top-0 z-20 shadow-sm">
      <div class="flex items-center gap-3 text-xs text-slate-500">
        <span class="px-2.5 py-1 rounded bg-slate-100 text-slate-700 font-medium border border-slate-200">
          <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block mr-1"></span> Academic Year 2026–2027
        </span>
        <span id="activeDeptBreadcrumb" class="font-medium text-slate-800">All Departments Overview</span>
      </div>

      <div class="flex items-center gap-3" id="topActionButtons">
        <button onclick="exportCSV()" class="px-3 py-1.5 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 text-xs rounded font-medium flex items-center gap-2 shadow-sm transition">
          <i class="fa-solid fa-file-arrow-down text-slate-500"></i> Export (.CSV)
        </button>
        <button onclick="openModalForAdd()" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded font-medium flex items-center gap-2 shadow-sm transition">
          <i class="fa-solid fa-user-plus text-xs"></i> Register Student
        </button>
      </div>
    </header>

    <main class="p-8 space-y-6 max-w-7xl w-full mx-auto">

      <!-- ================= VIEW 1: STUDENT COHORT VIEW ================= -->
      <div id="view-cohort" class="space-y-6">
        
        <!-- Live Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <div class="bg-white border border-slate-200 p-4 rounded-lg shadow-sm">
            <span class="text-xs uppercase tracking-wider font-semibold text-slate-500">Total Enrolled</span>
            <div id="statTotalStudents" class="text-2xl font-bold text-slate-900 mt-1"><?= $metrics['total_students'] ?></div>
            <span class="text-[11px] text-slate-400">Institutional Registry</span>
          </div>
          <div class="bg-white border border-slate-200 p-4 rounded-lg shadow-sm">
            <span class="text-xs uppercase tracking-wider font-semibold text-slate-500">Weighted CGPA</span>
            <div class="text-2xl font-bold text-blue-700 mt-1">
              <span id="statAvgCgpa"><?= $metrics['avg_cgpa'] ?></span> 
              <span class="text-xs text-slate-400 font-normal">/ 10.0</span>
            </div>
            <span class="text-[11px] text-slate-400">Average Across Streams</span>
          </div>
          <div class="bg-white border border-slate-200 p-4 rounded-lg shadow-sm">
            <span class="text-xs uppercase tracking-wider font-semibold text-slate-500">Average Attendance</span>
            <div class="text-2xl font-bold text-emerald-600 mt-1">
              <span id="statAvgAttendance"><?= $metrics['avg_attendance'] ?></span>%
            </div>
            <span class="text-[11px] text-emerald-700">Compliance &ge; 75%</span>
          </div>
          <div class="bg-white border border-slate-200 p-4 rounded-lg shadow-sm">
            <span class="text-xs uppercase tracking-wider font-semibold text-slate-500">Dean's Distinctions</span>
            <div id="statDeansList" class="text-2xl font-bold text-purple-700 mt-1"><?= $metrics['deans_list_count'] ?></div>
            <span class="text-[11px] text-purple-700">CGPA &ge; 8.50</span>
          </div>
        </div>

        <!-- Filter Toolbar -->
        <div class="bg-white border border-slate-200 rounded-lg p-4 shadow-sm space-y-3">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-md border border-slate-200" id="degreePillsContainer">
              <!-- Populated by JS -->
            </div>

            <div class="relative w-full md:w-72">
              <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-xs text-slate-400"></i>
              <input type="text" id="searchInput" placeholder="Search by name, roll, or email..." class="w-full bg-slate-50 border border-slate-300 rounded-md pl-9 pr-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
            </div>
          </div>

          <div class="flex items-center gap-2 pt-2 border-t border-slate-100 overflow-x-auto text-xs" id="yearFilterRow">
            <span class="text-slate-500 font-medium text-[11px] uppercase tracking-wider shrink-0 mr-1">Academic Year:</span>
            <div class="flex items-center gap-1" id="yearPillButtons">
              <!-- Populated by JS -->
            </div>
          </div>
        </div>

        <!-- Data Grid -->
        <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
          <div class="p-3 border-b border-slate-200 flex justify-between items-center bg-slate-50/60 text-xs text-slate-500">
            <div id="cohortSummary" class="font-medium">Active Cohort Records</div>
            <div id="recordCounter" class="font-mono">Loading...</div>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold uppercase tracking-wider">
                  <th class="py-3 px-4">Roll Number</th>
                  <th class="py-3 px-4">Student Profile</th>
                  <th class="py-3 px-4">Department & Degree</th>
                  <th class="py-3 px-4">Year Level</th>
                  <th class="py-3 px-4">CGPA</th>
                  <th class="py-3 px-4">Attendance</th>
                  <th class="py-3 px-4">Standing</th>
                  <th class="py-3 px-4 text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="studentTableBody" class="divide-y divide-slate-100">
                <!-- Dynamically populated via AJAX -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- ================= VIEW 2: NOTIFICATIONS VIEW ================= -->
      <div id="view-notifications" class="space-y-4 hidden">
        <div class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm">
          <div class="flex items-center justify-between border-b border-slate-200 pb-4 mb-4">
            <div>
              <h3 class="text-base font-bold text-slate-900">Official Circulars & Exam Notices</h3>
              <p class="text-xs text-slate-500">Office of the Controller of Examinations & Academic Dean</p>
            </div>
            <span class="px-2.5 py-1 rounded bg-blue-50 text-blue-700 text-xs font-semibold border border-blue-200">Semester V (Odd Term)</span>
          </div>

          <div class="space-y-3">
            <div class="p-4 rounded-lg border border-slate-200 bg-slate-50/50 flex items-start justify-between gap-4">
              <div class="space-y-1">
                <div class="flex items-center gap-2">
                  <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-red-100 text-red-700 uppercase">Urgent</span>
                  <h4 class="text-xs font-bold text-slate-800">Winter End-Term Examination Schedule Released (2026)</h4>
                </div>
                <p class="text-xs text-slate-600">The provisional schedule for B.Tech & M.Tech 5th/7th Semester theory and practical examinations has been published.</p>
                <div class="text-[11px] text-slate-400 font-mono pt-1">Ref: NU/EXAM/2026/ET-08 • 2 days ago</div>
              </div>
              <button class="px-3 py-1 bg-white border border-slate-300 text-xs font-medium rounded hover:bg-slate-100 text-slate-700 shrink-0">Download PDF</button>
            </div>

            <div class="p-4 rounded-lg border border-slate-200 bg-slate-50/50 flex items-start justify-between gap-4">
              <div class="space-y-1">
                <div class="flex items-center gap-2">
                  <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-100 text-blue-700 uppercase">Academic</span>
                  <h4 class="text-xs font-bold text-slate-800">Mandatory 75% Attendance Review Deadline</h4>
                </div>
                <p class="text-xs text-slate-600">Departmental Heads must submit the finalized list of candidates under Academic Probation prior to hall ticket generation.</p>
                <div class="text-[11px] text-slate-400 font-mono pt-1">Ref: NU/SET/ATT/2026/41 • 5 days ago</div>
              </div>
              <button class="px-3 py-1 bg-white border border-slate-300 text-xs font-medium rounded hover:bg-slate-100 text-slate-700 shrink-0">View Circular</button>
            </div>

            <div class="p-4 rounded-lg border border-slate-200 bg-slate-50/50 flex items-start justify-between gap-4">
              <div class="space-y-1">
                <div class="flex items-center gap-2">
                  <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-700 uppercase">Research</span>
                  <h4 class="text-xs font-bold text-slate-800">Call for Winter Research Internships (IIT / IIIT Guwahati Collaboration)</h4>
                </div>
                <p class="text-xs text-slate-600">Students in 3rd year CSE and IT are invited to submit expressions of interest for winter collaborative projects.</p>
                <div class="text-[11px] text-slate-400 font-mono pt-1">Ref: NU/R&D/INTERN/2026/02 • 1 week ago</div>
              </div>
              <button class="px-3 py-1 bg-white border border-slate-300 text-xs font-medium rounded hover:bg-slate-100 text-slate-700 shrink-0">Apply Details</button>
            </div>
          </div>
        </div>
      </div>

      <!-- ================= VIEW 3: SYLLABUS & CURRICULUM VIEW ================= -->
      <div id="view-syllabus" class="space-y-4 hidden">
        <div class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm">
          <div class="border-b border-slate-200 pb-4 mb-4">
            <h3 class="text-base font-bold text-slate-900">Curriculum & Approved Course Syllabi</h3>
            <p class="text-xs text-slate-500">School of Engineering & Technology (AICTE / UGC Compliant 160-Credit Scheme)</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="border border-slate-200 rounded-lg p-4 bg-slate-50/50 space-y-2">
              <div class="flex items-center justify-between">
                <span class="font-bold text-xs text-slate-900">Computer Science & Engineering</span>
                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-[10px] font-semibold">B.Tech & M.Tech</span>
              </div>
              <p class="text-xs text-slate-600">Core courses: Data Structures, Operating Systems, Database Management Systems, Computer Networks, and Machine Learning Electives.</p>
              <div class="flex gap-2 pt-2">
                <a href="#" class="text-xs text-blue-600 font-medium hover:underline"><i class="fa-solid fa-file-pdf mr-1"></i> B.Tech Syllabus</a>
                <span class="text-slate-300">•</span>
                <a href="#" class="text-xs text-blue-600 font-medium hover:underline"><i class="fa-solid fa-file-pdf mr-1"></i> M.Tech Specialization</a>
              </div>
            </div>

            <div class="border border-slate-200 rounded-lg p-4 bg-slate-50/50 space-y-2">
              <div class="flex items-center justify-between">
                <span class="font-bold text-xs text-slate-900">Information Technology</span>
                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-[10px] font-semibold">B.Tech & M.Tech</span>
              </div>
              <p class="text-xs text-slate-600">Core courses: Web Architectures, Information Security, Cloud Computing, Distributed Systems, and Data Warehousing.</p>
              <div class="flex gap-2 pt-2">
                <a href="#" class="text-xs text-blue-600 font-medium hover:underline"><i class="fa-solid fa-file-pdf mr-1"></i> B.Tech Syllabus</a>
                <span class="text-slate-300">•</span>
                <a href="#" class="text-xs text-blue-600 font-medium hover:underline"><i class="fa-solid fa-file-pdf mr-1"></i> M.Tech Scheme</a>
              </div>
            </div>

            <div class="border border-slate-200 rounded-lg p-4 bg-slate-50/50 space-y-2">
              <div class="flex items-center justify-between">
                <span class="font-bold text-xs text-slate-900">Electronics & Communication Engineering</span>
                <span class="px-2 py-0.5 bg-slate-200 text-slate-700 rounded text-[10px] font-semibold">B.Tech</span>
              </div>
              <p class="text-xs text-slate-600">Core courses: Signals and Systems, Microprocessors, Analog & Digital VLSI, Fiber Optics, and Antenna Wave Propagation.</p>
              <div class="pt-2">
                <a href="#" class="text-xs text-blue-600 font-medium hover:underline"><i class="fa-solid fa-file-pdf mr-1"></i> B.Tech 4-Year Scheme</a>
              </div>
            </div>

            <div class="border border-slate-200 rounded-lg p-4 bg-slate-50/50 space-y-2">
              <div class="flex items-center justify-between">
                <span class="font-bold text-xs text-slate-900">Agriculture & Biotechnology Eng.</span>
                <span class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded text-[10px] font-semibold">B.Tech, M.Tech, PhD</span>
              </div>
              <p class="text-xs text-slate-600">Core courses: Soil and Water Conservation, Farm Machinery, Bioprocess Engineering, Genetic Engineering, and Research Methodology.</p>
              <div class="flex gap-2 pt-2">
                <a href="#" class="text-xs text-blue-600 font-medium hover:underline"><i class="fa-solid fa-file-pdf mr-1"></i> Agri Syllabus</a>
                <span class="text-slate-300">•</span>
                <a href="#" class="text-xs text-blue-600 font-medium hover:underline"><i class="fa-solid fa-file-pdf mr-1"></i> Biotech Syllabus</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ================= VIEW 4: AUDIT LOGS VIEW ================= -->
      <div id="view-audit" class="space-y-4 hidden">
        <div class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm">
          <h3 class="text-base font-bold text-slate-900 mb-1">Administrative Audit Trail</h3>
          <p class="text-xs text-slate-500 mb-4">Immutable transaction log of database modifications and credential validations</p>

          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold uppercase tracking-wider">
                  <th class="py-2.5 px-3">Timestamp</th>
                  <th class="py-2.5 px-3">Operator</th>
                  <th class="py-2.5 px-3">Action</th>
                  <th class="py-2.5 px-3">Affected Entity</th>
                  <th class="py-2.5 px-3">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 font-mono text-[11px]">
                <tr>
                  <td class="py-2.5 px-3 text-slate-500">2026-10-22 18:45:10</td>
                  <td class="py-2.5 px-3 text-slate-800">admin (Adarsh K.)</td>
                  <td class="py-2.5 px-3 text-blue-600">UPDATE_RECORD</td>
                  <td class="py-2.5 px-3">NU/CSE/2024/01</td>
                  <td class="py-2.5 px-3 text-emerald-600 font-bold">COMMITTED</td>
                </tr>
                <tr>
                  <td class="py-2.5 px-3 text-slate-500">2026-10-22 18:30:22</td>
                  <td class="py-2.5 px-3 text-slate-800">admin (Adarsh K.)</td>
                  <td class="py-2.5 px-3 text-emerald-600">INSERT_RECORD</td>
                  <td class="py-2.5 px-3">NU/IT/MT/24/03</td>
                  <td class="py-2.5 px-3 text-emerald-600 font-bold">COMMITTED</td>
                </tr>
                <tr>
                  <td class="py-2.5 px-3 text-slate-500">2026-10-22 17:15:00</td>
                  <td class="py-2.5 px-3 text-slate-800">system_cron</td>
                  <td class="py-2.5 px-3 text-slate-600">SYNC_AGGREGATES</td>
                  <td class="py-2.5 px-3">students_db_summary</td>
                  <td class="py-2.5 px-3 text-emerald-600 font-bold">SUCCESS</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </main>
  </div>

  <!-- Unified Add & Edit Modal Drawer -->
  <div id="studentModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 w-full max-w-lg rounded-lg shadow-2xl p-6 space-y-4">
      <div class="flex justify-between items-center border-b border-slate-200 pb-3">
        <h3 id="modalHeading" class="text-sm font-bold text-slate-900 uppercase tracking-wide">Register New Student</h3>
        <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
      </div>

      <form id="studentForm" onsubmit="handleFormSubmit(event)" class="space-y-3 text-xs">
        <input type="hidden" id="student_id" name="id" value="0">

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-slate-600 mb-1 font-medium">Roll Number</label>
            <input type="text" id="form_roll" required placeholder="e.g. NU/CSE/2024/01" class="w-full bg-slate-50 border border-slate-300 rounded px-3 py-2 text-slate-800 font-mono focus:border-blue-500 outline-none">
          </div>
          <div>
            <label class="block text-slate-600 mb-1 font-medium">Full Name</label>
            <input type="text" id="form_name" required placeholder="Student Legal Name" class="w-full bg-slate-50 border border-slate-300 rounded px-3 py-2 text-slate-800 focus:border-blue-500 outline-none">
          </div>
        </div>

        <div>
          <label class="block text-slate-600 mb-1 font-medium">Institutional Email</label>
          <input type="email" id="form_email" required placeholder="student@nagalanduniv.ac.in" class="w-full bg-slate-50 border border-slate-300 rounded px-3 py-2 text-slate-800 focus:border-blue-500 outline-none">
        </div>

        <div>
          <label class="block text-slate-600 mb-1 font-medium">Department</label>
          <select id="form_dept" onchange="syncDegreeOptionsInModal()" class="w-full bg-slate-50 border border-slate-300 rounded px-3 py-2 text-slate-800 focus:border-blue-500 outline-none">
            <option value="Agriculture Engineering & Technology">Agriculture Engineering & Technology</option>
            <option value="Biotechnology Engineering">Biotechnology Engineering</option>
            <option value="Computer Science & Engineering">Computer Science & Engineering</option>
            <option value="Electronics & Communication Engineering">Electronics & Communication Engineering</option>
            <option value="Information Technology">Information Technology</option>
          </select>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-slate-600 mb-1 font-medium">Degree Level</label>
            <select id="form_degree" onchange="syncYearOptionsInModal()" class="w-full bg-slate-50 border border-slate-300 rounded px-3 py-2 text-slate-800 focus:border-blue-500 outline-none">
              <!-- Dynamically populated -->
            </select>
          </div>
          <div>
            <label class="block text-slate-600 mb-1 font-medium">Academic Year</label>
            <select id="form_year" class="w-full bg-slate-50 border border-slate-300 rounded px-3 py-2 text-slate-800 focus:border-blue-500 outline-none">
              <!-- Dynamically populated -->
            </select>
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-slate-600 mb-1 font-medium">CGPA (0.00 – 10.00)</label>
            <input type="number" step="0.01" min="0" max="10" id="form_cgpa" required placeholder="7.50" class="w-full bg-slate-50 border border-slate-300 rounded px-3 py-2 text-slate-800 focus:border-blue-500 outline-none font-mono">
          </div>
          <div>
            <label class="block text-slate-600 mb-1 font-medium">Attendance %</label>
            <input type="number" step="0.1" min="0" max="100" id="form_attendance" value="85.0" required class="w-full bg-slate-50 border border-slate-300 rounded px-3 py-2 text-slate-800 focus:border-blue-500 outline-none font-mono">
          </div>
        </div>

        <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
          <button type="button" onclick="closeModal()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded">Cancel</button>
          <button type="submit" id="modalSubmitBtn" class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded shadow-sm">Save Student</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // State Management
    let currentView = 'cohort';
    let currentDept = 'All';
    let currentDegree = 'All';
    let currentYear = 'All';
    let currentSearch = '';
    let globalData = [];
    let debounceTimer;

    // Academic Rules Mapping
    const deptRules = {
      'Agriculture Engineering & Technology': ['B.Tech', 'M.Tech', 'PhD'],
      'Biotechnology Engineering': ['B.Tech', 'M.Tech', 'PhD'],
      'Computer Science & Engineering': ['B.Tech', 'M.Tech'],
      'Electronics & Communication Engineering': ['B.Tech'],
      'Information Technology': ['B.Tech', 'M.Tech']
    };

    const yearRules = {
      'B.Tech': [1, 2, 3, 4],
      'M.Tech': [1, 2],
      'PhD': [1, 2, 3, 4, 5]
    };

    // Accordion Toggle for Department
    function toggleDeptAccordion() {
      const subMenu = document.getElementById('deptSubMenu');
      const chevron = document.getElementById('deptChevron');
      const isHidden = subMenu.classList.contains('hidden');

      if (isHidden) {
        subMenu.classList.remove('hidden');
        chevron.style.transform = 'rotate(0deg)';
      } else {
        subMenu.classList.add('hidden');
        chevron.style.transform = 'rotate(-90deg)';
      }
    }

    // Portal Switcher
    function switchView(viewName) {
      currentView = viewName;

      ['cohort', 'notifications', 'syllabus', 'audit'].forEach(v => {
        const el = document.getElementById(`view-${v}`);
        if (el) el.classList.add('hidden');
      });

      const target = document.getElementById(`view-${viewName}`);
      if (target) target.classList.remove('hidden');

      document.querySelectorAll('.main-nav-btn').forEach(btn => {
        const isMatch = btn.getAttribute('data-view') === viewName;
        if (isMatch) {
          btn.className = 'main-nav-btn w-full flex items-center justify-between px-3 py-2 rounded text-left font-medium bg-blue-600 text-white transition';
        } else {
          btn.className = 'main-nav-btn w-full flex items-center justify-between px-3 py-2 rounded text-left text-slate-300 hover:bg-slate-700/50 hover:text-white transition';
        }
      });

      const actionButtons = document.getElementById('topActionButtons');
      if (actionButtons) {
        if (viewName === 'cohort') actionButtons.classList.remove('hidden');
        else actionButtons.classList.add('hidden');
      }
    }

    // Department Selector
    function setDept(dept) {
      currentDept = dept;

      document.querySelectorAll('.dept-sub-btn').forEach(btn => {
        const isMatch = btn.getAttribute('data-dept') === dept;
        if (isMatch) {
          btn.className = 'dept-sub-btn w-full text-left px-2.5 py-1.5 rounded text-[11px] font-semibold bg-slate-700 text-blue-300 transition truncate';
        } else {
          btn.className = 'dept-sub-btn w-full text-left px-2.5 py-1.5 rounded text-[11px] text-slate-300 hover:bg-slate-700/60 hover:text-white transition truncate';
        }
      });

      document.getElementById('activeDeptBreadcrumb').textContent = 
        dept === 'All' ? 'All Departments Overview' : dept;

      syncDegreeFilterBar();
      fetchFilteredRecords();
    }

    function setDegree(degree) {
      currentDegree = degree;
      currentYear = 'All';
      document.querySelectorAll('.degree-pill').forEach(btn => {
        const isMatch = btn.getAttribute('data-degree') === degree;
        btn.className = `degree-pill px-3 py-1 rounded text-xs ${
          isMatch ? 'font-semibold bg-white text-slate-800 shadow-sm' : 'font-medium text-slate-600 hover:text-slate-900'
        }`;
      });
      syncYearPillButtons();
      fetchFilteredRecords();
    }

    function setYear(year) {
      currentYear = year;
      document.querySelectorAll('.year-pill').forEach(btn => {
        const isMatch = btn.getAttribute('data-year') === String(year);
        btn.className = `year-pill px-2.5 py-0.5 rounded text-xs ${
          isMatch ? 'bg-blue-600 text-white font-medium' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
        }`;
      });
      fetchFilteredRecords();
    }

    function syncDegreeFilterBar() {
      const allowedDegrees = currentDept === 'All' 
        ? ['B.Tech', 'M.Tech', 'PhD'] 
        : deptRules[currentDept];

      const container = document.getElementById('degreePillsContainer');
      let html = `<button onclick="setDegree('All')" class="degree-pill px-3 py-1 rounded text-xs ${currentDegree === 'All' ? 'font-semibold bg-white text-slate-800 shadow-sm' : 'font-medium text-slate-600 hover:text-slate-900'}" data-degree="All">All Levels</button>`;
      
      allowedDegrees.forEach(deg => {
        const active = currentDegree === deg;
        html += `<button onclick="setDegree('${deg}')" class="degree-pill px-3 py-1 rounded text-xs ${active ? 'font-semibold bg-white text-slate-800 shadow-sm' : 'font-medium text-slate-600 hover:text-slate-900'}" data-degree="${deg}">${deg}</button>`;
      });

      container.innerHTML = html;

      if (currentDegree !== 'All' && !allowedDegrees.includes(currentDegree)) {
        currentDegree = 'All';
      }
      syncYearPillButtons();
    }

    function syncYearPillButtons() {
      const yearContainer = document.getElementById('yearPillButtons');
      let years = [1, 2, 3, 4];
      if (currentDegree === 'M.Tech') years = [1, 2];
      if (currentDegree === 'PhD') years = [1, 2, 3, 4, 5];
      if (currentDegree === 'All') years = [1, 2, 3, 4, 5];

      let html = `<button onclick="setYear('All')" class="year-pill px-2.5 py-0.5 rounded text-xs ${currentYear === 'All' ? 'bg-blue-600 text-white font-medium' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'}" data-year="All">All Years</button>`;
      years.forEach(yr => {
        const active = String(currentYear) === String(yr);
        html += `<button onclick="setYear(${yr})" class="year-pill px-2.5 py-0.5 rounded text-xs ${active ? 'bg-blue-600 text-white font-medium' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'}" data-year="${yr}">${yr}${getYearSuffix(yr)} Year</button>`;
      });

      yearContainer.innerHTML = html;
    }

    function getYearSuffix(yr) {
      if (yr === 1) return 'st';
      if (yr === 2) return 'nd';
      if (yr === 3) return 'rd';
      return 'th';
    }

    // Modal Dynamic Select Syncing
    function syncDegreeOptionsInModal(selectedDegree = null) {
      const dept = document.getElementById('form_dept').value;
      const degSelect = document.getElementById('form_degree');
      const allowed = deptRules[dept] || ['B.Tech'];
      
      degSelect.innerHTML = allowed.map(d => `<option value="${d}">${d}</option>`).join('');
      if (selectedDegree && allowed.includes(selectedDegree)) {
        degSelect.value = selectedDegree;
      }
      syncYearOptionsInModal();
    }

    function syncYearOptionsInModal(selectedYear = null) {
      const deg = document.getElementById('form_degree').value;
      const yrSelect = document.getElementById('form_year');
      const maxYears = yearRules[deg] || [1, 2, 3, 4];

      yrSelect.innerHTML = maxYears.map(y => `<option value="${y}">${y}${getYearSuffix(y)} Year</option>`).join('');
      if (selectedYear) yrSelect.value = selectedYear;
    }

    // Modal Open/Close
    function openModalForAdd() {
      document.getElementById('modalHeading').textContent = 'Register New Student';
      document.getElementById('modalSubmitBtn').textContent = 'Commit Record';
      document.getElementById('student_id').value = '0';
      document.getElementById('studentForm').reset();

      if (currentDept !== 'All') {
        document.getElementById('form_dept').value = currentDept;
      }
      syncDegreeOptionsInModal(currentDegree !== 'All' ? currentDegree : null);
      document.getElementById('studentModal').classList.remove('hidden');
    }

    function openModalForEdit(student) {
      document.getElementById('modalHeading').textContent = 'Edit Student Record';
      document.getElementById('modalSubmitBtn').textContent = 'Update Record';
      document.getElementById('student_id').value = student.id;
      document.getElementById('form_roll').value = student.roll_number;
      document.getElementById('form_name').value = student.full_name;
      document.getElementById('form_email').value = student.email;
      document.getElementById('form_dept').value = student.department;

      syncDegreeOptionsInModal(student.degree_level);
      document.getElementById('form_degree').value = student.degree_level;
      
      syncYearOptionsInModal(student.academic_year);
      document.getElementById('form_year').value = student.academic_year;

      document.getElementById('form_cgpa').value = parseFloat(student.cgpa).toFixed(2);
      document.getElementById('form_attendance').value = parseFloat(student.attendance_pct).toFixed(1);

      document.getElementById('studentModal').classList.remove('hidden');
    }

    function closeModal() {
      document.getElementById('studentModal').classList.add('hidden');
    }

    // Form Submit (AJAX) - automatically refetches table and updates top cards
    async function handleFormSubmit(e) {
      e.preventDefault();
      const payload = {
        id: parseInt(document.getElementById('student_id').value),
        roll_number: document.getElementById('form_roll').value.trim(),
        full_name: document.getElementById('form_name').value.trim(),
        email: document.getElementById('form_email').value.trim(),
        department: document.getElementById('form_dept').value,
        degree_level: document.getElementById('form_degree').value,
        academic_year: parseInt(document.getElementById('form_year').value),
        cgpa: parseFloat(document.getElementById('form_cgpa').value),
        attendance_pct: parseFloat(document.getElementById('form_attendance').value)
      };

      try {
        const res = await fetch('api/save_student.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
        const result = await res.json();
        if (result.status === 'success') {
          closeModal();
          fetchFilteredRecords(); // updates both table and top 4 metric cards
        } else {
          alert('Error: ' + result.message);
        }
      } catch (err) {
        alert('Server communication failure');
      }
    }

    // Data Fetching, Metric Updating & Table Rendering
    async function fetchFilteredRecords() {
      const counter = document.getElementById('recordCounter');
      counter.textContent = 'Synchronizing...';

      const url = `api/search.php?dept=${encodeURIComponent(currentDept)}&degree=${encodeURIComponent(currentDegree)}&year=${encodeURIComponent(currentYear)}&q=${encodeURIComponent(currentSearch)}`;
      
      try {
        const res = await fetch(url);
        const json = await res.json();

        if (json.status === 'success') {
          globalData = json.data;
          renderTable(globalData);
          counter.textContent = `${globalData.length} active records`;

          // LIVE UPDATE TOP METRIC CARDS
          if (json.metrics) {
            document.getElementById('statTotalStudents').textContent = json.metrics.total_students;
            document.getElementById('statAvgCgpa').textContent = parseFloat(json.metrics.avg_cgpa).toFixed(2);
            document.getElementById('statAvgAttendance').textContent = parseFloat(json.metrics.avg_attendance).toFixed(1);
            document.getElementById('statDeansList').textContent = json.metrics.deans_list_count;
          }
        }
      } catch (err) {
        counter.textContent = 'Sync Error';
      }
    }

    function renderTable(data) {
      const body = document.getElementById('studentTableBody');
      if (data.length === 0) {
        body.innerHTML = `<tr><td colspan="8" class="py-8 text-center text-slate-400">No records found for the selected department, degree, and year filters.</td></tr>`;
        return;
      }

      body.innerHTML = data.map(s => {
        let standingBadge = 'bg-slate-100 text-slate-700 border-slate-300';
        if (s.academic_status === "Dean's List") standingBadge = 'bg-purple-50 text-purple-700 border-purple-200 font-semibold';
        if (s.academic_status === 'Academic Probation') standingBadge = 'bg-red-50 text-red-700 border-red-200 font-semibold';
        if (s.academic_status === 'Good Standing') standingBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200 font-semibold';

        const jsonString = JSON.stringify(s).replace(/"/g, '&quot;');

        return `
          <tr class="hover:bg-slate-50 transition-colors">
            <td class="py-3 px-4 font-mono font-medium text-blue-700">${s.roll_number}</td>
            <td class="py-3 px-4">
              <div class="font-semibold text-slate-900">${s.full_name}</div>
              <div class="text-[11px] text-slate-500">${s.email}</div>
            </td>
            <td class="py-3 px-4">
              <span class="inline-block px-1.5 py-0.5 rounded bg-slate-100 border border-slate-200 text-[10px] font-mono mr-1 text-slate-700">${s.degree_level}</span>
              <span class="text-slate-600">${s.department}</span>
            </td>
            <td class="py-3 px-4 text-slate-700 font-medium">
              ${s.academic_year}${getYearSuffix(parseInt(s.academic_year))} Year
            </td>
            <td class="py-3 px-4 font-mono font-bold text-slate-900 text-sm">${parseFloat(s.cgpa).toFixed(2)}</td>
            <td class="py-3 px-4">
              <div class="flex items-center gap-2">
                <div class="w-16 bg-slate-200 rounded-full h-1.5 overflow-hidden">
                  <div class="bg-blue-600 h-1.5 rounded-full" style="width: ${s.attendance_pct}%"></div>
                </div>
                <span class="text-[11px] font-mono text-slate-600">${s.attendance_pct}%</span>
              </div>
            </td>
            <td class="py-3 px-4">
              <span class="px-2 py-0.5 rounded border text-[10px] ${standingBadge}">
                ${s.academic_status}
              </span>
            </td>
            <td class="py-3 px-4 text-right space-x-2">
              <button onclick="openModalForEdit(${jsonString})" class="text-slate-400 hover:text-blue-600 p-1 transition" title="Edit Student">
                <i class="fa-regular fa-pen-to-square"></i>
              </button>
              <button onclick="deleteRecord(${s.id})" class="text-slate-400 hover:text-red-600 p-1 transition" title="Purge Record">
                <i class="fa-regular fa-trash-can"></i>
              </button>
            </td>
          </tr>
        `;
      }).join('');
    }

    async function deleteRecord(id) {
      if (!confirm('Confirm deletion of this student record?')) return;
      try {
        const res = await fetch('api/delete.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id: id })
        });
        const json = await res.json();
        if (json.status === 'success') {
          fetchFilteredRecords(); // refetches table and updates top cards after deletion
        }
      } catch (err) {
        alert('Network request error');
      }
    }

    function exportCSV() {
      if (!globalData.length) return;
      let csv = 'Roll Number,Full Name,Email,Department,Degree,Year,CGPA,Attendance,Status\n';
      globalData.forEach(r => {
        csv += `"${r.roll_number}","${r.full_name}","${r.email}","${r.department}","${r.degree_level}",${r.academic_year},${r.cgpa},${r.attendance_pct},"${r.academic_status}"\n`;
      });
      const blob = new Blob([csv], { type: 'text/csv' });
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.setAttribute('href', url);
      a.setAttribute('download', `NU_Cohort_Export_${new Date().toISOString().slice(0,10)}.csv`);
      a.click();
    }

    // Live Search
    document.getElementById('searchInput').addEventListener('input', (e) => {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => {
        currentSearch = e.target.value.trim();
        fetchFilteredRecords();
      }, 200);
    });

    // Initial Setup
    syncDegreeFilterBar();
    fetchFilteredRecords();
  </script>
</body>
</html>