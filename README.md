# Enterprise Academic Registry & Departmental SIS

A centralized institutional Student Information System (SIS) engineered for Nagaland University (School of Engineering & Technology, Kohima Campus). Built using PHP PDO and MySQL, this system features dynamic multi-departmental filtering, real-time debounced asynchronous querying, role-based records management, and automated cohort analytics.

---

## 🏛️ Academic Structure & Supported Streams

The platform enforces strict relational boundaries across five engineering departments and distinct academic levels:

- **Agriculture Engineering & Technology:** B.Tech (Years 1–4), M.Tech (Years 1–2), PhD (Years 1–5)
- **Biotechnology Engineering:** B.Tech (Years 1–4), M.Tech (Years 1–2), PhD (Years 1–5)
- **Computer Science & Engineering:** B.Tech (Years 1–4), M.Tech (Years 1–2)
- **Electronics & Communication Engineering:** B.Tech (Years 1–4)
- **Information Technology:** B.Tech (Years 1–4), M.Tech (Years 1–2)

---

## 🚀 Key Technical Features

- **Relational Integrity & Normalization:** Uses strict MySQL `ENUM` and `CHECK` constraints to ensure data consistency across departments, degree tiers, and academic standings.
- **Secure Backend Architecture:** Utilizes PHP Data Objects (PDO) with parameterized prepared statements (`ATTR_EMULATE_PREPARES => false`) to eliminate SQL injection vectors.
- **Dynamic Real-Time Filtering:** Implements debounced AJAX querying to filter students instantly by department, degree level, academic year, and free-text search without full page refreshes.
- **Live Institutional Metrics:** Aggregates and displays cohort metrics (Total Enrolled, Weighted Mean CGPA, Average Attendance %, Dean's List Distinctions) dynamically computed from database records.
- **Client-Side Export Utility:** Integrated client-side data serialization engine to export filtered student rosters directly to structured CSV format.
- **Institutional Light-Slate ERP UI:** Clean, distraction-free administration dashboard styled with Tailwind CSS, FontAwesome icons, and collapsible departmental navigation.

---

## 🛠️ Tech Stack

- **Backend:** PHP 8.x (PDO MySQL Driver)
- **Database:** MySQL 8.x (Relational Engine)
- **Frontend:** Vanilla JavaScript (ES6+ Fetch API), Tailwind CSS (CDN), FontAwesome 6
- **Server Environment:** Apache (via XAMPP)

---

## 💻 Local Setup & Installation

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/AdarshKanaujiya/Student-Management-System.git](https://github.com/AdarshKanaujiya/Student-Management-System.git)
