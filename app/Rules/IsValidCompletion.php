<?php

namespace App\Rules;

use App\User;
use App\Package;
use App\Recommendation;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Validation\Rule;

class IsValidCompletion implements Rule
{
    protected $errorMessage;

    protected $user;

    protected $package;

    protected $packageStatuses;

    protected $numIncompleteStatuses;

    protected $numExemptStatuses;

    protected $objectiveTypes;

    protected $completionValue;

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

        $this->numIncompleteStatuses = 0;

        $this->numExemptStatuses = 0;

        $this->objectiveTypes = $this->package
            ->lesson
            ->objectives
            ->pluck('type')
            ->filter()
            ->unique()
            ->toArray();

        $this->completionValue = request('complete') ? request('complete') : $this->package->complete;
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
        if (request('complete') === null) {
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
        $this->hasEvaluationDetails() && 
        $this->correctRecommendation();
    }

    protected function correctStatuses()
    {
        if ($this->completionValue === 'A') {
            foreach ($this->objectiveTypes as $type) {
                if (request()->has($this->packageStatuses[$type]['column'])) {
                    $this->packageStatuses[$type]['value'] = request($this->packageStatuses[$type]['column']);
                }

                if ($this->packageStatuses[$type]['value'] === 'incomplete') {
                    $this->numIncompleteStatuses += 1;
                }

                if ($this->numIncompleteStatuses > 1) {
                    $this->errorMessage = 'Only one of the statuses can be marked as "Incomplete".';
                    
                    return false;
                }

                $validValue = array_search($this->packageStatuses[$type]['value'], ['incomplete', 'complete_eg3', 'complete_eg4']);

                if ($validValue === false) {
                    $this->errorMessage = 'One of the statuses has not been marked "Incomplete", "Complete - EG3" or "Complete - EG4".';

                    return false;
                }
            }

            if ($this->packageStatuses['theory']['value'] === 'incomplete') {
                $this->errorMessage = 'The "Theory" status cannot be marked as "Incomplete".';

                return false;
            }
        }

        if ($this->completionValue === 'B') {
            foreach ($this->objectiveTypes as $type) {
                if (request()->has($this->packageStatuses[$type]['column'])) {
                    $this->packageStatuses[$type]['value'] = request($this->packageStatuses[$type]['column']);
                }

                if ($this->packageStatuses[$type]['value'] === 'exempt') {
                    $this->numExemptStatuses += 1;
                }
            }

            if ($this->numExemptStatuses === 0) {
                $this->errorMessage = 'There must be at least one "Exempt" status if this package is to be signed off as an "Exemption.';

                return false;
            }
        }

        return true;
    }

    protected function allObjectivesComplete()
    {
        $packageObjectives = $this->package
            ->lesson
            ->objectives
            ->map(function ($objective) {
                return [
                    'id' => $objective->id,
                    'type' => $objective->type
                ];
            });

        if (request()->has('objectives')) {
            $userObjectives = request('objectives');
        } else {
            $userObjectives =  $this->user
                ->objectives
                ->whereIn('id', $packageObjectives->pluck('id')->toArray())
                ->pluck('id')
                ->toArray();
        }

        
        foreach ($this->objectiveTypes as $type) {
            $objectivesForType = Arr::pluck(
                Arr::where($packageObjectives->toArray(), function ($value, $key) use ($type) {
                    if ($value['type'] === $type)  {
                        return $value['id'];
                    }
                }),
                'id'
            );

            $completedObjectivesForType = array_intersect($objectivesForType, $userObjectives);

            $diffArrayCount = 0;

            if (strpos($this->packageStatuses[$type]['value'], 'complete_') !== false) {
                $diffArrayCount = count(array_diff($objectivesForType, $completedObjectivesForType));
            }

            if ($diffArrayCount !== 0) {
                $this->errorMessage = 'The "' . ucfirst($type) . '" status has been marked as "Complete", but some of the associated objectives have not been checked.';
    
                return false;
            }
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

        if ($recommendation === null) {
            $this->errorMessage = 'You need to select a recommendation from the drop down menu.';

            return false;
        }

        if ($this->completionValue === 'A' && ($recommendation->code !== 'A' && $recommendation->code !== 'D')) {
            $this->errorMessage = 'This is not valid recommendation for a "Statement of Competency".';

            return false;
        }

        if ($this->completionValue === 'B' && $recommendation->code !== 'C') {
            $this->errorMessage = 'This is not valid recommendation for an "Exemption".';

            return false;
        }

        if (request()->has('recommendation_comment')) {
            $recommendationComment = request('recommendation_comment');
        } else {
            $recommendationComment =  $this->package->recommendation_comment;
        }

        if ($this->completionValue === 'A'  && !$recommendationComment && $recommendation->code === 'A') {
            $this->errorMessage = 'You need to add a recommendation comment.';

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

        if (!$evaluationDetails && $this->completionValue === 'A') {
            $this->errorMessage = 'Evaluation details have not yet been added.';

            return false;
        }

        return true;
    }
}
