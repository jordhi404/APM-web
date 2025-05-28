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

class cardPayment implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    protected $responseTrxId;

    /**
     * Create a new event instance.
     */
    public function __construct(string $responseTrxId, array $data)
    {
        $this->responseTrxId = $responseTrxId;
        $this->data = $data;

        Log::info("Card Payment Event called");
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('card.payment.Y4Y'),
        ];
    }

    public function broadcastAs()
    {
        return 'card.payment';
    }
}
