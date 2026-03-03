import os
filepath = "/Users/ugen/Documents/GitHub/customreg/lang/es/local_customreg.php"
with open(filepath, 'a', encoding='utf-8') as f:
    f.write("\n\$string['test_key'] = 'test_value';\n")
print(f"Appended test_key to {filepath}")
