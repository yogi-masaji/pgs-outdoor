<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ParkingController extends Controller
{
    public function scanWithAI(Request $request)
    {
        // Pastikan API Key aktif di Google AI Studio
        $apiKey = "-";

        // Gunakan gemini-2.0-flash untuk kemampuan vision terbaik
        $modelName = "gemini-2.0-flash";

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$apiKey}";

        try {
            $payload = $request->all();

            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->timeout(60)
                ->post($url, $payload);

            if ($response->failed()) {
                Log::error('Gemini API Error: ' . $response->body());
                return response::json([
                    'error' => 'Google API Error',
                    'message' => $response->json()['error']['message'] ?? 'Unknown Error'
                ], $response->status());
            }

            $data = $response->json();

            // Ambil teks hasil deteksi
            $textResult = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';

            // Bersihkan jika AI nakal memberikan tag markdown ```json
            $textResult = str_replace(['```json', '```'], '', $textResult);

            return response()->json(['result' => trim($textResult)]);
        } catch (\Exception $e) {
            Log::error('Server Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
