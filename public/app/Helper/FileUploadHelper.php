<?php
include_once __DIR__ . '/../Models/Post.php';


class FileUploadHelper extends Helper
{

    public static function handleFileUpload($file):array {
        $file_list = [];
        $file_error = 0;
        foreach ($file["images"]["error"] as $_file_error) {
            $file_error += $_file_error;
        }

        if ($file_error == UPLOAD_ERR_OK) {
            $upload_dir = '/images';
            for ($i = 0; $i < count($file['images']['tmp_name']); $i++) {
                $tmp_name = $file['images']['tmp_name'][$i];
                $name = basename($file['images']['name'][$i]);
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $file_name = md5(time() . $name);
                $file_name = $file_name . '.' . $ext;
                move_uploaded_file($tmp_name, __DIR__ . '/../..' . "$upload_dir/$file_name");
                array_push($file_list, "$upload_dir/$file_name");
            }
        }
        return $file_list;
    }

    public static function fileValidate($file):bool {
        $file_error = 0;
        $is_no_file_uploaded = false;
        foreach ($file["images"]["error"] as $_file_error) {
            if ($_file_error != UPLOAD_ERR_NO_FILE) {
                $file_error += $_file_error;
            } else {
                $is_no_file_uploaded = true;
            }
        }
        if ($file_error == UPLOAD_ERR_OK && !$is_no_file_uploaded) {
            $allowed = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);
            for ($i = 0; $i< count($file['images']['name']); $i++) {
                $name = $file['images']['tmp_name'][$i];
                $type = exif_imagetype($name);
                if (!in_array($type, $allowed)) {
                    return false;
                }
            }
        }
        return true;
    }
}