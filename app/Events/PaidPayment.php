<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PaidPayment implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    protected $responseReffNo;

    /**
     * Create a new event instance.
     */
    public function __construct(string $responseReffNo, array $data)
    {
        $this->responseReffNo = $responseReffNo;
        $this->data = $data;

        // Log::info("Event called");
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('paid.payment.5ucc355'),
            // new PrivateChannel('paid.payment.' . $this->responseReffId),
        ];
    }

    public function broadcastAs()
    {
        return 'paid.payment';
    }
}
