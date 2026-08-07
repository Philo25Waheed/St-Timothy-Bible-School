<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل حساب جديد - مدرسة الكتاب المقدس</title>

    <!-- Google Fonts Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Design System App CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 15px;
        }
        .register-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xl);
            width: 100%;
            max-width: 800px;
            padding: 40px;
        }
        .role-option-card {
            border: 2px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 16px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .role-option-card:hover, .role-option-card.active {
            border-color: var(--accent);
            background-color: #fffbeb;
            color: #92400e;
        }
        .role-option-card i {
            font-size: 24px;
            margin-bottom: 8px;
            display: block;
        }
    </style>
</head>
<body>

<div class="register-card">
    <div style="text-align: center; margin-bottom: 30px;">
        <a href="/" style="font-size: 32px; color: var(--accent);"><i class="fas fa-bible"></i></a>
        <h1 style="font-size: 24px; font-weight: 800; color: var(--primary-dark); margin-top: 10px;">إنشاء حساب جديد بالمنصة</h1>
        <p style="color: var(--text-muted); font-size: 14px;">يرجى ملء البيانات المطلوبة لتقديم طلب إنشاء الحساب</p>
    </div>

    @if($errors->any())
        <div style="background-color: #fef2f2; color: #991b1b; border-right: 4px solid #ef4444; padding: 12px 16px; border-radius: var(--radius-sm); margin-bottom: 20px; font-size: 13px;">
            <ul style="margin: 0; padding-right: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST" id="registerForm">
        @csrf

        <!-- Role Selection -->
        <label class="form-label" style="font-size: 15px; font-weight: 800;">اختر نوع الحساب (الصفة)</label>
        <div class="grid grid-cols-4" style="margin-bottom: 24px;">
            <div class="role-option-card active" onclick="selectRole('student')" id="role-card-student">
                <i class="fas fa-graduation-cap" style="color: var(--accent);"></i>
                <div style="font-weight: 800; font-size: 14px;">طالب</div>
            </div>
            <div class="role-option-card" onclick="selectRole('parent')" id="role-card-parent">
                <i class="fas fa-users-between-lines" style="color: var(--primary);"></i>
                <div style="font-weight: 800; font-size: 14px;">ولي أمر</div>
            </div>
            <div class="role-option-card" onclick="selectRole('servant')" id="role-card-servant">
                <i class="fas fa-church" style="color: var(--success);"></i>
                <div style="font-weight: 800; font-size: 14px;">خادم</div>
            </div>
            <div class="role-option-card" onclick="selectRole('admin')" id="role-card-admin">
                <i class="fas fa-user-shield" style="color: #8b5cf6;"></i>
                <div style="font-weight: 800; font-size: 14px;">مسؤول (إدارة)</div>
            </div>
        </div>
        <input type="hidden" name="role" id="selectedRole" value="student">

        <!-- Personal Info -->
        <div class="grid grid-cols-2">
            <div class="form-group">
                <label class="form-label">الاسم بالكامل</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="أدخل الاسم الثلاثي أو الرباعي" required>
            </div>
            <div class="form-group">
                <label class="form-label">البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="example@domain.com" required>
            </div>
        </div>

        <div class="grid grid-cols-2">
            <div class="form-group">
                <label class="form-label">كلمة المرور</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <div class="form-group">
                <label class="form-label">تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
            </div>
        </div>

        <div class="grid grid-cols-3">
            <div class="form-group">
                <label class="form-label">النوع</label>
                <select name="gender" class="form-control" required>
                    <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>ذكر</option>
                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>أنثى</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">تاريخ الميلاد</label>
                <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">رقم الهاتف / التليفون</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="012xxxxxxxx" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">العنوان التفصيلي</label>
            <input type="text" name="address" class="form-control" value="{{ old('address') }}" placeholder="المدينة، الشارع، رقم المبنى" required>
        </div>

        <!-- Student Specific Section -->
        <div id="student-section" class="form-section">
            <div class="grid grid-cols-2">
                <div class="form-group">
                    <label class="form-label">المرحلة الدراسية</label>
                    <select name="stage_id" class="form-control">
                        @foreach($stages as $stg)
                            <option value="{{ $stg->id }}">{{ $stg->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">الفصل الدراسي الملتحق به</label>
                    <select name="class_id" class="form-control">
                        @foreach($classes as $cls)
                            <option value="{{ $cls->id }}">{{ $cls->name }} ({{ $cls->grade->name ?? '' }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Parent Specific Section: Children Info -->
        <div id="parent-section" class="form-section" style="display: none; background: #f8fafc; border: 1px dashed var(--primary-light); padding: 20px; border-radius: var(--radius-md); margin-bottom: 24px;">
            <h3 style="font-size: 15px; font-weight: 800; color: var(--primary-dark); margin-bottom: 12px;">
                <i class="fas fa-children" style="color: var(--accent);"></i> بيانات الأبناء الملتحقين بالمدرسة
            </h3>
            <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 16px;">أدخل أسماء أبنائك وفصولهم لربط حساباتهم بحسابك بعد اعتماد الإدارة:</p>
            
            <div id="children-container">
                <div class="grid grid-cols-2 child-row" style="margin-bottom: 12px;">
                    <div>
                        <input type="text" name="child_name[]" class="form-control" placeholder="اسم الابن / الابنة">
                    </div>
                    <div>
                        <select name="child_class_id[]" class="form-control">
                            <option value="">اختر الفصل الدراسي...</option>
                            @foreach($classes as $cls)
                                <option value="{{ $cls->id }}">{{ $cls->name }} ({{ $cls->grade->name ?? '' }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-outline btn-sm" onclick="addChildRow()"><i class="fas fa-plus"></i> إضافة ابن آخر +</button>
        </div>

        <!-- Information Alert -->
        <div style="background-color: #f0fdf4; color: #166534; border-right: 4px solid #22c55e; padding: 14px; border-radius: var(--radius-sm); margin-bottom: 24px; font-size: 13px; line-height: 1.6;">
            <i class="fas fa-circle-info"></i> <strong>ملاحظة هامة:</strong> القبول غير فوري. سيتم تفعيل حسابك فور مراجعة واعتماد البيانات من قِبل إدارة مدرسة الكتاب المقدس، وسيتم إخطارك بالقبول عبر البريد الإلكتروني.
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 14px; font-size: 16px;"><i class="fas fa-paper-plane"></i> تقديم طلب التسجيل</button>

        <div style="text-align: center; margin-top: 20px; font-size: 14px;">
            لديك حساب بالفعل؟ <a href="{{ route('login') }}" style="color: var(--accent); font-weight: 700;">تسجيل الدخول</a>
        </div>
    </form>
</div>

<script>
    function selectRole(role) {
        document.getElementById('selectedRole').value = role;
        document.querySelectorAll('.role-option-card').forEach(card => card.classList.remove('active'));
        document.getElementById('role-card-' + role).classList.add('active');

        // Toggle sections
        document.getElementById('student-section').style.display = (role === 'student') ? 'block' : 'none';
        document.getElementById('parent-section').style.display = (role === 'parent') ? 'block' : 'none';
    }

    function addChildRow() {
        const container = document.getElementById('children-container');
        const firstRow = container.querySelector('.child-row');
        const newRow = firstRow.cloneNode(true);
        newRow.querySelector('input').value = '';
        newRow.querySelector('select').selectedIndex = 0;
        container.appendChild(newRow);
    }
</script>

</body>
</html>
