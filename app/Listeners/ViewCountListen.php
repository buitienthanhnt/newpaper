<?php

namespace App\Listeners;

use App\Events\ViewCount;
use App\Models\ViewSource;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Thanhnt\Nan\Helper\LogTha;

class ViewCountListen implements ShouldQueue
{
    /**
     * @var ViewSource
     */
    protected $viewSource;

    /**
     * @var LogTha
     */
    protected $logTha;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ViewSource $viewSource, LogTha $logTha)
    {
        $this->viewSource = $viewSource;
        $this->logTha = $logTha;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ViewCount  $event
     * @return void
     */
    public function handle(ViewCount $event)
    {
        $type = null;
        $source = $event->source;
        if ($source instanceof \App\Models\Paper) {
            $type = ViewSource::PAPER_TYPE;
        }

        if ($source instanceof \App\Models\Category) {
            $type = ViewSource::CATEGORY_TYPE;
        }
        try {
            $data_source = $this->viewSource->firstOrCreate(["type" => $type, "source_id" => $source->id], ["value" => 1]);
            $data_source->value = $data_source->value + 1;
            $data_source->save(); // save new source value.
            $this->logTha->logViewCount('info', "update source for type: $type with id: {id}, count: {count}", ['id' => $data_source->source_id, 'count' => $data_source->value]);
            return $event;
        } catch (\Throwable $th) {
            $this->logTha->logError('warning', "add count source error: ".$th->getMessage());
        }
    }
}
