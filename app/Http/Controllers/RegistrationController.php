<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\UserRegistration;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RegistrationController extends Controller
{
    public function index()
    {
        return Inertia::render('Registration/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'company_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        // Add device info
        $validated['device_info'] = $request->header('User-Agent') ?? 'Unknown';
        $validated['user_agent'] = $request->header('User-Agent') ?? 'Unknown';
        $validated['ip_address'] = $request->ip();

        // Create registration
        $registration = UserRegistration::create($validated);

        // Get all criteria grouped by category
        $criteria = Category::with(['criteria' => function ($query) {
            $query->orderBy('order');
        }])
            ->get()
            ->mapWithKeys(function($category) {
                // Modify the data structure to make it more React-friendly
                return [$category->name => $category->criteria->toArray()];
            })
            ->toArray();

        // Redirect to the assessment page with the registration and criteria data
        return Inertia::render('Registration/Assessment', [
            'registration' => $registration,
            'criteria' => $criteria,
        ]);
    }
}
