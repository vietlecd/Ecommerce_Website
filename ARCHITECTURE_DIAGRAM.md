# Architecture Diagram - About & Q&A Features

## System Architecture

```
┌─────────────────────────────────────────────────────────────────────┐
│                           USER INTERFACE                            │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ┌─────────────────┐              ┌─────────────────┐             │
│  │   About Page    │              │   Q&A Page      │             │
│  │  /about/index   │              │   /qna/index    │             │
│  └────────┬────────┘              └────────┬────────┘             │
│           │                                 │                       │
└───────────┼─────────────────────────────────┼───────────────────────┘
            │                                 │
            │                                 │
┌───────────┼─────────────────────────────────┼───────────────────────┐
│           │         CONTROLLERS             │                       │
├───────────┼─────────────────────────────────┼───────────────────────┤
│           │                                 │                       │
│  ┌────────▼───────────┐          ┌─────────▼──────────┐           │
│  │ AboutController    │          │  QnaController      │           │
│  │  - index()         │          │   - index()         │           │
│  └────────┬───────────┘          └─────────┬──────────┘           │
│           │                                 │                       │
│           │                                 │                       │
│  ┌────────▼───────────┐          ┌─────────▼──────────┐           │
│  │AdminAboutController│          │AdminQnaController   │           │
│  │  - edit()          │          │  - manage()         │           │
│  │  - upload image    │          │  - add()            │           │
│  └────────┬───────────┘          │  - edit()           │           │
│           │                      │  - delete()         │           │
│           │                      └─────────┬──────────┘           │
└───────────┼──────────────────────────────────┼───────────────────────┘
            │                                 │
            │                                 │
┌───────────┼─────────────────────────────────┼───────────────────────┐
│           │          MODELS                 │                       │
├───────────┼─────────────────────────────────┼───────────────────────┤
│           │                                 │                       │
│  ┌────────▼───────────┐          ┌─────────▼──────────┐           │
│  │   AboutModel       │          │   QnaModel          │           │
│  │                    │          │                     │           │
│  │ - getAboutContent()│          │ - getAllActiveQna() │           │
│  │ - updateAbout      │          │ - getAllQna()       │           │
│  │   Content()        │          │ - getQnaCount()     │           │
│  │                    │          │ - getQnaById()      │           │
│  └────────┬───────────┘          │ - addQna()          │           │
│           │                      │ - updateQna()       │           │
│           │                      │ - deleteQna()       │           │
│           │                      └─────────┬──────────┘           │
└───────────┼──────────────────────────────────┼───────────────────────┘
            │                                 │
            │                                 │
┌───────────┼─────────────────────────────────┼───────────────────────┐
│           │         DATABASE                │                       │
├───────────┼─────────────────────────────────┼───────────────────────┤
│           │                                 │                       │
│  ┌────────▼───────────┐          ┌─────────▼──────────┐           │
│  │   about table      │          │   qna table         │           │
│  │                    │          │                     │           │
│  │ - AboutID (PK)     │          │ - QnaID (PK)        │           │
│  │ - Title            │          │ - Question          │           │
│  │ - Content          │          │ - Answer            │           │
│  │ - Image            │          │ - DisplayOrder      │           │
│  │ - LastUpdated      │          │ - IsActive          │           │
│  │ - UpdatedBy (FK)   │          │ - DateCreated       │           │
│  │                    │          │ - LastUpdated       │           │
│  │ → admin (FK)       │          │ - CreatedBy (FK)    │           │
│  └────────────────────┘          │                     │           │
│                                  │ → admin (FK)        │           │
│                                  └─────────────────────┘           │
└─────────────────────────────────────────────────────────────────────┘
```

## Request Flow

### User Viewing About Page
```
User Browser
    │
    ├─→ GET /index.php?controller=about&action=index
    │
    ├─→ AboutController::index()
    │       │
    │       ├─→ AboutModel::getAboutContent()
    │       │       │
    │       │       └─→ Database Query: SELECT FROM about
    │       │
    │       └─→ views/components/header.php
    │       └─→ views/pages/about.php (renders data)
    │       └─→ views/components/footer.php
    │
    └─→ HTML Response
```

### Admin Editing About Page
```
Admin Browser
    │
    ├─→ GET /index.php?controller=adminAbout&action=edit
    │
    ├─→ AdminAboutController::edit()
    │       │
    │       ├─→ Check admin authentication
    │       │
    │       ├─→ AboutModel::getAboutContent()
    │       │
    │       └─→ views/admin/pages/edit-about.php
    │
    └─→ HTML Form Response
    
    ┌─→ POST /index.php?controller=adminAbout&action=edit
    │   (with form data: title, content, image)
    │
    ├─→ AdminAboutController::edit()
    │       │
    │       ├─→ Validate input
    │       ├─→ Handle image upload
    │       ├─→ AboutModel::updateAboutContent()
    │       │       │
    │       │       └─→ Database: UPDATE about SET ...
    │       │
    │       └─→ Success/Error message
    │
    └─→ Redirect or re-render with message
```

### User Viewing Q&A Page
```
User Browser
    │
    ├─→ GET /index.php?controller=qna&action=index
    │
    ├─→ QnaController::index()
    │       │
    │       ├─→ QnaModel::getAllActiveQna()
    │       │       │
    │       │       └─→ Database: SELECT FROM qna WHERE IsActive=1
    │       │
    │       └─→ views/components/header.php
    │       └─→ views/pages/qna.php (renders Q&A list)
    │       └─→ views/components/footer.php
    │
    └─→ HTML Response
```

### Admin Managing Q&A
```
Admin Browser
    │
    ├─→ GET /index.php?controller=adminQna&action=manage
    │
    ├─→ AdminQnaController::manage()
    │       │
    │       ├─→ Check admin authentication
    │       │
    │       ├─→ QnaModel::getAllQna($search, $limit, $offset)
    │       ├─→ QnaModel::getQnaCount()
    │       │
    │       └─→ views/admin/pages/manage-qna.php
    │
    └─→ HTML Table with Q&A list

    ┌─→ GET /index.php?controller=adminQna&action=add
    │
    ├─→ AdminQnaController::add()
    │       │
    │       └─→ views/admin/pages/add-qna.php
    │
    └─→ HTML Form

    ┌─→ POST /index.php?controller=adminQna&action=add
    │
    ├─→ AdminQnaController::add()
    │       │
    │       ├─→ Validate input
    │       ├─→ QnaModel::addQna()
    │       │       │
    │       │       └─→ Database: INSERT INTO qna
    │       │
    │       └─→ Success message
    │
    └─→ Re-render form with success message

    ┌─→ GET /index.php?controller=adminQna&action=edit&id=X
    │
    ├─→ AdminQnaController::edit()
    │       │
    │       ├─→ QnaModel::getQnaById($id)
    │       │
    │       └─→ views/admin/pages/edit-qna.php
    │
    └─→ HTML Form (pre-filled)

    ┌─→ POST /index.php?controller=adminQna&action=edit&id=X
    │
    ├─→ AdminQnaController::edit()
    │       │
    │       ├─→ Validate input
    │       ├─→ QnaModel::updateQna($id, ...)
    │       │       │
    │       │       └─→ Database: UPDATE qna SET ...
    │       │
    │       └─→ Success message
    │
    └─→ Re-render form with success

    ┌─→ GET /index.php?controller=adminQna&action=delete&id=X
    │
    ├─→ AdminQnaController::delete()
    │       │
    │       ├─→ Confirm delete
    │       ├─→ QnaModel::deleteQna($id)
    │       │       │
    │       │       └─→ Database: DELETE FROM qna WHERE ...
    │       │
    │       └─→ Success message
    │
    └─→ Redirect to manage page
```

## Component Interaction

```
┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐
│  Route   │────▶│Controller│────▶│  Model   │────▶│ Database │
│(index.php)│     │          │     │          │     │          │
└──────────┘     └────┬─────┘     └──────────┘     └──────────┘
                      │
                      ├─→ Authentication Check (admin pages)
                      ├─→ Input Validation
                      ├─→ Business Logic
                      │
                      ▼
                 ┌──────────┐
                 │   View   │────▶ HTML Response
                 │(Template)│
                 └──────────┘
```

## Navigation Flow

```
Main Navigation
    │
    ├─→ Home
    ├─→ Products
    ├─→ News
    ├─→ About ──────────┐
    ├─→ Q&A ────────┐   │
    └─→ Cart        │   │
                    │   │
                    │   └─→ /about/index (Public)
                    │
                    └─→ /qna/index (Public)

Admin Sidebar
    │
    ├─→ Dashboard
    ├─→ Products
    ├─→ Orders
    ├─→ Customers
    ├─→ News
    ├─→ About Page ──────┐
    └─→ Q&A ─────────┐   │
                     │   │
                     │   └─→ /adminAbout/edit (Admin Only)
                     │
                     └─→ /adminQna/manage (Admin Only)
                          │
                          ├─→ Add New Q&A
                          ├─→ Edit Q&A
                          └─→ Delete Q&A
```

## File Organization

```
Ecommerce_Website/
│
├── controllers/
│   ├── AboutController.php         (User-facing)
│   ├── QnaController.php           (User-facing)
│   ├── AdminAboutController.php    (Admin only)
│   └── AdminQnaController.php      (Admin only)
│
├── models/
│   ├── AboutModel.php              (Database operations)
│   └── QnaModel.php                (Database operations)
│
├── views/
│   ├── pages/
│   │   ├── about.php               (Public view)
│   │   └── qna.php                 (Public view)
│   │
│   ├── admin/pages/
│   │   ├── edit-about.php          (Admin view)
│   │   ├── manage-qna.php          (Admin view)
│   │   ├── add-qna.php             (Admin view)
│   │   └── edit-qna.php            (Admin view)
│   │
│   ├── components/
│   │   ├── header.php              (Updated)
│   │   └── footer.php              (Updated)
│   │
│   └── admin/components/
│       └── header.php              (Updated)
│
├── assets/
│   ├── config/mysql/
│   │   └── add_about_qna_tables.sql (Migration)
│   │
│   └── images/
│       └── about/                   (Image uploads)
│
└── index.php                        (Updated routing)
```
