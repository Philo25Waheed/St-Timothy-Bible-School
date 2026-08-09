<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Message;
use App\Models\StudentProfile;

class MessagingFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_student_can_message_servant_and_admin_only()
    {
        $studentUser = User::where('email', 'student@bibleschool.com')->first();
        $servantUser = User::where('email', 'servant@bibleschool.com')->first();
        $adminUser = User::where('email', 'admin@bibleschool.com')->first();
        $otherServant = User::where('email', 'servant2@bibleschool.com')->first();

        // 1. Student can message class servant
        $response = $this->actingAs($studentUser)->post(route('messages.store'), [
            'receiver_id' => $servantUser->id,
            'message' => 'سلام يا قدس الأب/الخادم',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('messages', [
            'sender_id' => $studentUser->id,
            'receiver_id' => $servantUser->id,
            'message' => 'سلام يا قدس الأب/الخادم',
        ]);

        // 2. Student can message Admin
        $response = $this->actingAs($studentUser)->post(route('messages.store'), [
            'receiver_id' => $adminUser->id,
            'message' => 'استفسار للمسؤول',
        ]);
        $response->assertRedirect();

        // 3. Student CANNOT message unassigned servant
        $response = $this->actingAs($studentUser)->post(route('messages.store'), [
            'receiver_id' => $otherServant->id,
            'message' => 'رسالة خافية',
        ]);
        $response->assertSessionHas('error');
    }

    public function test_admin_can_message_any_user()
    {
        $adminUser = User::where('email', 'admin@bibleschool.com')->first();
        $studentUser = User::where('email', 'student@bibleschool.com')->first();

        $response = $this->actingAs($adminUser)->post(route('messages.store'), [
            'receiver_id' => $studentUser->id,
            'message' => 'تنبيه إداري من الأدمن',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('messages', [
            'sender_id' => $adminUser->id,
            'receiver_id' => $studentUser->id,
        ]);
    }

    public function test_whatsapp_style_conversation_sorting_by_newest_message()
    {
        $adminUser = User::where('email', 'admin@bibleschool.com')->first();
        $studentUser = User::where('email', 'student@bibleschool.com')->first();
        $servantUser = User::where('email', 'servant@bibleschool.com')->first();

        // Admin receives old message from Student
        $msg1 = new Message([
            'sender_id' => $studentUser->id,
            'receiver_id' => $adminUser->id,
            'message' => 'رسالة قديمة من الطالب',
            'is_read' => false,
        ]);
        $msg1->created_at = now()->subHours(5);
        $msg1->save();

        // Admin receives new message from Servant
        $msg2 = new Message([
            'sender_id' => $servantUser->id,
            'receiver_id' => $adminUser->id,
            'message' => 'رسالة جديدة من الخادم',
            'is_read' => false,
        ]);
        $msg2->created_at = now()->addDays(1);
        $msg2->save();

        $response = $this->actingAs($adminUser)->get(route('messages.index'));
        $response->assertStatus(200);

        $conversations = $response->viewData('conversations');
        $this->assertGreaterThanOrEqual(2, $conversations->count());

        // Top conversation (position 0) must be the servant (newest message)
        $this->assertEquals($servantUser->id, $conversations->first()['contact']->id);
    }

    public function test_unread_message_badge_counter_and_reading_state()
    {
        $studentUser = User::where('email', 'student@bibleschool.com')->first();
        $servantUser = User::where('email', 'servant@bibleschool.com')->first();

        // Student sends message to Servant
        Message::create([
            'sender_id' => $studentUser->id,
            'receiver_id' => $servantUser->id,
            'message' => 'رسالة غير مقروءة بعد',
            'is_read' => false,
        ]);

        // Servant checks messages page -> unread count should be > 0 before selecting
        $response = $this->actingAs($servantUser)->get(route('messages.index'));
        $response->assertStatus(200);
        $totalUnread = $response->viewData('totalUnreadMessages');
        $this->assertGreaterThanOrEqual(1, $totalUnread);

        // When servant views conversation with student, messages mark as read
        $response = $this->actingAs($servantUser)->get(route('messages.index', ['user_id' => $studentUser->id]));
        $response->assertStatus(200);

        $this->assertDatabaseHas('messages', [
            'sender_id' => $studentUser->id,
            'receiver_id' => $servantUser->id,
            'is_read' => true,
        ]);
    }
}
