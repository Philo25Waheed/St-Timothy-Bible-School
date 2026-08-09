<?php

namespace App\Services;

class WhatsAppNotificationService
{
    /**
     * Generate direct WhatsApp web/app link for parent notifications
     */
    public static function generateLink(?string $phone, string $message): string
    {
        if (!$phone) {
            return '#';
        }

        // Clean phone number (remove spaces, hyphens, plus)
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($cleanPhone, '01')) {
            $cleanPhone = '2' . $cleanPhone; // Default to Egypt code if local mobile format
        }

        return 'https://wa.me/' . $cleanPhone . '?text=' . urlencode($message);
    }

    public static function buildAbsenceMessage(string $parentName, string $studentName, string $className, string $date): string
    {
        return "سلام ونعمة أ/ {$parentName} 🌹\nنود إحاطتكم علماً بغياب ابننا العزيز ({$studentName}) اليوم الموافق {$date} عن حصة التربية الدينية بـ {$className}.\nنصلّي لأجله وننتظر مشاركته معنا في الحصة القادمة! 🙏";
    }

    public static function buildBadgeMessage(string $parentName, string $studentName, string $badgeTitle, string $badgeDesc): string
    {
        return "تهانينا أ/ {$parentName} 🎉🌟!\nنعلمكم بحصول بطلنا ({$studentName}) اليوم على وسام جديد بمدرسة القديس تيموثاوس للكتاب المقدس:\n🏅 *{$badgeTitle}*\n{$badgeDesc}\nربنا يبارك حياته ونموّه الروحي! 🙌";
    }

    public static function buildExamMessage(string $parentName, string $studentName, string $examTitle, string $date): string
    {
        return "تذكير أ/ {$parentName} 📚\nيرجى العلم بأنه قد تم إدراج اختبار جديد للطالب ({$studentName}) بعنوان:\n📖 *{$examTitle}*\nالموعد: {$date}\nمع تمنياتنا له بالنجاح والتوفيق! ✨";
    }
}
