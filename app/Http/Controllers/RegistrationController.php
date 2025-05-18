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
        $validated['ip_address'] = $this->getClientIp($request);

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

    public function getClientIp(Request $request)
    {
        // Check for X-Forwarded-For header first
        foreach (array('HTTP_X_FORWARDED_FOR', 'X_FORWARDED_FOR') as $key) {
            if ($request->server->has($key)) {
                $ips = explode(',', $request->server->get($key));
                $ip = trim($ips[0]);

                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        // Then check for other common headers
        $headers = [
            'HTTP_CLIENT_IP',
            'HTTP_X_REAL_IP',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_TRUE_CLIENT_IP',   // Cloudflare Enterprise
            'REMOTE_ADDR',
        ];

        foreach ($headers as $header) {
            if ($request->server->has($header)) {
                $ip = $request->server->get($header);

                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return $request->ip(); // Fallback to Laravel's method
    }
}
