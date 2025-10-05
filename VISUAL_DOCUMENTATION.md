# Visual Documentation - About & Q&A Features

## User Interface Pages

### 1. About Page
**URL:** `/index.php?controller=about&action=index`

**Features:**
- Displays customizable title and content
- Optional image display
- Shows last update information
- Responsive design with centered layout
- Clean, professional styling with white card background

**Layout:**
```
┌─────────────────────────────────────────────┐
│  Navigation: Home | Products | News | About | Q&A | Cart
└─────────────────────────────────────────────┘

     ┌───────────────────────────────────────┐
     │     [Page Title - Large, Centered]    │
     ├───────────────────────────────────────┤
     │                                       │
     │  [Optional Image - Centered]          │
     │                                       │
     │  Content text with proper spacing...  │
     │  Multiple paragraphs supported...     │
     │                                       │
     │  Last updated: [Date] by [Admin Name] │
     └───────────────────────────────────────┘

┌─────────────────────────────────────────────┐
│  Footer: About Us | Q&A | Contact           │
└─────────────────────────────────────────────┘
```

### 2. Q&A Page
**URL:** `/index.php?controller=qna&action=index`

**Features:**
- Displays all active Q&A items
- Question icon for visual appeal
- Clear question and answer separation
- Ordered by display order
- Empty state message if no Q&A items

**Layout:**
```
┌─────────────────────────────────────────────┐
│  Navigation: Home | Products | News | About | Q&A | Cart
└─────────────────────────────────────────────┘

     ┌───────────────────────────────────────┐
     │  Frequently Asked Questions           │
     ├───────────────────────────────────────┤
     │                                       │
     │  🔵 What are your shipping options?   │
     │     We offer free standard shipping... │
     │                                       │
     ├───────────────────────────────────────┤
     │  🔵 What is your return policy?       │
     │     We accept returns within 30 days...│
     │                                       │
     ├───────────────────────────────────────┤
     │  🔵 How do I know my shoe size?       │
     │     We provide a detailed size guide...│
     │                                       │
     └───────────────────────────────────────┘

┌─────────────────────────────────────────────┐
│  Footer: About Us | Q&A | Contact           │
└─────────────────────────────────────────────┘
```

## Admin Management Pages

### 3. Edit About Page
**URL:** `/index.php?controller=adminAbout&action=edit`

**Features:**
- Edit page title
- Edit content (multiline textarea)
- Upload/replace image
- Preview current image
- Success/error messages
- Link to view public page

**Layout:**
```
┌─────────────────────────────────────────────┐
│  Admin Sidebar: Dashboard | Products |      │
│                Orders | Customers | News |  │
│                About Page | Q&A             │
└─────────────────────────────────────────────┘

  Edit About Page Content        [View Page]

  [Success/Error Message if any]

  Page Title:
  ┌─────────────────────────────────────────┐
  │ About ShoeStore                         │
  └─────────────────────────────────────────┘

  Content:
  ┌─────────────────────────────────────────┐
  │ Welcome to ShoeStore...                 │
  │                                         │
  │ (Large text area)                       │
  │                                         │
  └─────────────────────────────────────────┘

  Image (optional):
  [Current image preview if exists]
  [Choose File] No file chosen

  [Update Content]
```

### 4. Manage Q&A (List)
**URL:** `/index.php?controller=adminQna&action=manage`

**Features:**
- View all Q&A items (active and inactive)
- Search functionality
- Add new Q&A button
- Edit/Delete actions
- Display order column
- Status badges (Active/Inactive)
- Pagination
- Success/error messages

**Layout:**
```
┌─────────────────────────────────────────────┐
│  Admin Sidebar: Dashboard | Products |      │
│                Orders | Customers | News |  │
│                About Page | Q&A             │
└─────────────────────────────────────────────┘

  Manage Q&A                    [Add New Q&A]

  [Success/Error Message if any]

  Search: ┌───────────────────┐ [Search]
          │                   │
          └───────────────────┘

  ┌───┬───────────┬──────────┬─────┬────────┬─────────┬─────────┐
  │ID │ Question  │ Answer   │Order│ Status │ Created │ Actions │
  ├───┼───────────┼──────────┼─────┼────────┼─────────┼─────────┤
  │ 1 │ What are  │ We offer │  1  │[Active]│ Admin 1 │Edit|Del │
  │ 2 │ What is   │ We accept│  2  │[Active]│ Admin 1 │Edit|Del │
  │ 3 │ How do I  │ We provid│  3  │[Active]│ Admin 1 │Edit|Del │
  └───┴───────────┴──────────┴─────┴────────┴─────────┴─────────┘

  [< Previous] [1] [2] [3] [Next >]
```

### 5. Add Q&A
**URL:** `/index.php?controller=adminQna&action=add`

**Features:**
- Question input field
- Answer textarea
- Display order input
- Active checkbox
- Success message stays on same page
- Back to list button

**Layout:**
```
┌─────────────────────────────────────────────┐
│  Admin Sidebar: Dashboard | Products |      │
│                Orders | Customers | News |  │
│                About Page | Q&A             │
└─────────────────────────────────────────────┘

  Add New Q&A                [Back to List]

  [Success/Error Message if any]

  Question: *
  ┌─────────────────────────────────────────┐
  │ Enter the question                      │
  └─────────────────────────────────────────┘

  Answer: *
  ┌─────────────────────────────────────────┐
  │ Enter the answer                        │
  │                                         │
  │ (Large text area)                       │
  │                                         │
  └─────────────────────────────────────────┘

  Display Order:
  ┌─────┐  Lower numbers appear first
  │  0  │
  └─────┘

  ☑ Active (Display on Q&A page)

  [Add Q&A]
```

### 6. Edit Q&A
**URL:** `/index.php?controller=adminQna&action=edit&id=X`

**Features:**
- Pre-filled form with existing data
- Same fields as Add Q&A
- Update button instead of Add
- Back to list button

**Layout:**
```
┌─────────────────────────────────────────────┐
│  Admin Sidebar: Dashboard | Products |      │
│                Orders | Customers | News |  │
│                About Page | Q&A             │
└─────────────────────────────────────────────┘

  Edit Q&A                   [Back to List]

  [Success/Error Message if any]

  Question: *
  ┌─────────────────────────────────────────┐
  │ What are your shipping options?         │
  └─────────────────────────────────────────┘

  Answer: *
  ┌─────────────────────────────────────────┐
  │ We offer free standard shipping on...   │
  │                                         │
  │ (Large text area with existing content) │
  │                                         │
  └─────────────────────────────────────────┘

  Display Order:
  ┌─────┐  Lower numbers appear first
  │  1  │
  └─────┘

  ☑ Active (Display on Q&A page)

  [Update Q&A]
```

## Navigation Updates

### Main Header (User-facing)
**Before:**
- Home | Products | News | Cart

**After:**
- Home | Products | News | **About** | **Q&A** | Cart

### Footer (User-facing)
**Before:**
- About Us (dead link)

**After:**
- About Us (links to `/index.php?controller=about&action=index`)
- **Q&A** (links to `/index.php?controller=qna&action=index`)

### Admin Sidebar
**Before:**
- Dashboard
- Products
- Orders
- Customers
- News

**After:**
- Dashboard
- Products
- Orders
- Customers
- News
- **About Page** (links to edit page)
- **Q&A** (links to manage page)

## Color Scheme

### User Pages
- Background: Light gray (#f5f5f5)
- Content cards: White (#fff)
- Text: Dark gray (#333 for headings, #555 for body)
- Question icon: Blue (#3498db)
- Shadows: Subtle (0 2px 10px rgba(0,0,0,0.1))

### Admin Pages
- Primary button: Blue (#007bff)
- Button hover: Darker blue (#0056b3)
- Success messages: Green background (#d4edda)
- Error messages: Red background (#f8d7da)
- Active badge: Green (#d4edda with #155724 text)
- Inactive badge: Red (#f8d7da with #721c24 text)
- Table hover: Light gray (#f5f5f5)

## Responsive Design
- Maximum width: 900px for content
- Center-aligned content
- Padding adjusts for mobile devices
- Tables scroll horizontally on small screens
