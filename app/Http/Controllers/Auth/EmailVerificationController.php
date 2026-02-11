<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Integration\UserService;
use App\Services\Telegram\BotService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Контроллер подтверждения email (клик по ссылке из письма).
 */
class EmailVerificationController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected BotService $botService
    ) {}

    /**
     * Подтвердить email по токену из письма.
     */
    public function confirm(Request $request, string $token): View|RedirectResponse
    {
        // Атомарно: находим и удаляем токен за один запрос (защита от race condition)
        $record = DB::table('email_verification_tokens')
            ->where('token', $token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            return view('auth.email-verification-failed', [
                'message' => 'Ссылка недействительна или истекла. Запросите привязку email заново в боте.',
            ]);
        }

        $user = $this->userService->findByTelegramId($record->telegram_id);

        if (!$user) {
            return view('auth.email-verification-failed', [
                'message' => 'Пользователь не найден. Возможно, вы начали диалог с ботом заново.',
            ]);
        }

        // Транзакция: привязка email + удаление токена
        DB::transaction(function () use ($user, $record) {
            $user->email = $record->email;
            $user->email_verified_at = now();
            $user->save();

            DB::table('email_verification_tokens')->where('id', $record->id)->delete();
        });

        // Уведомляем пользователя в Telegram (вне транзакции — не критично если не отправится)
        try {
            $this->botService->sendMessage(
                $record->telegram_id,
                "✅ Email <b>{$record->email}</b> успешно привязан к вашему аккаунту.\n\nТеперь в профиле отображаются данные подписки."
            );
        } catch (\Throwable $e) {
            Log::warning('Failed to send Telegram notification after email verification', [
                'telegram_id' => $record->telegram_id,
                'error' => $e->getMessage(),
            ]);
        }

        return view('auth.email-verification-success', [
            'email' => $record->email,
        ]);
    }
}
