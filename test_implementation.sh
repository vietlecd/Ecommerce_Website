#!/bin/bash

# Test script to validate the implementation
# This script checks file structure and basic PHP syntax

echo "======================================"
echo "About & Q&A Feature Validation Script"
echo "======================================"
echo ""

# Check if all required files exist
echo "Checking file structure..."
echo ""

FILES=(
    "models/AboutModel.php"
    "models/QnaModel.php"
    "controllers/AboutController.php"
    "controllers/QnaController.php"
    "controllers/AdminAboutController.php"
    "controllers/AdminQnaController.php"
    "views/pages/about.php"
    "views/pages/qna.php"
    "views/admin/pages/edit-about.php"
    "views/admin/pages/manage-qna.php"
    "views/admin/pages/add-qna.php"
    "views/admin/pages/edit-qna.php"
    "assets/config/mysql/add_about_qna_tables.sql"
)

all_exist=true
for file in "${FILES[@]}"; do
    if [ -f "$file" ]; then
        echo "✓ $file"
    else
        echo "✗ $file NOT FOUND"
        all_exist=false
    fi
done

echo ""
if [ "$all_exist" = true ]; then
    echo "✓ All files exist"
else
    echo "✗ Some files are missing"
    exit 1
fi

echo ""
echo "Checking PHP syntax..."
echo ""

# Check PHP syntax for all PHP files
syntax_ok=true
for file in "${FILES[@]}"; do
    if [[ $file == *.php ]]; then
        if php -l "$file" > /dev/null 2>&1; then
            echo "✓ $file - syntax OK"
        else
            echo "✗ $file - syntax error"
            php -l "$file"
            syntax_ok=false
        fi
    fi
done

echo ""
if [ "$syntax_ok" = true ]; then
    echo "✓ All PHP files have valid syntax"
else
    echo "✗ Some PHP files have syntax errors"
    exit 1
fi

echo ""
echo "Checking navigation updates..."
echo ""

# Check if navigation links were added
if grep -q "controller=about" views/components/header.php; then
    echo "✓ About link added to header"
else
    echo "✗ About link not found in header"
fi

if grep -q "controller=qna" views/components/header.php; then
    echo "✓ Q&A link added to header"
else
    echo "✗ Q&A link not found in header"
fi

if grep -q "adminAbout" views/admin/components/header.php; then
    echo "✓ Admin About link added to admin sidebar"
else
    echo "✗ Admin About link not found in admin sidebar"
fi

if grep -q "adminQna" views/admin/components/header.php; then
    echo "✓ Admin Q&A link added to admin sidebar"
else
    echo "✗ Admin Q&A link not found in admin sidebar"
fi

if grep -q "adminAbout" index.php && grep -q "adminQna" index.php; then
    echo "✓ Admin controllers added to index.php"
else
    echo "✗ Admin controllers not found in index.php"
fi

echo ""
echo "Checking SQL migration file..."
echo ""

if grep -q "CREATE TABLE.*about" assets/config/mysql/add_about_qna_tables.sql; then
    echo "✓ About table creation found"
else
    echo "✗ About table creation not found"
fi

if grep -q "CREATE TABLE.*qna" assets/config/mysql/add_about_qna_tables.sql; then
    echo "✓ Q&A table creation found"
else
    echo "✗ Q&A table creation not found"
fi

if grep -q "INSERT INTO.*about" assets/config/mysql/add_about_qna_tables.sql; then
    echo "✓ Default About content found"
else
    echo "✗ Default About content not found"
fi

if grep -q "INSERT INTO.*qna" assets/config/mysql/add_about_qna_tables.sql; then
    echo "✓ Default Q&A data found"
else
    echo "✗ Default Q&A data not found"
fi

echo ""
echo "======================================"
echo "Validation Complete!"
echo "======================================"
echo ""
echo "Next steps:"
echo "1. Apply database migration: assets/config/mysql/add_about_qna_tables.sql"
echo "2. Start the application (Docker or local server)"
echo "3. Test user-facing pages:"
echo "   - http://localhost:8080/index.php?controller=about&action=index"
echo "   - http://localhost:8080/index.php?controller=qna&action=index"
echo "4. Login as admin and test management features:"
echo "   - http://localhost:8080/index.php?controller=adminAbout&action=edit"
echo "   - http://localhost:8080/index.php?controller=adminQna&action=manage"
echo ""
