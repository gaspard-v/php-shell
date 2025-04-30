<?php

namespace GaspardV\PhpShell\Comm;

use SQLite3;

class SQLite implements CommInterface
{
    protected static const FILENAME  = "messages.db";
    protected static const ENCRYPTION_KEY = "f*pF4HI6%OC6TVl7lTZY"; // TODO use env variable
    protected static const TABLENAME = "Message";

    protected static const TABLE_STR = <<<EOF
    CREATE TABLE IF NOT EXISTS {${self::TABLENAME}} (
        message_id INTEGER PRIMARY KEY,
        job_id TEXT NOT NULL,
        worker_id TEXT NOT NULL,
        message_type TEXT NOT NULL,
        message BLOB
    )
    EOF;

    protected SQLite3 $db;
    public function __construct()
    {
        $tags = SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE;
        try {
            $this->db = new SQLite3(self::FILENAME, $tags, self::ENCRYPTION_KEY);
            $this->db->exec(self::TABLE_STR);
        } catch (\Exception $e) {
            $this->db->close();
            throw $e;
        }
    }
    public function send(
        string $workerId,
        string $jobId,
        string $messageType,
        int $size,
        $message
    ): ?int {
        $query = <<<EOF
        INSERT INTO 
            {${self::TABLENAME}}(
                worker_id, 
                job_id, 
                message_type, 
                message
            )
        VALUES(
            ':worker_id', 
            ':job_id', 
            ':message_type', 
            zeroblob({$size})
        )
        RETURNING message_id
        EOF;
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':worker_id', $workerId, SQLITE3_TEXT);
        $stmt->bindValue(':job_id', $jobId, SQLITE3_TEXT);
        $stmt->bindValue(':message_type', $messageType, SQLITE3_TEXT);
        $result = $stmt->execute();
        $result->fetchArray(SQLITE3_ASSOC);

        // TODO finish this
        $this->db->openBlob(self::TABLENAME, 'message', 1, 'main', SQLITE3_OPEN_READWRITE);
        return null;
    }
    public function get(string $jobId) {}
}
