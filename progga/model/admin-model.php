<?php

require_once __DIR__ . "/../db.php";

class AdminModel
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    /*
        Small helpers keep the code readable.
        Every SELECT/INSERT/UPDATE/DELETE below uses mysqli prepared statements.
    */
    private function bindDynamic($stmt, $types, &$params)
    {
        if ($types === "" || empty($params)) {
            return true;
        }

        $refs = [$types];
        foreach ($params as &$value) {
            $refs[] = &$value;
        }

        return call_user_func_array([$stmt, "bind_param"], $refs);
    }

    private function selectAll($sql, $types = "", $params = [])
    {
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return [];
        }

        $this->bindDynamic($stmt, $types, $params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    private function selectOne($sql, $types = "", $params = [])
    {
        $rows = $this->selectAll($sql, $types, $params);
        return $rows[0] ?? null;
    }

    private function executeQuery($sql, $types = "", $params = [])
    {
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $this->bindDynamic($stmt, $types, $params);
        return $stmt->execute();
    }

    public function dashboardStats()
    {
        $stats = [
            "users_by_role" => [
                "admin" => 0,
                "seeker" => 0,
                "employer" => 0,
                "recruiter" => 0
            ],
            "total_active_jobs" => 0,
            "applications_today" => 0,
            "pending_employers" => 0,
            "pending_verifications" => 0
        ];

        $rows = $this->selectAll("SELECT role, COUNT(*) AS total FROM users GROUP BY role");
        foreach ($rows as $row) {
            if (isset($stats["users_by_role"][$row["role"]])) {
                $stats["users_by_role"][$row["role"]] = (int)$row["total"];
            }
        }

        $row = $this->selectOne("SELECT COUNT(*) AS total FROM jobs WHERE status = ?", "s", ["active"]);
        $stats["total_active_jobs"] = (int)($row["total"] ?? 0);

        $row = $this->selectOne("SELECT COUNT(*) AS total FROM applications WHERE DATE(applied_at) = CURDATE()");
        $stats["applications_today"] = (int)($row["total"] ?? 0);

        $row = $this->selectOne("SELECT COUNT(*) AS total FROM users WHERE role = ? AND is_verified = 0 AND is_active = 1", "s", ["employer"]);
        $stats["pending_employers"] = (int)($row["total"] ?? 0);

        $row = $this->selectOne("SELECT COUNT(*) AS total FROM users WHERE role IN ('employer', 'recruiter') AND is_verified = 0 AND is_active = 1");
        $stats["pending_verifications"] = (int)($row["total"] ?? 0);
        return $stats;
    }

    public function getUsersByRole($role, $keyword = "", $verification = "")
    {
        $like = "%" . $keyword . "%";
        $sql = "SELECT id, name, email, phone, role, is_active, is_verified, created_at
                FROM users
                WHERE role = ? AND (name LIKE ? OR email LIKE ? OR phone LIKE ?)";
        $types = "ssss";
        $params = [$role, $like, $like, $like];

        if ($verification === "pending") {
            $sql .= " AND is_verified = 0 AND is_active = 1";
        } elseif ($verification === "verified") {
            $sql .= " AND is_verified = 1";
        } elseif ($verification === "suspended") {
            $sql .= " AND is_active = 0";
        }

        $sql .= " ORDER BY created_at DESC";
        return $this->selectAll($sql, $types, $params);
    }

    public function getUser($id)
    {
        return $this->selectOne("SELECT * FROM users WHERE id = ? LIMIT 1", "i", [$id]);
    }



    public function getProfileForUser($userId, $role)
    {
        if ($role === "employer") {
            return $this->selectOne("SELECT company_name, industry, company_size, description, website, address, logo_path FROM employer_profiles WHERE user_id = ?", "i", [$userId]);
        }

        if ($role === "recruiter") {
            return $this->selectOne("SELECT agency_name, specialization, description, website FROM recruiter_profiles WHERE user_id = ?", "i", [$userId]);
        }

        if ($role === "seeker") {
            return $this->selectOne("SELECT headline, summary, skills, years_experience, education_level, current_salary, expected_salary, preferred_location, resume_path FROM seeker_profiles WHERE user_id = ?", "i", [$userId]);
        }

        return null;
    }

    public function getAdminActionsForUser($userId)
    {
        return $this->selectAll(
            "SELECT aa.action_type, aa.note, aa.created_at, u.name AS admin_name
             FROM admin_actions aa
             LEFT JOIN users u ON aa.admin_id = u.id
             WHERE aa.target_user_id = ?
             ORDER BY aa.created_at DESC",
            "i",
            [$userId]
        );
    }

    public function approveUser($id, $adminId)
    {
        $done = $this->executeQuery(
            "UPDATE users SET is_verified = 1, is_active = 1 WHERE id = ? AND role IN ('employer', 'recruiter')",
            "i",
            [$id]
        );

        if ($done) {
            $this->recordAction($adminId, $id, "approve", "Verification approved");
        }

        return $done;
    }

    public function rejectUser($id, $adminId, $reason)
    {
        $done = $this->executeQuery(
            "UPDATE users SET is_verified = 0, is_active = 0 WHERE id = ? AND role IN ('employer', 'recruiter')",
            "i",
            [$id]
        );

        if ($done) {
            $this->recordAction($adminId, $id, "reject", $reason);
        }

        return $done;
    }

    public function setUserActive($id, $adminId, $active)
    {
        $active = (int)$active;
        $done = $this->executeQuery(
            "UPDATE users SET is_active = ? WHERE id = ? AND role != 'admin'",
            "ii",
            [$active, $id]
        );

        if ($done) {
            $this->recordAction(
                $adminId,
                $id,
                $active ? "reactivate" : "suspend",
                $active ? "Account reactivated" : "Account suspended or deactivated"
            );
        }

        return $done;
    }

    public function recordAction($adminId, $targetUserId, $action, $note)
    {
        return $this->executeQuery(
            "INSERT INTO admin_actions (admin_id, target_user_id, action_type, note) VALUES (?, ?, ?, ?)",
            "iiss",
            [$adminId, $targetUserId, $action, $note]
        );
    }

    public function getCategories()
    {
        return $this->selectAll(
            "SELECT c.id, c.name, c.description,
                    COUNT(j.id) AS total_jobs,
                    COALESCE(SUM(CASE WHEN j.status = 'active' THEN 1 ELSE 0 END), 0) AS active_jobs
             FROM categories c
             LEFT JOIN jobs j ON c.id = j.category_id
             GROUP BY c.id, c.name, c.description
             ORDER BY c.name"
        );
    }

    public function createCategory($name, $description)
    {
        return $this->executeQuery(
            "INSERT INTO categories (name, description) VALUES (?, ?)",
            "ss",
            [$name, $description]
        );
    }

    public function renameCategory($id, $name, $description)
    {
        return $this->executeQuery(
            "UPDATE categories SET name = ?, description = ? WHERE id = ?",
            "ssi",
            [$name, $description, $id]
        );
    }

    public function deleteCategory($id, &$message)
    {
        $row = $this->selectOne(
            "SELECT COUNT(*) AS total FROM jobs WHERE category_id = ? AND status = ?",
            "is",
            [$id, "active"]
        );

        if ((int)($row["total"] ?? 0) > 0) {
            $message = "Cannot delete this category because active jobs are using it.";
            return false;
        }

        return $this->executeQuery("DELETE FROM categories WHERE id = ?", "i", [$id]);
    }

    public function getJobs($keyword = "", $status = "", $employerId = "", $recruiterId = "")
    {
        $sql = "SELECT j.*, c.name AS category_name,
                       eu.name AS employer_name,
                       ru.name AS recruiter_name
                FROM jobs j
                LEFT JOIN categories c ON j.category_id = c.id
                LEFT JOIN users eu ON j.employer_id = eu.id
                LEFT JOIN users ru ON j.recruiter_id = ru.id
                WHERE (j.title LIKE ? OR j.location LIKE ? OR eu.name LIKE ? OR ru.name LIKE ?)";

        $like = "%" . $keyword . "%";
        $types = "ssss";
        $params = [$like, $like, $like, $like];

        if ($status !== "") {
            $sql .= " AND j.status = ?";
            $types .= "s";
            $params[] = $status;
        }

        if ($employerId !== "") {
            $sql .= " AND j.employer_id = ?";
            $types .= "i";
            $params[] = (int)$employerId;
        }

        if ($recruiterId !== "") {
            $sql .= " AND j.recruiter_id = ?";
            $types .= "i";
            $params[] = (int)$recruiterId;
        }

        $sql .= " ORDER BY j.created_at DESC";
        return $this->selectAll($sql, $types, $params);
    }

    public function getEmployers()
    {
        return $this->selectAll(
            "SELECT id, name FROM users WHERE role = ? ORDER BY name",
            "s",
            ["employer"]
        );
    }

    public function getRecruiters()
    {
        return $this->selectAll(
            "SELECT id, name FROM users WHERE role = ? ORDER BY name",
            "s",
            ["recruiter"]
        );
    }

    public function setJobFeatured($jobId, $featured)
    {
        return $this->executeQuery(
            "UPDATE jobs SET is_featured = ? WHERE id = ? AND status != 'removed'",
            "ii",
            [(int)$featured, $jobId]
        );
    }

    public function toggleJobFeatured($jobId)
    {
        $done = $this->executeQuery(
            "UPDATE jobs SET is_featured = IF(is_featured = 1, 0, 1) WHERE id = ? AND status != 'removed'",
            "i",
            [$jobId]
        );

        if (!$done) {
            return false;
        }

        $row = $this->selectOne("SELECT is_featured FROM jobs WHERE id = ?", "i", [$jobId]);
        return $row ? (int)$row["is_featured"] : false;
    }

    public function removeJob($jobId)
    {
        return $this->executeQuery(
            "UPDATE jobs SET status = 'removed', is_featured = 0 WHERE id = ?",
            "i",
            [$jobId]
        );
    }

    public function getComplaints($status = "")
    {
        $sql = "SELECT c.*, su.name AS submitter_name, tu.name AS subject_name
                FROM complaints c
                LEFT JOIN users su ON c.submitter_id = su.id
                LEFT JOIN users tu ON c.subject_id = tu.id";
        $types = "";
        $params = [];

        if ($status !== "") {
            $sql .= " WHERE c.status = ?";
            $types = "s";
            $params[] = $status;
        }

        $sql .= " ORDER BY c.created_at DESC";
        return $this->selectAll($sql, $types, $params);
    }

    public function resolveComplaint($id, $adminNote)
    {
        return $this->executeQuery(
            "UPDATE complaints SET status = 'resolved', admin_note = ?, resolved_at = NOW() WHERE id = ?",
            "si",
            [$adminNote, $id]
        );
    }

    public function getPolicies()
    {
        $rows = $this->selectAll("SELECT policy_key, policy_value FROM platform_policies ORDER BY policy_key");
        $policies = [];

        foreach ($rows as $row) {
            $policies[$row["policy_key"]] = $row["policy_value"];
        }

        return $policies;
    }

    public function setPolicy($key, $value)
    {
        return $this->executeQuery(
            "INSERT INTO platform_policies (policy_key, policy_value)
             VALUES (?, ?)
             ON DUPLICATE KEY UPDATE policy_value = VALUES(policy_value), updated_at = CURRENT_TIMESTAMP",
            "ss",
            [$key, $value]
        );
    }

    public function analytics()
    {
        return [
            "jobs_per_category" => $this->selectAll(
                "SELECT c.name, COUNT(j.id) AS total
                 FROM categories c
                 LEFT JOIN jobs j ON c.id = j.category_id
                 GROUP BY c.id, c.name
                 ORDER BY total DESC"
            ),
            "applications_over_time" => $this->selectAll(
                "SELECT DATE(applied_at) AS application_date, COUNT(*) AS total
                 FROM applications
                 GROUP BY DATE(applied_at)
                 ORDER BY application_date DESC
                 LIMIT 30"
            ),
            "top_employers" => $this->selectAll(
                "SELECT u.name, COUNT(a.id) AS total_applications
                 FROM users u
                 LEFT JOIN jobs j ON u.id = j.employer_id
                 LEFT JOIN applications a ON j.id = a.job_id
                 WHERE u.role = ?
                 GROUP BY u.id, u.name
                 ORDER BY total_applications DESC
                 LIMIT 10",
                "s",
                ["employer"]
            ),
            "active_recruiters" => $this->selectAll(
                "SELECT u.name, COUNT(j.id) AS total_jobs
                 FROM users u
                 LEFT JOIN jobs j ON u.id = j.recruiter_id
                 WHERE u.role = ?
                 GROUP BY u.id, u.name
                 ORDER BY total_jobs DESC
                 LIMIT 10",
                "s",
                ["recruiter"]
            ),
            "popular_locations" => $this->selectAll(
                "SELECT location, COUNT(*) AS total
                 FROM jobs
                 WHERE location IS NOT NULL AND location != ''
                 GROUP BY location
                 ORDER BY total DESC
                 LIMIT 10"
            ),
            "popular_job_types" => $this->selectAll(
                "SELECT job_type, COUNT(*) AS total
                 FROM jobs
                 GROUP BY job_type
                 ORDER BY total DESC"
            ),
            "user_growth" => $this->selectAll(
                "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, role, COUNT(*) AS total
                 FROM users
                 GROUP BY DATE_FORMAT(created_at, '%Y-%m'), role
                 ORDER BY month DESC, role"
            )
        ];
    }

    public function createAnnouncement($title, $body, $adminId)
    {
        return $this->executeQuery(
            "INSERT INTO announcements (title, body, created_by) VALUES (?, ?, ?)",
            "ssi",
            [$title, $body, $adminId]
        );
    }

    public function deleteAnnouncement($id)
    {
        return $this->executeQuery("DELETE FROM announcements WHERE id = ?", "i", [$id]);
    }

    public function getAnnouncements()
    {
        return $this->selectAll(
            "SELECT a.*, u.name AS admin_name
             FROM announcements a
             LEFT JOIN users u ON a.created_by = u.id
             ORDER BY a.created_at DESC"
        );
    }

    public function monthlyReport($month)
    {
        $start = $month . "-01";
        $end = date("Y-m-t", strtotime($start));

        $data = ["month" => $month];

        $row = $this->selectOne(
            "SELECT COUNT(*) AS total FROM users WHERE DATE(created_at) BETWEEN ? AND ?",
            "ss",
            [$start, $end]
        );
        $data["new_users"] = (int)($row["total"] ?? 0);

        $data["new_users_by_role"] = $this->selectAll(
            "SELECT role, COUNT(*) AS total FROM users WHERE DATE(created_at) BETWEEN ? AND ? GROUP BY role",
            "ss",
            [$start, $end]
        );

        $row = $this->selectOne(
            "SELECT COUNT(*) AS total FROM jobs WHERE DATE(created_at) BETWEEN ? AND ?",
            "ss",
            [$start, $end]
        );
        $data["jobs_posted"] = (int)($row["total"] ?? 0);

        $row = $this->selectOne(
            "SELECT COUNT(*) AS total FROM applications WHERE DATE(applied_at) BETWEEN ? AND ?",
            "ss",
            [$start, $end]
        );
        $data["applications"] = (int)($row["total"] ?? 0);

        $data["top_category"] = $this->selectOne(
            "SELECT c.name, COUNT(j.id) AS total
             FROM categories c
             LEFT JOIN jobs j ON c.id = j.category_id AND DATE(j.created_at) BETWEEN ? AND ?
             GROUP BY c.id, c.name
             ORDER BY total DESC
             LIMIT 1",
            "ss",
            [$start, $end]
        ) ?: ["name" => "N/A", "total" => 0];

        $data["top_employer"] = $this->selectOne(
            "SELECT u.name, COUNT(a.id) AS total
             FROM users u
             LEFT JOIN jobs j ON u.id = j.employer_id
             LEFT JOIN applications a ON j.id = a.job_id AND DATE(a.applied_at) BETWEEN ? AND ?
             WHERE u.role = ?
             GROUP BY u.id, u.name
             ORDER BY total DESC
             LIMIT 1",
            "sss",
            [$start, $end, "employer"]
        ) ?: ["name" => "N/A", "total" => 0];

        $data["most_active_recruiter"] = $this->selectOne(
            "SELECT u.name, COUNT(j.id) AS total
             FROM users u
             LEFT JOIN jobs j ON u.id = j.recruiter_id AND DATE(j.created_at) BETWEEN ? AND ?
             WHERE u.role = ?
             GROUP BY u.id, u.name
             ORDER BY total DESC
             LIMIT 1",
            "sss",
            [$start, $end, "recruiter"]
        ) ?: ["name" => "N/A", "total" => 0];

        $row = $this->selectOne(
            "SELECT COUNT(*) AS total FROM complaints WHERE DATE(created_at) BETWEEN ? AND ?",
            "ss",
            [$start, $end]
        );
        $data["complaints_opened"] = (int)($row["total"] ?? 0);

        $row = $this->selectOne(
            "SELECT COUNT(*) AS total FROM complaints WHERE status = ? AND DATE(created_at) BETWEEN ? AND ?",
            "sss",
            ["resolved", $start, $end]
        );
        $data["complaints_resolved"] = (int)($row["total"] ?? 0);

        return $data;
    }
}
