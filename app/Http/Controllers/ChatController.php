<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use Prism\Prism\Facades\PrismServer;

final class ChatController extends Controller
{
    public function index(): Response
    {
        /** @var User $user */
        $user = Auth::user();

        return Inertia::render('Chat/Index', [
            'subscriptionEnabled' => $user->subscribed('Larasonic Pro ✨'),
            'systemPrompt' => view('prompts.system')->render(),
            'models' => PrismServer::prisms()->pluck('name'),
        ]);
    }
}
