<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') - مدرسة القديس تيموثاوس للكتاب المقدس</title>

    <!-- Favicon / Tab Logo -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

    <!-- FontAwesome 6 & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom EdTech Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @stack('styles')
</head>
<body>
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
    <div class="app-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="brand-logo" style="width: 58px; height: 58px; background: #ffffff; padding: 4px; border: 1px solid rgba(255,255,255,0.25); border-radius: 14px; box-shadow: 0 4px 12px rgba(0,0,0,0.25);">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain; border-radius: 10px;">
                </div>
                <div>
                    <div class="brand-name" style="font-size: 15px; font-weight: 800;">مدرسة القديس تيموثاوس للكتاب المقدس</div>
                    <div style="font-size: 11px; color: var(--accent-gold);">Faith • Knowledge • Spirit</div>
                </div>
                <button type="button" class="sidebar-close-btn" id="sidebarCloseBtn" aria-label="إغلاق القائمة">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="sidebar-menu">
                <a href="{{ route('dashboard') }}" class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>الرئيسية</span>
                </a>

                @if(Auth::user()->isAdmin())
                    <div class="sidebar-label">المستخدمون والنظام</div>
                    <a href="{{ route('admin.pending.index') }}" class="sidebar-item {{ request()->routeIs('admin.pending.*') ? 'active' : '' }}">
                        <i class="fas fa-user-clock" style="color: var(--accent);"></i>
                        <span>طلبات التسجيل المعلقة</span>
                        @php $pendingCount = \App\Models\User::where('is_active', false)->count(); @endphp
                        @if($pendingCount > 0)
                            <span class="badge badge-warning" style="margin-right: auto;">{{ $pendingCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('students.index') }}" class="sidebar-item {{ request()->routeIs('students.*') ? 'active' : '' }}">
                        <i class="fas fa-user-graduate"></i>
                        <span>إدارة الطلاب</span>
                    </a>
                    <a href="{{ route('servants.index') }}" class="sidebar-item {{ request()->routeIs('servants.*') ? 'active' : '' }}">
                        <i class="fas fa-user-tie"></i>
                        <span>إدارة الخدام</span>
                    </a>
                    <a href="{{ route('parents.index') }}" class="sidebar-item {{ request()->routeIs('parents.*') ? 'active' : '' }}">
                        <i class="fas fa-users-between-lines"></i>
                        <span>أولياء الأمور</span>
                    </a>

                    <div class="sidebar-label">الهيكل الأكاديمي</div>
                    <a href="{{ route('academic.years') }}" class="sidebar-item {{ request()->routeIs('academic.years') ? 'active' : '' }}">
                        <i class="fas fa-calendar-days"></i>
                        <span>السنوات الدراسية</span>
                    </a>
                    <a href="{{ route('academic.stages') }}" class="sidebar-item {{ request()->routeIs('academic.stages') ? 'active' : '' }}">
                        <i class="fas fa-layer-group"></i>
                        <span>المراحل الدراسية</span>
                    </a>
                    <a href="{{ route('academic.grades') }}" class="sidebar-item {{ request()->routeIs('academic.grades') ? 'active' : '' }}">
                        <i class="fas fa-graduation-cap"></i>
                        <span>الصفوف الدراسية</span>
                    </a>
                    <a href="{{ route('academic.classes') }}" class="sidebar-item {{ request()->routeIs('academic.classes') ? 'active' : '' }}">
                        <i class="fas fa-school"></i>
                        <span>الفصول الدراسية</span>
                    </a>

                    <div class="sidebar-label">التعليم والمناهج</div>
                    <a href="{{ route('curriculum.index') }}" class="sidebar-item {{ request()->routeIs('curriculum.*') ? 'active' : '' }}">
                        <i class="fas fa-book-open"></i>
                        <span>المناهج والدروس</span>
                    </a>
                    <a href="{{ route('quizzes.index') }}" class="sidebar-item {{ request()->routeIs('quizzes.*') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <span>الاختبارات القصيرة</span>
                    </a>
                    <a href="{{ route('exams.index') }}" class="sidebar-item {{ request()->routeIs('exams.*') ? 'active' : '' }}">
                        <i class="fas fa-file-pen"></i>
                        <span>الامتحانات الرسمية</span>
                    </a>
                    <a href="{{ route('servant.prayers.index') }}" class="sidebar-item {{ request()->routeIs('servant.prayers.*') ? 'active' : '' }}">
                        <i class="bi bi-hands-fill text-danger"></i>
                        <span>طلبات صلوات الطلاب</span>
                    </a>
                    <a href="{{ route('reports.index') }}" class="sidebar-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>التقارير والإحصائيات</span>
                    </a>
                @endif

                @if(Auth::user()->isServant())
                    <div class="sidebar-label">الخدمة وفصلي</div>
                    <a href="{{ route('curriculum.index') }}" class="sidebar-item {{ request()->routeIs('curriculum.*') || request()->routeIs('lessons.*') ? 'active' : '' }}">
                        <i class="fas fa-book-open"></i>
                        <span>المناهج والدروس</span>
                    </a>
                    <a href="{{ route('attendance.index') }}" class="sidebar-item {{ request()->routeIs('attendance.index') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-user"></i>
                        <span>تسجيل الحضور اليومي</span>
                    </a>
                    <a href="{{ route('attendance.qr_scanner') }}" class="sidebar-item {{ request()->routeIs('attendance.qr_scanner') ? 'active' : '' }}">
                        <i class="bi bi-qr-code-scan text-success"></i>
                        <span>ماسح QR Code الحضور</span>
                    </a>
                    <a href="{{ route('servant.prayers.index') }}" class="sidebar-item {{ request()->routeIs('servant.prayers.*') ? 'active' : '' }}">
                        <i class="bi bi-hands-fill text-danger"></i>
                        <span>صلوات الطلاب والافتقاد</span>
                    </a>
                    <a href="{{ route('quizzes.index') }}" class="sidebar-item {{ request()->routeIs('quizzes.*') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <span>إنشاء الاختبارات</span>
                    </a>
                    <a href="{{ route('verses.index') }}" class="sidebar-item {{ request()->routeIs('verses.*') ? 'active' : '' }}">
                        <i class="fas fa-quote-right"></i>
                        <span>متابعة حفظ الآيات</span>
                    </a>
                @endif

                @if(Auth::user()->isStudent())
                    <div class="sidebar-label">تعلمي ومساري</div>
                    <a href="{{ route('curriculum.index') }}" class="sidebar-item {{ request()->routeIs('curriculum.*') ? 'active' : '' }}">
                        <i class="fas fa-book-bookmark"></i>
                        <span>منهجي والدروس</span>
                    </a>
                    <a href="{{ route('journal.index') }}" class="sidebar-item {{ request()->routeIs('journal.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-bookmark-fill text-primary"></i>
                        <span>دفتر التخصيص والصلوات</span>
                    </a>
                    <a href="{{ route('verses.index') }}" class="sidebar-item {{ request()->routeIs('verses.*') ? 'active' : '' }}">
                        <i class="fas fa-quote-right"></i>
                        <span>آيات الحفظ</span>
                    </a>
                @endif

                @if(Auth::user()->isParent())
                    <div class="sidebar-label">متابعة الأبناء</div>
                    <a href="{{ route('parent.weekly_digest') }}" class="sidebar-item {{ request()->routeIs('parent.weekly_digest') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-bar-graph-fill text-info"></i>
                        <span>التقرير الأسبوعي للأبناء</span>
                    </a>
                @endif

                <div class="sidebar-label">التواصل والأنشطة</div>
                @php
                    $unreadMessagesBadge = \App\Models\Message::where('receiver_id', Auth::id())->where('is_read', false)->count();
                @endphp
                <a href="{{ route('messages.index') }}" class="sidebar-item {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                    <i class="fas fa-comments"></i>
                    <span>الرسائل المباشرة</span>
                    @if($unreadMessagesBadge > 0)
                        <span class="badge bg-danger rounded-pill me-auto">{{ $unreadMessagesBadge }}</span>
                    @endif
                </a>
                <a href="{{ route('notifications.index') }}" class="sidebar-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i>
                    <span>الإشعارات</span>
                </a>
                <a href="{{ route('events.index') }}" class="sidebar-item {{ request()->routeIs('events.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>التقويم والرحلات</span>
                </a>
                <a href="{{ route('events.gallery') }}" class="sidebar-item {{ request()->routeIs('events.gallery') ? 'active' : '' }}">
                    <i class="bi bi-images text-purple"></i>
                    <span>معرض الصور والألبوم</span>
                </a>
                <a href="{{ route('news.index') }}" class="sidebar-item {{ request()->routeIs('news.*') ? 'active' : '' }}">
                    <i class="fas fa-newspaper"></i>
                    <span>أخبار الكنيسة</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Top Navbar -->
            <header class="topbar">
                <div class="topbar-right d-flex align-items-center gap-3">
                    <button type="button" class="mobile-sidebar-toggle" id="sidebarToggle" aria-label="فتح القائمة الجانبية">
                        <i class="fas fa-bars"></i>
                    </button>

                    <a href="{{ route('messages.index') }}" style="position: relative; font-size: 18px; color: var(--text-muted);" title="الرسائل المباشرة">
                        <i class="bi bi-chat-dots-fill"></i>
                        @if($unreadMessagesBadge > 0)
                            <span style="position: absolute; top: -5px; right: -5px; background: #ef4444; color: white; font-size: 10px; font-weight: bold; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                {{ $unreadMessagesBadge }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('notifications.index') }}" style="position: relative; font-size: 18px; color: var(--text-muted);" title="الإشعارات">
                        <i class="fas fa-bell"></i>
                        @php
                            $unreadCount = Auth::user()->notifications()->where('is_read', false)->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span style="position: absolute; top: -5px; right: -5px; background: var(--danger); color: white; font-size: 10px; font-weight: bold; width: 16px; height: 16px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>
                </div>

                <div class="topbar-left">
                    <div class="user-profile-menu">
                        <img src="{{ Auth::user()->avatar_url }}" alt="avatar" class="user-avatar">
                        <div class="user-info">
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <span class="badge-role {{ Auth::user()->role }}">
                                @switch(Auth::user()->role)
                                    @case('admin') مسؤول النظام @break
                                    @case('servant') خادم الفصل @break
                                    @case('student') طالب @break
                                    @case('parent') ولي أمر @break
                                @endswitch
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('profile') }}" class="btn btn-outline btn-sm" title="الملف الشخصي">
                        <i class="fas fa-user-gear"></i>
                    </a>

                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm" title="تسجيل الخروج">
                            <i class="fas fa-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Alerts Container -->
            <div class="page-body">
                @if(session('success'))
                    <div class="card" style="background: #d1fae5; border-color: #10b981; color: #065f46; padding: 14px 20px; font-weight: 700; margin-bottom: 20px;">
                        <i class="fas fa-circle-check"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="card" style="background: #fee2e2; border-color: #ef4444; color: #991b1b; padding: 14px 20px; font-weight: 700; margin-bottom: 20px;">
                        <i class="fas fa-triangle-exclamation"></i> {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="card" style="background: #fee2e2; border-color: #ef4444; color: #991b1b; padding: 14px 20px; margin-bottom: 20px;">
                        <ul style="margin-right: 20px;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Mobile Navigation Bar -->
    <div class="mobile-nav">
        <a href="{{ route('dashboard') }}" class="mobile-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>الرئيسية</span>
        </a>
        <a href="{{ route('curriculum.index') }}" class="mobile-nav-item {{ request()->routeIs('curriculum.*') ? 'active' : '' }}">
            <i class="fas fa-book"></i>
            <span>الدروس</span>
        </a>
        <a href="{{ route('notifications.index') }}" class="mobile-nav-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
            <i class="fas fa-bell"></i>
            <span>الإشعارات</span>
        </a>
        <a href="{{ route('messages.index') }}" class="mobile-nav-item {{ request()->routeIs('messages.*') ? 'active' : '' }}">
            <i class="fas fa-comments"></i>
            <span>الرسائل</span>
        </a>
        <a href="{{ route('profile') }}" class="mobile-nav-item {{ request()->routeIs('profile') ? 'active' : '' }}">
            <i class="fas fa-user"></i>
            <span>حسابي</span>
        </a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('sidebarToggle');
            const closeBtn = document.getElementById('sidebarCloseBtn');
            const backdrop = document.getElementById('sidebarBackdrop');
            const sidebar = document.querySelector('.sidebar');

            function openSidebar() {
                if (sidebar) sidebar.classList.add('show');
                if (backdrop) backdrop.classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                if (sidebar) sidebar.classList.remove('show');
                if (backdrop) backdrop.classList.remove('show');
                document.body.style.overflow = '';
            }

            if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);
        });
    </script>

    @stack('scripts')
</body>
</html>
