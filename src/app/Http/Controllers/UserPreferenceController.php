<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserPreferenceController extends Controller
{
    public function updateTheme(Request $request)
    {
        $user = auth()->user();
        $preferences = $user->preferences ?? [];
        $preferences['theme'] = $request->theme; // 'light' or 'dark'

        $user->update(['preferences' => $preferences]);

        return response()->json(['success' => true]);
    }

    public function getPreferences()
    {
        $preferences = auth()->user()->preferences ?? ['theme' => 'light'];
        return response()->json($preferences);
    }
}
