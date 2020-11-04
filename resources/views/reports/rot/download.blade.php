<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

        <style>
            body { margin: 0; padding: 0; min-width: 100%; width: 100% !important; height: 100% !important;}
            body, table, td, div, p, a { -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; }
            html {
                font-size: 14px;
                font-family: sans-serif;
            }

            h2 {
                width: 100%;
                font-size: 1.5rem;
                line-height: 1.3;
                margin-bottom: .5rem;
            }

            h3 {
                width: 100%;
                font-size: 1.25rem;
                line-height: 1.3;
                margin-bottom: .5rem;
            }

            h4 {
                width: 100%;
                font-size: 1rem;
                line-height: 1.3;
                margin-bottom: .5rem;
            }

            th,
            td {
                padding: .5rem 1rem;
                border: 1px solid #e2e8f0;
            }

            .no-table-border {
                padding: .5rem;
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse !important;
                border-spacing: 0;
                border: 1px solid white;
            }

            .checkmark {
                display:inline-block;
                width: 18px;
                height: 18px;
                -ms-transform: rotate(45deg); /* IE 9 */
                -webkit-transform: rotate(45deg); /* Chrome, Safari, Opera */
                transform: rotate(45deg);
            }

            .checkmark_stem {
                position: absolute;
                width:3px;
                height:9px;
                background-color:green;
                left:11px;
                top:6px;
            }

            .checkmark_kick {
                position: absolute;
                width:3px;
                height:3px;
                background-color:green;
                left:8px;
                top:12px;
            }

            @page {
                margin: 1.5cm;
            }
        </style>
    </head>

    <body>
        @if (count($incompletePackages))
            <h2>
                Incomplete packages for {{ $user->fullname }}
            </h2>

            <ul>
                @foreach($incompletePackages as $package)
                    <li style="margin-bottom: .25rem;">
                        <a 
                            href="{{ env('APP_URL') }}/users/{{ $user->id }}/packages/{{ $package->id }}"
                            style="color: #4299E1; text-decoration: none;"
                        >{{ $package->name }}</a>
                    </li>
                @endforeach
            </ul>

            <hr style="visibility: hidden; page-break-after: always;">
        @endif

        @foreach($packages as $package)
            <h2 class="margin-bottom: 0;">
                {{ $package->lesson->topic_id ? 
                $package->lesson->topic->number . '.' . str_pad($package->lesson->number, 2, '0', STR_PAD_LEFT) . ' - ' . $package->lesson->name : 
                'No topic.' . str_pad($package->lesson->number, 2, '0', STR_PAD_LEFT) . ' - ' . $package->lesson->name }}
            </h2>

            <table style="width: 100%; border-collapse: collapse !important; border-spacing: 0;">
                <tbody>
                    <tr>
                        <td class="no-table-border">
                            <p style="font-family: sans-serif; width: 100%; font-size: 1rem; margin-bottom: -.5rem;">
                                <strong>Name:</strong> 
                                <a 
                                    href="{{ env('APP_URL') }}/users/{{ $user->id }}"
                                    style="color: #4299E1; text-decoration: none;"
                                >{{ $user->fullname }}</a>
                            </p>
                    
                            <p style="font-family: sans-serif; width: 100%; font-size: 1rem; margin-bottom: -.5rem;">
                                <strong>Appointment date:</strong> {{ $user->appointment_date ? $user->appointment_date->format('m/d/y') : 'No appointment date entered' }}
                            </p>
                    
                            <p style="font-family: sans-serif; width: 100%; font-size: 1rem; margin-bottom: -.5rem;">
                                <strong>Version:</strong> {{ $package->lesson->lessonVersion->version }}
                            </p>
                        </td>
    
                        <td class="no-table-border">
                            @if (isset($user->reportingStructure()['manager']))
                                <p style="font-family: sans-serif; width: 100%; font-size: 1rem; margin-bottom: -.5rem;">
                                    <strong>Manager:</strong> {{ implode(',', $user->reportingStructure()['manager']->pluck('fullname')->toArray()) }}
                                </p>
                            @endif
    
                            @if (isset($user->reportingStructure()['head_of_operations']))
                                <p style="font-family: sans-serif; width: 100%; font-size: 1rem; margin-bottom: -.5rem;">
                                    <strong>Area Head:</strong> {{ implode(',', $user->reportingStructure()['head_of_operations']->pluck('fullname')->toArray()) }}
                                </p>
                            @endif
    
                            @if (isset($user->reportingStructure()['supervisor']))
                                <p style="font-family: sans-serif; width: 100%; font-size: 1rem; margin-bottom: 0;">
                                    <strong>Supervisor:</strong> {{ implode(',', $user->reportingStructure()['supervisor']->pluck('fullname')->toArray()) }}
                                </p>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            <table style="width: 100%; font-family: sans-serif; border-collapse: collapse; margin-top: 1rem;">
                <thead>
                    <tr>
                        <th style="text-align: left">Lesson Package</th>
                        <th>Progress EG03 Level</th>
                        <th>Progress EG04 Level</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <th style="text-align: left">Theory</th>
                        <td style="text-align: center; {{ !$packageMeta[$package->lesson->name]['eg3_t'] ? 'background-color: #CBD5E0;' : '' }}">
                            {{ $packageMeta[$package->lesson->name]['theory_status_eg3'] }}
                        </td>
                        <td style="text-align: center; {{ !$packageMeta[$package->lesson->name]['eg4_t'] ? 'background-color: #CBD5E0;' : '' }}">
                            {{ $packageMeta[$package->lesson->name]['theory_status_eg4'] }}
                        </td>
                    </tr>

                    <tr>
                        <th style="text-align: left">Practical Application</th>
                        <td style="text-align: center; {{ !$packageMeta[$package->lesson->name]['eg3_p'] ? 'background-color: #CBD5E0;' : '' }}">
                            {{ $packageMeta[$package->lesson->name]['practical_status_eg3'] }}
                        </td>
                        <td style="text-align: center; {{ !$packageMeta[$package->lesson->name]['eg4_p'] ? 'background-color: #CBD5E0;' : '' }}">
                            {{ $packageMeta[$package->lesson->name]['practical_status_eg4'] }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <p style="margin: .5rem 0;">
                <strong>Status Indicators:</strong> <strong>I</strong> = Incomplete, <strong>C</strong> = Complete, <strong>D</strong> = Deferred, <strong>E</strong> = Exempt
            </p>

            <h3>Objectives</h3>

            @if (count($packageMeta[$package->lesson->name]['theory_objectives']))
                <strong style="margin-bottom: 0.5rem;">Theory</strong>
                
                <ul style="list-style: none; margin: 0; padding: 0;">
                    @foreach ($packageMeta[$package->lesson->name]['theory_objectives'] as $objective)

                        <li style="margin: 0; padding: 0; margin-top: .25rem; margin-bottom: 0.25rem; line-height: 1.5;">
                            @if ($user->objectives->where('id', '=', $objective->id)->count()) 
                                <span class="checkmark" style="position: relative;">
                                    <div class="checkmark_stem"></div>
                                    <div class="checkmark_kick"></div>
                                </span> 
                            @else 
                                &ndash; 
                            @endif
                            {{ $objective->number }} - 
                            {{ $objective->name }}
                        </li>

                    @endforeach
                </ul>
            @endif

            @if (count($packageMeta[$package->lesson->name]['practical_objectives']))
                <strong style="margin-bottom: 0.5rem; margin-top: 0.75rem; display: block;">Practical application</strong>

                <ul style="list-style: none; margin: 0; padding: 0;">
                    @foreach ($packageMeta[$package->lesson->name]['practical_objectives'] as $objective)

                        <li style="margin: 0; padding: 0; margin-top: .25rem; margin-bottom: 0.25rem; line-height: 1.5;">
                            @if ($user->objectives->where('id', '=', $objective->id)->count()) 
                                <span class="checkmark" style="position: relative;">
                                    <div class="checkmark_stem"></div>
                                    <div class="checkmark_kick"></div>
                                </span> 
                            @else 
                                &ndash; 
                            @endif
                            {{ $objective->number }} - 
                            {{ $objective->name }}
                        </li>

                    @endforeach
                </ul>
            @endif

            <h3>Evaluation details</h3>

            @if ($package->evaluated_by)
                <p>
                    <small>
                        <strong>Written by:</strong> {{ $packageMeta[$package->lesson->name]['evaluated_by'] }} 
                        ({{ $packageMeta[$package->lesson->name]['evaluated_by_role'] }}) on 
                        {{ $package->evaluated_at->format('m/d/Y') }}
                    </small>
                </p>

                {!! $package->evaluation_details !!}
            @else
                <p>No evaluation details entered.</p>
            @endif

            <h3>Recommendation</h3>

            @if ($package->recommendation_id)
                <p>
                    <strong>{{ $packageMeta[$package->lesson->name]['recommendation']->code }}</strong> - 
                    {{ $packageMeta[$package->lesson->name]['recommendation']->name }}
                </p>

                @if ($package->recommendation_comment)

                    <h4>Comment</h4>

                    <p>
                        <small>
                            <strong>Recommended by:</strong> {{ $packageMeta[$package->lesson->name]['recommendation_comment_by'] }} 
                            ({{ $packageMeta[$package->lesson->name]['recommendation_comment_by_role'] }}) on 
                            {{ $package->recommendation_comment_at->format('m/d/Y') }}
                        </small>
                    </p>

                    {!! $package->recommendation_comment !!}

                @endif
            @else
                <p>No recommendation given.</p>
            @endif

            <h3>Statement of competency</h3>

            <p>[ 
                @if ($package->complete === 'A')  
                    <span class="checkmark" style="position: relative;">
                        <div class="checkmark_stem"></div>
                        <div class="checkmark_kick"></div>
                    </span>
                @endif
            ] <strong>Statement of Competency</strong> - I agree with the recommendation and that the details have been reviewed, the objectives have been met and that the Lesson Package is complete.</p>

            <p>[ 
                @if ($package->complete === 'B')  
                    <span class="checkmark" style="position: relative;">
                        <div class="checkmark_stem"></div>
                        <div class="checkmark_kick"></div>
                    </span>
                @endif
            ] <strong>Exemption</strong> - I agree with the recommendation and not all objectives were expected to be completed for this lesson package. This does not exempt the candidate from the Lesson Package objectives should Management decide it is required at any time.</p>

            @if ($package->complete)

                <p>
                    <small>
                        <strong>Signed of by:</strong> {{ $packageMeta[$package->lesson->name]['signed_off_by'] }} 
                        ({{ $packageMeta[$package->lesson->name]['signed_off_by_role'] }}) on 
                        {{ $package->signed_off_at->format('m/d/Y') }}
                    </small>
                </p>

            @endif

            @if ($package->comment)

                <h4>Comment</h4>

                <p>
                    <small>
                        <strong>Written by:</strong> {{ $packageMeta[$package->lesson->name]['commented_by'] }} 
                        ({{ $packageMeta[$package->lesson->name]['commented_by_role'] }}) on 
                        {{ $package->commented_at->format('m/d/Y') }}
                    </small>
                </p>

                {!! $package->comment !!}

            @endif
            
            @if (!$loop->last)
                <hr style="visibility: hidden; page-break-after: always;">
            @endif
        @endforeach
    </body>
</html>