<?php

namespace App\Traits;

use App\Supervisor;

trait HasSupervisors
{
    protected $employeeArr = [];

    protected $supervisorArr = [];

    public function supervisors()
    {
        return $this->belongsToMany(Supervisor::class, 'supervisors_users');
    }

    public function reportingStructure()
    {
        if ($this->hasAnyReportingStructure() === true) {
            return collect();
        }

        if (optional($this->supervisors)->count() === 0) {
            $this->getEmployees();

            return collect($this->employeeArr)
                ->unique()
                ->groupBy('role');
        }

        if (optional($this->supervisor)->users === null) {
            $this->getSupervisors();

            return collect($this->supervisorArr)
                ->unique()
                ->groupBy('role');
        }

        $this->getEmployees();

        $this->getSupervisors();

        return collect($this->supervisorArr)
            ->unique()
            ->merge(collect($this->employeeArr)->unique())
            ->groupBy('role');
    }

    protected function getEmployees()
    {
        $employees = $this->supervisor->users->load(
            'roles'
        );

        $this->mappedEmployees($employees);
    }

    protected function getSupervisors()
    {
        $supervisors = $this->supervisors->each->load(
            'user.roles'
        );

        $this->mappedSupervisors($supervisors);
    }

    protected function hasAnyReportingStructure()
    {
        return optional($this->supervisors)->count() === 0 &&
               optional($this->supervisor)->users === null;
    }

    protected function mappedEmployees($employees)
    {
        foreach ($employees as $employee) {
            $this->employeeArr[] = $this->getEmployeeMeta($employee);

            if (optional($employee->supervisor)->users !== null) {
                $this->mappedEmployees(
                    $employee->supervisor->users->load(
                        'roles'
                    )
                );
            }
        }
    }

    protected function mappedSupervisors($supervisors)
    {
        foreach ($supervisors as $supervisor) {
            $this->supervisorArr[] = $this->getSupervisorMeta($supervisor);

            if (optional($supervisor->user)->supervisors !== null) {
                $this->mappedSupervisors(
                    $supervisor->user->supervisors->each->load(
                        'user.roles'
                    )
                );
            }
        }
    }

    protected function getEmployeeMeta($employee)
    {
        return [
            'id' => $employee->id,
            'role' => $employee->roles[0]->name,
            'rank' => $employee->roles[0]->rank,
            'firstname' => $employee->firstname,
            'lastname' => $employee->lastname,
            'fullname' => "{$employee->firstname} {$employee->lastname}"
        ];
    }

    protected function getSupervisorMeta($supervisor)
    {
        return [
            'id' => $supervisor->user_id,
            'role' => $supervisor->user->roles[0]->name,
            'rank' => $supervisor->user->roles[0]->rank,
            'firstname' => $supervisor->user->firstname,
            'lastname' => $supervisor->user->lastname,
            'fullname' => "{$supervisor->user->firstname} {$supervisor->user->lastname}"
        ];
    }
}
