<?php
$result = [];
if (!function_exists("isJson")) {
    function isJson($string)
    {
        $jsonData = json_decode($string, true);
        return json_last_error() === JSON_ERROR_NONE ? $jsonData : false;
    }
}


$data = array(
    "logoFilePath" => "./assets/logo.png",
    "inputFilePath" => "./input/ronaldo.jpg",
    "outputFilePath" => "./output/WaterMarked-" . date("Ymd-His-") . rand() . ".jpg"
);

$json_data = addslashes(json_encode($data));


$output = shell_exec("python ./test.py \"$json_data\"");

if ($jsonData = isJson($output)) {
    if (isset($jsonData['error'])) {
        $result["error"] = "ML error";
    }
    if (isset($jsonData['isEven'])) {
        $result["even"] = $jsonData['isEven'];
        $result["num"] = $jsonData['num'];
    }
    if (isset($jsonData['outputImgBase64'], $jsonData['outputFilePath'])) {
        file_put_contents($jsonData['outputFilePath'], base64_decode($jsonData['outputImgBase64']));
        $result["saveTo"] = $jsonData['outputFilePath'];
    }
} else {
    $result["error"] = $output;
}

echo json_encode($result);
