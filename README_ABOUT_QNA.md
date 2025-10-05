# About and Q&A Feature Implementation

## Overview
This implementation adds two new features to the Ecommerce Website:
1. **About Page** - A user-facing page with editable content for administrators
2. **Q&A Page** - A frequently asked questions page with full CRUD management for administrators

## Database Setup

### Required Tables
Two new tables need to be created in the database:

1. **`about`** - Stores About page content
2. **`qna`** - Stores Q&A items

### How to Apply Database Changes

Run the SQL migration file to create the required tables:

```bash
# If using Docker:
docker-compose exec mysql mysql -u shoes_user -pshoes_pass shoe < assets/config/mysql/add_about_qna_tables.sql

# Or using phpMyAdmin:
# 1. Login to phpMyAdmin (http://localhost:8081)
# 2. Select the 'shoe' database
# 3. Go to SQL tab
# 4. Copy and paste the contents of assets/config/mysql/add_about_qna_tables.sql
# 5. Click 'Go' to execute
```

## Features Implemented

### User-Facing Features

#### 1. About Page (`/index.php?controller=about&action=index`)
- Displays company information
- Shows title, content, and optional image
- Accessible from main navigation and footer
- Responsive design with clean layout

#### 2. Q&A Page (`/index.php?controller=qna&action=index`)
- Lists all active Q&A items
- Ordered by display order
- Clean, easy-to-read format
- Accessible from main navigation and footer

### Admin Management Features

#### 3. About Page Management (`/index.php?controller=adminAbout&action=edit`)
- Edit About page title and content
- Upload/replace About page image
- Track who last updated the content and when
- Accessible from admin sidebar

#### 4. Q&A Management (`/index.php?controller=adminQna&action=manage`)
- View all Q&A items (active and inactive)
- Add new Q&A items
- Edit existing Q&A items
- Delete Q&A items
- Set display order for questions
- Toggle active/inactive status
- Search functionality
- Pagination support
- Accessible from admin sidebar

## Files Created

### Models
- `models/AboutModel.php` - Handles About page data operations
- `models/QnaModel.php` - Handles Q&A data operations

### Controllers
- `controllers/AboutController.php` - User-facing About page controller
- `controllers/QnaController.php` - User-facing Q&A page controller
- `controllers/AdminAboutController.php` - Admin About page management
- `controllers/AdminQnaController.php` - Admin Q&A management (CRUD operations)

### Views - User Pages
- `views/pages/about.php` - About page display
- `views/pages/qna.php` - Q&A page display

### Views - Admin Pages
- `views/admin/pages/edit-about.php` - About page editor
- `views/admin/pages/manage-qna.php` - Q&A list with actions
- `views/admin/pages/add-qna.php` - Add new Q&A form
- `views/admin/pages/edit-qna.php` - Edit Q&A form

### Database
- `assets/config/mysql/add_about_qna_tables.sql` - SQL migration file

## Files Modified

### Navigation Updates
- `views/components/header.php` - Added About and Q&A links to main navigation
- `views/components/footer.php` - Updated footer links
- `views/admin/components/header.php` - Added About and Q&A to admin sidebar
- `index.php` - Added new admin controllers to allowed list

## Testing Checklist

### User Features
- [ ] Visit About page and verify content displays correctly
- [ ] Visit Q&A page and verify questions display correctly
- [ ] Check that navigation links work from header
- [ ] Check that navigation links work from footer
- [ ] Verify responsive design on mobile

### Admin Features - About Page
- [ ] Login as admin
- [ ] Navigate to About Page management
- [ ] Edit title and content
- [ ] Upload an image
- [ ] Verify changes appear on user-facing page
- [ ] Verify "Last Updated" information is correct

### Admin Features - Q&A
- [ ] Navigate to Q&A management
- [ ] Add a new Q&A item
- [ ] Edit an existing Q&A item
- [ ] Change display order
- [ ] Toggle active/inactive status
- [ ] Search for Q&A items
- [ ] Delete a Q&A item
- [ ] Verify changes appear on user-facing page

## Default Content

### About Page
The migration includes default content about ShoeStore with a welcome message, mission statement, and customer service information.

### Q&A Items
Six sample Q&A items are included covering:
1. Shipping options
2. Return policy
3. Size guide
4. Product warranty
5. Order tracking
6. Physical store locations

## Admin Credentials
Use the following credentials to test admin features:
- Username: `admin1`
- Password: `pass123`

## Notes
- All admin pages require authentication (admin role)
- Image uploads for About page are stored in `assets/images/about/`
- Q&A items can be set as inactive to hide them without deletion
- Display order allows control over question sequence
- The implementation follows the existing code patterns (similar to News management)
