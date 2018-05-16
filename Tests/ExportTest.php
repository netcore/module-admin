<?php

namespace Modules\Admin\Tests;

use Tests\TestCase;
use Maatwebsite\Excel\Facades\Excel;

class ExportTest extends TestCase
{
    public function testExcelExport()
    {
        if (!config('netcore.module-admin.test.export.active')) {
            $this->assertTrue(true);

            return false;
        }

        Excel::create('test_file', function ($excel) {
            $excel->sheet('test_sheet', function ($sheet) {
                $sheet->fromArray([
                    ['data1', 'data2'],
                    ['data3', 'data4']
                ]);
            });
        });

        $this->assertTrue(true);
    }
}