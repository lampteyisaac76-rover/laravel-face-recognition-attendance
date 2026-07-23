<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VerificationCode;
use App\Mail\VerificationCodeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // ─────────────────────────────────────────────
    // SHOW PAGES
    // ─────────────────────────────────────────────

    public function showAdminLogin()
    {
        return view('auth.admin-login');
    }

    public function showLecturerLogin()
    {
        return view('auth.lecturer-login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function showAdminRegister()
    {
        return view('auth.admin-register');
    }

    // ─────────────────────────────────────────────
    // ADMIN SELF-REGISTRATION (master code gated)
    // ─────────────────────────────────────────────

    public function registerAdmin(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users',
            'staff_id'     => 'required|string|max:20|unique:users',
            'phone_number' => 'required|string|max:15',
            'password'     => 'required|string|min:8|confirmed',
            'admin_code'   => 'required|string',
        ]);

        if ($request->admin_code !== 'GCTU-ADMIN-2026') {
            return back()
                ->withErrors(['admin_code' => 'Invalid Master Admin Code.'])
                ->withInput();
        }

        User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'staff_id'     => $request->staff_id,
            'phone_number' => $request->phone_number,
            'role'         => 'admin',
            'is_verified'  => true,
            'password'     => Hash::make($request->password),
        ]);

        return redirect()->route('admin.login')
            ->with('success', 'Admin account created successfully. You can now log in.');
    }

    // ─────────────────────────────────────────────
    // LECTURER SELF-REGISTRATION (whitelist gated)
    // ─────────────────────────────────────────────

    public function register(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'staff_id' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Match email + staff_id against admin-created profile.
        // We check is_verified = false so retries always work
        // even if something failed mid-flow previously.
        $user = User::where('email', $request->email)
                    ->where('staff_id', $request->staff_id)
                    ->where('role', 'lecturer')
                    ->where('is_verified', false)
                    ->first();

        if (!$user) {
            return back()
                ->withErrors([
                    'email' => 'No matching registration found. Please confirm your '
                             . 'email and Staff ID with your administrator.',
                ])
                ->withInput();
        }

        // Store the hashed password in session temporarily.
        // We do NOT save it to the database yet.
        // It will only be committed after OTP is confirmed.
        session(['pending_password' => Hash::make($request->password)]);

        // Send OTP to lecturer's email
        return $this->sendOTP($user);
    }

    // ─────────────────────────────────────────────
    // OTP
    // ─────────────────────────────────────────────

    protected function sendOTP(User $user)
    {
        $code = rand(100000, 999999);

        VerificationCode::create([
            'user_id'    => $user->id,
            'email'      => $user->email,
            'phone'      => $user->phone_number,
            'code'       => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        Log::info("OTP generated for {$user->email} ({$user->staff_id}): {$code}");

        try {
            Mail::to($user->email)->send(new VerificationCodeMail($code));
            Log::info("OTP email successfully sent to {$user->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send OTP to {$user->email}: " . $e->getMessage());
        }

        return redirect()->route('verify.otp', ['staff_id' => $user->staff_id]);
    }

    public function resendOTP($staff_id)
    {
        $user = User::where('staff_id', $staff_id)->firstOrFail();

        // Invalidate all previous unused codes
        VerificationCode::where('user_id', $user->id)
                        ->where('is_used', false)
                        ->update(['is_used' => true]);

        return $this->sendOTP($user);
    }

    public function showVerifyOTP($staff_id)
    {
        return view('auth.verify-otp', compact('staff_id'));
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:users,staff_id',
            'code'     => 'required|digits:6',
        ]);

        $user = User::where('staff_id', $request->staff_id)->first();

        Log::info("OTP verification attempt — staff_id: {$request->staff_id}, code: {$request->code}");

        $verification = VerificationCode::where('user_id', $user->id)
            ->where('code', $request->code)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if ($verification) {
            // Mark OTP as used
            $verification->update(['is_used' => true]);

            // NOW commit the password and activate the account
            $user->update([
                'password'    => session('pending_password'),
                'is_verified' => true,
            ]);

            // Clear temporary password from session
            session()->forget('pending_password');

            Log::info("Account activated successfully for {$request->staff_id}");

            return redirect()->route('lecturer.login')
                ->with('success', 'Account activated successfully. You can now log in.');
        }

        Log::warning("OTP mismatch or expired for {$request->staff_id}");
        return back()->withErrors(['code' => 'Invalid or expired OTP. Please try again.']);
    }

    // ─────────────────────────────────────────────
    // LOGIN / LOGOUT
    // ─────────────────────────────────────────────

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'staff_id' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->is_verified) {
                Auth::logout();
                return redirect()
                    ->route('verify.otp', ['staff_id' => $user->staff_id])
                    ->with('error', 'Please verify your account before logging in.');
            }

            $request->session()->regenerate();

            return redirect()->intended(
                $user->role === 'admin' ? '/admin/dashboard' : '/lecturer/dashboard'
            );
        }

        return back()->withErrors([
            'staff_id' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}