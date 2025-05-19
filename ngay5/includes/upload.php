<?php

function uploadFile($file)
{
    $uploadFile = 'uploads/';
    $maxFileSize = 2 * 1024 * 1024;
    $dangFile = ['image/jpg', 'image/png', 'application/pdf'];

    if($file['size'] > $maxFileSize){
        echo "File quá 2MB";
        return;
    }

    if(!in_array($file['type'], $dangFile)){
        echo "Sai định dạng file. Chỉ chấp nhận JPG, PNG, PDF";
        return;
    }

    $timestamp = time();
    $fileName = $timestamp . '_' . basename($file['name']);
    $filePath = $uploadFile . $fileName;

    if(move_uploaded_file($file['tmp_name'], $filePath)){
        echo "Tải lên thành công";
    }else{
        echo "Lỗi";
    }

}