<?php
include_once __DIR__ . '/../Models/Post.php';


class FileUploadHelper extends Helper
{

    public static function handleFileUpload($file, $post_id) {
        $Post_model = new Post();

        $file_error = 0;
        foreach ($file["images"]["error"] as $_file_error) {
            $file_error += $_file_error;
        }

        if (!($file_error > 0)) {
            $upload_dir = '/images';
            for ($i = 0; $i < count($file['images']['tmp_name']); $i++) {
                $tmp_name = $file['images']['tmp_name'][$i];
                $name = basename($file['images']['name'][$i]);
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $file_name = md5(time() . $name);
                $file_name = $file_name . '.' . $ext;
                move_uploaded_file($tmp_name, __DIR__ . '/../..' . "$upload_dir/$file_name");
                $Post_model->insertImage($post_id, "$upload_dir/$file_name");
            }
        }

    }

    public static function fileValidate($file):bool {
        $file_error = 0;
        foreach ($file["images"]["error"] as $_file_error) {
            if ($_file_error != 4) {
                $file_error += $_file_error;
            }
        }
        if ($file_error == 0) {
            $allowed = array('jpg', 'jpeg', 'png', 'gif');
            for ($i = 0; $i< count($file['images']['name']); $i++) {
                $name = basename($file['images']['name'][$i]);
                $ext = pathinfo($name, PATHINFO_EXTENSION);

                if (!empty($ext) && !in_array($ext, $allowed)) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }
}