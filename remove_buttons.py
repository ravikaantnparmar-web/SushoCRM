import os
import re

dir_path = r'c:\xampp\htdocs\SushobhaCRM\modules'

count = 0
for root, dirs, files in os.walk(dir_path):
    for file in files:
        if file.endswith('.php'):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            
            new_content = re.sub(r'[ \t]*<div class="topbar-right">.*?</div>\n?', '', content, flags=re.DOTALL)
            
            if new_content != content:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                count += 1
                print(f"Updated: {filepath}")

print(f"Total files updated: {count}")
