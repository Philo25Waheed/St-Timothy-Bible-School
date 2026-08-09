<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Calculate available contacts based on strict role permissions
        $availableContacts = collect();

        if ($user->isStudent()) {
            // Student can message: Class servant(s) & Admin(s)
            $studentProfile = $user->studentProfile;
            $servantIds = collect();

            if ($studentProfile) {
                if ($studentProfile->servant_id) {
                    $servantIds->push($studentProfile->servant_id);
                }
                if ($studentProfile->class_id) {
                    $schoolClass = SchoolClass::with('servants')->find($studentProfile->class_id);
                    if ($schoolClass) {
                        if ($schoolClass->servant_id) {
                            $servantIds->push($schoolClass->servant_id);
                        }
                        if ($schoolClass->servants) {
                            $servantIds = $servantIds->merge($schoolClass->servants->pluck('id'));
                        }
                    }
                }
            }
            $servantIds = $servantIds->unique()->filter();

            $availableContacts = User::where(function($q) use ($servantIds) {
                $q->whereIn('id', $servantIds)->where('role', 'servant');
            })->orWhere('role', 'admin')->where('id', '!=', $user->id)->get();

        } elseif ($user->isServant()) {
            // Servant can message: Class students, their parents, & Admin(s)
            $classIdsPivot = $user->assignedClasses()->pluck('classes.id');
            $classIdsDirect = SchoolClass::where('servant_id', $user->id)->pluck('id');
            $classIds = $classIdsPivot->merge($classIdsDirect)->unique()->filter();

            $studentProfiles = StudentProfile::where(function($q) use ($classIds, $user) {
                if ($classIds->isNotEmpty()) {
                    $q->whereIn('class_id', $classIds);
                }
                $q->orWhere('servant_id', $user->id);
            })->get();

            $studentUserIds = $studentProfiles->pluck('user_id')->filter()->unique();
            $parentUserIds = $studentProfiles->pluck('parent_id')->filter()->unique();

            $availableContacts = User::where(function($q) use ($studentUserIds, $parentUserIds) {
                $q->whereIn('id', $studentUserIds)
                  ->orWhereIn('id', $parentUserIds);
            })->orWhere('role', 'admin')->where('id', '!=', $user->id)->get();

        } elseif ($user->isParent()) {
            // Parent can message: Children's class servants & Admin(s)
            $studentProfiles = StudentProfile::where('parent_id', $user->id)->get();
            $servantIds = $studentProfiles->pluck('servant_id')->filter();
            $classIds = $studentProfiles->pluck('class_id')->filter();

            $classServantIds = SchoolClass::whereIn('id', $classIds)->pluck('servant_id')->filter();
            $pivotServantIds = DB::table('class_servant')->whereIn('class_id', $classIds)->pluck('servant_id');

            $allServantIds = $servantIds->merge($classServantIds)->merge($pivotServantIds)->unique()->filter();

            $availableContacts = User::where(function($q) use ($allServantIds) {
                $q->whereIn('id', $allServantIds)->where('role', 'servant');
            })->orWhere('role', 'admin')->where('id', '!=', $user->id)->get();

        } else {
            // Admin can message all users except self
            $availableContacts = User::where('id', '!=', $user->id)->get();
        }

        // 2. Fetch all raw messages for user and group by conversation partner
        $rawMessages = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->latest()
            ->get();

        $grouped = $rawMessages->groupBy(function($msg) use ($user) {
            return $msg->sender_id == $user->id ? $msg->receiver_id : $msg->sender_id;
        });

        // 3. Build WhatsApp-style sorted conversation list (Newest message first)
        $conversations = collect();

        foreach ($grouped as $otherUserId => $msgList) {
            $contact = User::find($otherUserId);
            if (!$contact) continue;

            $lastMessage = $msgList->first(); // latest() sort
            $unreadCount = $msgList->where('receiver_id', $user->id)->where('is_read', false)->count();

            $conversations->push([
                'contact' => $contact,
                'last_message' => $lastMessage,
                'unread_count' => $unreadCount,
                'updated_at' => $lastMessage->created_at,
            ]);
        }

        // Sort by last message created_at descending
        $conversations = $conversations->sortByDesc('updated_at')->values();

        // 4. Select active contact (from request param or top conversation or first available contact)
        $activeContactId = $request->query('user_id');
        
        if ($activeContactId) {
            $activeContact = User::find($activeContactId);
        } else if ($conversations->isNotEmpty()) {
            $activeContact = $conversations->first()['contact'];
        } else {
            $activeContact = $availableContacts->first();
        }

        // 5. Load active thread messages and mark unread as read
        $messages = [];
        if ($activeContact) {
            $messages = Message::where(function($q) use ($user, $activeContact) {
                $q->where('sender_id', $user->id)->where('receiver_id', $activeContact->id);
            })->orWhere(function($q) use ($user, $activeContact) {
                $q->where('sender_id', $activeContact->id)->where('receiver_id', $user->id);
            })->orderBy('created_at')->get();

            // Mark as read for current active contact
            Message::where('sender_id', $activeContact->id)
                ->where('receiver_id', $user->id)
                ->where('is_read', false)
                ->update(['is_read' => true, 'read_at' => now()]);
        }

        // Total unread count across all conversations
        $totalUnreadMessages = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        return view('messages.index', compact(
            'conversations',
            'activeContact',
            'messages',
            'availableContacts',
            'totalUnreadMessages'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ], [
            'message.required' => 'محتوى الرسالة مطلوب.',
        ]);

        $sender = Auth::user();
        $receiver = User::findOrFail($request->receiver_id);

        // Security Check based on sender role
        if ($sender->isStudent()) {
            $studentProfile = $sender->studentProfile;
            $servantIds = collect();
            if ($studentProfile) {
                if ($studentProfile->servant_id) {
                    $servantIds->push($studentProfile->servant_id);
                }
                if ($studentProfile->class_id) {
                    $schoolClass = SchoolClass::with('servants')->find($studentProfile->class_id);
                    if ($schoolClass) {
                        if ($schoolClass->servant_id) {
                            $servantIds->push($schoolClass->servant_id);
                        }
                        if ($schoolClass->servants) {
                            $servantIds = $servantIds->merge($schoolClass->servants->pluck('id'));
                        }
                    }
                }
            }
            $validServantIds = $servantIds->unique()->filter()->toArray();

            if (!in_array($receiver->id, $validServantIds) && !$receiver->isAdmin()) {
                return back()->with('error', 'غير مصرح لك بمراسلة هذا المستخدم. يمكنك مراسلة خادم فصلك أو الأدمن فقط.');
            }

        } elseif ($sender->isServant()) {
            $classIdsPivot = $sender->assignedClasses()->pluck('classes.id');
            $classIdsDirect = SchoolClass::where('servant_id', $sender->id)->pluck('id');
            $classIds = $classIdsPivot->merge($classIdsDirect)->unique()->filter();

            $studentProfiles = StudentProfile::where(function($q) use ($classIds, $sender) {
                if ($classIds->isNotEmpty()) {
                    $q->whereIn('class_id', $classIds);
                }
                $q->orWhere('servant_id', $sender->id);
            })->get();

            $validUserIds = $studentProfiles->pluck('user_id')
                ->merge($studentProfiles->pluck('parent_id'))
                ->filter()
                ->unique()
                ->toArray();

            if (!in_array($receiver->id, $validUserIds) && !$receiver->isAdmin()) {
                return back()->with('error', 'غير مصرح لك بمراسلة هذا المستخدم. يمكنك مراسلة مخدومين فصلك وأولياء أمورهم والأدمن فقط.');
            }

        } elseif ($sender->isParent()) {
            $studentProfiles = StudentProfile::where('parent_id', $sender->id)->get();
            $servantIds = $studentProfiles->pluck('servant_id')->filter();
            $classIds = $studentProfiles->pluck('class_id')->filter();

            $classServantIds = SchoolClass::whereIn('id', $classIds)->pluck('servant_id')->filter();
            $pivotServantIds = DB::table('class_servant')->whereIn('class_id', $classIds)->pluck('servant_id');

            $allServantIds = $servantIds->merge($classServantIds)->merge($pivotServantIds)->unique()->filter()->toArray();

            if (!in_array($receiver->id, $allServantIds) && !$receiver->isAdmin()) {
                return back()->with('error', 'غير مصرح لك بمراسلة هذا المستخدم. يمكنك مراسلة خادم فصل ابنك أو الأدمن فقط.');
            }
        }

        Message::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'student_id' => $request->student_id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return redirect()->route('messages.index', ['user_id' => $receiver->id])->with('success', 'تم إرسال الرسالة بنجاح.');
    }
}
