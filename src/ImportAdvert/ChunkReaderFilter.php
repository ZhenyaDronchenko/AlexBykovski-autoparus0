<?php

namespace App\ImportAdvert;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class ChunkReaderFilter implements IReadFilter
{
    private $startRow = 0;
    private $endRow   = 0;
    private $columns = null;

    /**  Set the list of rows that we want to read  */
    public function setRows($startRow, $chunkSize, $columns = null) {
        $this->startRow = $startRow;
        $this->endRow   = $startRow + $chunkSize;
        $this->columns   = $columns;
    }

    public function readCell($column, $row, $worksheetName = '') {
        //if($row < 3){
            //var_dump((!$this->columns || in_array($column, $this->columns)));
        //}
        if (($row == 1) || ($row >= $this->startRow && $row < $this->endRow)) {
            if (!$this->columns || in_array($column, $this->columns)) {
                return true;
            }
        }

        return false;

        //  Only read the heading row, and the configured rows
//        return ($row == 1) || ($row >= $this->startRow && $row < $this->endRow) &&
//            (!$this->columns || in_array($column, range('A','E')));
    }
}