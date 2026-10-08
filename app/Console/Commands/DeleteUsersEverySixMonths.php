<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\DB;

class DeleteUsersEverySixMonths extends Command
{
    protected $signature = 'users:delete-6-months';
    protected $description = 'Hapus user yang terdaftar lebih dari 6 bulan (kecuali admin) dan image Cloudinary-nya';
    private function extractCloudinaryPublicId(?string $url): string
    {
        if (!$url) {
            return '';
        }

        $path = parse_url($url, PHP_URL_PATH);

        if (!$path) {
            return '';
        }
        $path = preg_replace('#^/.*/upload/v\d+/#', '', $path);
        $path = ltrim($path, '/');
        return preg_replace('/\.[^.]+$/', '', $path);
    }

    public function handle()
    {
        $sixMonthsAgo = now()->subMonths(6);
        $totalDeleted = 0;

        User::where('created_at', '<=', $sixMonthsAgo)
            ->whereDoesntHave('roles', function ($q) {
                $q->whereIn('name', ['admin', 'super_admin']);
            })
            ->with('detailUser')
            ->chunk(100, function ($users) use (&$totalDeleted) {

                DB::beginTransaction();

                try {
                    foreach ($users as $user) {
                        if ($user->detailUser && $user->detailUser->image) {

                            $publicId = $this->extractCloudinaryPublicId(
                                $user->detailUser->image
                            );

                            if ($publicId) {
                                try {
                                    Cloudinary::destroy($publicId, [
                                        'resource_type' => 'image',
                                        'invalidate' => true
                                    ]);
                                } catch (\Throwable $e) {
                                    Log::error('Cloudinary delete failed', [
                                        'user_id' => $user->id,
                                        'public_id' => $publicId,
                                        'error' => $e->getMessage()
                                    ]);
                                }
                            }
                        }

                        optional($user->detailUser)->delete();
                        $user->delete();

                        $totalDeleted++;
                    }

                    DB::commit();

                } catch (\Throwable $e) {
                    DB::rollBack();

                    Log::error('Delete user chunk failed', [
                        'error' => $e->getMessage()
                    ]);
                }
            });

        $this->info("Berhasil menghapus {$totalDeleted} user (beserta image Cloudinary)");

        Log::info('Delete users every 6 months finished', [
            'total_deleted' => $totalDeleted
        ]);
    }
}
