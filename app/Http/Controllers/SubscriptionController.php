<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Laravel\Cashier\Checkout;
use Laravel\Cashier\Subscription;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class SubscriptionController extends Controller
{
    /**
     * Redirect authenticated user to Stripe billing portal.
     */
    public function index(): RedirectResponse
    {
        if (! Config::get('cashier.billing_enabled')) {
            return redirect()->route('dashboard');
        }

        return type(Auth::user())->as(User::class)->redirectToBillingPortal(route('subscriptions.create'));
    }

    /**
     * Display subscription management page with active subscriptions and available plans.
     */
    public function create(Request $request): Response|RedirectResponse
    {
        if (! Config::get('cashier.billing_enabled')) {
            return redirect()->route('dashboard');
        }

        /** @var User $user */
        $user = $request->user();

        /** @var Collection<int, Subscription> $activeSubscriptions */
        $activeSubscriptions = Subscription::query()->where(['user_id' => $user->id])->active()->get();

        return Inertia::render('subscriptions/Index', [
            'activeSubscriptions' => $activeSubscriptions,
            'availableSubscriptions' => config('subscriptions.subscriptions'),
            'activeInvoices' => Inertia::defer(fn () => $user->invoices()),
        ]);
    }

    /**
     * Create Stripe Checkout session for selected subscription plan.
     *
     * @throws NotFoundHttpException If subscription plan not found
     */
    public function show(string $subscription): Checkout|RedirectResponse
    {
        if (! Config::get('cashier.billing_enabled')) {
            return redirect()->route('dashboard');
        }

        /** @var array<int, array<string, mixed>> $subscriptionConfig */
        $subscriptionConfig = Config::array('subscriptions.subscriptions');
        $subscriptions = collect($subscriptionConfig);

        abort_unless(
            in_array($subscription, $subscriptions->pluck('price_id')->toArray()),
            404
        );

        /** @var User $user */
        $user = request()->user();

        /** @var array<string, mixed>|null $subscriptionData */
        $subscriptionData = $subscriptions->firstWhere('price_id', $subscription);

        abort_if($subscriptionData === null, 404);

        $name = type(Arr::get($subscriptionData, 'plan'))->asString();

        return $user
            ->newSubscription($name, $subscription)
            ->checkout([
                'success_url' => route('subscriptions.index'),
                'cancel_url' => route('subscriptions.create'),
            ]);
    }
}
