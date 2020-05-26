<?php

namespace App\Rules;

use App\User;
use App\Package;
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
        return $this->correctStatuses();
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
}
