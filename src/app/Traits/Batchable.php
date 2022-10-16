<?php

namespace App\Traits;

use Throwable;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Contracts\Queue\ShouldQueue;

trait Batchable
{

    public $batchId;
    public $finished = false;

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
            $job
        ])->allowFailures()->catch(function (Batch $batch, Throwable $e) {
            dump($e->getMessage());
        })->dispatch();

        $this->batchId = $batch->id;
        return $batch;
    }

    public function checkStatus()
    {
        $finished =  Bus::findBatch($this->batchId)->finished();
        if ($finished) {
            $this->batchFinished();
        }
        $this->finished = $finished;
    }
}
