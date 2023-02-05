<?php

namespace Tests\Feature;

use App\Training;
use Tests\TestCase;
use App\TrainingRegistration;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Actions\TrainingRegistrationsFilterParserAction;

class TrainingRegistrationsFilterParserActionTest extends TestCase
{
    use DatabaseTransactions;

    public function testGettingSingleQuery()
    {
        $registrations = TrainingRegistration::factory()->count(10)->create([
            'is_celec_memeber' => 1,
            'study_level' => 'L1',
        ]);

        $filterText = 'Is Celec Memeber=1';
        $registrationsBuilder = TrainingRegistration::query();
        $trainingRegistrationsFilterParserAction = new TrainingRegistrationsFilterParserAction($filterText, new TrainingRegistration, $registrationsBuilder);

        $this->assertEquals($registrations->count(), $trainingRegistrationsFilterParserAction->parse()->get()->count());

        $registrations = TrainingRegistration::factory()->count(3)->create([
            'is_celec_memeber' => 1,
            'study_level' => 'L2',
        ]);
        $filterText = 'Study Level=L2';
        $registrationsBuilder = TrainingRegistration::query();
        $trainingRegistrationsFilterParserAction = new TrainingRegistrationsFilterParserAction($filterText, new TrainingRegistration, $registrationsBuilder);

        $this->assertEquals($registrations->count(), $trainingRegistrationsFilterParserAction->parse()->get()->count());


        $registrations = TrainingRegistration::factory()->count(1)->create([
            'is_celec_memeber' => 1,
            'study_level' => 'L2',
            'created_at' => '2022-12-01',
        ]);
        $filterText = 'Created at<2023-01-01';
        $registrationsBuilder = TrainingRegistration::query();
        $trainingRegistrationsFilterParserAction = new TrainingRegistrationsFilterParserAction($filterText, new TrainingRegistration, $registrationsBuilder);

        $this->assertEquals($registrations->count(), $trainingRegistrationsFilterParserAction->parse()->get()->count());
    }

    public function testFilterMultipleColumns(): void
    {
        $registrations1 = TrainingRegistration::factory()->count(10)->create([
            'is_celec_memeber' => 1,
            'study_level' => 'L2',
        ]);

        $registrations2 = TrainingRegistration::factory()->count(3)->create([
            'is_celec_memeber' => 0,
            'study_level' => 'L1',
            'created_at' => '2022-12-01',
        ]);

        $filterText = 'Created at<2023-01-01/Is Celec Memeber=0';
        $registrationsBuilder = TrainingRegistration::query();
        $trainingRegistrationsFilterParserAction = new TrainingRegistrationsFilterParserAction($filterText, new TrainingRegistration, $registrationsBuilder);

        $this->assertEquals($registrations2->count(), $trainingRegistrationsFilterParserAction->parse()->get()->count());
    }

    public function testUserFilterFunctionLessThan()
    {
        $registrations1 = TrainingRegistration::factory()->count(10)->create([
            'is_celec_memeber' => 1,
            'study_level' => 'L2',
        ]);
        TrainingRegistration::factory()->count(3)->create([
            'email' => 'walid@mail.com',
            'is_celec_memeber' => 1,
            'study_level' => 'L2',
        ]);
        $filterText = 'have_trainings_count_less_than(3)';
        $registrationsBuilder = TrainingRegistration::query();
        $trainingRegistrationsFilterParserAction = new TrainingRegistrationsFilterParserAction($filterText, new TrainingRegistration, $registrationsBuilder);

        $this->assertEquals($registrations1->count(), $trainingRegistrationsFilterParserAction->parse()->get()->count());
    }

    public function testUserFilterFunctionMoreThan()
    {
        $registrations1 = TrainingRegistration::factory()->count(10)->create([
            'is_celec_memeber' => 1,
            'study_level' => 'L2',
        ]);
        $registrations2 = TrainingRegistration::factory()->count(4)->create([
            'email' => 'walid@mail.com',
            'is_celec_memeber' => 1,
            'study_level' => 'L2',
        ]);
        $filterText = 'have_trainings_count_more_than(3)';
        $registrationsBuilder = TrainingRegistration::query();
        $trainingRegistrationsFilterParserAction = new TrainingRegistrationsFilterParserAction($filterText, new TrainingRegistration, $registrationsBuilder);

        $this->assertEquals($registrations2->count(), $trainingRegistrationsFilterParserAction->parse()->get()->count());
    }


    public function testFilterUserFunctionTrainingsLessThan(): void
    {
        $trainings = Training::factory()->hasImage(1)->count(10)->create();

        $registrations1 = TrainingRegistration::factory()->count(10)->create([
            'is_celec_memeber' => 1,
            'study_level' => 'L2',
        ]);

        $registration = $registrations1->first();

        $trainings->first()->invitations()->create([
            'path' => '123.pdf',
            'member_id' => $registration->id
        ]);
        $trainings->first()->invitations()->create([
            'path' => '123.pdf',
            'member_id' => $registration->id
        ]);

        $filterText = 'has_been_accepted_to_trainings_less_than(1)';
        $registrationsBuilder = TrainingRegistration::query();
        $trainingRegistrationsFilterParserAction = new TrainingRegistrationsFilterParserAction($filterText, new TrainingRegistration, $registrationsBuilder);
        $this->assertEquals(9, $trainingRegistrationsFilterParserAction->parse()->get()->count());
    }

    public function testFilterUserFunctionTrainingsGreaterhan(): void
    {
        $trainings = Training::factory()->hasImage(1)->count(10)->create();

        $registrations1 = TrainingRegistration::factory()->count(10)->create([
            'is_celec_memeber' => 1,
            'study_level' => 'L2',
        ]);

        $registration = $registrations1->first();

        $trainings->first()->invitations()->create([
            'path' => '123.pdf',
            'member_id' => $registration->id
        ]);
        $trainings->first()->invitations()->create([
            'path' => '123.pdf',
            'member_id' => $registration->id
        ]);

        $filterText = 'has_been_accepted_to_trainings_greater_than(1)';
        $registrationsBuilder = TrainingRegistration::query();
        $trainingRegistrationsFilterParserAction = new TrainingRegistrationsFilterParserAction($filterText, new TrainingRegistration, $registrationsBuilder);
        $this->assertEquals(1, $trainingRegistrationsFilterParserAction->parse()->get()->count());
    }

    public function testComplexeFilter(): void
    {
        // Training
        //     inscription 10 -> 5 L1 and 5L2
        //     invitations 4 -> 1 L1 and 3L2

        $trainings = Training::factory()->hasImage(1)->count(10)->create();
        $registrationsL1 = TrainingRegistration::factory()->count(5)->create([
            'is_celec_memeber' => 1,
            'study_level' => 'L1',
        ]);

        $registrationsL10 = TrainingRegistration::factory()->count(8)->create([
            'is_celec_memeber' => 0,
            'study_level' => 'L1',
        ]);

        $registrationsL21 = TrainingRegistration::factory()->count(2)->create([
            'is_celec_memeber' => 1,
            'study_level' => 'L2',
        ]);

        $registrationsL22 = TrainingRegistration::factory()->count(3)->create([
            'is_celec_memeber' => 1,
            'study_level' => 'L2',
            'email' => 'walid@mail.com'
        ]);

        $registrationsL11 = $registrationsL1->take(1);
        
        foreach($registrationsL22 as $registration) {
            $trainings->first()->invitations()->create([
                'path' => '123.pdf',
                'member_id' => $registration->id,
            ]);
        }
        $filterText = 'Study Level=L2/has_been_accepted_to_trainings_greater_than(1)';

        $registrationsBuilder = TrainingRegistration::query();
        $trainingRegistrationsFilterParserAction = new TrainingRegistrationsFilterParserAction($filterText, new TrainingRegistration, $registrationsBuilder);

        $this->assertEquals(3, $trainingRegistrationsFilterParserAction->parse()->get()->count());


        $filterText = 'Study Level=L2/has_been_accepted_to_trainings_less_than(1)';

        $registrationsBuilder = TrainingRegistration::query();
        $trainingRegistrationsFilterParserAction = new TrainingRegistrationsFilterParserAction($filterText, new TrainingRegistration, $registrationsBuilder);

        $this->assertEquals(2, $trainingRegistrationsFilterParserAction->parse()->get()->count());

        $filterText = 'Study Level=L1/has_been_accepted_to_trainings_less_than(1)';

        $registrationsBuilder = TrainingRegistration::query();
        $trainingRegistrationsFilterParserAction = new TrainingRegistrationsFilterParserAction($filterText, new TrainingRegistration, $registrationsBuilder);
        $this->assertEquals(13, $trainingRegistrationsFilterParserAction->parse()->get()->count());


        foreach($registrationsL11 as $registration) {
            $trainings->first()->invitations()->create([
                'path' => '123.pdf',
                'member_id' => $registration->id,
            ]);
        }

        $registrationsBuilder = TrainingRegistration::query();
        $trainingRegistrationsFilterParserAction = new TrainingRegistrationsFilterParserAction($filterText, new TrainingRegistration, $registrationsBuilder);
        $this->assertEquals(12, $trainingRegistrationsFilterParserAction->parse()->get()->count());

        $filterText = 'Study Level=L1/Is Celec Memeber=1/has_been_accepted_to_trainings_less_than(1)';
        $registrationsBuilder = TrainingRegistration::query();
        $trainingRegistrationsFilterParserAction = new TrainingRegistrationsFilterParserAction($filterText, new TrainingRegistration, $registrationsBuilder);
        $this->assertEquals(4, $trainingRegistrationsFilterParserAction->parse()->get()->count());

    }
}
