<?php

Kernel::Import("system.db.abstracttable");

class BrandTable extends AbstractTable
{
    function BrandTable(&$connection)
    {
        parent::AbstractTable($connection, DB_EMPIK_TABLE_BRAND);

        $this->addTableField('Wares_id', DB_COLUMN_NUMERIC, true);
        $this->addTableField('Brand_id', DB_COLUMN_NUMERIC);
        $this->addTableField('Brand_name');
    }

    function getBrandByWaresId($Wares_id) {
        $SQL = sprintf("SELECT * FROM %s WHERE Wares_id = %s LIMIT 0, 1", $this->tableName, $Wares_id);
        $result = $this->connection->ExecuteScalar($SQL);
        return $result['Brand_name'];
    }

}