import json
import sys
import base64
import os
from PIL import Image as PILImg
from io import BytesIO

def add_watermark(background_path, watermark_path, position=(0, 0)):
    background = PILImg.open(background_path)
    watermark = PILImg.open(watermark_path)
    background.paste(watermark, position, watermark)
    buffered = BytesIO()
    background.save(buffered, format="PNG")
    base64_string = base64.b64encode(buffered.getvalue()).decode("utf-8")
    return base64_string


if len(sys.argv) > 1:
    json_data = sys.argv[1]
    data = json.loads(json_data)
    result = {"jsonLoaded":True}
        
    if "inputFilePath" in data and "outputFilePath" in data and "logoFilePath":
        if os.path.exists(data['inputFilePath']):
            with open(data['inputFilePath'], "rb") as image_file:
                encoded_string = add_watermark(data['inputFilePath'], data['logoFilePath'])
                result['outputImgBase64'] = encoded_string
                result['outputFilePath'] = data['outputFilePath']

    print(json.dumps(result)) 
else:
    print(json.dumps({"error": "No input provided from PHP."}))
