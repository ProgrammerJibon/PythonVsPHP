<?php
$result = [];

if (!function_exists("hexToRGB")) {
    function hexToRGB($hex)
    {
        $hex = str_replace('#', '', $hex);
        $alpha = 127;

        if (strlen($hex) === 8) {
            $alpha = hexdec(substr($hex, 6, 2)) / 255 * 127;
            $hex = substr($hex, 0, 6);
        }

        $rgb = [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
            $alpha
        ];

        return $rgb;
    }
}

if (!function_exists("add_text_to_image")) {
    function add_text_to_image($image_path, $output_file_path, $text, $text_color = "#000000", $padding = 10, $font_size = 100)
    {
        $image = imagecreatefromjpeg($image_path);

        $image_width = imagesx($image);
        $image_height = imagesy($image);

        $font_path = './assets/DatBox.ttf'; 

        
        $lineX = explode("\n", $text);

        $lines = [];
        foreach($lineX as $line){
            if($line != ""){
                $lines[] = $line;
            }            
        }

        $max_width = 0;
        $total_height = 0;

        foreach ($lines as $line) {
            $bbox = imagettfbbox($font_size, 0, $font_path, $line);
            $line_width = $bbox[2] - $bbox[0];
            $line_height = $bbox[1] - $bbox[7];
            $max_width = max($max_width, $line_width);
            $total_height += $line_height;
        }

        $x_position = $image_width - $max_width - $padding;
        $y_position = $image_height - $total_height - $padding;

        $rgb = hexToRGB($text_color);
        $color = imagecolorallocatealpha($image, $rgb[0], $rgb[1], $rgb[2], $rgb[3]);

        
        foreach ($lines as $index => $line) {
            $bbox = imagettfbbox($font_size, 0, $font_path, $line);
            $line_height = $bbox[1] - $bbox[7];
            
            
            $current_y_position = $y_position + ($line_height * $index);

            $bbox = imagettfbbox($font_size, 0, $font_path, $line);
            $line_width = $bbox[2] - $bbox[0];
            $x_position = $image_width - $line_width - $padding; 

            imagettftext($image, $font_size, 0, $x_position, $current_y_position + $line_height, $color, $font_path, $line);
        }

        imagejpeg($image, $output_file_path);
        imagedestroy($image);

        return $output_file_path;
    }
}


$tempDate = date("Ymd-His-") . rand();
$tempText = "";
foreach(explode("-", $tempDate) as $tempTextX){
    $tempText.="\n".$tempTextX;
}
$tempText.="\n"."Approved";


$data = array(
    "inputText" => $tempText,
    "inputTextPadding" => 10,
    "inputTextColor" => "#ffffff4d",
    "inputFilePath" => "./input/ronaldo.jpg",
    "outputFilePath" => "./output/WaterMarked-" . $tempDate . ".jpg",
    "fontSize" => 100
);

if (isset($data['inputFilePath'], $data['inputText'], $data['outputFilePath'], $data['inputTextColor'], $data['fontSize'], $data['fontSize'])) {
    if (file_exists($data['inputFilePath'])) {
        $result['saveTo'] = add_text_to_image(
            $data['inputFilePath'],
            $data['outputFilePath'],
            $data['inputText'],
            $data['inputTextColor'],
            $data['inputTextPadding'],
            $data['fontSize']
        );
    } else {
        $result['error'] = "Input file does not exist.";
    }
} else {
    $result['error'] = "Missing required data.";
}

// echo json_encode($result);