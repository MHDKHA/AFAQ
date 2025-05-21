<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tool;
use App\Models\User;
use App\Models\UserRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        // Get the tool slug from the query string if available
        $toolSlug = $request->query('tool');

        // Get all active tools to pass to MainLayout
        $tools = Tool::where('is_active', true)->get();

        return Inertia::render('Registration/Form', [
            'toolSlug' => $toolSlug,
            'tools' => $tools,
            'locale' => app()->getLocale()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'company_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'tool_slug' => 'nullable|string|exists:tools,slug',
        ]);

        // Add device info
        $registrationData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'company_name' => $validated['company_name'],
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'country' => $validated['country'] ?? null,
            'device_info' => $request->header('User-Agent') ?? 'Unknown',
            'user_agent' => $request->header('User-Agent') ?? 'Unknown',
            'ip_address' => $this->getClientIp($request),
        ];

        // Create user registration
        $registration = UserRegistration::create($registrationData);

        // Create actual user account
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // If tool slug provided, assign the corresponding role to the user
        if (!empty($validated['tool_slug'])) {
            $toolSlug = $validated['tool_slug'];

            // Get or create role based on tool slug
            $role = Role::firstOrCreate(['name' => $toolSlug]);

            // Assign role to user
            $user->assignRole($role);
        }

        // Log the user in
        auth()->login($user);

        // Redirect based on whether a tool was specified
        if (!empty($validated['tool_slug'])) {
            return redirect()->route('tools.show', $validated['tool_slug']);
        }

        // Default redirect to tools index
        return redirect()->route('tools.index');
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
