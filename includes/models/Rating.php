<?php

class Rating
{
    private $post_id = null;
    private $positive = 0;
    private $negative = 0;
    private $comment = null;

    public static $table = 'postrating';

    function __construct(array $input = null) {
        global $table_prefix;

        $this->post_id = ($input['post_id']) ?? null;
        $this->positive = ($input['positive']) ?? null;
        $this->negative = ($input['negative']) ?? null;
        $this->comment = ($input['comment']) ?? null;

        $table = $table_prefix . 'wp_postrating';
    }

    public static function find(int $post_id)
    {
        global $table_prefix, $wpdb;
        $res = $wpdb->get_results("SELECT * FROM {$table_prefix}" . self::$table . " WHERE post_id = $post_id LIMIT 1",ARRAY_A);
        $rating = (isset($res[0])) ? new Rating($res[0]) : null;
        return $rating;
    }

    public function setPositive()
    {
        return $this->positive += 1;
    }

    public function setNegative()
    {
        return $this->negative += 1;
    }

    public function setComment(string $comment)
    {
        $comments = json_decode($this->comment);
        $comments[] = $comment;
        $this->comment = json_encode($comments);
        return $this->comment;
    }

    public function save()
    {
        global $wpdb;
        $sql = "INSERT INTO " . self::$table . " (`post_id`, `positive`, `negative`, `comment`) VALUES ( " . $this->post_id . ", '" . $this->positive . "', '" . $this->negative . "', '" . $this->comment . "')";
        return $wpdb->get_results($sql);
    }

    public function update()
    {
        global $wpdb;
        $sql = "UPDATE " . self::$table . " SET 
        `positive` = " . $this->positive . ",
        `negative` = " . $this->negative . ", 
        `comment` = '" . $this->comment . "' 
        WHERE post_id = " . $this->post_id;
        return $wpdb->get_results($sql);
    }

    public static function getAll($page = 1, $orderBy = 'id', $order = 'DESC')
    {
        global $table_prefix, $wpdb;
        $limit = 50;
        $offset = $limit * ($page-1);
        return $wpdb->get_results("SELECT " . self::$table . ".*, {$table_prefix}posts.post_name as url, {$table_prefix}posts.post_title as post_title FROM {$table_prefix}" . self::$table . " as " . self::$table . " INNER JOIN {$table_prefix}posts ON post_id = {$table_prefix}posts.id ORDER BY {$table_prefix}" . self::$table . "." . $orderBy . " " . $order . " LIMIT " . $limit . " OFFSET " . $offset);
    }

    public function getValues()
    {
        return ['positive' => $this->positive, 'negative' => $this->negative];
    }
}