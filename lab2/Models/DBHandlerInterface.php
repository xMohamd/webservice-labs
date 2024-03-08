User
<?php
interface DBHandlerInterface
{
    public function connect();
    public function get_data($fields = array(),  $start = 0);
    public function disconnect();
    public function get_record_by_id($id, $primary_key);
    public function search_by_column($name_column, $value);
}
