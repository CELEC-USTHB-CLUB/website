<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait FilterUserFunctionTrait
{

    public function have_trainings_count_less_than($builder, $parameters): Builder
    {
        return $builder->whereRaw('(SELECT count(*) from training_registrations as training_registrations_groupped where email = training_registrations.email group by email) < ?', [$parameters[0]]);
    }

    public function have_trainings_count_more_than($builder, $parameters): Builder
    {
        return $builder->whereRaw('(SELECT count(*) from training_registrations as training_registrations_groupped where email = training_registrations.email group by email) > ?', [$parameters[0]]);
    }

    public function has_been_accepted_to_trainings_greater_than($builder, $parameters): Builder
    {

        return $builder
            ->whereRaw('(select
                            count(*)
                        from
                    training_registrations as registrations_by_email
                    JOIN invitations ON invitations.member_id = registrations_by_email.id
                    where training_registrations.email = registrations_by_email.email) > ?', [$parameters[0]]);
    }

    public function has_been_accepted_to_trainings_less_than($builder, $parameters): Builder
    {
        return $builder
            ->whereRaw('(select
                            count(*)
                        from
                    training_registrations as registrations_by_email
                    JOIN invitations ON invitations.member_id = registrations_by_email.id
                    where training_registrations.email = registrations_by_email.email) < ?', [$parameters[0]]);
    }
}
