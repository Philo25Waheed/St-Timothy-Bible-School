<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AcademicYear;
use App\Models\Stage;
use App\Models\Grade;
use App\Models\SchoolClass;
use App\Models\StudentProfile;
use App\Models\Curriculum;
use App\Models\Unit;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\AttendanceRecord;
use App\Models\StudentPoint;
use App\Models\Achievement;
use App\Models\StudentAchievement;
use App\Models\BibleVerse;
use App\Models\StudentVerseProgress;
use App\Models\News;
use App\Models\Event;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Core Users
        $admin = User::create([
            'name' => 'د. يوسف صبحي',
            'email' => 'admin@bibleschool.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '01223456789',
            'gender' => 'male',
            'is_active' => true,
        ]);

        $servant1 = User::create([
            'name' => 'أ. مينا سامي',
            'email' => 'servant@bibleschool.com',
            'password' => Hash::make('password'),
            'role' => 'servant',
            'phone' => '01223456788',
            'gender' => 'male',
            'is_active' => true,
        ]);

        $servant2 = User::create([
            'name' => 'تاسوني مريم كميل',
            'email' => 'servant2@bibleschool.com',
            'password' => Hash::make('password'),
            'role' => 'servant',
            'phone' => '01223456787',
            'gender' => 'female',
            'is_active' => true,
        ]);

        $parent1 = User::create([
            'name' => 'م. مجدي عادل',
            'email' => 'parent@bibleschool.com',
            'password' => Hash::make('password'),
            'role' => 'parent',
            'phone' => '01223456786',
            'gender' => 'male',
            'is_active' => true,
        ]);

        $parent2 = User::create([
            'name' => 'د. هاني توفيق',
            'email' => 'parent2@bibleschool.com',
            'password' => Hash::make('password'),
            'role' => 'parent',
            'phone' => '01223456785',
            'gender' => 'male',
            'is_active' => true,
        ]);

        $studentUser1 = User::create([
            'name' => 'مارك مجدي',
            'email' => 'student@bibleschool.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '01223456784',
            'gender' => 'male',
            'is_active' => true,
        ]);

        $studentUser2 = User::create([
            'name' => 'مارينا مجدي',
            'email' => 'student2@bibleschool.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '01223456783',
            'gender' => 'female',
            'is_active' => true,
        ]);

        $studentUser3 = User::create([
            'name' => 'بيتر هاني',
            'email' => 'student3@bibleschool.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '01223456782',
            'gender' => 'male',
            'is_active' => true,
        ]);

        $studentUser4 = User::create([
            'name' => 'سارة هاني',
            'email' => 'student4@bibleschool.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '01223456781',
            'gender' => 'female',
            'is_active' => true,
        ]);

        // 2. Academic Structure
        $academicYear = AcademicYear::create([
            'name' => '2025/2026',
            'is_current' => true,
            'start_date' => '2025-09-01',
            'end_date' => '2026-06-30',
        ]);

        $stage1 = Stage::create([
            'name' => 'المرحلة الابتدائية',
            'description' => 'الصفوف من الأول إلى السادس الابتدائي',
            'order' => 1,
        ]);

        $stage2 = Stage::create([
            'name' => 'المرحلة الإعدادية',
            'description' => 'الصفوف من الأول إلى الثالث الإعدادي',
            'order' => 2,
        ]);

        $stage3 = Stage::create([
            'name' => 'المرحلة الثانوية',
            'description' => 'الصفوف من الأول إلى الثالث الثانوي',
            'order' => 3,
        ]);

        $grade5 = Grade::create(['stage_id' => $stage1->id, 'name' => 'الصف الخامس الابتدائي', 'order' => 5]);
        $grade6 = Grade::create(['stage_id' => $stage1->id, 'name' => 'الصف السادس الابتدائي', 'order' => 6]);
        $gradePrep1 = Grade::create(['stage_id' => $stage2->id, 'name' => 'الصف الأول الإعدادي', 'order' => 1]);

        $class1 = SchoolClass::create([
            'grade_id' => $grade6->id,
            'name' => 'فصل القديس مارمرقس',
            'room' => 'قاعة 101',
            'servant_id' => $servant1->id,
        ]);
        $class1->servants()->attach($servant1->id);

        $class2 = SchoolClass::create([
            'grade_id' => $grade5->id,
            'name' => 'فصل القديسة مريم العذراء',
            'room' => 'قاعة 102',
            'servant_id' => $servant2->id,
        ]);
        $class2->servants()->attach($servant2->id);

        // 3. Student Profiles
        $student1 = StudentProfile::create([
            'user_id' => $studentUser1->id,
            'stage_id' => $stage1->id,
            'grade_id' => $grade6->id,
            'class_id' => $class1->id,
            'parent_id' => $parent1->id,
            'servant_id' => $servant1->id,
            'code' => 'STU-1001',
            'birth_date' => '2014-05-12',
            'address' => 'القاهرة، مصر',
            'notes' => 'طالب متميز في ألحان الكنيسة وحفظ الكتاب المقدس',
        ]);

        $student2 = StudentProfile::create([
            'user_id' => $studentUser2->id,
            'stage_id' => $stage1->id,
            'grade_id' => $grade5->id,
            'class_id' => $class2->id,
            'parent_id' => $parent1->id,
            'servant_id' => $servant2->id,
            'code' => 'STU-1002',
            'birth_date' => '2015-08-20',
            'address' => 'القاهرة، مصر',
            'notes' => 'مواظبة جداً على الحضور والأنشطة',
        ]);

        $student3 = StudentProfile::create([
            'user_id' => $studentUser3->id,
            'stage_id' => $stage1->id,
            'grade_id' => $grade6->id,
            'class_id' => $class1->id,
            'parent_id' => $parent2->id,
            'servant_id' => $servant1->id,
            'code' => 'STU-1003',
            'birth_date' => '2014-03-15',
            'address' => 'الجيزة، مصر',
            'notes' => 'محاط بالاهتمام ومحب لدراسة الكتاب',
        ]);

        $student4 = StudentProfile::create([
            'user_id' => $studentUser4->id,
            'stage_id' => $stage1->id,
            'grade_id' => $grade5->id,
            'class_id' => $class2->id,
            'parent_id' => $parent2->id,
            'servant_id' => $servant2->id,
            'code' => 'STU-1004',
            'birth_date' => '2015-11-04',
            'address' => 'الجيزة، مصر',
            'notes' => 'هادئة ومتميزة في الاختبارات الكتابية',
        ]);

        // 4. Curricula, Units, Lessons
        $curriculum = Curriculum::create([
            'title' => 'منهج التربية الكنسية - الصف السادس الابتدائي',
            'stage_id' => $stage1->id,
            'grade_id' => $grade6->id,
            'academic_year_id' => $academicYear->id,
            'description' => 'منهج دراسي شامل يتناول دراسة أسفار العهد القديم والعهد الجديد وطقس الكنيسة والألحان.',
            'cover_image' => 'curriculum_cover.jpg',
            'is_published' => true,
        ]);

        $unit1 = Unit::create([
            'curriculum_id' => $curriculum->id,
            'title' => 'الوحدة الأولى: حياة الإيمان والإرادة',
            'term' => 1,
            'description' => 'دروس عن شخصيات كتابية عاشت الإيمان الحقيقي',
            'order' => 1,
        ]);

        $unit2 = Unit::create([
            'curriculum_id' => $curriculum->id,
            'title' => 'الوحدة الثانية: رحلات الأنبياء والرسل',
            'term' => 1,
            'description' => 'رحلات خروج الشعب ورحلات القديس بولس الرسول',
            'order' => 2,
        ]);

        $lesson1 = Lesson::create([
            'unit_id' => $unit1->id,
            'title' => 'الدرس الأول: إبراهيم رجل الإيمان والطاعة',
            'description' => 'كيف أطاع أبونا إبراهيم نداء الله وخرج وهو لا يعلم إلى أين يذهب.',
            'content' => '<h2>دعوة إبراهيم والإيمان الحقيقي</h2><p>دعاء الله لإبراهيم لكي يترك أرضه وعشيرته ويمضي إلى الأرض التي يريه إياها. اتسم إبراهيم بالإيمان القوي والاتكال الكامل على الله في كل خطوات حياته.</p><h3>نقاط الدرس الرئيسية:</h3><ul><li>الطاعة الفورية لنداء الرب</li><li>بناء المذبح في كل مكان نزل فيه</li><li>الوعد الإلهي بالنصرة والبركة</li></ul>',
            'bible_verse' => 'تك 12: 1-9',
            'memory_verse' => '«بِالإِيمَانِ إِبْرَاهِيمُ لَمَّا دُعِيَ أَطَاعَ أَنْ يَخْرُجَ إِلَى الْمَكَانِ الَّذِي كَانَ عَتِيدًا أَنْ يَأْخُذَهُ مِيرَاثًا» (عب 11: 8)',
            'objectives' => ['أن يفهم الطالب معنى الإيمان العملي', 'أن يحفظ آية الدرس', 'أن يطبق الطاعة لله في حياته اليومية'],
            'cover_image' => 'lesson1.jpg',
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'pdf_file' => 'lessons/abraham_lesson.pdf',
            'order' => 1,
            'status' => 'published',
        ]);

        $lesson2 = Lesson::create([
            'unit_id' => $unit1->id,
            'title' => 'الدرس الثاني: يوسف الصديق والأمانة في الغربة',
            'description' => 'حياة يوسف في بيت فوتيفار وفي السجن وتدبير الله الصالح.',
            'content' => '<h2>أمانة يوسف في كل ظروف الحياة</h2><p>كان الرب مع يوسف فكان رجلاً ناجحاً. حفظ يوسف طهارته وأمانته رغم التجارب الشديدة والغربة، ورفض الخطيئة قائلاً: كيف أصنع هذا الشر العظيم وأخطئ إلى الله؟</p>',
            'bible_verse' => 'تك 39: 1-23',
            'memory_verse' => '«كَيْفَ أَصْنَعُ هذَا الشَّرَّ الْعَظِيمَ وَأَخْطِئُ إِلَى اللهِ؟» (تك 39: 9)',
            'objectives' => ['أن يتعلم الطالب أهمية الطهارة والنزاهة', 'أن يدرك أن الله مع الأمناء دائماً'],
            'cover_image' => 'lesson2.jpg',
            'order' => 2,
            'status' => 'published',
        ]);

        $lesson3 = Lesson::create([
            'unit_id' => $unit2->id,
            'title' => 'الدرس الثالث: موسى النبي وعبور البحر الأحمر',
            'description' => 'خروج شعب الله من مصر وتدخل الله الإلهي العجيب.',
            'content' => '<h2>قوة الصلاة وقيادة الرب</h2><p>وقف موسى والشعب أمام البحر الأحمر والعدو خلفهم، فقال لهم موسى: الرب يقاتل عنكم وأنتم تصمتون. فشق الرب البحر وعبر الشعب في اليابسة.</p>',
            'bible_verse' => 'خر 14: 1-31',
            'memory_verse' => '«الرَّبُّ يُقَاتِلُ عَنْكُمْ وَأَنْتُمْ تَصْمُتُونَ» (خر 14: 14)',
            'objectives' => ['الثقة في قدرة الله على إنقاذ أولاده', 'تطبيق الصلاة وقت الشدة'],
            'cover_image' => 'lesson3.jpg',
            'order' => 1,
            'status' => 'published',
        ]);

        // 5. Lesson Progress for Students
        LessonProgress::create([
            'student_id' => $student1->id,
            'lesson_id' => $lesson1->id,
            'status' => 'completed',
            'completed_at' => Carbon::now()->subDays(5),
        ]);

        LessonProgress::create([
            'student_id' => $student1->id,
            'lesson_id' => $lesson2->id,
            'status' => 'completed',
            'completed_at' => Carbon::now()->subDays(2),
        ]);

        LessonProgress::create([
            'student_id' => $student2->id,
            'lesson_id' => $lesson1->id,
            'status' => 'completed',
            'completed_at' => Carbon::now()->subDays(4),
        ]);

        // 6. Quizzes & Questions
        $quiz1 = Quiz::create([
            'lesson_id' => $lesson1->id,
            'title' => 'اختبار قصير: إبراهيم رجل الإيمان',
            'description' => 'اختبار تقييمي لقياس مدى استيعاب درس إبراهيم رجل الإيمان',
            'duration_minutes' => 15,
            'passing_score' => 60,
            'total_marks' => 30,
            'created_by' => $servant1->id,
            'is_published' => true,
        ]);

        Question::create([
            'quiz_id' => $quiz1->id,
            'question_text' => 'ما هي المدينة التي خرج منها إبراهيم؟',
            'question_type' => 'multiple_choice',
            'options' => ['أور الكلدانيين', 'أورشليم', 'أريحا', 'دمشق'],
            'correct_answer' => 'أور الكلدانيين',
            'explanation' => 'خرج إبراهيم من أور الكلدانيين بحسب سفر التكوين 12.',
            'marks' => 10,
        ]);

        Question::create([
            'quiz_id' => $quiz1->id,
            'question_text' => 'إبراهيم بنى مذبحاً للرب في كل مكان نزل فيه.',
            'question_type' => 'true_false',
            'options' => ['صواب', 'خطأ'],
            'correct_answer' => 'صواب',
            'explanation' => 'كان إبراهيم يبني مذبحاً ويدعو باسم الرب في كل محطة.',
            'marks' => 10,
        ]);

        Question::create([
            'quiz_id' => $quiz1->id,
            'question_text' => 'اكمل الآية: باليمين إبراهيم لما دعي أطاع أن يخرج إلى المكان الذي كان عتيداً أن يأخذه...',
            'question_type' => 'multiple_choice',
            'options' => ['ميراثاً', 'هدية', 'مأوى', 'ملكاً'],
            'correct_answer' => 'ميراثاً',
            'explanation' => 'عب 11: 8',
            'marks' => 10,
        ]);

        QuizAttempt::create([
            'quiz_id' => $quiz1->id,
            'student_id' => $student1->id,
            'score' => 30,
            'total_marks' => 30,
            'percentage' => 100.00,
            'passed' => true,
            'answers' => ['1' => 'أور الكلدانيين', '2' => 'صواب', '3' => 'ميراثاً'],
            'completed_at' => Carbon::now()->subDays(3),
        ]);

        QuizAttempt::create([
            'quiz_id' => $quiz1->id,
            'student_id' => $student3->id,
            'score' => 20,
            'total_marks' => 30,
            'percentage' => 66.67,
            'passed' => true,
            'answers' => ['1' => 'أور الكلدانيين', '2' => 'خطأ', '3' => 'ميراثاً'],
            'completed_at' => Carbon::now()->subDays(1),
        ]);

        // 7. Exam System
        $exam1 = Exam::create([
            'title' => 'امتحان المنتصف - التربية الكنسية (الفصل الدراسي الأول)',
            'stage_id' => $stage1->id,
            'grade_id' => $grade6->id,
            'curriculum_id' => $curriculum->id,
            'duration_minutes' => 45,
            'passing_score' => 50,
            'total_marks' => 100,
            'start_date' => Carbon::now()->subDays(7),
            'end_date' => Carbon::now()->addDays(7),
            'is_published' => true,
            'created_by' => $admin->id,
        ]);

        Question::create([
            'exam_id' => $exam1->id,
            'question_text' => 'من هو الأب الملقب بأبي الآباء ورجل الإيمان؟',
            'question_type' => 'multiple_choice',
            'options' => ['إبراهيم', 'يعقوب', 'إسحق', 'موسى'],
            'correct_answer' => 'إبراهيم',
            'explanation' => 'يسمى أبونا إبراهيم بأبي الآباء.',
            'marks' => 50,
        ]);

        Question::create([
            'exam_id' => $exam1->id,
            'question_text' => 'ماذا قال يوسف عندما تعرض للتجربة في بيت فوتيفار؟',
            'question_type' => 'multiple_choice',
            'options' => [
                'كيف أصنع هذا الشر العظيم وأخطئ إلى الله؟',
                'أنا لا أستطيع الإجابة',
                'الرب يقاتل عنكم وأنتم تصمتون',
                'لا تخافوا وقفوا وانظروا خلاص الرب'
            ],
            'correct_answer' => 'كيف أصنع هذا الشر العظيم وأخطئ إلى الله؟',
            'explanation' => 'سفر التكوين 39: 9',
            'marks' => 50,
        ]);

        ExamAttempt::create([
            'exam_id' => $exam1->id,
            'student_id' => $student1->id,
            'score' => 100,
            'total_marks' => 100,
            'percentage' => 100.00,
            'passed' => true,
            'answers' => ['1' => 'إبراهيم', '2' => 'كيف أصنع هذا الشر العظيم وأخطئ إلى الله؟'],
            'completed_at' => Carbon::now()->subDays(2),
        ]);

        // 8. Attendance Records
        $dates = [
            Carbon::now()->subDays(14)->toDateString(),
            Carbon::now()->subDays(7)->toDateString(),
            Carbon::now()->toDateString(),
        ];

        foreach ($dates as $date) {
            AttendanceRecord::create([
                'class_id' => $class1->id,
                'student_id' => $student1->id,
                'recorded_by' => $servant1->id,
                'date' => $date,
                'status' => 'present',
                'notes' => 'حضور مبكر ومشاركة فعالة',
            ]);

            AttendanceRecord::create([
                'class_id' => $class1->id,
                'student_id' => $student3->id,
                'recorded_by' => $servant1->id,
                'date' => $date,
                'status' => $date === Carbon::now()->toDateString() ? 'late' : 'present',
                'notes' => 'تأخر 10 دقائق',
            ]);

            AttendanceRecord::create([
                'class_id' => $class2->id,
                'student_id' => $student2->id,
                'recorded_by' => $servant2->id,
                'date' => $date,
                'status' => 'present',
                'notes' => 'حاضرة بنشاط',
            ]);

            AttendanceRecord::create([
                'class_id' => $class2->id,
                'student_id' => $student4->id,
                'recorded_by' => $servant2->id,
                'date' => $date,
                'status' => 'present',
                'notes' => 'حاضرة بنشاط',
            ]);
        }

        // 9. Points & Achievements
        StudentPoint::create([
            'student_id' => $student1->id,
            'given_by' => $servant1->id,
            'amount' => 10,
            'reason' => 'حضور مبكر والتزام في الفصل',
            'category' => 'attendance',
        ]);

        StudentPoint::create([
            'student_id' => $student1->id,
            'given_by' => $servant1->id,
            'amount' => 10,
            'reason' => 'تفوق في اختبار درس إبراهيم',
            'category' => 'quiz',
        ]);

        StudentPoint::create([
            'student_id' => $student1->id,
            'given_by' => $servant1->id,
            'amount' => 5,
            'reason' => 'تسميع آية عب 11: 8 ممتاز',
            'category' => 'verse',
        ]);

        StudentPoint::create([
            'student_id' => $student2->id,
            'given_by' => $servant2->id,
            'amount' => 10,
            'reason' => 'مشاركة متميزة في الإجابات',
            'category' => 'behavior',
        ]);

        $badge1 = Achievement::create([
            'title' => 'عالم الكتاب المقدس',
            'description' => 'الحصول على الدرجة النهائية في 3 اختبارات كتابية متتالية',
            'icon' => 'fas fa-book-bible',
            'badge_code' => 'bible_scholar',
        ]);

        $badge2 = Achievement::create([
            'title' => 'المواظب المثالي',
            'description' => 'حضور جميع حصص التربية الكنسية خلال الشهر دون غياب',
            'icon' => 'fas fa-calendar-check',
            'badge_code' => 'perfect_attendance',
        ]);

        $badge3 = Achievement::create([
            'title' => 'حافظ الآيات',
            'description' => 'تسميع 10 آيات كتابية بامتياز',
            'icon' => 'fas fa-award',
            'badge_code' => 'memory_master',
        ]);

        StudentAchievement::create([
            'student_id' => $student1->id,
            'achievement_id' => $badge1->id,
            'awarded_at' => Carbon::now()->subDays(3),
        ]);

        StudentAchievement::create([
            'student_id' => $student1->id,
            'achievement_id' => $badge2->id,
            'awarded_at' => Carbon::now()->subDays(1),
        ]);

        StudentAchievement::create([
            'student_id' => $student2->id,
            'achievement_id' => $badge2->id,
            'awarded_at' => Carbon::now()->subDays(1),
        ]);

        // 10. Bible Verse Library & Student Progress
        $v1 = BibleVerse::create([
            'text' => 'بِالإِيمَانِ إِبْرَاهِيمُ لَمَّا دُعِيَ أَطَاعَ أَنْ يَخْرُجَ إِلَى الْمَكَانِ الَّذِي كَانَ عَتِيدًا أَنْ يَأْخُذَهُ مِيرَاثًا',
            'reference' => 'عبرانيين 11: 8',
            'stage_id' => $stage1->id,
            'grade_id' => $grade6->id,
        ]);

        $v2 = BibleVerse::create([
            'text' => 'الرَّبُّ يُقَاتِلُ عَنْكُمْ وَأَنْتُمْ تَصْمُتُونَ',
            'reference' => 'خروج 14: 14',
            'stage_id' => $stage1->id,
            'grade_id' => $grade6->id,
        ]);

        $v3 = BibleVerse::create([
            'text' => 'كَيْفَ أَصْنَعُ هذَا الشَّرَّ الْعَظِيمَ وَأَخْطِئُ إِلَى اللهِ؟',
            'reference' => 'تكوين 39: 9',
            'stage_id' => $stage1->id,
            'grade_id' => $grade6->id,
        ]);

        StudentVerseProgress::create([
            'student_id' => $student1->id,
            'bible_verse_id' => $v1->id,
            'status' => 'excellent',
            'notes' => 'حافظ للآية بالشواهد وبصوت واضح',
            'checked_by' => $servant1->id,
        ]);

        StudentVerseProgress::create([
            'student_id' => $student1->id,
            'bible_verse_id' => $v2->id,
            'status' => 'completed',
            'notes' => 'حافظ للآية جيد جداً',
            'checked_by' => $servant1->id,
        ]);

        StudentVerseProgress::create([
            'student_id' => $student2->id,
            'bible_verse_id' => $v1->id,
            'status' => 'excellent',
            'notes' => 'تسميع ممتازة',
            'checked_by' => $servant2->id,
        ]);

        // 11. News Articles
        News::create([
            'title' => 'افتتاح العام الدراسي الجديد بمدرسة الكتاب المقدس',
            'content' => 'نرحب بجميع أبنائنا الطلاب وأولياء أمورهم في بداية عام دراسي بروحي وتعليمي متميز ملؤه البركة والنمو في معرفة كلمة الله.',
            'cover_image' => 'news_opening.jpg',
            'author_id' => $admin->id,
            'is_published' => true,
            'published_at' => Carbon::now()->subDays(10),
        ]);

        News::create([
            'title' => 'مسابقة حفظ الكتاب المقدس السنوية',
            'content' => 'تعلن الكنيسة عن بدء التجهيز للمسابقة السنوية لحفظ أسفار الكتاب المقدس وتوزيع الهدايا والدروع للمتفوقين.',
            'cover_image' => 'news_contest.jpg',
            'author_id' => $admin->id,
            'is_published' => true,
            'published_at' => Carbon::now()->subDays(4),
        ]);

        // 12. Calendar Events
        Event::create([
            'title' => 'رحلة دراسية واستكشافية لمكتبة الدير',
            'description' => 'رحلة خاصة لطلاب المرحلة الابتدائية للاطلاع على المخطوطات والكتب الروحية القديمة.',
            'event_type' => 'trip',
            'start_time' => Carbon::now()->addDays(5)->setHour(8)->setMinute(0),
            'end_time' => Carbon::now()->addDays(5)->setHour(17)->setMinute(0),
            'location' => 'دير القديس أنبا بيشوي - وادي النطرون',
            'stage_id' => $stage1->id,
        ]);

        Event::create([
            'title' => 'اختبار نهاية الشهر في التربية الكنسية',
            'description' => 'امتحان شامل لجميع الصفوف في الوحدات الأولى والثانية.',
            'event_type' => 'exam',
            'start_time' => Carbon::now()->addDays(10)->setHour(16)->setMinute(0),
            'end_time' => Carbon::now()->addDays(10)->setHour(18)->setMinute(0),
            'location' => 'مبنى قاعات التربية الكنسية',
            'stage_id' => $stage1->id,
        ]);

        // 13. Messages
        Message::create([
            'sender_id' => $parent1->id,
            'receiver_id' => $servant1->id,
            'student_id' => $student1->id,
            'message' => 'سلام ونعمة أستاذ مينا، أود الاطمئنان على مستوى مارك في تسميع الألحان وآيات هذا الأسبوع.',
            'is_read' => true,
            'read_at' => Carbon::now()->subDays(1),
        ]);

        Message::create([
            'sender_id' => $servant1->id,
            'receiver_id' => $parent1->id,
            'student_id' => $student1->id,
            'message' => 'أهلاً يا باشمهندس مجدي، مارك ممتاز جداً ومواظب وحصل على شارة "عالم الكتاب المقدس" هذا الأسبوع!',
            'is_read' => false,
        ]);

        // 14. Notifications
        Notification::create([
            'user_id' => $studentUser1->id,
            'title' => 'وسام جديد!',
            'message' => 'تم منحك وسام "عالم الكتاب المقدس" لتفوقك في الاختبارات الكتابية.',
            'type' => 'achievement',
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => $studentUser1->id,
            'title' => 'نقاط جديدة!',
            'message' => 'تم إضافة 10 نقاط لحسابك بواسطة أ. مينا سامي لتفوقك في الاختبار.',
            'type' => 'general',
            'is_read' => true,
            'read_at' => Carbon::now()->subDays(1),
        ]);

        Notification::create([
            'user_id' => $parent1->id,
            'title' => 'تقرير الحضور',
            'message' => 'تم تسجيل حضور الطالب مارك مجدي بنجاح اليوم.',
            'type' => 'attendance',
            'is_read' => false,
        ]);
    }
}
