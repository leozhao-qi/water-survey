<?php

namespace App\Rules;

use App\User;
use App\Package;
use App\Recommendation;
use Illuminate\Contracts\Validation\Rule;

class IsValidCompletion implements Rule
{
    protected $errorMessage;

    protected $user;

    protected $package;

    protected $packageStatuses;

    public function __construct(User $user, Package $package)
    {
        $this->user = $user;
        $this->package = $package;

        $this->packageStatuses = [
            'theory' => [
                'column' => 'theory_status',
                'value' => $this->package->theory_status
            ],
            'practical_application' => [
                'column' => 'practical_status',
                'value' => $this->package->practical_status
            ]
        ];
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (request('complete') === 0) {
            return true;
        }

        if (!$this->validations()) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMessage;
    }

    protected function validations()
    {
        return $this->correctStatuses() && 
        $this->allObjectivesComplete() &&
        $this->correctRecommendation() &&
        $this->hasEvaluationDetails();
    }

    protected function correctStatuses()
    {
        $objectiveTypes = $this->package->lesson->objectives->pluck('type')->filter()->unique()->toArray();

        foreach ($objectiveTypes as $type) {
            if (request()->has($this->packageStatuses[$type]['column'])) {
                $this->packageStatuses[$type]['value'] = request($this->packageStatuses[$type]['column']);
            }

            $validValue = array_search($this->packageStatuses[$type]['value'], ['exempt', 'complete_eg3', 'complete_eg4']);

            if ($validValue === false) {
                $this->errorMessage = 'One of the statuses has not been marked "Exempt", "Complete - EG3" or "Complete - EG4".';

                return false;
            }
        }

        return true;
    }

    protected function allObjectivesComplete()
    {
        $packageObjectives = $this->package->lesson->objectives->pluck('id')->toArray();

        if (request()->has('objectives')) {
            $userObjectives = request('objectives');
        } else {
            $userObjectives =  $this->user->objectives->whereIn('id', $packageObjectives)->pluck('id')->toArray();
        }

        $diffArrayCount = count(array_diff($packageObjectives, $userObjectives));

        if ($diffArrayCount !== 0) {
            $this->errorMessage = 'All of the objectives have not been marked as complete.';

            return false;
        }

        return true;
    }

    protected function correctRecommendation()
    {
        if (request()->has('recommendation_id')) {
            $recommendation = Recommendation::find(request('recommendation_id'));
        } else {
            $recommendation =  $this->package->recommendation;
        }

        if ($recommendation === null || !$recommendation->completion) {
            $this->errorMessage = 'This is not valid recommendation for lesson package completion.';

            return false;
        }

        return true;
    }

    protected function hasEvaluationDetails()
    {
        if (request()->has('evaluation_details')) {
            $evaluationDetails = request('evaluation_details');
        } else {
            $evaluationDetails =  $this->package->evaluation_details;
        }

        if (!$evaluationDetails) {
            $this->errorMessage = 'Evaluation details have not yet been added.';

            return false;
        }

        return true;
    }
}
