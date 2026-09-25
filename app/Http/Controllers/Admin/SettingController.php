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
            'gemini_api_key' => AppSetting::get('gemini_api_key', ''),
            'gemini_model' => AppSetting::get('gemini_model', 'gemini-2.0-flash'),
            'enable_voice_greeting' => AppSetting::get('enable_voice_greeting', '1'),
            'enable_sound_fx' => AppSetting::get('enable_sound_fx', '1'),
            'public_sse_enabled' => AppSetting::get('public_sse_enabled', '1'),
            'kiosk_session_timeout' => AppSetting::get('kiosk_session_timeout', '60'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'election_title' => 'required|string|max:255',
            'institution_name' => 'required|string|max:255',
            'quorum_percentage' => 'required|numeric|min:1|max:100',
            'voting_status' => 'required|in:STARTED,PAUSED,STOPPED',
            'gemini_api_key' => 'nullable|string|max:255',
            'gemini_model' => 'required|string|in:gemini-2.0-flash,gemini-1.5-flash,gemini-1.5-pro',
            'kiosk_session_timeout' => 'required|integer|min:10|max:600',
        ]);

        $validated['enable_voice_greeting'] = $request->has('enable_voice_greeting') ? '1' : '0';
        $validated['enable_sound_fx'] = $request->has('enable_sound_fx') ? '1' : '0';
        $validated['public_sse_enabled'] = $request->has('public_sse_enabled') ? '1' : '0';

        foreach ($validated as $key => $val) {
            AppSetting::set($key, (string) ($val ?? '0'));
        }

        ActivityLog::log(
            'SETTINGS_UPDATE',
            'ADMIN',
            'Konfigurasi sistem TapVote AI berhasil diperbarui oleh Administrator.'
        );

        return redirect()->route('admin.settings')->with('success', 'Konfigurasi sistem berhasil disimpan dan diterapkan.');
    }
}
