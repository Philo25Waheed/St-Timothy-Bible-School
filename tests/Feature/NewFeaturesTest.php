<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\Event;
use App\Models\PrayerRequest;

class NewFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_student_can_access_spiritual_journal_and_submit_entry()
    {
        $studentUser = User::where('email', 'student@bibleschool.com')->first();

        $response = $this->actingAs($studentUser)->get(route('journal.index'));
        $response->assertStatus(200);
        $response->assertSee('دفتر التخصيص والصلوات');

        // Store journal entry
        $response = $this->actingAs($studentUser)->post(route('journal.store'), [
            'title' => 'تأمل في مزمور 23',
            'content' => 'الرب رعي فلا يعوزني شيء',
            'mood' => 'مبتهج 😇',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('spiritual_journals', [
            'title' => 'تأمل في مزمور 23',
        ]);
    }

    public function test_student_can_submit_prayer_request_and_servant_can_respond()
    {
        $studentUser = User::where('email', 'student@bibleschool.com')->first();
        $servantUser = User::where('email', 'servant@bibleschool.com')->first();

        // Student submits prayer request
        $this->actingAs($studentUser)->post(route('prayers.store'), [
            'title' => 'صلاة لأجل الامتحانات',
            'details' => 'أطلب بركة الرب في امتحانات الترم الأول',
        ]);

        $prayer = PrayerRequest::where('title', 'صلاة لأجل الامتحانات')->first();
        $this->assertNotNull($prayer);

        // Servant views prayer requests
        $response = $this->actingAs($servantUser)->get(route('servant.prayers.index'));
        $response->assertStatus(200);
        $response->assertSee('صلاة لأجل الامتحانات');

        // Servant updates prayer status
        $response = $this->actingAs($servantUser)->post(route('servant.prayers.update', $prayer->id), [
            'status' => 'praying',
            'servant_notes' => 'نصلي لأجلك بالتوفيق والبركة',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('prayer_requests', [
            'id' => $prayer->id,
            'status' => 'praying',
        ]);
    }

    public function test_servant_can_scan_student_qr_code_and_record_attendance()
    {
        $servantUser = User::where('email', 'servant@bibleschool.com')->first();
        $student = StudentProfile::first();

        $response = $this->actingAs($servantUser)->get(route('attendance.qr_scanner'));
        $response->assertStatus(200);

        // Scan student code via AJAX
        $response = $this->actingAs($servantUser)->postJson(route('attendance.qr_scan'), [
            'code' => $student->code,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('attendance_records', [
            'student_id' => $student->id,
            'status' => 'present',
        ]);
    }

    public function test_parent_can_access_weekly_digest()
    {
        $parentUser = User::where('email', 'parent@bibleschool.com')->first();

        $response = $this->actingAs($parentUser)->get(route('parent.weekly_digest'));
        $response->assertStatus(200);
        $response->assertSee('التقرير الأسبوعي لولي الأمر');
    }

    public function test_user_can_register_for_event_and_view_gallery()
    {
        $studentUser = User::where('email', 'student@bibleschool.com')->first();
        $event = Event::first();

        // Register for event
        $response = $this->actingAs($studentUser)->post(route('events.register', $event->id));
        $response->assertRedirect();

        $this->assertDatabaseHas('event_registrations', [
            'event_id' => $event->id,
            'user_id' => $studentUser->id,
        ]);

        // View gallery
        $response = $this->actingAs($studentUser)->get(route('events.gallery'));
        $response->assertStatus(200);
    }
}
