<?php
$result = [];

if (!function_exists("isJson")) {
    function isJson($string)
    {
        $jsonData = json_decode($string, true);
        return json_last_error() === JSON_ERROR_NONE ? $jsonData : false;
    }
}

if (!function_exists("add_watermark")) {
    function add_watermark($background_path, $watermark_path, $output_file_path, $position = [0, 0])
    {
        $background = imagecreatefromjpeg($background_path);
        $watermark = imagecreatefrompng($watermark_path);

        $watermark_width = imagesx($watermark);
        $watermark_height = imagesy($watermark);
        $x_position = $position[0];
        $y_position = $position[1];

        imagecopy($background, $watermark, $x_position, $y_position, 0, 0, $watermark_width, $watermark_height);
        imagejpeg($background, $output_file_path);
        imagedestroy($background);
        imagedestroy($watermark);

        return $output_file_path;
    }
}

$data = array(
    "logoFilePath" => "./assets/logo.png",
    "inputFilePath" => "./input/ronaldo.jpg",
    "outputFilePath" => "./output/WaterMarked-" . date("Ymd-His-") . rand() . ".jpg"
);

if (isset($data['inputFilePath'], $data['outputFilePath'], $data['logoFilePath'])) {
    if (file_exists($data['inputFilePath']) && file_exists($data['logoFilePath'])) {
        $result['saveTo'] = add_watermark($data['inputFilePath'], $data['logoFilePath'], $data['outputFilePath']);
    } else {
        $result['error'] = "File paths are incorrect or the files do not exist.";
    }
} else {
    $result['error'] = "Missing file paths.";
}

echo json_encode($result);
