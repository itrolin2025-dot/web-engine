import sys

def fix_front_controller():
    path = "/Users/rolinnuisel/Development/web-engine/app/Http/Controllers/FrontController.php"
    with open(path, "r") as f:
        content = f.read()
    
    # Fix syntax error in getNavbarPresets signature
    content = content.replace(
        "public static function getNavbarPresets($categories = [], customerType=[])",
        "public static function getNavbarPresets($categories = [], $customerType = 'Free Edition')"
    )
    
    # Also if the user wants to filter menus based on customer type, I can add it, 
    # but the prompt just says "fix it because I want to get customerType". Let's just fix the syntax.
    
    with open(path, "w") as f:
        f.write(content)

def fix_pages_controller():
    path = "/Users/rolinnuisel/Development/web-engine/app/Http/Controllers/PagesController.php"
    with open(path, "r") as f:
        content = f.read()

    # Add customer_type to select
    content = content.replace(
        "'customers.email as customer_email'\n            )",
        "'customers.email as customer_email',\n                'customers.customer_type as customer_type'\n            )"
    )

    # Assign $customerType
    content = content.replace(
        "$title = $website->title ?? 'Signature Fragrance';",
        "$title = $website->title ?? 'Signature Fragrance';\n        $customerType = $website->customer_type ?? 'Free Edition';"
    )

    # Pass $customerType to getNavbarPresets
    content = content.replace(
        "$navbarPresets = \\App\\Http\\Controllers\\FrontController::getNavbarPresets($categories);",
        "$navbarPresets = \\App\\Http\\Controllers\\FrontController::getNavbarPresets($categories, $customerType);"
    )

    with open(path, "w") as f:
        f.write(content)

fix_front_controller()
fix_pages_controller()
