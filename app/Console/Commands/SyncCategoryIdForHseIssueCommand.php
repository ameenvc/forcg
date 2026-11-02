<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncCategoryIdForHseIssueCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:category_id_for_hse_issue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync category_id for hse issue from category table to hseissues table.';

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
     * @return int
     */
    public function handle()
    {
        $hseIssues = \App\HseIssue::all();
        $hseCategories = \App\HseCategory::all();
        $this->info('Start sync category_id for hse issue...');
        $numOfEffectedRows = 0;
        $hseIssues->each(function($hseIssue) use ($hseCategories, &$numOfEffectedRows) {
            $category = $hseCategories->filter(function($category) use ($hseIssue) {
                return strtolower(str_replace([' ', '-'], '', $category->categoryname)) == strtolower(str_replace([' ', '-'], '', $hseIssue->category));
            })->first();
            if ($category) {
                $hseIssue->category_id = $category->id;
                $hseIssue->save();
                $numOfEffectedRows++;
            }
        });
        $this->info('Sync category_id for hse issue successfully. Total hse issues: ' . $hseIssues->count() . '. Total effected rows: ' . $numOfEffectedRows);
        return 0;
    }
}
