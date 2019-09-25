<?php namespace bulksms\message;

use bulksms\Config;
use bulksms\message\Message;

/**
 * Message database handler.
 *
 * Class MessageStore
 * @package bulksms\message
 */
class MessageStore
{
    private $conn;

    function __construct()
    {
        $mysqlConfig = Config::get("mysql");
        $this->conn = mysqli_connect($mysqlConfig->host, $mysqlConfig->user, $mysqlConfig->pass, $mysqlConfig->db);
    }

    /**
     * Save a message to the database.
     *
     * @param Message $msgObj
     * @return int|null
     */
    function save(Message $msgObj): ?int
    {
        $recipients = mysqli_real_escape_string($this->conn, implode(',', $msgObj->recipients));
        $msgStr = mysqli_real_escape_string($this->conn, $msgObj->msg);

        $sql = "INSERT INTO message values(
                NULL, 
                \"$msgStr\", 
                \"$recipients\", 
                \"$msgObj->from\",
                 \"$msgObj->status\", 
                \"$msgObj->parent\", 
                \"$msgObj->order\");";

        if (mysqli_query($this->conn, $sql)) {
            $id = $last_id = mysqli_insert_id($this->conn);
            return $id;
        }

        echo "ERROR: Could not execute $sql. " . mysqli_error($this->conn);
        return null;
    }

    function update(Message $msg): bool {
        // TODO
        return true;
    }

    /**
     * Function retrieves the message from the db given message id.
     *
     * @param int $id
     * @return Message|null
     */
    function get(int $id): ?Message
    {
        if (!$this->conn || is_null($id)) {
            return null;
        }

        $result = mysqli_query($this->conn, "SELECT * FROM message WHERE id=$id");

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $msg = new Message($row['msg'], $row['from'], explode(',', $row['recipients']));
            $msg->setId($row['id']);
            $msg->setStatus($row['status']);
            $msg->setParent($row['parent']);
            $msg->setOrder($row['order']);
            return $msg;
        }

        return null;
    }
}