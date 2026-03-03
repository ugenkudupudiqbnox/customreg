import os
import re

languages = ["ar", "bn", "de", "es", "fr", "gu", "hi", "id", "it", "ja", "kn", "ko", "ml", "mr", "nl", "or", "pa", "pt", "ru", "ta", "te", "tr", "ur", "zh_cn"]

base_path = "/Users/ugen/Documents/GitHub/customreg"

for lang in languages:
    filepath = os.path.join(base_path, "lang", lang, "local_customreg.php")
    if not os.path.exists(filepath):
        continue
    
    with open(filepath, 'r', encoding='utf-8') as f:
        lines = f.readlines()
    
    new_lines = []
    for line in lines:
        # Match literal \$string or test_key
        if "\\$string" in line or "test_key" in line:
            continue
        new_lines.append(line)
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.writelines(new_lines)
    print(f"Cleaned {lang}")
