<?php

namespace Qihucms\Information\Commands;

use Illuminate\Console\Command;

class CheckMessageCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'information:checkMessage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check message.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // 删除一个月前的消息
        $count = \DB::table('information_messages')->where('status', 1)
            ->where('created_at', '<', now()->subMonths()->toDateTimeString())
            ->delete();

        $this->info('A total of ' . $count . ' items were deleted.');
    }
}
