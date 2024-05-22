<?php

namespace EdsonFernandes\VisitTracker;

require_once __DIR__ . '/../config.php';

use Exception;
use PDO;

class Tracker
{

    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public static function postRegisterVisit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return json_encode(['error' => 'Method Not Allowed']);
        }

        try {
            $tracker = new self($GLOBALS['pdo']);

            if (!$tracker->isValidRequest()) {
                http_response_code(403);
                return json_encode(['error' => 'Forbidden']);
            }

            $payload = json_decode(file_get_contents('php://input'), true);

            if (!$tracker->validate($payload)) {
                http_response_code(400);
                return json_encode(['error' => 'Bad Request', 'message' => 'Missing required fields']);
            }

            $save = $tracker->saveVisit($payload);
            if ($save) {
                http_response_code(201);
                return json_encode(['success' => true, 'message' => 'Visit saved successfully']);
            }
        } catch (Exception $th) {
            http_response_code(500);
            return json_encode(['error' => 500, 'message' => $th->getMessage()]);
        }

        http_response_code(500);
        return json_encode(['error' => 'Internal Server Error', 'message' => 'Something went wrong, please try again.']);
    }

    public static function showIndexPage()
    {
        $indexContent = file_get_contents(__DIR__ . '/../views/index.php');
        return $indexContent;
    }

    public static function getRetrieveData()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            return json_encode(['error' => 'Method Not Allowed']);
        }

        $tracker = new self($GLOBALS['pdo']);

        $isJsonRequest = isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;
        if (!$isJsonRequest) {
            return $tracker->showIndexPage();
        }

        try {
            $page = isset($_GET['page']) ? intval($_GET['page'], 10) : 1;
            $perPage = isset($_GET['per_page']) ? intval($_GET['per_page'], 10) : 10;

            $totalVisits = $tracker->getTotalVisits();
            $data = $tracker->getPaginatedData($page, $perPage);

            http_response_code(200);
            return json_encode(['success' => true, 'data' => $data, 'total' => $totalVisits]);
        } catch (Exception $th) {
            http_response_code(500);
            return json_encode(['error' => 500, 'message' => $th->getMessage()]);
        }
    }

    private function isValidRequest()
    {
        $allowedOrigin = getenv('TRACKER_ALLOWED_ORIGIN');
        if (empty($allowedOrigin)) {
            return false;
        }

        if (isset($_SERVER['HTTP_ORIGIN'])) {
            return $_SERVER['HTTP_ORIGIN'] === $allowedOrigin;
        }

        if (isset($_SERVER['HTTP_REFERER'])) {
            $referer_host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
            return $referer_host === parse_url($allowedOrigin, PHP_URL_HOST);
        }

        return false;
    }

    private function validate(array $data)
    {
        $requiredFields = ['ip_address', 'user_agent', 'visited_page'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return false;
            }
        }
        return true;
    }

    private function saveVisit(array $data)
    {
        $fields = [
            'ip_address',
            'user_agent',
            'visited_page',
            'referrer',
            'visit_datetime',
            'country',
            'city',
            'device',
            'screen_resolution',
            'browser',
            'browser_version',
            'operating_system',
        ];

        $filteredData = array_intersect_key($data, array_flip($fields));

        $columns = implode(', ', array_keys($filteredData));
        $placeholders = ':' . implode(', :', array_keys($filteredData));

        $sql = "INSERT INTO tracked_visits ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($filteredData);
    }

    private function getTotalVisits(): int
    {
        $sql = 'SELECT COUNT(*) as total FROM tracked_visits';
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    private function getPaginatedData(int $page, int $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        $sql = 'SELECT * FROM tracked_visits LIMIT :limit OFFSET :offset';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private static function jsonResponse($statusCode, $statusMessage, $data = null)
    {
        http_response_code($statusCode);
        $response = ['status' => $statusMessage];
        if ($data !== null) {
            $response['data'] = $data;
        }
        return json_encode($response);
    }
}
