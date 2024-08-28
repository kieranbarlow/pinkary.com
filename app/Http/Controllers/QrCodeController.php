<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\QrCode;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

final readonly class QrCodeController
{
    /**
     * Generate a QR code for the user.
     */
    public function __invoke(Request $request): StreamedResponse
    {
        $user = type($request->user())->as(User::class);

        $qrCode = (new QrCode())->generate(
            route('profile.show', [
                'username' => $user->username,
            ])
        );

        return response()->streamDownload(
            function () use ($qrCode): void {
                /** @var string $qrCode */
                echo $qrCode;
            },
            'pinkary_'.$user->username.'.png',
            ['Content-Type' => 'image/png']
        );
    }
}
