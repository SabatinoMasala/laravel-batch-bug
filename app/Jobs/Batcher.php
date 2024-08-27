<?php

namespace App\Jobs;

use Illuminate\Bus\ChainedBatch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Bus;

class Batcher implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // The type of this batch will be PendingBatch and will cause a SerializationError
        $this->prependToChain(
            Bus::batch([
                new \App\Jobs\HelloWorld(),
            ])
        );

        // To mitigate this, you can manually create a ChainedBatch from the PendingBatch as follows:
        $chainedBatch = new ChainedBatch(Bus::batch([
            new HelloWorld(),
        ]));
        $this->prependToChain($chainedBatch);
    }
}
