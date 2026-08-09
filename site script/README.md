# دليل إعداد ونشر تطبيق مدرسة الكتاب المقدس عبر Google Apps Script 🚀

مرحباً بك في النسخة الخاصة بـ **Google Apps Script** لمنصة **مدرسة الكتاب المقدس ("Bible School Management Platform")**.

تم تصميم وتطوير هذه النسخة لتعمل مباشرة على منصة سحابة جبل جوجل (**Google Workspace**) وتتكامل تلقائياً مع **جداول بيانات جوجل (Google Sheets)** كقاعدة بيانات سحابية مجانية ودائمة.

---

## 📁 هيكل مجلد السكريبت (`site script`)

```text
site script/
├── Code.gs             # كود الخادم الرئيسي بلغة Google Apps Script (V8 Engine)
├── Index.html          # الهيكل والتصميم الرئيسي لصفحة Web App (RTL Arabic)
├── Styles.html         # نظام التصميم والألوان والنماذج المتقدمة (CSS3)
├── ClientScript.html   # محرك التفاعل والتنقل الفوري وتوصيل البيانات (JS Client SPA)
├── appsscript.json     # ملف الإعدادات والمانيفست الخاص بـ Google Script
└── README.md           # دليل التعليمات والتشغيل (هذا الملف)
```

---

## 🛠️ خطوات النشر والاستضافة على Google Apps Script

### الخطوة 1: إنشاء مشروع سكريبت جديد
1. اذهب إلى منصة **[Google Apps Script](https://script.google.com/)**.
2. انقر على **مشروع جديد (New Project)**.
3. قم بتسمية المشروع: `مدرسة الكتاب المقدس - Web App`.

### الخطوة 2: نسخ الملفات للمشروع
قم بإنشاء الملفات التالية داخل محرر Google Script ونقل الأكواد إليها من هذا المجلد:

1. **`Code.gs`**: انسخ محتوى [`Code.gs`](file:///d:/bible%20school/site%20script/Code.gs).
2. **`Index.html`**: أنشئ ملف HTML باسم `Index` وانسخ محتوى [`Index.html`](file:///d:/bible%20school/site%20script/Index.html).
3. **`Styles.html`**: أنشئ ملف HTML باسم `Styles` وانسخ محتوى [`Styles.html`](file:///d:/bible%20school/site%20script/Styles.html).
4. **`ClientScript.html`**: أنشئ ملف HTML باسم `ClientScript` وانسخ محتوى [`ClientScript.html`](file:///d:/bible%20school/site%20script/ClientScript.html).
5. **`appsscript.json`**: اضغط على إعدادات المشروع (⚙️) وفَعّل خيار *Show "appsscript.json" manifest file in editor* ثم انسخ الإعدادات.

---

## 📊 ربط وإعداد قاعدة البيانات (Google Sheets)

1. افتح تطبيق الـ Web App المنشور أو قم بتشغيل الدالة `apiSetupSpreadsheet()` في محرر Google Script.
2. سينشئ التطبيق تلقائياً شيت جديد باسم `مدرسة الكتاب المقدس - قاعدة البيانات` في حساب Google Drive الخاص بك.
3. يحتوي الشيت على 11 جدولاً منظماً للطلاب، الخدام، الحضور والغياب، المناهج، النقاط، والاختبارات.

---

## 🚀 نشر التطبيق كـ Web App

1. من أعلى شاشة المحرر، انقر على **نشر (Deploy) -> نشر جديد (New deployment)**.
2. اختر نوع النشر: **تطبيق ويب (Web app)**.
3. حدد الخيارات التالية:
   - **الوصف**: `الإصدار الأول - مدرسة الكتاب المقدس`.
   - **Execute as**: `Me (حسابك الشخصي/المؤسسي)`.
   - **Who has access**: `Anyone (أي شخص)` أو `Anyone with Google account`.
4. اضغط **نشر (Deploy)** ووافق على الصلاحيات Required Permissions.
5. انسخ رابط الـ **Web App URL** وشاركه مع الخدام والطلاب وأولياء الأمور!

---

## 💡 المميزات والخصائص

- **تعمل 100% مجاناً** بدون الحاجة لاستضافة مدفوعة أو سيرفرات خارجية.
- **واجهة كاملة باللغة العربية (RTL)** وتصميم متجاوب مع الهواتف الذكية والأجهزة اللوحية.
- **تحكم متكامل بالأدوار**: إمكانية استعراض النظام كـ (مسؤول نظام، خادم فصل، طالب، ولي أمر).
- **وضع تشغيل محلي مزدوج**: يعالج البيانات عبر Google Sheets عند النشر، ويدعم التجربة السريعة (LocalStorage) عند الفتح المباشر في المتصفح.
