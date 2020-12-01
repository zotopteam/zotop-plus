<?php

namespace Modules\Content\Console;

use Illuminate\Console\Command;
use Modules\Content\Models\Content;

class PublishFutureCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:publish-future';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish the future content!';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Content::where('status', 'future')->where('publish_at', '<', now())->update([
            'status' => 'publish',
        ]);

        $this->info('Publish the future content success!');
    }
}
