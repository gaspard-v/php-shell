<?php

namespace GaspardV\PhpShell\Comm\SQLite;

use SQLite3;
use GaspardV\PhpShell\Comm;

class SQLite implements Comm\CommInterface
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

    protected static self $instance = null;
    protected function __construct()
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

    public static function inst(): self {
        if (self::$instance !== null) {
            return self::$instance;
        }
        self::$instance = new self();
        return self::$instance;
    }

    private static function getMessageInsertQuery(int $size): string
    {
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
        EOF;
        return $query;
    }

    public function prepare(
        string $workerId,
        string $jobId,
        string $messageType,
        int $size
    ): SendMessage {
        $query = self::getMessageInsertQuery($size);
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':worker_id', $workerId, SQLITE3_TEXT);
        $stmt->bindValue(':job_id', $jobId, SQLITE3_TEXT);
        $stmt->bindValue(':message_type', $messageType, SQLITE3_TEXT);
        $stmt->execute();
        $rowId = $this->db->lastInsertRowID();

        if ($rowId <= 0)
            throw new SQLiteException('Unable to get the last inserted row id');

        $res = $this->db->openBlob(self::TABLENAME, 'message', $rowId, 'main', SQLITE3_OPEN_READWRITE);
        if ($res === false)
            throw new SQLiteException("Unable to open blob row $rowId");

        return new SendMessage($res);
    }
    public function get(string $jobId) {}
    public function send(
        string $workerId,
        string $jobId,
        string $messageType,
        int $size,
        mixed $message
    ): int {
        $stmt = $this->prepare(
            $workerId,
            $jobId,
            $messageType,
            $size
        );
        $callback = fn($resource) => fwrite($resource, $message);

        $ret = $stmt->send($callback);
        if ($ret === false) throw new SQLiteException("fwrite return false");
        return $ret;
    }
}
