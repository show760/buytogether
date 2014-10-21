<?php
namespace Buytogether\Library;

use BuyTogether\Model\Img;

class ImgLibrary
{
    public static function imgError($op = null)
    {
        switch ($op) {
            case '1':
                switch ($_FILES["file"]["error"]) {
                    case 1:
                        // 檔案大小超出了伺服器上傳限制 UPLOAD_ERR_INI_SIZE
                        $msg = array(
                            'status' => false,
                            'string' => "The file is too large (server).",
                        );
                        break;
                    case 2:
                        // 要上傳的檔案大小超出瀏覽器限制 UPLOAD_ERR_FORM_SIZE
                        $msg = array(
                            'status' => false,
                            'string' => 'The file is too large (form).',
                        );
                        break;
                    case 3:
                        // 檔案僅部分被上傳 UPLOAD_ERR_PARTIAL
                        $msg = array(
                            'status' => false,
                            'string' => 'The file was only partially uploaded.',
                        );
                        break;
                    case 4:
                        // 沒有找到要上傳的檔案 UPLOAD_ERR_NO_FILE
                        $msg = array(
                            'status' => false,
                            'string' => 'No file was uploaded.',
                        );
                        break;
                    case 5:
                        // 伺服器臨時檔案遺失
                        $msg = array(
                            'status' => false,
                            'string' => 'The servers temporary folder is missing.',
                        );
                        break;
                    case 6:
                        // 檔案寫入到站存資料夾錯誤 UPLOAD_ERR_NO_TMP_DIR
                        $msg = array(
                            'status' => false,
                            'string' => 'Failed to write to the temporary folder.',
                        );
                        break;
                    case 7:
                        // 無法寫入硬碟 UPLOAD_ERR_CANT_WRITE
                        $msg = array(
                            'status' => false,
                            'string' => 'Failed to write file to disk.',
                        );
                        break;
                    case 8:
                        // UPLOAD_ERR_EXTENSION
                        $msg = array(
                            'status' => false,
                            'string' => 'File upload stopped by extension.',
                        );
                        break;
                }
                return $msg;
                break;
            case '2':
                $msg = array(
                    'status' => false,
                    'string' => "輸入資料有誤,請進行確認",
                );
                return $msg;
                break;
            default:
                $msg = array(
                    'status' => false,
                    'string' => "Unknown error",
                );
                return $msg;
                break;
        }
    }
}
