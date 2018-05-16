<?php

namespace Modules\Admin\Tests;

use Tests\TestCase;

class UploadTest extends TestCase
{
    public function testLargeFileUpload()
    {
        if (!config('netcore.module-admin.test.file_upload.active')) {
            $this->assertTrue(true);

            return false;
        }

        $checkSize = config('netcore.module-admin.test.file_upload.size');

        $this->assertTrue($checkSize <= $this->getMaximumFileUploadSize());
    }

    /**
     * This function returns the maximum files size that can be uploaded
     * in PHP
     * @returns int File size in bytes
     **/
    public function getMaximumFileUploadSize()
    {
        return min(convertPHPSizeToBytes(ini_get('post_max_size')), convertPHPSizeToBytes(ini_get('upload_max_filesize')));
    }

    /**
     * This function transforms the php.ini notation for numbers (like '2M') to an integer (2*1024*1024 in this case)
     *
     * @param string $sSize
     * @return integer The value in bytes
     */
    public function convertPHPSizeToBytes($sSize)
    {
        //
        $sSuffix = strtoupper(substr($sSize, -1));
        if (!in_array($sSuffix, array('P', 'T', 'G', 'M', 'K'))) {
            return (int)$sSize;
        }
        $iValue = substr($sSize, 0, -1);
        switch ($sSuffix) {
            case 'P':
                $iValue *= 1024;
            // Fallthrough intended
            case 'T':
                $iValue *= 1024;
            // Fallthrough intended
            case 'G':
                $iValue *= 1024;
            // Fallthrough intended
            case 'M':
                $iValue *= 1024;
            // Fallthrough intended
            case 'K':
                $iValue *= 1024;
                break;
        }
        return (int)$iValue;
    }
}