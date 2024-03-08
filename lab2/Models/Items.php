<?php

class Items extends Illuminate\Database\Eloquent\Model
{
    protected $table = 'items';

    public $timestamps = false;

    public function getTableColumns()
    {
        $new_items = array();
        $items = $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
        foreach ($items as $item) {
            array_push($new_items, $item);
        }
        return $new_items;
    }
}
