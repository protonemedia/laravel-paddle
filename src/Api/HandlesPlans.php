<?php

namespace ProtoneMedia\LaravelPaddle\Api;

trait HandlesPlans
{
    /**
     * https://developer.paddle.com/api-reference/subscription-api/plans/listplans
     */
    public function listPlans(array $data = [])
    {
        return new Request('/2.0/subscription/plans', $data);
    }

    /**
     * https://developer.paddle.com/api-reference/subscription-api/plans/createplan
     */
    public function createPlan(array $data = [])
    {
        return new Request('/2.0/subscription/plans_create', $data, [
            'plan_name'       => 'required|string',
            'plan_trial_days' => 'required|integer|min:0',
            'plan_type'       => 'required|in:day,week,month,year',
            'plan_length'     => 'required|integer|min:1',
        ]);
    }
}
