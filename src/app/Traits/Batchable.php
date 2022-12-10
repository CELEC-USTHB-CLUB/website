<?php

namespace App\Traits;

use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Bus;
use Throwable;

trait Batchable
{
    public $batchId;

    public $finished = false;

    public $error = false;

    public function getBatchProperty(): ?Batch
    {
        return ($this->batchId) ? Bus::findBatch($this->batchId) : null;
    }

    public function batch(ShouldQueue $job): Batch
    {
        if ($this->batchId !== null) {
            $batch = Bus::findBatch($this->batchId);
            $batch->cancel();
            $this->batchId = null;
            $this->finished = false;
        }
        $batch = Bus::batch([
            $job,
        ])->allowFailures()->catch(function (Batch $batch, Throwable $e) {
            dump($e->getMessage());
        })->dispatch();

        $this->batchId = $batch->id;

        return $batch;
    }

    public function checkStatus()
    {
        $bus = Bus::findBatch($this->batchId);
        if ($bus->failedJobs > 0) {
            $this->finished = true;
            $this->error = true;
        }else {
            if ($bus->finished()) {
                $this->batchFinished($bus);
            }
            $this->finished = $bus->finished();
        }
        
    }
}
