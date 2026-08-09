/**
 * مدرسة الكتاب المقدس — Google Apps Script Backend
 * -------------------------------------------------------------
 * This script serves the web app UI and manages data storage inside
 * Google Sheets (or script cache/properties).
 */

function doGet(e) {
  var template = HtmlService.createTemplateFromFile('Index');
  return template.evaluate()
    .setTitle('مدرسة الكتاب المقدس | منصة التعليم الرقمي والإدارة')
    .addMetaTag('viewport', 'width=device-width, initial-scale=1.0')
    .setXFrameOptionsMode(HtmlService.XFrameOptionsMode.ALLOWALL);
}

/**
 * Helper function to include HTML files in Google Apps Script templates
 */
function include(filename) {
  return HtmlService.createHtmlOutputFromFile(filename).getContent();
}

/**
 * Get or create the Google Sheet database
 */
function getDatabase() {
  var props = PropertiesService.getScriptProperties();
  var sheetId = props.getProperty('SPREADSHEET_ID');
  var ss;
  
  if (sheetId) {
    try {
      ss = SpreadsheetApp.openById(sheetId);
    } catch (e) {
      ss = null;
    }
  }
  
  if (!ss) {
    try {
      ss = SpreadsheetApp.getActiveSpreadsheet();
    } catch (e) {
      ss = null;
    }
  }
  
  if (!ss) {
    ss = SpreadsheetApp.create('مدرسة الكتاب المقدس - قاعدة البيانات');
    props.setProperty('SPREADSHEET_ID', ss.getId());
  }
  
  return ss;
}

/**
 * Initialize all database tables (Sheets) with default data if empty
 */
function apiSetupSpreadsheet() {
  var ss = getDatabase();
  var sheets = {
    'Users': ['id', 'name', 'email', 'role', 'phone', 'gender'],
    'Students': ['id', 'name', 'code', 'stage', 'grade', 'class', 'servant_name', 'points'],
    'Servants': ['id', 'name', 'phone', 'class', 'email'],
    'Parents': ['id', 'name', 'phone', 'children_count'],
    'Curricula': ['id', 'title', 'unit', 'lesson_title', 'video_url', 'pdf_url', 'verses'],
    'Quizzes': ['id', 'title', 'subject', 'questions_count', 'duration_min'],
    'Attendance': ['id', 'date', 'student_id', 'student_name', 'status', 'servant_name'],
    'Points': ['id', 'student_id', 'student_name', 'points', 'reason', 'date'],
    'Verses': ['id', 'title', 'reference', 'text', 'category'],
    'News': ['id', 'title', 'content', 'category', 'date'],
    'Messages': ['id', 'sender', 'recipient', 'text', 'timestamp']
  };

  for (var sheetName in sheets) {
    var sheet = ss.getSheetByName(sheetName);
    if (!sheet) {
      sheet = ss.insertSheet(sheetName);
      sheet.appendRow(sheets[sheetName]);
    }
  }

  return {
    status: 'success',
    spreadsheetUrl: ss.getUrl(),
    message: 'تم إعداد قاعدة البيانات بنجاح في Google Sheets!'
  };
}

/**
 * Get Initial System Data for Client Application
 */
function apiGetInitialData() {
  var sampleData = getInitialMockData();
  return {
    status: 'success',
    data: sampleData
  };
}

/**
 * Save Student
 */
function apiSaveStudent(student) {
  var ss = getDatabase();
  var sheet = ss.getSheetByName('Students');
  if (sheet) {
    var id = student.id || 'STU-' + Math.floor(1000 + Math.random() * 9000);
    sheet.appendRow([
      id, student.name, student.code || ('ST-' + id), student.stage || 'المرحلة الإبتدائية',
      student.grade || 'الصف الرابع', student.className || 'فصل القديس أثناسيوس',
      student.servant || 'أ. مينا سامي', student.points || 0
    ]);
  }
  return { status: 'success', message: 'تم حفظ بيانات الطالب بنجاح' };
}

/**
 * Save Servant
 */
function apiSaveServant(servant) {
  var ss = getDatabase();
  var sheet = ss.getSheetByName('Servants');
  if (sheet) {
    var id = servant.id || 'SRV-' + Math.floor(1000 + Math.random() * 9000);
    sheet.appendRow([id, servant.name, servant.phone, servant.className, servant.email]);
  }
  return { status: 'success', message: 'تم حفظ بيانات الخادم بنجاح' };
}

/**
 * Record Attendance
 */
function apiSaveAttendance(records) {
  var ss = getDatabase();
  var sheet = ss.getSheetByName('Attendance');
  if (sheet && Array.isArray(records)) {
    var today = new Date().toISOString().split('T')[0];
    records.forEach(function(rec) {
      sheet.appendRow([
        'ATT-' + Date.now(), today, rec.student_id, rec.student_name, rec.status, rec.servant_name || 'الخادم'
      ]);
    });
  }
  return { status: 'success', message: 'تم تسجيل الحضور بنجاح' };
}

/**
 * Default initial mock structure if Google Sheet is fresh
 */
function getInitialMockData() {
  return {
    currentUser: {
      name: 'أ/ بطرس كميل',
      role: 'admin',
      email: 'admin@bibleschool.eg',
      avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80'
    },
    stats: {
      totalStudents: 142,
      totalServants: 18,
      activeQuizzes: 6,
      attendanceRate: '94%'
    },
    students: [
      { id: 'STU-101', name: 'بيشوي مينا عادل', code: 'ST-101', stage: 'المرحلة الإبتدائية', grade: 'الصف الرابع', className: 'فصل القديس أثناسيوس', servant: 'أ. مينا سامي', points: 180 },
      { id: 'STU-102', name: 'مارينا يوسف حنا', code: 'ST-102', stage: 'المرحلة الإبتدائية', grade: 'الصف الرابع', className: 'فصل العذراء مريم', servant: 'ت. سارة سمير', points: 210 },
      { id: 'STU-103', name: 'كيرلس صموئيل فرج', code: 'ST-103', stage: 'المرحلة الإعدادية', grade: 'الصف الأول', className: 'فصل القديس مارمرقس', servant: 'أ. باسيليوس ماهر', points: 145 },
      { id: 'STU-104', name: 'ساندرا مجدي رزق', code: 'ST-104', stage: 'المرحلة الإعدادية', grade: 'الصف الثاني', className: 'فصل القديسة دميانة', servant: 'ت. مريم نبيل', points: 260 },
      { id: 'STU-105', name: 'دانيال رفيق مكرم', code: 'ST-105', stage: 'المرحلة الثانوية', grade: 'الصف الثالث', className: 'فصل القديس بولس', servant: 'أ. يوحنا رفعت', points: 195 }
    ],
    servants: [
      { id: 'SRV-01', name: 'أ. مينا سامي', phone: '01223456789', className: 'فصل القديس أثناسيوس (رابع ابتدائي)', email: 'mina@bibleschool.eg' },
      { id: 'SRV-02', name: 'ت. سارة سمير', phone: '01012345678', className: 'فصل العذراء مريم (رابع ابتدائي)', email: 'sara@bibleschool.eg' },
      { id: 'SRV-03', name: 'أ. باسيليوس ماهر', phone: '01122334455', className: 'فصل القديس مارمرقس (أول إعدادي)', email: 'basil@bibleschool.eg' },
      { id: 'SRV-04', name: 'ت. مريم نبيل', phone: '01200112233', className: 'فصل القديسة دميانة (ثاني إعدادي)', email: 'mary@bibleschool.eg' }
    ],
    curricula: [
      {
        id: 'CUR-01',
        title: 'منهج العقيدة الأرثوذكسية',
        unit: 'الوحدة الأولى: قانون الإيمان',
        lesson_title: 'الدرس الأول: الإيمان بإله واحد ضابط الكل',
        video_url: 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        pdf_url: '#',
        verses: '«نُؤْمِنُ بِإِلهٍ وَاحِدٍ، اللهِ الآبِ ضَابِطِ الْكُلِّ»'
      },
      {
        id: 'CUR-02',
        title: 'تاريخ الكنيسة والقديسين',
        unit: 'الوحدة الثانية: عصر الشهداء',
        lesson_title: 'الدرس الثاني: سيرة القديس مارجرجس الروماني',
        video_url: 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        pdf_url: '#',
        verses: '«كُنْ أَمِينًا إِلَى الْمَوْتِ فَسَأُعْطِيكَ إِكْلِيلَ الْحَيَاةِ» (رؤ 2: 10)'
      },
      {
        id: 'CUR-03',
        title: 'دراسات في العهد الجديد',
        unit: 'الوحدة الأولى: إنجيل متى',
        lesson_title: 'الدرس الثالث: الموعظة على الجبل والطوبايات',
        video_url: 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        pdf_url: '#',
        verses: '«أَنْتُمْ نُورُ الْعَالَمِ. لاَ يُمْكِنُ أَنْ تُخْفَى مَدِينَةٌ مَوْضُوعَةٌ عَلَى جَبَلٍ» (مت 5: 14)'
      }
    ],
    quizzes: [
      { id: 'QZ-101', title: 'اختبار دراسات الكتاب المقدس - شهر أغسطس', subject: 'العهد الجديد', questions_count: 5, duration_min: 15, passScore: 80 },
      { id: 'QZ-102', title: 'مسابقة طقس الكنيسة والألحان', subject: 'الطقس القبطي', questions_count: 10, duration_min: 20, passScore: 70 },
      { id: 'QZ-103', title: 'تحدي حفظ الآيات الروحية الأسبوعي', subject: 'آيات الإنجيل', questions_count: 5, duration_min: 10, passScore: 90 }
    ],
    verses: [
      { id: 'V-01', title: 'الآية الأسبوعية 1', reference: 'مزمور 119: 105', text: '«سِرَاجٌ لِرِجْلِي كَلاَمُكَ وَنُورٌ لِسَبِيلِي»', category: 'العهد القديم' },
      { id: 'V-02', title: 'الآية الأسبوعية 2', reference: 'يوحنا 14: 6', text: '«أَنَا هُوَ الطَّرِيقُ وَالْحَقُّ وَالْحَيَاةُ. لَيْسَ أَحَدٌ يَأْتِي إِلَى الآبِ إِلاَّ بِي»', category: 'إنجيل يوحنا' },
      { id: 'V-03', title: 'الآية الأسبوعية 3', reference: 'فيلبي 4: 13', text: '«أَسْتَطِيعُ كُلَّ شَيْءٍ فِي الْمَسِيحِ الَّذِي يُقَوِّينِي»', category: 'رسائل بولس الرسول' }
    ],
    news: [
      { id: 'N-01', title: 'افتتاح مهرجان الكرازة المرقسية الصيفي', content: 'نعلن عن بدء فعاليات المهرجان الصيفي لجميع المراحل الدراسية يوم الجمعة القادم بعد القداس الالهي.', category: 'إعلان مهم', date: '2026-08-07' },
      { id: 'N-02', title: 'رحلة الكشافة والخدمة إلى دير القديس أنبا بيشوي', content: 'تسجيل الأسماء متاح الآن لدى خدام الفصول، عدد الأماكن محدود.', category: 'رحلات', date: '2026-08-05' }
    ]
  };
}
