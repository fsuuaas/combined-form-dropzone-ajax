<?php

/**
 * PHP Image uploader Script
 */
$uploaddir = './uploader/';

$images = restructureArray($_FILES);
//echo '<pre>';print_r($images);echo '</pre>';exit;

$data = [];

foreach ($images as $key => $image) {
    $name = $image['name'];
    $uploadfile = $uploaddir . basename($name);
    if (move_uploaded_file($image['tmp_name'], $uploadfile)) {
        $data[$key]['success'] = true;
        $data[$key]['src'] = $name;

    } else {
        $data[$key]['success'] = false;
        $data[$key]['src'] = $name;
    }
}   

echo json_encode($data);exit;

/**
 * RestructureArray method
 * 
 * @param array $images array of images
 * 
 * @return array $result array of images
 */
function restructureArray(array $images)
{
    $result = array();
    foreach ($images as $key => $value) {
        foreach ($value as $k => $val) {
            for ($i = 0; $i < count($val); $i++) {
                $result[$i][$k] = $val[$i];
            }
        }
    }

    return $result;
}


