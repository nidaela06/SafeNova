<?php
$host       = "tramway.proxy.rlwy.net";
$kullanici  = "root";
$db_sifre   = "rtFqFcMRVszREQvMUPPqDcdonJDGwsRU";
$veritabani = "railway";
$port       = 51401;

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$veritabani;charset=utf8mb4",
        $kullanici,
        $db_sifre,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    header('Content-Type: application/json');
    die(json_encode(["status" => "error", "message" => "DB hatası: " . $e->getMessage()]));
}

class StmtCompat {
    public PDOStatement $stmt;
    public MysqliCompat $conn;
    public int $num_rows = 0;
    public int $affected_rows = 0;
    public string $error = '';
    private array $bound = [];

    public function __construct(PDOStatement $stmt, MysqliCompat $conn) {
        $this->stmt = $stmt;
        $this->conn = $conn;
    }

    public function bind_param(string $types, &...$vars): void {
        $this->bound = [];
        foreach ($vars as $i => &$v) {
            $this->bound[$i] = &$v;
        }
    }

    public function execute(): bool {
        try {
            $params = [];
            foreach ($this->bound as &$v) $params[] = $v;
            $ok = $this->stmt->execute($params ?: null);
            $this->affected_rows = $this->stmt->rowCount();
            $this->num_rows      = $this->stmt->rowCount();
            $this->conn->insert_id = (int)$this->conn->pdo->lastInsertId();
            return $ok;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function get_result(): self { return $this; }
    public function store_result(): void {}

    public function fetch_assoc(): ?array {
        $row = $this->stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function fetch_all(int $mode = 0): array {
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function close(): void {}
}

class MysqliCompat {
    public PDO $pdo;
    public int $insert_id = 0;
    public string $error = '';

    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public function prepare(string $sql): StmtCompat {
        return new StmtCompat($this->pdo->prepare($sql), $this);
    }

    public function query(string $sql): StmtCompat {
        return new StmtCompat($this->pdo->query($sql), $this);
    }

    public function set_charset(string $c): void {}
    public function close(): void {}
}

$conn = new MysqliCompat($pdo);
?>
