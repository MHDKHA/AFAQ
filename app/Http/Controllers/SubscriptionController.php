<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class SubscriptionController extends Controller
{
    public function showOptions()
    {
        return Inertia::render('Subscription/Options');
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'plan' => 'required|in:monthly,annual',
        ]);

        // This would be replaced with your actual payment processing logic
        // For now, we'll just assign the premium role

        $user = $request->user();
        $user->assignRole('premium');

        // Redirect to Filament dashboard
        return redirect('/afaq')
            ->with('success', 'Subscription successful! You now have full access to all reports.');
    }
}
