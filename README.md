### Laravel Version

https://github.com/laravel/framework/issues/52468

11.20.0

### Description

When creating and dispatching a chain with a nested batch, the type of the batch will be a `ChainedBatch`
```
\Illuminate\Support\Facades\Bus::chain([
    Bus::batch([
        new \App\Jobs\HelloWorld(),
    ])
])->dispatch();
```

However, when appending or prepending a batch to a chain, the type will be a `PendingBatch`, leading to serialization errors.

```
\Illuminate\Support\Facades\Bus::chain([
    new \App\Jobs\Batcher(),
])->dispatch();

// App\Jobs\Batcher()

<?php

namespace App\Jobs;

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
        // The type of this batch will be PendingBatch
        $this->prependToChain(
            Bus::batch([
                new \App\Jobs\HelloWorld(),
            ])
        );
    }
}
```

To mitigate this, you can manually create a ChainedBatch from the PendingBatch as follows:

```
$chainedBatch = new ChainedBatch(Bus::batch([
    new HelloWorld(),
]));
$this->prependToChain($chainedBatch);
```

And this will make sure the batch gets queued onto the chain correctly.

### Steps To Reproduce

Dispatching a batch onto a chain from within a job using `prependToChain` or `appendToChain` will result into a closure serialization error.
