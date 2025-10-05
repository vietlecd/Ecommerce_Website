# Testing Guide - About & Q&A Features

## Prerequisites

1. **Database Setup**
   ```bash
   # Apply the SQL migration
   docker-compose exec mysql mysql -u shoes_user -pshoes_pass shoe < assets/config/mysql/add_about_qna_tables.sql
   ```

2. **Start Application**
   ```bash
   # Start Docker containers
   docker-compose up -d
   
   # Access application at: http://localhost:8080
   ```

3. **Admin Credentials**
   - Username: `admin1`
   - Password: `pass123`

## Quick Validation

Run the automated validation script:
```bash
./test_implementation.sh
```

Expected output: All checks should pass with ✓ symbols.

## Manual Testing Checklist

### 1. User-Facing: About Page

#### Test: View About Page
- [ ] Navigate to http://localhost:8080/index.php?controller=about&action=index
- [ ] Verify page title displays: "About ShoeStore"
- [ ] Verify content is readable and properly formatted
- [ ] Verify page layout is centered and clean
- [ ] Check footer shows "Last updated" information
- [ ] Test on mobile (responsive design)

**Expected Result:**
- Page displays default About content
- Clean, professional layout
- No errors in browser console

#### Test: Navigation Links
- [ ] Click "About" in main navigation header
- [ ] Click "About Us" in footer links
- [ ] Both should lead to the About page

**Expected Result:**
- Both links work correctly
- Page loads without errors

---

### 2. User-Facing: Q&A Page

#### Test: View Q&A Page
- [ ] Navigate to http://localhost:8080/index.php?controller=qna&action=index
- [ ] Verify page title: "Frequently Asked Questions"
- [ ] Verify 6 default questions are displayed
- [ ] Check question icons appear (blue circles)
- [ ] Verify answers are properly formatted
- [ ] Test on mobile (responsive design)

**Expected Result:**
- All questions and answers display correctly
- Questions in proper order (1-6)
- Clean FAQ layout

#### Test: Navigation Links
- [ ] Click "Q&A" in main navigation header
- [ ] Click "Q&A" in footer links
- [ ] Both should lead to the Q&A page

**Expected Result:**
- Both links work correctly
- Page loads without errors

---

### 3. Admin: About Page Management

#### Test: Access Admin Page
- [ ] Login as admin (admin1 / pass123)
- [ ] Click "About Page" in admin sidebar
- [ ] Verify redirected to edit page

**Expected Result:**
- Page loads: http://localhost:8080/index.php?controller=adminAbout&action=edit
- Form shows current About content
- No authentication errors

#### Test: Edit Title and Content
- [ ] Change the title to: "About Our Store"
- [ ] Modify the content text
- [ ] Click "Update Content"
- [ ] Verify success message appears
- [ ] Click "View Page" button
- [ ] Verify changes appear on public page

**Expected Result:**
- Success message: "About page content updated successfully!"
- Changes visible on public page
- "Last Updated" information updated

#### Test: Image Upload
- [ ] Click "Choose File" and select an image (JPG, PNG)
- [ ] Click "Update Content"
- [ ] Verify success message
- [ ] Verify image preview appears in form
- [ ] Check public page shows the new image

**Expected Result:**
- Image uploads successfully
- Image stored in `assets/images/about/`
- Image displays on public page
- Old image replaced (if existed)

#### Test: Validation
- [ ] Clear the title field
- [ ] Try to submit
- [ ] Verify error message appears

**Expected Result:**
- Error: "Title and content are required."
- Form not submitted

---

### 4. Admin: Q&A Management (List)

#### Test: Access Q&A Management
- [ ] Login as admin
- [ ] Click "Q&A" in admin sidebar
- [ ] Verify manage page loads

**Expected Result:**
- Page loads: http://localhost:8080/index.php?controller=adminQna&action=manage
- Table shows all Q&A items (6 default)
- Each row shows: ID, Question, Answer preview, Order, Status, Created By

#### Test: Search Functionality
- [ ] Enter "shipping" in search box
- [ ] Click "Search"
- [ ] Verify only shipping-related Q&A appears
- [ ] Clear search and verify all items return

**Expected Result:**
- Search filters results correctly
- Shows matching questions/answers
- Clear search shows all items

#### Test: Status Badges
- [ ] Verify all default items show "Active" badge in green
- [ ] Check that badge colors match status

**Expected Result:**
- Active items: Green badge
- Inactive items: Red badge (when testing with inactive items)

---

### 5. Admin: Add New Q&A

#### Test: Access Add Form
- [ ] Click "Add New Q&A" button
- [ ] Verify form loads

**Expected Result:**
- Page loads: http://localhost:8080/index.php?controller=adminQna&action=add
- Empty form with all fields

#### Test: Add Q&A Item
- [ ] Enter question: "Do you ship internationally?"
- [ ] Enter answer: "Yes, we ship to over 50 countries worldwide."
- [ ] Set display order: 7
- [ ] Keep "Active" checkbox checked
- [ ] Click "Add Q&A"

**Expected Result:**
- Success message: "Q&A added successfully!"
- Form ready for another entry
- New item appears in manage list

#### Test: Validation
- [ ] Leave question field empty
- [ ] Enter answer only
- [ ] Try to submit
- [ ] Verify error message

**Expected Result:**
- Error: "Question and answer are required."
- Form not submitted

---

### 6. Admin: Edit Q&A

#### Test: Access Edit Form
- [ ] Go to Q&A management page
- [ ] Click "Edit" on any Q&A item
- [ ] Verify form loads with existing data

**Expected Result:**
- Page loads: http://localhost:8080/index.php?controller=adminQna&action=edit&id=X
- Form pre-filled with current question and answer
- Display order and active status correct

#### Test: Update Q&A
- [ ] Modify the question text
- [ ] Update the answer
- [ ] Change display order
- [ ] Click "Update Q&A"
- [ ] Verify success message

**Expected Result:**
- Success message: "Q&A updated successfully!"
- Form shows updated data
- Changes appear in manage list

#### Test: Toggle Active Status
- [ ] Uncheck "Active" checkbox
- [ ] Click "Update Q&A"
- [ ] Go to public Q&A page
- [ ] Verify item no longer appears

**Expected Result:**
- Item hidden from public page
- Still visible in admin manage list
- Status badge shows "Inactive"

#### Test: Validation
- [ ] Clear question field
- [ ] Try to submit
- [ ] Verify error message

**Expected Result:**
- Error: "Question and answer are required."
- Form not submitted

---

### 7. Admin: Delete Q&A

#### Test: Delete Confirmation
- [ ] Go to Q&A management page
- [ ] Click "Delete" on any Q&A item
- [ ] Verify browser confirmation dialog appears
- [ ] Click "Cancel"
- [ ] Verify item still exists

**Expected Result:**
- Confirmation dialog: "Are you sure you want to delete this Q&A?"
- Item not deleted when cancelled

#### Test: Delete Q&A
- [ ] Click "Delete" again
- [ ] Click "OK" in confirmation
- [ ] Verify redirect to manage page
- [ ] Check success message

**Expected Result:**
- Success message: "Q&A deleted successfully!"
- Item removed from list
- Item no longer on public page

---

### 8. Display Order Testing

#### Test: Reorder Questions
- [ ] Edit Q&A #1, change order to 10
- [ ] Edit Q&A #6, change order to 1
- [ ] View public Q&A page
- [ ] Verify questions reordered

**Expected Result:**
- Questions display in new order
- Lower numbers appear first
- Changes immediate on public page

---

### 9. Pagination Testing

#### Test: Add Many Q&A Items
- [ ] Add 15+ Q&A items (if not already present)
- [ ] Go to Q&A management page
- [ ] Verify pagination appears

**Expected Result:**
- Shows 10 items per page
- Pagination controls at bottom
- Can navigate between pages

#### Test: Pagination Navigation
- [ ] Click page "2"
- [ ] Verify next set of items loads
- [ ] Click "Previous"
- [ ] Verify returns to page 1

**Expected Result:**
- Pagination works correctly
- Items load properly on each page
- Current page highlighted

---

### 10. Image Upload Advanced Testing

#### Test: Large Image
- [ ] Go to About page edit
- [ ] Upload a large image (5MB+)
- [ ] Verify upload succeeds or shows appropriate error

**Expected Result:**
- Large images handled appropriately
- Error message if too large

#### Test: Invalid File Type
- [ ] Try uploading a PDF or other non-image file
- [ ] Verify appropriate handling

**Expected Result:**
- Error or rejection of non-image files
- Clear error message

#### Test: Replace Image
- [ ] Upload image A
- [ ] Upload image B (replacement)
- [ ] Verify old image deleted from server

**Expected Result:**
- Old image file removed from `assets/images/about/`
- New image displayed
- No orphaned files

---

### 11. Security Testing

#### Test: Unauthorized Access
- [ ] Logout or use incognito window
- [ ] Try to access: http://localhost:8080/index.php?controller=adminAbout&action=edit
- [ ] Verify redirect to login

**Expected Result:**
- Redirected to login page
- Cannot access admin features
- No data exposure

#### Test: Non-Admin User
- [ ] Login as regular member (user1 / userpass)
- [ ] Try to access admin pages via URL
- [ ] Verify access denied

**Expected Result:**
- Access denied
- Redirect to login or error page

---

### 12. Cross-Browser Testing

Test on multiple browsers:
- [ ] Chrome/Chromium
- [ ] Firefox
- [ ] Safari (if available)
- [ ] Edge

**Expected Result:**
- All features work consistently
- Layout renders correctly
- No browser-specific issues

---

### 13. Mobile Responsiveness

#### Test: Mobile View
- [ ] Resize browser to mobile width (375px)
- [ ] Test About page on mobile
- [ ] Test Q&A page on mobile
- [ ] Test admin pages on mobile

**Expected Result:**
- Content readable and properly sized
- Navigation accessible
- Forms usable on small screens
- No horizontal scrolling

---

### 14. Error Handling

#### Test: Database Errors
- [ ] Temporarily break database connection
- [ ] Try to access pages
- [ ] Verify graceful error handling

**Expected Result:**
- User-friendly error messages
- No sensitive information exposed
- Application doesn't crash

---

## Performance Testing

### Load Time
- [ ] Measure page load time for About page
- [ ] Measure page load time for Q&A page
- [ ] Verify under 2 seconds

### Database Queries
- [ ] Check number of queries per page
- [ ] Verify efficient query execution

---

## Acceptance Criteria

### ✅ All Tests Should Pass
- User can view About page
- User can view Q&A page
- Admin can edit About content
- Admin can upload About images
- Admin can view all Q&A items
- Admin can add new Q&A
- Admin can edit Q&A
- Admin can delete Q&A
- Admin can search Q&A
- Admin can reorder Q&A
- Navigation links work correctly
- Authentication enforced
- Responsive design works

---

## Bug Reporting Template

If you find issues, report them with:

```
**Bug Title:** [Short description]

**Steps to Reproduce:**
1. [First step]
2. [Second step]
3. [...]

**Expected Result:**
[What should happen]

**Actual Result:**
[What actually happened]

**Screenshots:**
[Attach if applicable]

**Environment:**
- Browser: [Chrome/Firefox/etc]
- Device: [Desktop/Mobile]
- Screen size: [1920x1080/etc]
```

---

## Test Results Log

Create a test results log:

```
Date: [YYYY-MM-DD]
Tester: [Name]

| Test Case | Status | Notes |
|-----------|--------|-------|
| View About Page | PASS | - |
| Edit About Content | PASS | - |
| Add Q&A | PASS | - |
| ... | ... | ... |

Overall Status: [PASS/FAIL]
Issues Found: [Number]
```

---

## Automated Testing (Future Enhancement)

Consider adding:
- Unit tests for models
- Integration tests for controllers
- End-to-end tests with Selenium
- API tests if REST endpoints added

---

## Support

For issues or questions:
1. Check IMPLEMENTATION_SUMMARY.md
2. Review VISUAL_DOCUMENTATION.md
3. Check logs/errors.log
4. Contact development team
