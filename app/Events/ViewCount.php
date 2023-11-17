<?php

namespace App\Events;

use App\Models\Category;
use App\Models\Paper;
use App\Models\ViewSource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Thanhnt\Nan\Helper\LogTha;

class ViewCount
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Paper|Category
     */
    public $source;

    protected $logTha;

    /**
     * Create a new event instance.
     *
     * @var Paper|Category
     * @return void
     */
    public function __construct($source)
    {
        $this->source = $source;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
