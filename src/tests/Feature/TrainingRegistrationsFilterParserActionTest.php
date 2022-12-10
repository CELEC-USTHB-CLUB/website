<?php

namespace Tests\Feature;

use App\Actions\TrainingRegistrationsFilterParserAction;
use App\TrainingRegistration;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TrainingRegistrationsFilterParserActionTest extends TestCase
{
    use DatabaseTransactions;

    public function testGettingSingleQuery()
    {
        $registrations = TrainingRegistration::factory()->count(10)->create([
            'is_celec_memeber' => 1,
            'study_level' => 'L1',
        ]);

        $filterText = 'Is Celec Member=1';
        $registrationsBuilder = TrainingRegistration::query();
        $trainingRegistrationsFilterParserAction = new TrainingRegistrationsFilterParserAction($filterText, new TrainingRegistration, $registrationsBuilder);

        $this->assertEquals($registrations->count(), $trainingRegistrationsFilterParserAction->parse()->get()->count());
    }
}
