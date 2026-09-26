<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'election_title' => AppSetting::get('election_title', 'Pemilihan Pengurus Koperasi Mandiri Sejahtera 2026'),
            'institution_name' => AppSetting::get('institution_name', 'Koperasi Karyawan PT Semen Indonesia'),
            'quorum_percentage' => AppSetting::get('quorum_percentage', '50.0'),
            'voting_status' => AppSetting::get('voting_status', 'STARTED'),
            'voting_deadline' => AppSetting::get('voting_deadline', ''),
            'gemini_api_key' => AppSetting::get('gemini_api_key', ''),
            'gemini_model' => AppSetting::get('gemini_model', 'gemini-2.0-flash'),
            'enable_voice_greeting' => AppSetting::get('enable_voice_greeting', '1'),
            'enable_sound_fx' => AppSetting::get('enable_sound_fx', '1'),
            'public_sse_enabled' => AppSetting::get('public_sse_enabled', '1'),
            'show_candidate_nik' => AppSetting::get('show_candidate_nik', '0'),
            'enable_nfc_mobile' => AppSetting::get('enable_nfc_mobile', '1'),
            'enable_demo_accounts' => AppSetting::get('enable_demo_accounts', '1'),
            'throttle_click_ms' => AppSetting::get('throttle_click_ms', '800'),
            'kiosk_session_timeout' => AppSetting::get('kiosk_session_timeout', '60'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'election_title' => 'sometimes|required|string|max:255',
            'institution_name' => 'sometimes|required|string|max:255',
            'quorum_percentage' => 'sometimes|required|numeric|min:1|max:100',
            'voting_status' => 'sometimes|required|in:STARTED,PAUSED,STOPPED',
            'voting_deadline' => 'nullable|string|max:50',
            'gemini_api_key' => 'nullable|string|max:255',
            'gemini_model' => 'sometimes|required|string|in:gemini-2.0-flash,gemini-1.5-flash,gemini-1.5-pro',
            'throttle_click_ms' => 'sometimes|required|integer|min:200|max:5000',
            'kiosk_session_timeout' => 'sometimes|required|integer|min:10|max:600',
        ]);

        $booleanFields = [
            'enable_voice_greeting',
            'enable_sound_fx',
            'public_sse_enabled',
            'show_candidate_nik',
            'enable_nfc_mobile',
            'enable_demo_accounts',
        ];

        foreach ($booleanFields as $field) {
            if ($request->has($field)) {
                $validated[$field] = $request->boolean($field) ? '1' : '0';
            }
        }

        if ($request->has('voting_deadline')) {
            $validated['voting_deadline'] = (string) $request->input('voting_deadline', '');
        }

        foreach ($validated as $key => $val) {
            AppSetting::set($key, (string) ($val ?? ''));
        }

        ActivityLog::log(
            'SETTINGS_UPDATE',
            'ADMIN',
            'Konfigurasi sistem TapVote AI berhasil diperbarui otomatis oleh Administrator.'
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tersimpan otomatis',
                'saved_at' => now()->format('H:i:s') . ' WIB',
                'updated' => array_keys($validated),
            ]);
        }

        return redirect()->route('admin.settings')->with('success', 'Konfigurasi sistem berhasil disimpan dan diterapkan.');
    }

    public function testGemini(Request $request, \App\Services\AiReasoningService $aiService)
    {
        $key = $request->input('gemini_api_key') ?: AppSetting::get('gemini_api_key');
        $result = $aiService->testConnection($key);
        return response()->json($result);
    }
}
