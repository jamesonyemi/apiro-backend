<?php

namespace App\Console;

use App\Models\Post;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        $schedule->call( function() {

           $stale_posts = DB::table('posts')
            ->whereNotIn("id", DB::table('comments')->pluck("post_id") )
            ->where('created_at', '<=', Carbon::now()->subYears(1))
            ->get();

            foreach ($stale_posts as $key => $post) {
                Post::find($post->id)->delete();
            }

        })->everyFifteenSeconds();


    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}