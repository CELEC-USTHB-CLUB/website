<?php 

namespace App\Contracts;

use Illuminate\Bus\Batch;

interface BatchTerminateable {
    public function batchFinished(Batch $bus) : void;
}