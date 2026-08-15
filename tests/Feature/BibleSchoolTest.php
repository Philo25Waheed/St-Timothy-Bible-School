<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Curriculum;
use App\Models\Unit;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Grade;

class BibleSchoolTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed');
    }

    public function test_landing_page_is_accessible(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_admin_can_login_and_access_dashboard(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('إجمالي الطلاب');
    }

    public function test_servant_can_access_dashboard(): void
    {
        $servant = User::where('role', 'servant')->first();
        $response = $this->actingAs($servant)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('فصولي الدراسية');
    }

    public function test_student_can_access_dashboard(): void
    {
        $student = User::where('role', 'student')->first();
        $response = $this->actingAs($student)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('تقدمك في المنهج');
    }

    public function test_parent_can_access_dashboard(): void
    {
        $parent = User::where('role', 'parent')->first();
        $response = $this->actingAs($parent)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('متابعة الأبناء');
    }

    public function test_student_management_page_accessible_for_admin(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/students');
        $response->assertStatus(200);
    }

    public function test_curriculum_page_accessible(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/curriculum');
        $response->assertStatus(200);
    }

    public function test_curriculum_create_page_accessible(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/curriculum/create');
        $response->assertStatus(200);
        $response->assertSee('إنشاء منهج دراسي جديد');
    }

    public function test_student_can_view_curriculum_and_lessons(): void
    {
        $student = User::where('role', 'student')->first();
        $response = $this->actingAs($student)->get('/curriculum');
        $response->assertStatus(200);
        $response->assertSee('المناهج والدروس');

        $curriculum = Curriculum::first();
        if ($curriculum) {
            $showResponse = $this->actingAs($student)->get('/curriculum/' . $curriculum->id);
            $showResponse->assertStatus(200);
        }
    }

    public function test_reports_page_accessible(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/reports');
        $response->assertStatus(200);
    }

    public function test_messages_page_accessible(): void
    {
        $parent = User::where('role', 'parent')->first();
        $response = $this->actingAs($parent)->get('/messages');
        $response->assertStatus(200);
    }

    public function test_registration_page_is_accessible(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('إنشاء حساب جديد بالمنصة');
    }

    public function test_user_can_submit_registration_request_pending_approval(): void
    {
        $response = $this->post('/register', [
            'name' => 'سامح نبيل',
            'email' => 'sameh_test@bibleschool.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'gender' => 'male',
            'phone' => '01234567890',
            'birth_date' => '2012-05-15',
            'address' => 'القاهرة، مصر',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('users', [
            'email' => 'sameh_test@bibleschool.com',
            'is_active' => false,
        ]);
    }

    public function test_pending_user_cannot_login_before_approval(): void
    {
        $user = User::create([
            'name' => 'حساب معلق',
            'email' => 'pending_user@bibleschool.com',
            'password' => bcrypt('password123'),
            'role' => 'student',
            'is_active' => false,
        ]);

        $response = $this->post('/login', [
            'email' => 'pending_user@bibleschool.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_can_approve_pending_user(): void
    {
        $admin = User::where('role', 'admin')->first();
        $user = User::create([
            'name' => 'طالب جديد للاعتماد',
            'email' => 'to_approve@bibleschool.com',
            'password' => bcrypt('password123'),
            'role' => 'student',
            'is_active' => false,
        ]);

        $response = $this->actingAs($admin)->post('/admin/pending-approvals/' . $user->id . '/approve');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_assign_servant_to_class(): void
    {
        $admin = User::where('role', 'admin')->first();
        $servant = User::where('role', 'servant')->first();
        $class = SchoolClass::first();

        $response = $this->actingAs($admin)->post('/academic/classes/' . $class->id, [
            'servant_id' => $servant->id,
            'name' => $class->name,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('classes', [
            'id' => $class->id,
            'servant_id' => $servant->id,
        ]);
    }

    public function test_servant_can_create_quiz_for_their_assigned_class(): void
    {
        $servant = User::where('role', 'servant')->first();
        $class = SchoolClass::first();
        
        // Ensure servant is assigned to class
        $servant->assignedClasses()->sync([$class->id]);

        $response = $this->actingAs($servant)->post('/quizzes', [
            'title' => 'اختبار مخصص لفصل الخادم',
            'class_id' => $class->id,
            'duration_minutes' => 20,
            'passing_score' => 60,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('quizzes', [
            'title' => 'اختبار مخصص لفصل الخادم',
            'class_id' => $class->id,
            'created_by' => $servant->id,
        ]);
    }

    public function test_servant_cannot_create_quiz_for_unassigned_class(): void
    {
        $servant = User::where('role', 'servant')->first();
        $class1 = SchoolClass::first();
        $class2 = SchoolClass::skip(1)->first();

        if ($class2) {
            // Assign servant only to class1
            $servant->assignedClasses()->sync([$class1->id]);

            $response = $this->actingAs($servant)->post('/quizzes', [
                'title' => 'محاولة إنشاء اختبار لفصل آخر',
                'class_id' => $class2->id,
                'duration_minutes' => 15,
                'passing_score' => 50,
            ]);

            $response->assertSessionHasErrors('class_id');
        }
    }

    public function test_servant_can_add_lesson_to_assigned_curriculum_grade(): void
    {
        $servant = User::where('role', 'servant')->first();
        $class = SchoolClass::with('grade')->first();
        $servant->assignedClasses()->sync([$class->id]);

        // Find or create curriculum matching the servant's class grade
        $curriculum = Curriculum::where('grade_id', $class->grade_id)->first();
        if (!$curriculum) {
            $curriculum = Curriculum::create([
                'title' => 'منهج المرحلة',
                'grade_id' => $class->grade_id,
                'stage_id' => $class->grade->stage_id,
            ]);
        }

        $unit = $curriculum->units()->create([
            'title' => 'الوحدة الأولى',
            'term' => 1,
        ]);

        $response = $this->actingAs($servant)->post('/units/' . $unit->id . '/lessons', [
            'title' => 'درس جديد من الخادم لفصله',
            'bible_verse' => 'يو 3: 16',
            'content' => 'محتوى الدرس التدريبي للخدمة',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('lessons', [
            'unit_id' => $unit->id,
            'title' => 'درس جديد من الخادم لفصله',
        ]);
    }
}
