<?php


function uploadFile($file, $folderNname)
{
    $fileName = time().'.'.$file->getClientOriginalExtension();
    $file->move(public_path('files/' . $folderNname), $fileName);
    return $fileName;
}

function deleteFile($file, $folderNname)
{
    $file_path = public_path('files/' . $folderNname . '/') . $file;
    if (file_exists($file_path)) {
        unlink($file_path);
        return true;
    }
    return false;
}
