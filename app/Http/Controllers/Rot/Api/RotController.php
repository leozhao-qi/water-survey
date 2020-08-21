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
        $this->middleware(['role:administrator']);
    }

    public function index()
    {
        return UserResource::collection(
            User::role('apprentice')->get()
        );
    }

    public function download()
    {
        $user = User::with(
            'objectives', 'packages', 'packages.lesson', 'packages.lesson.topic', 'packages.lesson.level', 'packages.lesson.lessonVersion', 'packages.lesson.objectives'
        )->find(request()->query('user'));

        $packagesFromRequest = explode(',', request()->query('packages'));

        $packages = $user->packages->filter(function ($package) use ($packagesFromRequest) {
            return in_array($package->id, $packagesFromRequest);
        });

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
                'evaluated_by_role' => $package->evaluated_by ? ucfirst(str_replace('_', ' ', optional(User::find($package->evaluated_by))->roles->first()->name)) : null,
                'recommendation' => $package->recommendation_id ? Recommendation::find($package->recommendation_id) : null,
                'recommendation_comment_by' => $package->recommendation_comment_by ? optional(User::find($package->recommendation_comment_by))->fullname : null,
                'recommendation_comment_by_role' => $package->recommendation_comment_by ? ucfirst(str_replace('_', ' ', optional(User::find($package->recommendation_comment_by))->roles->first()->name)) : null,
                'signed_off_by' => $package->signed_off_by ? optional(User::find($package->signed_off_by))->fullname : null,
                'signed_off_by_role' => $package->signed_off_by ? ucfirst(str_replace('_', ' ', optional(User::find($package->signed_off_by))->roles->first()->name)) : null,
                'commented_by' => $package->commented_by ? optional(User::find($package->commented_by))->fullname : null,
                'commented_by_role' => $package->commented_by ? ucfirst(str_replace('_', ' ', optional(User::find($package->commented_by))->roles->first()->name)) : null,
            ];
        }

        $pdf = App::make('dompdf.wrapper');

        $pdf->loadView('reports.rot.download', compact('packages', 'user', 'packageMeta'));

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

        if ((int) $package['lesson']['level']['code'] === $level && $level === 3 && $package[$type . '_status'] === 'complete_eg3') {
            return 'C';
        }

        if ((int) $package['lesson']['level']['code'] === $level && $level === 4 && $package[$type . '_status'] === 'complete_eg4') {
            return 'C';
        }

        return '';
    }
}
