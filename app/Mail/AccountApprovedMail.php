<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('🎉 تم اعتماد وتفعيل حسابك في مدرسة الكتاب المقدس')
                    ->html('
                        <div style="direction: rtl; text-align: right; font-family: Cairo, sans-serif; padding: 20px; background-color: #f8fafc; border-radius: 8px;">
                            <h2 style="color: #0f172a;">مرحباً بك يا ' . e($this->user->name) . ' 👋</h2>
                            <p style="font-size: 16px; color: #334155;">سعداء بإعلامك أنه قد تم <strong>اعتماد وتفعيل حسابك بنجاح</strong> من قِبل إدارة مدرسة الكتاب المقدس.</p>
                            <div style="background-color: #ffffff; padding: 15px; border-right: 4px solid #f59e0b; margin: 20px 0; border-radius: 6px;">
                                <p style="margin: 0; font-size: 14px;"><strong>بيانات التسجيل:</strong></p>
                                <p style="margin: 5px 0;">البريد الإلكتروني: <code>' . e($this->user->email) . '</code></p>
                                <p style="margin: 5px 0;">الصفة / الدور: ' . e($this->user->role) . '</p>
                            </div>
                            <p>يمكنك الآن تسجيل الدخول مباشرة إلى منصتك ومتابعة جميع الأنشطة والدروس:</p>
                            <a href="' . route('login') . '" style="display: inline-block; background-color: #1e3a8a; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold;">تسجيل الدخول للمنصة</a>
                        </div>
                    ');
    }
}
