<?php

namespace App\Http\Controllers;

use App\Models\DetailUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function form()
    {
        if (empty(Auth::user()->email_verified_at)) {
            return redirect()->route('user.tests')->with('error', 'Session expired. Please login again.');
        }
        $profile = DetailUser::where('user_id', Auth::id())->first();

        return view('profile', compact('profile'));
    }

    private function extractCloudinaryPublicId(?string $url): string
    {
        if (!$url) {
            return '';
        }

        $path = parse_url($url, PHP_URL_PATH);
        $path = preg_replace('#^/.*/upload/v[0-9]+/#', '', $path);

        return pathinfo($path, PATHINFO_FILENAME)
            ? dirname($path) . '/' . pathinfo($path, PATHINFO_FILENAME)
            : '';
    }

    public function save(Request $request)
    {
        $profile = DetailUser::where('user_id', Auth::id())->first();

        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg',
            'name' => 'required|string|max:255',
            'no_id' => 'required|integer|unique:detail_users,no_id,' . optional($profile)->id,
            'birth_date' => [
                'required',
                'date',
                'before_or_equal:' . Carbon::now()->subYears(18)->format('Y-m-d'),
            ],
            'gender' => 'nullable|in:male,female',
            'religion' => 'required|string|max:50',
            'citizenship' => 'nullable|in:WNI,WNA',
            'fathers_name' => 'required|string|max:255',
            'fathers_occupation' => 'nullable|string|max:255',
            'mothers_name' => 'required|string|max:255',
            'mothers_occupation' => 'nullable|string|max:255',
            'school' => 'required|string|max:50',
            'gpa' => 'nullable|string|max:10',
            'phone_number' => 'nullable|string|max:15',
            'level' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'regency' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
        ], [
            'birth_date.before_or_equal' => 'Umur minimal 18 tahun.',
        ]);

        try {
            if ($request->hasFile('image')) {

                if ($profile && $profile->image) {
                    $publicId = $this->extractCloudinaryPublicId($profile->image);

                    if ($publicId) {
                        Cloudinary::destroy($publicId, [
                            'resource_type' => 'image',
                            'invalidate' => true
                        ]);
                    }
                }

                $uploaded = cloudinary()->upload(
                    $request->file('image')->getRealPath(),
                    [
                        'folder' => 'profiles',
                        'resource_type' => 'image',
                        'transformation' => [
                            'width' => 400,
                            'height' => 400,
                            'crop' => 'fit',
                            'quality' => 'auto',
                            'fetch_format' => 'auto',
                        ]
                    ]
                );

                $validated['image'] = $uploaded->getSecurePath();
            }

            $profile
                ? $profile->update($validated)
                : DetailUser::create(array_merge(
                    ['user_id' => Auth::id()],
                    $validated
                ));

            return redirect()
                ->route('user.tests')
                ->with('success', 'Profile berhasil disimpan.');

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function provinces(Request $request)
    {
        try {
            $response = Http::timeout(10)->get('https://wilayah.id/api/provinces.json');
            if ($response->successful()) {
                return response()->json($response->json(), 200);
            }
            return response()->json(['message' => 'Failed to fetch provinces'], $response->status());
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch provinces'], 500);
        }
    }

    public function regencies($provinceCode)
    {
        try {
            $response = Http::timeout(10)->get("https://wilayah.id/api/regencies/{$provinceCode}.json");
            if ($response->successful()) {
                return response()->json($response->json(), 200);
            }
            return response()->json(['message' => 'Failed to fetch regencies'], $response->status());
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch regencies'], 500);
        }
    }

    public function districts($regencyCode)
    {
        try {
            $response = Http::timeout(10)->get("https://wilayah.id/api/districts/{$regencyCode}.json");
            if ($response->successful()) {
                return response()->json($response->json(), 200);
            }
            return response()->json(['message' => 'Failed to fetch districts'], $response->status());
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch districts'], 500);
        }
    }

    public function villages($districtCode)
    {
        try {
            $response = Http::timeout(10)->get("https://wilayah.id/api/villages/{$districtCode}.json");
            if ($response->successful()) {
                return response()->json($response->json(), 200);
            }
            return response()->json(['message' => 'Failed to fetch villages'], $response->status());
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch villages'], 500);
        }
    }
}
