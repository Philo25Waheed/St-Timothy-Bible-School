# Walkthrough — Bible School Management & E-Learning Platform ("مدرسة الكتاب المقدس")

We have successfully built and verified the complete **Bible School Management & E-Learning Platform ("مدرسة الكتاب المقدس")** in Laravel.

## A. Final Project Structure

```
d:\bible school
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AcademicController.php
│   │   │   ├── AchievementController.php
│   │   │   ├── AttendanceController.php
│   │   │   ├── AuthController.php
│   │   │   ├── CurriculumController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── EventController.php
│   │   │   ├── ExamController.php
│   │   │   ├── LandingController.php
│   │   │   ├── MessageController.php
│   │   │   ├── NewsController.php
│   │   │   ├── NotificationController.php
│   │   │   ├── ParentController.php
│   │   │   ├── PointController.php
│   │   │   ├── QuizController.php
│   │   │   ├── ReportController.php
│   │   │   ├── ServantController.php
│   │   │   ├── StudentController.php
│   │   │   └── VerseController.php
│   │   └── Middleware/
│   │       └── CheckRole.php
│   └── Models/ (25 Eloquent Models)
├── database/
│   ├── migrations/ (7 schema migrations)
│   ├── seeders/
│   │   └── DatabaseSeeder.php
│   └── database.sqlite
├── public/
│   ├── css/
│   │   └── app.css (Arabic/RTL EdTech custom design system)
│   └── storage/
├── resources/
│   └── views/
│       ├── admin/ (students, servants, parents, academic)
│       ├── auth/ (login, profile)
│       ├── curriculum/ (index, show, lesson_view)
│       ├── dashboards/ (admin, servant, student, parent)
│       ├── errors/ (404, 403, 500, 419)
│       ├── events/
│       ├── exams/ (index, create, builder, take, result)
│       ├── layouts/ (app.blade.php, public.blade.php)
│       ├── messages/
│       ├── news/
│       ├── notifications/
│       ├── quizzes/ (index, create, builder, take, result)
│       ├── reports/ (index, student, class, attendance, exam)
│       ├── verses/
│       └── landing.blade.php
├── routes/
│   └── web.php (90 registered routes)
└── tests/
    └── Feature/
        └── BibleSchoolTest.php (9 passing tests)
```

---

## B. Database Schema & Architecture

The application defines 25 Eloquent models connected with full foreign key constraints, indexes, and cascades:
- `users`: `role` (`admin`, `servant`, `student`, `parent`), `phone`, `gender`, `avatar`, `is_active`.
- `academic_years`, `stages`, `grades`, `classes`: Academic structural hierarchy.
- `students`: Linked to `user_id`, `stage_id`, `grade_id`, `class_id`, `parent_id`, `servant_id`, `code`.
- `curricula`, `units`, `lessons`, `lesson_progress`: E-Learning structure with video links, PDF downloads, and memory verses.
- `quizzes`, `exams`, `questions`, `quiz_attempts`, `exam_attempts`: Auto-graded assessment system.
- `attendance_records`: Daily class attendance tracking (`present`, `absent`, `late`, `excused`).
- `student_points`, `achievements`, `student_achievements`: Gamified points log & badge collection.
- `bible_verses`, `student_verse_progress`: Reciting tracker.
- `news`, `events`, `messages`, `notifications`: Communication center & calendar.

---

## C. Roles & Permissions (RBAC)

| Role | Access Scope | Key Capabilities |
| :--- | :--- | :--- |
| **Admin** (`مسؤول النظام`) | Full System | Dashboard, Student/Servant/Parent CRUD, Academic Structure, Curricula, Exam Builder, Reports |
| **Servant** (`خادم الفصل`) | Assigned Classes | Servant Dashboard, Daily Attendance Logger, Points Awarder, Quiz Creator, Verse Checker, Parent Messaging |
| **Student** (`طالب`) | Personal Learning | Student Dashboard, Lesson Reader, Completed Lessons, Quiz/Exam Taking, Score View, Badges, Streak Tracker |
| **Parent** (`ولي أمر`) | Linked Children | Multi-Child Switcher Dashboard, Child Attendance Calendar, Grades, Points, Verse Recitation Progress, Servant Messaging |

---

## D. Routes Overview (90 Registered Routes)

Key route endpoints:
- Public Landing: `GET /`
- Auth: `GET /login`, `POST /login`, `POST /logout`, `GET /profile`, `POST /profile`, `POST /change-password`
- Dashboards: `GET /dashboard` (Redirects dynamically based on user role)
- Academic Management: `GET /academic/years`, `/stages`, `/grades`, `/classes`
- Student Management: `RESOURCE /students` (index, create, store, show, edit, update, destroy)
- Servant & Parent Management: `RESOURCE /servants`, `RESOURCE /parents`
- E-Learning: `GET /curriculum`, `GET /lessons/{lesson}`, `POST /lessons/{lesson}/complete`
- Quiz & Exam Builder: `GET /quizzes`, `POST /quizzes`, `GET /quizzes/{quiz}/take`, `POST /quizzes/{quiz}/submit`
- Attendance: `GET /attendance`, `POST /attendance`
- Messaging & Notifications: `GET /messages`, `POST /messages`, `GET /notifications`
- Reports: `GET /reports`, `/reports/student`, `/reports/class`, `/reports/attendance`, `/reports/exam`

---

## E. Demo Credentials for Testing

All accounts are pre-populated via `php artisan db:seed`. Quick 1-click auto-fill buttons are available on the Login page (`/login`):

| Role | Email | Password | Details |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@bibleschool.com` | `password` | د. يوسف صبحي |
| **Servant** | `servant@bibleschool.com` | `password` | أ. مينا سامي (خادم فصل القديس مارمرقس) |
| **Student** | `student@bibleschool.com` | `password` | الطالب مارك مجدي (الصف السادس الابتدائي) |
| **Parent** | `parent@bibleschool.com` | `password` | م. مجدي عادل (ولي أمر مارك ومارينا) |

---

## F. Automated Test Results

Executed `php artisan test --filter=BibleSchoolTest`:

```bash
PASS  Tests\Feature\BibleSchoolTest
  ✓ landing page is accessible
  ✓ login page is accessible
  ✓ admin can login and access dashboard
  ✓ servant can access dashboard
  ✓ student can access dashboard
  ✓ parent can access dashboard
  ✓ student management page accessible for admin
  ✓ curriculum page accessible
  ✓ reports page accessible

Tests:    9 passed (13 assertions)
Duration: 0.98s
```

---

## G. Installation & Execution Commands

To run the application locally:

```bash
# 1. Install Composer dependencies
composer install

# 2. Run Database Migrations & Seeders
php artisan migrate:fresh --seed

# 3. Create Storage Symbolic Link
php artisan storage:link

# 4. Start Local Development Server
php artisan serve
```

Access the application in your browser at `http://localhost:8000`.
