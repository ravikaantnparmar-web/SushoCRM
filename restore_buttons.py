import os
import re

dir_path = r'c:\xampp\htdocs\SushobhaCRM\modules'

count = 0
for root, dirs, files in os.walk(dir_path):
    for file in files:
        if file.endswith('.php'):
            filepath = os.path.join(root, file)
            module_name = os.path.basename(root)
            
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            
            # Find the page-header div and its contents up to its closing div
            m = re.search(r'(<div class="page-header">\s*<div class="page-header-left">.*?</div>\s*)(</div>)', content, re.DOTALL)
            
            if m:
                header_inner = m.group(1)
                
                # Check if there is already a button in the page-header (not in breadcrumbs)
                # The breadcrumbs contain <a href=...>, but the action buttons usually have class="btn..."
                if 'class="btn' not in header_inner and "class='btn" not in header_inner:
                    
                    # Determine what button to add
                    if file == 'index.php':
                        # Should have a 'New/Add' button
                        # We can derive the label from the page title or module name
                        singular = module_name.rstrip('s').capitalize()
                        if singular == 'Entrie': singular = 'Entry'
                        btn_html = f'<a href="<?= BASE_URL ?>/modules/{module_name}/create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New {singular}</a>\n'
                    elif file in ['create.php', 'edit.php']:
                        btn_html = f'<a href="<?= BASE_URL ?>/modules/{module_name}/index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>\n'
                    elif file == 'view.php':
                        btn_html = f'<a href="<?= BASE_URL ?>/modules/{module_name}/index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>\n'
                    else:
                        btn_html = ''
                    
                    if btn_html:
                        # Insert the button before the closing </div> of page-header
                        new_content = content[:m.end(1)] + "  " + btn_html + content[m.start(2):]
                        with open(filepath, 'w', encoding='utf-8') as f:
                            f.write(new_content)
                        count += 1
                        print(f"Added button to: {filepath}")

print(f"Total files updated: {count}")
