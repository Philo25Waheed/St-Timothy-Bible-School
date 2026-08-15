<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ServantController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\AcademicController;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\VerseController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SpiritualJournalController;

// Public Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Guest Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('profile.password');

    // Central Dashboard Redirector
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Spiritual Journal & Prayer Requests (Student)
    Route::get('/journal', [SpiritualJournalController::class, 'index'])->name('journal.index');
    Route::post('/journal', [SpiritualJournalController::class, 'storeJournal'])->name('journal.store');
    Route::post('/prayer-requests', [SpiritualJournalController::class, 'storePrayer'])->name('prayers.store');

    // Parent Digest
    Route::get('/parent/weekly-digest', [ParentController::class, 'weeklyDigest'])->name('parent.weekly_digest');

    // Events RSVP & Gallery
    Route::get('/events/gallery', [EventController::class, 'gallery'])->name('events.gallery');
    Route::post('/events/{event}/register', [EventController::class, 'register'])->name('events.register');
    Route::post('/events/{event}/cancel-registration', [EventController::class, 'cancelRegistration'])->name('events.cancel_registration');

    // Lesson Reader for all authenticated users
    Route::get('/lessons/{lesson}', [CurriculumController::class, 'showLesson'])->name('lessons.show');
    Route::post('/lessons/{lesson}/complete', [CurriculumController::class, 'markCompleted'])->name('lessons.complete');

    // Quiz & Exam Taking for Students / Servants / Admin
    Route::get('/quizzes/{quiz}/take', [QuizController::class, 'take'])->name('quizzes.take');
    Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');
    Route::get('/quiz-attempts/{attempt}/result', [QuizController::class, 'result'])->name('quizzes.result');

    Route::get('/exams/{exam}/take', [ExamController::class, 'take'])->name('exams.take');
    Route::post('/exams/{exam}/submit', [ExamController::class, 'submit'])->name('exams.submit');
    Route::get('/exam-attempts/{attempt}/result', [ExamController::class, 'result'])->name('exams.result');

    // Notifications & Messages
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read_all');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');

    // News & Events Public Views
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/verses', [VerseController::class, 'index'])->name('verses.index');

    // Curriculum Browser for all authenticated users
    Route::get('/curriculum', [CurriculumController::class, 'index'])->name('curriculum.index');

    // Servant & Admin Routes
    Route::middleware('role:admin,servant')->group(function () {
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
        Route::get('/attendance/qr-scanner', [AttendanceController::class, 'qrScanner'])->name('attendance.qr_scanner');
        Route::post('/attendance/qr-scan', [AttendanceController::class, 'scanQrCode'])->name('attendance.qr_scan');

        Route::get('/servant/prayer-requests', [SpiritualJournalController::class, 'servantIndex'])->name('servant.prayers.index');
        Route::post('/servant/prayer-requests/{prayerRequest}', [SpiritualJournalController::class, 'updatePrayer'])->name('servant.prayers.update');

        Route::post('/events/photos', [EventController::class, 'storePhoto'])->name('events.photos.store');

        Route::post('/points', [PointController::class, 'store'])->name('points.store');
        Route::post('/achievements/award', [AchievementController::class, 'award'])->name('achievements.award');
        Route::post('/verses/progress', [VerseController::class, 'updateProgress'])->name('verses.progress');

        // Quiz Management
        Route::get('/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
        Route::get('/quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');
        Route::post('/quizzes', [QuizController::class, 'store'])->name('quizzes.store');
        Route::get('/quizzes/{quiz}/builder', [QuizController::class, 'edit'])->name('quizzes.edit');
        Route::post('/quizzes/{quiz}/questions', [QuizController::class, 'storeQuestion'])->name('quizzes.questions.store');
        Route::delete('/questions/{question}', [QuizController::class, 'destroyQuestion'])->name('questions.destroy');

        // Exam Builder
        Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');
        Route::get('/exams/create', [ExamController::class, 'create'])->name('exams.create');
        Route::post('/exams', [ExamController::class, 'store'])->name('exams.store');
        Route::get('/exams/{exam}/builder', [ExamController::class, 'edit'])->name('exams.edit');
        Route::post('/exams/{exam}/questions', [ExamController::class, 'storeQuestion'])->name('exams.questions.store');

        // Curriculum Units & Lessons Management (Admin & Servant)
        Route::post('/curriculum/{curriculum}/units', [CurriculumController::class, 'storeUnit'])->name('curriculum.units.store');
        Route::post('/units/{unit}/lessons', [CurriculumController::class, 'storeLesson'])->name('units.lessons.store');

        // News & Events creation
        Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
        Route::post('/news', [NewsController::class, 'store'])->name('news.store');
        Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
        Route::post('/verses', [VerseController::class, 'store'])->name('verses.store');
    });

    // Admin Only Routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('students', StudentController::class);
        Route::resource('servants', ServantController::class);
        Route::resource('parents', ParentController::class);

        // Academic Structure
        Route::get('/academic/years', [AcademicController::class, 'years'])->name('academic.years');
        Route::post('/academic/years', [AcademicController::class, 'storeYear'])->name('academic.years.store');
        Route::get('/academic/stages', [AcademicController::class, 'stages'])->name('academic.stages');
        Route::post('/academic/stages', [AcademicController::class, 'storeStage'])->name('academic.stages.store');
        Route::get('/academic/grades', [AcademicController::class, 'grades'])->name('academic.grades');
        Route::post('/academic/grades', [AcademicController::class, 'storeGrade'])->name('academic.grades.store');
        Route::get('/academic/classes', [AcademicController::class, 'classes'])->name('academic.classes');
        Route::post('/academic/classes', [AcademicController::class, 'storeClass'])->name('academic.classes.store');
        Route::post('/academic/classes/{class}', [AcademicController::class, 'updateClass'])->name('academic.classes.update');

        // Curriculum Creation & Modification (Admin Only)
        Route::get('/curriculum/create', [CurriculumController::class, 'create'])->name('curriculum.create');
        Route::post('/curriculum', [CurriculumController::class, 'store'])->name('curriculum.store');

        // Achievements
        Route::get('/achievements', [AchievementController::class, 'index'])->name('achievements.index');

        // Reports Module
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/student', [ReportController::class, 'studentReport'])->name('reports.student');
        Route::get('/reports/class', [ReportController::class, 'classReport'])->name('reports.class');
        Route::get('/reports/attendance', [ReportController::class, 'attendanceReport'])->name('reports.attendance');
        Route::get('/reports/exam', [ReportController::class, 'examReport'])->name('reports.exam');

        // Pending Account Registrations Approvals
        Route::get('/admin/pending-approvals', [\App\Http\Controllers\PendingApprovalController::class, 'index'])->name('admin.pending.index');
        Route::post('/admin/pending-approvals/{user}/approve', [\App\Http\Controllers\PendingApprovalController::class, 'approve'])->name('admin.pending.approve');
        Route::delete('/admin/pending-approvals/{user}/reject', [\App\Http\Controllers\PendingApprovalController::class, 'reject'])->name('admin.pending.reject');
    });

    // Specific curriculum details view (Must come AFTER /curriculum/create)
    Route::get('/curriculum/{curriculum}', [CurriculumController::class, 'show'])->whereNumber('curriculum')->name('curriculum.show');
});
