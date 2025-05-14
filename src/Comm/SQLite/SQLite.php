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
        EOF;
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':worker_id', $workerId, SQLITE3_TEXT);
        $stmt->bindValue(':job_id', $jobId, SQLITE3_TEXT);
        $stmt->bindValue(':message_type', $messageType, SQLITE3_TEXT);
        $stmt->execute();
        $rowId = $this->db->lastInsertRowID();

        if ($rowId <= 0) return null;

        $res = $this->db->openBlob(self::TABLENAME, 'message', $rowId, 'main', SQLITE3_OPEN_READWRITE);
        if ($res === false) return null;

        // TODO handle error
        // TODO use a callback function
        //      and pass the resource in arg
        $ret = fwrite($res, $message);
        fclose($res);
        if ($ret === false) return null;
        return $ret;
    }
    public function get(string $jobId) {}
}
