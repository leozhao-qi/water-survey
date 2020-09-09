<?php

namespace App\Http\Controllers\Sot;

use App\User;
use App\Topic;
use App\Package;
use App\Recommendation;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Http\Resources\Sot\SotPackageResource;

class SotController extends Controller
{
    public function __construct()
    {
        $this->middleware(['sot']);
    }

    public function show(User $user)
    {
        $topics = Topic::orderBy('number')->get();

        $packages = $this->getFormattedPackages($user);

        return view('reports.sot.web', compact('topics', 'packages', 'user'));
    }

    public function download(User $user)
    {
        $topics = Topic::orderBy('number')->get();

        $packages = $this->getFormattedPackages($user);

        $pdf = App::make('dompdf.wrapper');

        $pdf->loadView('reports.sot.download', compact('user', 'topics', 'packages'));

        return $pdf->download(str_replace(' ', '_', $user->fullname) . '_sot.pdf');
    }

    protected function getFormattedPackages(User $user)
    {
        $packages = $user->packages
            ->map(function ($package) {
                if ($package->recommendation_id === null) {
                    $recommendation = false;
                } else {
                    $recommendation = Recommendation::find($package->recommendation_id)->completion;
                }

                if ($package->complete) {
                    $status = 'A';
                } elseif ($recommendation && !$package->complete) {
                    $status = 'R';
                } else {
                    $status = 'N';
                }

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

                return [
                    'id' => $package->id,
                    'topic_number' => $package->lesson->topic->number,
                    'lesson_number' => (int) $package->lesson->number,
                    'lesson_name' => $package->lesson->name,
                    'completed_in_both' => $package->lesson->completed_in_both ? true : false,
                    'level' => $package->lesson->level->code,
                    'eg3_t' => $eg3_theory,
                    'eg4_t' => $eg4_theory,
                    'eg3_p' => $eg3_practical,
                    'eg4_p' => $eg4_practical,
                    'theory_status_eg3' => $this->getStatus('theory', $package, 3),
                    'practical_status_eg3' => $this->getStatus('practical', $package, 3),
                    'theory_status_eg4' => $this->getStatus('theory', $package, 4),
                    'practical_status_eg4' => $this->getStatus('practical', $package, 4),
                    'eg3_practical' => (int) $package->lesson->level->code === 3 && $package->practical_status ? true : false,
                    'eg4_practical' => (int) $package->lesson->level->code === 4 && $package->practical_status ? true : false,
                    'status' => $status,
                    'user_id' => $package->user_id
                ];
            })
            ->toArray();

        array_multisort(
            array_column($packages, 'topic_number'), SORT_ASC,
            array_column($packages, 'lesson_number'), SORT_ASC,
            $packages
        );

        $packages = array_reduce(
            $packages, 
            function (array $accumulator, array $element) {
                $accumulator[$element['topic_number']][] = $element;
            
                return $accumulator;
            },
            []
        );

        return $packages;
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
