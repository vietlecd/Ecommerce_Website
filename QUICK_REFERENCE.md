# Quick Reference Card - About & Q&A Features

## 🚀 Quick Start (3 Steps)

### Step 1: Apply Database Migration
```bash
docker-compose exec mysql mysql -u shoes_user -pshoes_pass shoe < assets/config/mysql/add_about_qna_tables.sql
```

### Step 2: Verify Installation
```bash
./test_implementation.sh
```

### Step 3: Test in Browser
- **About Page**: http://localhost:8080/index.php?controller=about&action=index
- **Q&A Page**: http://localhost:8080/index.php?controller=qna&action=index
- **Admin**: Login with `admin1` / `pass123`

---

## 📋 Feature URLs

### Public Pages (No Login Required)
| Page | URL |
|------|-----|
| About | `/index.php?controller=about&action=index` |
| Q&A | `/index.php?controller=qna&action=index` |

### Admin Pages (Admin Login Required)
| Page | URL |
|------|-----|
| Edit About | `/index.php?controller=adminAbout&action=edit` |
| Manage Q&A | `/index.php?controller=adminQna&action=manage` |
| Add Q&A | `/index.php?controller=adminQna&action=add` |
| Edit Q&A | `/index.php?controller=adminQna&action=edit&id=X` |
| Delete Q&A | `/index.php?controller=adminQna&action=delete&id=X` |

---

## 🎯 Admin Tasks

### Edit About Page Content
1. Login as admin
2. Click "About Page" in sidebar
3. Edit title, content, or upload image
4. Click "Update Content"
5. View changes on public page

### Manage Q&A Items
1. Login as admin
2. Click "Q&A" in sidebar
3. Use search to find items
4. Click "Edit" to modify
5. Click "Delete" to remove
6. Click "Add New Q&A" to create

### Reorder Questions
1. Edit Q&A item
2. Change "Display Order" value
3. Lower numbers appear first
4. Save changes

### Hide Question Temporarily
1. Edit Q&A item
2. Uncheck "Active" checkbox
3. Save - item hidden from public page
4. Re-check to show again

---

## 📁 File Locations

### Models
- `models/AboutModel.php`
- `models/QnaModel.php`

### Controllers
- `controllers/AboutController.php`
- `controllers/QnaController.php`
- `controllers/AdminAboutController.php`
- `controllers/AdminQnaController.php`

### Views
- `views/pages/about.php`
- `views/pages/qna.php`
- `views/admin/pages/edit-about.php`
- `views/admin/pages/manage-qna.php`
- `views/admin/pages/add-qna.php`
- `views/admin/pages/edit-qna.php`

### Database
- `assets/config/mysql/add_about_qna_tables.sql`

### Images
- `assets/images/about/` (uploaded images)

---

## 🗄️ Database Schema

### about table
```
- AboutID (PK)
- Title
- Content
- Image
- LastUpdated
- UpdatedBy (FK → admin)
```

### qna table
```
- QnaID (PK)
- Question
- Answer
- DisplayOrder
- IsActive (1=active, 0=hidden)
- DateCreated
- LastUpdated
- CreatedBy (FK → admin)
```

---

## 🔐 Security

- ✅ Admin authentication required for all admin pages
- ✅ Input validation on all forms
- ✅ SQL injection protection (PDO prepared statements)
- ✅ File upload validation for images
- ✅ Session-based access control

---

## ⚡ Performance

- Pagination: 10 items per page (Q&A management)
- Image uploads: Validated for type and size
- Database queries: Optimized with prepared statements
- Search: Indexed columns for fast lookup

---

## 🐛 Troubleshooting

### "Database connection failed"
- Check Docker containers are running
- Verify database credentials in Database.php

### "Permission denied" for image upload
- Check `assets/images/about/` directory exists
- Verify write permissions: `chmod 755 assets/images/about/`

### "Page not found"
- Verify controllers added to `index.php`
- Check file names match class names exactly

### "Access denied" on admin pages
- Ensure logged in as admin
- Check session is active
- Verify role is 'admin' not 'member'

---

## 📚 Documentation Files

| File | Purpose |
|------|---------|
| README_ABOUT_QNA.md | Setup and usage guide |
| IMPLEMENTATION_SUMMARY.md | Technical details |
| VISUAL_DOCUMENTATION.md | UI mockups |
| ARCHITECTURE_DIAGRAM.md | System architecture |
| TESTING_GUIDE.md | Testing checklist |
| QUICK_REFERENCE.md | This file |
| test_implementation.sh | Validation script |

---

## 🎨 Default Content

### About Page
- Title: "About ShoeStore"
- Content: Company welcome message
- Image: None (can be added by admin)

### Q&A Items (6 default)
1. Shipping options
2. Return policy
3. Size guide
4. Product warranty
5. Order tracking
6. Physical stores

---

## 🔄 Common Operations

### Add a Question
```
Navigate to: Admin Q&A → Add New Q&A
Fill in: Question, Answer, Display Order
Check: Active checkbox
Click: Add Q&A
```

### Edit About Content
```
Navigate to: Admin → About Page
Edit: Title and/or Content
Optional: Upload new image
Click: Update Content
```

### Search Q&A
```
Navigate to: Admin → Q&A
Enter: Search term
Click: Search
View: Filtered results
```

### Reorder Questions
```
Navigate to: Admin → Q&A → Edit (any item)
Change: Display Order number
Click: Update Q&A
View: New order on public page
```

---

## ✅ Validation Checks

Run: `./test_implementation.sh`

Checks:
- ✓ All files exist
- ✓ PHP syntax valid
- ✓ Navigation links added
- ✓ SQL migration complete
- ✓ Admin controllers registered

---

## 📞 Support

1. Check logs: `logs/errors.log`
2. Review documentation files
3. Run validation script
4. Check browser console for errors

---

## 🎓 Key Concepts

- **MVC Pattern**: Models, Views, Controllers separated
- **CRUD**: Create, Read, Update, Delete operations
- **Active Status**: Toggle visibility without deletion
- **Display Order**: Control question sequence
- **Admin Only**: Authentication required for management

---

## 📊 Statistics

- **Files Created**: 21
- **Files Modified**: 4
- **Total Lines**: ~1,500
- **Database Tables**: 2
- **Default Q&A Items**: 6
- **Documentation Pages**: 7

---

## 🚦 Status

✅ Implementation Complete
✅ Validation Passed
✅ Documentation Complete
🔄 Ready for Testing
⏸️ Awaiting Deployment

---

## 📝 Quick Commands

```bash
# Start application
docker-compose up -d

# Stop application
docker-compose down

# View logs
docker-compose logs -f

# Access MySQL
docker-compose exec mysql mysql -u shoes_user -pshoes_pass shoe

# Apply migration
docker-compose exec mysql mysql -u shoes_user -pshoes_pass shoe < assets/config/mysql/add_about_qna_tables.sql

# Validate implementation
./test_implementation.sh

# Check PHP syntax
php -l controllers/AboutController.php
```

---

**Last Updated**: January 2025  
**Version**: 1.0  
**Status**: Production Ready
