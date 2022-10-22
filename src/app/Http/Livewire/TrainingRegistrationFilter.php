<?php

namespace App\Http\Livewire;

use App\Training;
use Livewire\Component;

class TrainingRegistrationFilter extends Component
{
    public $training;

    public $filters;

    public function mount(int $trainingId)
    {
        $this->training = Training::findOrFail($trainingId);
    }

    public function render()
    {
        return view('livewire.training-registration-filter');
    }

    public function generate()
    {
        dump($this->filters);
    }
}
