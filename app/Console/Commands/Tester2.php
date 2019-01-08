<?php

namespace App\Console\Commands;

use App\Modules\Catalog\Models\Catalog;
use Illuminate\Console\Command;

class Tester2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tester2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $catalog = Catalog::with([
            'folders:id,catalog_id,name',
            'products:id,catalog_id,name',
            'products.folders:id,name',
        ])->first();

        var_dump($catalog->toArray());

    }
}
