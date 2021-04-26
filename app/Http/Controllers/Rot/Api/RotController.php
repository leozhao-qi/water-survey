<?php

namespace App\Http\Controllers\Rot\Api;

use App\User;
use App\Package;
use App\Recommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserResource;

class RotController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator|supervisor|head_of_operations', 'auth']);
    }

    public function index()
    {
        if (auth()->user()->hasAnyRole('supervisor', 'head_of_operations')) {
            return UserResource::collection(
                User::find(auth()->user()->reportingStructure()['apprentice']->pluck('id'))
            );
        }

        return User::role('apprentice')->get();
    }

    public function download()
    {
        $user = User::with(
            'objectives', 'packages', 'packages.lesson', 'packages.lesson.topic', 'packages.lesson.level', 'packages.lesson.lessonVersion', 'packages.lesson.objectives'
        )->find(request()->query('user'));
        
        if (request()->query('packages')) {
            $packagesFromRequest = explode(',', request()->query('packages'));

            $packages = $user->packages
                ->filter(function ($package) use ($packagesFromRequest) {
                    return in_array($package->id, $packagesFromRequest);
                })
                ->map(function ($package) {
                    $package->name = $package->lesson->topic->number . '.' . str_pad($package->lesson->number, 2, '0', STR_PAD_LEFT) . ' - ' . $package->lesson->name;

                    return $package;
                })
                ->sortBy('name');;

            $incompletePackages = [];
        } elseif (request()->query('type') === 'eg_3_4') {
            $packages = $user->packages
                ->filter(function ($package) {
                    return 
                    (
                        $package->lesson->level->code === '3' || 
                        $package->lesson->completed_in_both
                    )
                     && 
                    (
                        $package->theory_status === 'complete_eg3' || 
                        $package->practical_status === 'complete_eg3' || 
                        $package->theory_status === 'exempt' || 
                        $package->practical_status === 'exempt' || 
                        $package->theory_status === 'deferred' || 
                        $package->practical_status === 'deferred'
                    );
                })
                ->map(function ($package) {
                    $package->name = $package->lesson->topic->number . '.' . str_pad($package->lesson->number, 2, '0', STR_PAD_LEFT) . ' - ' . $package->lesson->name;

                    return $package;
                })
                ->sortBy('name');

            $incompletePackages = $user->packages
                ->filter(function ($package) {
                    return 
                    (
                        $package->lesson->level->code === '3' || 
                        $package->lesson->completed_in_both
                    )
                     && 
                    (
                        $package->theory_status === 'incomplete' || 
                        $package->practical_status === 'incomplete'
                    );
                })
                ->map(function ($package) { 
                    $package->name = $package->lesson->topic->number . '.' . str_pad($package->lesson->number, 2, '0', STR_PAD_LEFT) . ' - ' . $package->lesson->name;

                    return $package;
                })
                ->sortBy('name');
        } elseif (request()->query('type') === 'eg_4_5') {
            $packages = $user->packages
                ->filter(function ($package) {
                    return 
                    (
                        $package->lesson->level->code === '3' &&
                        (
                            $package->theory_status === 'complete_eg4' ||
                            $package->practical_status === 'complete_eg4'
                        ) 
                    ) 
                    || 
                    (
                        (
                            $package->lesson->level->code === '4' || 
                            $package->lesson->completed_in_both
                        ) 
                        && 
                        (
                            $package->theory_status === 'complete_eg4' ||
                            $package->practical_status === 'complete_eg4' || 
                            $package->theory_status === 'exempt' || 
                            $package->practical_status === 'exempt'
                        )
                    );
                })
                ->map(function ($package) {
                    $package->name = $package->lesson->topic->number . '.' . str_pad($package->lesson->number, 2, '0', STR_PAD_LEFT) . ' - ' . $package->lesson->name;

                    return $package;
                })
                ->sortBy('name');

            $incompletePackages = $user->packages
                ->filter(function ($package) {
                    return 
                    (
                        $package->lesson->level->code === '3' &&
                        (
                            $package->theory_status === 'deferred' ||
                            $package->practical_status === 'deferred'
                        ) 
                    ) 
                    || 
                    (
                        (
                            $package->lesson->level->code === '4' || 
                            $package->lesson->completed_in_both
                        ) 
                        && 
                        (
                            $package->theory_status === 'incomplete' ||
                            $package->practical_status === 'incomplete'
                        )
                    );
                })
                ->map(function ($package) { 
                    $package->name = $package->lesson->topic->number . '.' . str_pad($package->lesson->number, 2, '0', STR_PAD_LEFT) . ' - ' . $package->lesson->name;

                    return $package;
                })
                ->sortBy('name');
        }

        $packageMeta = [];

        foreach ($packages as $package) {
            $eg3_theory = 
                ((int) $package->lesson->level->code === 4 && 
                (bool) $package->lesson->completed_in_both &&
                $package->theory_status !== null) 
                || 
                ((int) $package->lesson->level->code === 3 && 
                $package->theory_status !== null) ? 
                true : false;

            $eg3_practical = 
                ((int) $package->lesson->level->code === 4 && 
                (bool) $package->lesson->completed_in_both &&
                $package->practical_status !== null) 
                || 
                ((int) $package->lesson->level->code === 3 && 
                $package->practical_status !== null) ? 
                true : false;

            $eg4_theory = 
                ((int) $package->lesson->level->code === 3 && 
                (bool) $package->lesson->completed_in_both &&
                $package->theory_status !== null) 
                || 
                ((int) $package->lesson->level->code === 4 && 
                $package->theory_status !== null) ? 
                true : false;
            
            $eg4_practical = 
                ((int) $package->lesson->level->code === 3 && 
                (bool) $package->lesson->completed_in_both && 
                !is_null($package->practical_status))
                || 
                ((int) $package->lesson->level->code === 4 && 
                !is_null($package->practical_status));

            $packageMeta[$package->lesson->name] = [
                'eg3_t' => $eg3_theory,
                'eg4_t' => $eg4_theory,
                'eg3_p' => $eg3_practical,
                'eg4_p' => $eg4_practical,
                'theory_status_eg3' => $this->getStatus('theory', $package, 3),
                'practical_status_eg3' => $this->getStatus('practical', $package, 3),
                'theory_status_eg4' => $this->getStatus('theory', $package, 4),
                'practical_status_eg4' => $this->getStatus('practical', $package, 4),
                'evaluated_by' => $package->evaluated_by ? optional(User::find($package->evaluated_by))->fullname : null,
                'recommended_by' => $package->recommended_by ? optional(User::find($package->recommended_by))->fullname : null,
                'evaluated_by_role' => $package->evaluated_by ? ucfirst(str_replace('_', ' ', optional(User::find($package->evaluated_by))->roles->first()->name)) : null,
                'recommended_user_by_role' => $package->recommended_by ? ucfirst(str_replace('_', ' ', optional(User::find($package->recommended_by))->roles->first()->name)) : null,
                'recommendation' => $package->recommendation_id ? Recommendation::find($package->recommendation_id) : null,
                'recommendation_comment_by' => $package->recommendation_comment_by ? optional(User::find($package->recommendation_comment_by))->fullname : null,
                'recommendation_comment_by_role' => $package->recommendation_comment_by ? ucfirst(str_replace('_', ' ', optional(optional(User::find($package->recommendation_comment_by))->roles)->first()->name)) : null,
                'signed_off_by' => $package->signed_off_by ? optional(User::find($package->signed_off_by))->fullname : null,
                'signed_off_by_role' => $package->signed_off_by ? ucfirst(str_replace('_', ' ', optional(User::find($package->signed_off_by))->roles->first()->name)) : null,
                'commented_by' => $package->commented_by ? optional(User::find($package->commented_by))->fullname : null,
                'commented_by_role' => $package->commented_by ? ucfirst(str_replace('_', ' ', optional(User::find($package->commented_by))->roles->first()->name)) : null,
                'practical_objectives' => $package->lesson->objectives->filter(function ($objective) {
                    return $objective->type === 'practical_application';
                }),
                'theory_objectives' => $package->lesson->objectives->filter(function ($objective) {
                    return $objective->type === 'theory';
                })
            ];
        }

        $pdf = App::make('dompdf.wrapper');

        $pdf->loadView('reports.rot.download', compact('packages', 'user', 'packageMeta', 'incompletePackages'));

        return $pdf->download(str_replace(' ', '_', $user->fullname) . '_rot.pdf');
    }

    protected function getStatus($type, Package $package, $level) {
        if ((int) $package['lesson']['level']['code'] === $level && $package[$type . '_status'] === 'incomplete') {
            return 'I';
        }

        if ((int) $package['lesson']['level']['code'] === $level && $package[$type . '_status'] === 'deferred') {
            return 'D';
        }

        if ((int) $package['lesson']['level']['code'] === $level && $package[$type . '_status'] === 'exempt') {
            return 'E';
        }

        if ((int) $package['lesson']['level']['code'] === 3 && $level === 4 && $package[$type . '_status'] === 'complete_eg4') {
            return 'C';
        }

        if ((int) $package['lesson']['level']['code'] === 4 && $level === 3 && $package[$type . '_status'] === 'complete_eg3') {
            return 'C';
        }

        if ((int) $package['lesson']['level']['code'] === $level && $level === 3 && $package[$type . '_status'] === 'complete_eg3') {
            return 'C';
        }

        if ((int) $package['lesson']['level']['code'] === $level && $level === 4 && $package[$type . '_status'] === 'complete_eg4') {
            return 'C';
        }

        return '';
    }
}
