<?php

namespace App\Console\Commands;

use App\Models\Paper;
use App\Models\PaperContent;
use Exception;
use Illuminate\Console\Command;
use Thanhnt\Nan\Helper\LogTha;

class ChangeSource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tha:changeSource {paper_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'coppy source from root paper to contents paper table.';

    /**
     * @var Paper $paper
     */
    protected $paper;

    /**
     * @var PaperContent $paperContent
     */
    protected $paperContent;

    /**
     * @var LogTha $logTha
     */
    protected $logTha;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        Paper $paper,
        PaperContent $paperContent,
        LogTha $logTha
    ) {
        $this->paper = $paper;
        $this->paperContent = $paperContent;
        $this->logTha = $logTha;
        parent::__construct();
    }

    protected function checkExist(int $paper_id) : bool {
        $paperConten = PaperContent::where('type','=', 'conten')->where('paper_id', '=', $paper_id)->select()->first();
        if ($paperConten && $paperConten->id) {
            return true;
        }
        return false;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $paper_id = $this->argument('paper_id');
            if ($this->checkExist($paper_id)) {
                $this->error('conten of the paper_id has exist!');
                return;
            }
            /**
             * @var Paper $paper
             */
            $paper = $this->paper->find($paper_id);
            if (!$paper) {
                throw new Exception("source of the paper_id not found!");
            }
            $this->paperContent->create([
                "type" => "conten",
                "key" => "conten",
                "value" => $paper->conten,
                "paper_id" => $paper_id,
                "depend_value" => null,
            ]);
            $this->logTha->logEvent('info', "coppied for paper of: $paper_id ($paper->title)");
            $this->info("coppied for paper of: $paper_id \n");
        } catch (\Throwable $th) {
            $this->error('-----> '.$th->getMessage());
            //throw $th;
        }
    }
}
