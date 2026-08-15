# 🚀 دليل رفع منصة مدرسة الكتاب المقدس (Laravel 10 + PHP 8.1) على استضافة InfinityFree

يقدم هذا الدليل خطوات مفصلة وسهلة لنشر **منصة مدرسة القديس تيموثاوس للكتاب المقدس** المتوافقة مع **Laravel 10 و PHP 8.1** على استضافة **InfinityFree المجانية**.

---

## 📋 الخطوة 1: إنشاء قاعدة البيانات في لوحة تحكم InfinityFree

1. سجّل الدخول إلى لوحة التحكم **vPanel** في InfinityFree.
2. انتقل إلى قسم **MySQL Databases** ثم اضغط **Create Database**.
3. اكتب اسماً لقاعدة البيانات (مثلاً: `bible_school`).
4. احفظ البيانات التي ستظهر لك:
   - **MySQL Host Name** (مثال: `sql200.infinityfree.com` أو `sql302.epizy.com` - *ليس localhost*).
   - **Database Name** (مثال: `epiz_12345678_bible_school`).
   - **Database Username** (مثال: `epiz_12345678`).
   - **Database Password** (نفس كلمة سر حساب InfinityFree الرئيسي).

---

## 🗄️ الخطوة 2: استيراد بيانات قاعدة البيانات (phpMyAdmin)

1. من نفس صفحة **MySQL Databases** في لوحة التحكم، اضغط على زر **phpMyAdmin** بجانب قاعدة البيانات التي أنشأتها.
2. من القائمة الجانبية، اختر قاعدة البيانات `epiz_XXXXXX_bible_school`.
3. اضغط على تبويب **Import** (استيراد) من الأعلى.
4. اضغط على **Choose File** واختر الملف الجاهز: `bible_school_dump.sql` الموجود في المجلد الرئيسي للمشروع.
5. انزل لأسفل الصفحة واضغط **Go**. ستظهر رسالة خضراء تفيد بنجاح استيراد كافة الجداول والبيانات التجريبية والحسابات.

---

## ⚙️ الخطوة 3: تجهيز ملف `.env`

افتح ملف `.env` في جهازك وتأكد من كتابة القيم الحقيقية لحساب الاستضافة:

```env
APP_NAME="مدرسة القديس تيموثاوس للكتاب المقدس"
APP_ENV=production
APP_KEY=base64:N8/ZJv2OqDk5q2gL8W6d/H3V3X7g2Y4z5A6B7C8D9E0=
APP_DEBUG=false
APP_URL=http://YOUR_SUBDOMAIN.infinityfreeapp.com

APP_LOCALE=ar
APP_FALLBACK_LOCALE=ar
APP_FAKER_LOCALE=ar_SA

DB_CONNECTION=mysql
DB_HOST=sqlXXX.infinityfree.com
DB_PORT=3306
DB_DATABASE=epiz_XXXXXX_bible_school
DB_USERNAME=epiz_XXXXXX
DB_PASSWORD=YOUR_ACTUAL_DB_PASSWORD

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=local
```

---

## 📁 الخطوة 4: ملف `.htaccess` في المجلد الرئيسي

تأكد من وجود ملف `.htaccess` في المجلد الرئيسي للمشروع (بجانب مجلدات `app` و `vendor` و `public`):

```apache
RewriteEngine On

# تحويل روابط التخزين والصور المرفوعة تلقائياً
RewriteRule ^storage/(.*)$ /storage/app/public/$1 [END]

# تحويل كل الطلبات الأخرى إلى مجلد public
RewriteRule (.*) /public/$1 [L]
```

---

## 📤 الخطوة 5: رفع ملفات المشروع عبر FileZilla / File Manager

ارفع **جميع ملفات ومجلدات المشروع** مباشرة داخل مجلد `htdocs/` على الاستضافة، بحيث يكون الهيكل داخل `htdocs/`:

```text
htdocs/
├── .htaccess            <-- (ملف التحويل الرئيسي)
├── .env                 <-- (ملف الإعدادات)
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
│   ├── .htaccess        <-- (ملف توجيه لارافيل الداخلي)
│   ├── index.php        <-- (نقطة الدخول الرئيسية)
│   ├── css/
│   │   └── app.css      <-- (ملفات التصميم الجاهزة)
│   ├── images/
│   └── favicon.ico
├── resources/
├── routes/
├── storage/
│   ├── app/
│   │   └── public/      <-- (الصور والملفات المرفوعة)
│   ├── framework/
│   │   ├── cache/
│   │   ├── sessions/
│   │   └── views/
│   └── logs/
└── vendor/              <-- (حزم لارافيل 10 الجاهزة)
```

> [!CAUTION]
> **تنبيهات مهمة أثناء الرفع:**
> 1. **لا ترفع** مجلد `node_modules`.
> 2. تأكد من ظهور ورفع الملفات المخفية مثل `.env` و `.htaccess`.
> 3. تأكد أن المجلدات داخل `storage` و `bootstrap/cache` تملك تصريح الكتابة (Permissions 755 أو 777 إذا لزم الأمر).

---

## 🎉 الخطوة 6: تجربة الموقع

افتح رابط موقعك في المتصفح:
`http://YOUR_SUBDOMAIN.infinityfreeapp.com`

---

## 🔑 بيانات الحسابات الافتراضية للتجربة (Seed Accounts):

- **حساب الإدارة (Admin):**
  - البريد: `admin@school.com`
  - كلمة المرور: `password`
- **حساب خادم (Servant):**
  - البريد: `servant@school.com`
  - كلمة المرور: `password`
- **حساب طالب (Student):**
  - البريد: `student@school.com`
  - كلمة المرور: `password`
- **حساب ولي أمر (Parent):**
  - البريد: `parent@school.com`
  - كلمة المرور: `password`
