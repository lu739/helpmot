<?php

namespace App\Jobs;

use App\Actions\OrderLocation\Create\CreateLastLocationAction;
use App\Actions\OrderLocation\Create\Dto\CreateOrderLocationDto;
use App\Actions\OrderLocation\Update\Dto\UpdateOrderLocationDto;
use App\Actions\OrderLocation\Update\UpdateLastLocationAction;
use App\Models\Order;
use App\Models\OrderLocation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SaveLastOrderLocationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private OrderLocation $orderLocation, private array $allData, private Order $order)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->orderLocation->exists()) {
            (new UpdateLastLocationAction())->handle((new UpdateOrderLocationDto())
                ->setId($this->orderLocation->id)
                ->setDatetime($this->allData['datetime'])
                ->setLastLocation(json_encode($this->allData['last_location']))
            );
        } else {
            $dto = (new CreateOrderLocationDto())
                ->setOrderId($this->allData['order_id'])
                ->setDriverId($this->allData['driver_id'])
                ->setDatetime($this->allData['datetime'])
                ->setLastLocation(json_encode($this->allData['last_location']))
                ->setStartLocation($this->order->location_start);

            (new CreateLastLocationAction())->handle($dto);
        }
    }
}
