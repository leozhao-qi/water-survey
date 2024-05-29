<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

        <style>
            body { margin: 0; padding: 0; min-width: 100%; width: 100% !important; height: 100% !important;}
            body, table, td, div, p, a { -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; }
            table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse !important; border-spacing: 0; }
            html {
                font-size: 14px;
            }
        </style>
    </head>

    <body>
        <h1 style="font-family: sans-serif; width: 100%; font-size: 1.75rem; margin-bottom: .5rem;">
            Schedule of Training
        </h1>

        <table style="width: 100%; margin-bottom: 1rem;">
            <tbody>
                <tr>
                    <td>
                        <p style="font-family: sans-serif; width: 100%; font-size: 1rem; margin-bottom: -.5rem;">
                            <strong>Name:</strong> 
                            <a 
                                href="{{ env('APP_URL') }}/users/{{ $user['id'] }}"
                                style="color: #4299E1; text-decoration: none;"
                            >{{ $user['fullname'] }}</a>
                        </p>
                
                        <p style="font-family: sans-serif; width: 100%; font-size: 1rem; margin-bottom: -.5rem;">
                            <strong>Appointment date:</strong> {{ $user->appointment_date ? $user->appointment_date->format('m/d/y') : 'No appointment date entered' }}
                        </p>
                
                        <p style="font-family: sans-serif; width: 100%; font-size: 1rem; margin-bottom: -.5rem;">
                            <strong>Version:</strong> {{ $version }}
                        </p>
                    </td>

                    <td>
                        @if (isset($reportingStructure['manager']))
                            <p style="font-family: sans-serif; width: 100%; font-size: 1rem; margin-bottom: -.5rem;">
                                <strong>Manager:</strong> {{ implode(',', $reportingStructure['manager']->pluck('fullname')->toArray()) }}
                            </p>
                        @endif

                        @if (isset($reportingStructure['head_of_operations']))
                            <p style="font-family: sans-serif; width: 100%; font-size: 1rem; margin-bottom: -.5rem;">
                                <strong>Area Head:</strong> {{ implode(',', $reportingStructure['head_of_operations']->pluck('fullname')->toArray()) }}
                            </p>
                        @endif

                        @if (isset($reportingStructure['supervisor']))
                            <p style="font-family: sans-serif; width: 100%; font-size: 1rem; margin-bottom: 0;">
                                <strong>Supervisor:</strong> {{ implode(',', $reportingStructure['supervisor']->pluck('fullname')->toArray()) }}
                            </p>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        <table style="width: 100%; font-family: sans-serif; margin-bottom: 2rem; table-layout: fixed; font-size: .85rem">
            <thead>
                <tr>
                    <th style="padding: .5rem 1rem; border: 1px solid #e2e8f0; width: 30%;">
                        EG03/04 Theory/Practical codes
                    </th>

                    <th style="padding: .5rem 1rem; border: 1px solid #e2e8f0; width: 70%;">
                        RoT Status codes
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td style="padding: .5rem 1rem; border: 1px solid #e2e8f0; width: 30%; vertical-align: top;">
                        <ul>
                            <li style="margin-bottom: .5rem;">
                                <strong>Grey</strong> - Not applicable
                            </li>

                            <li style="margin-bottom: .5rem;">
                                <strong>I</strong> - Incomplete
                            </li>

                            <li style="margin-bottom: .5rem;">
                                <strong>E</strong> - Exempt
                            </li>

                            <li style="margin-bottom: .5rem;">
                                <strong>D</strong> - Deferred
                            </li>

                            <li>
                                <strong>C</strong> - Complete
                            </li>
                        </ul>
                    </td>

                    <td style="padding: .5rem 1rem; border: 1px solid #e2e8f0; width: 70%; vertical-align: top;">
                        <ul>
                            <li style="margin-bottom: .5rem;">
                                <strong>N</strong> - Not complete (recommendation code <strong>B</strong> or No recommendation code and No Final Sign Off).
                            </li>

                            <li style="margin-bottom: .5rem;">
                                <strong>R</strong> - Recommended by supervisor. Recommendation codes A,C or D entered. Final Sign off not yet complete.
                            </li>

                            <li>
                                <strong>A</strong> - Approved. Final Sign off complete.
                            </li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <table style="width: 100%; font-family: sans-serif; font-size: .85rem">
            <thead>
                <tr>
                    <th style="padding: .5rem 1rem; border: 1px solid #e2e8f0;" colspan="2">
                        {{ strtoupper('Lesson packages') }}
                    </th>
        
                    <th style="padding: .5rem 1rem; border: 1px solid #e2e8f0" colspan="2">EG03</th>
        
                    <th style="padding: .5rem 1rem; border: 1px solid #e2e8f0" colspan="2">EG04</th>
        
                    <th style="padding: .5rem 1rem; border: 1px solid #e2e8f0">Status</th>
                </tr>
        
                <tr>
                    <th style="padding: .5rem 1rem; border: 1px solid #e2e8f0; text-align: left;" colspan="2"></th>
        
                    <th style="padding: .5rem 1rem; border: 1px solid #e2e8f0">Theory</th>
        
                    <th style="padding: .5rem 1rem; border: 1px solid #e2e8f0">Practical</th>
        
                    <th style="padding: .5rem 1rem; border: 1px solid #e2e8f0">Theory</th>
        
                    <th style="padding: .5rem 1rem; border: 1px solid #e2e8f0">Practical</th>
        
                    <th style="padding: .5rem 1rem; border: 1px solid #e2e8f0"></th>
                </tr>
            </thead>
        
            <tbody>
        
                @foreach($topics as $topic)
        
                        <tr>
                            <td style="padding: .5rem 1rem; border: 1px solid #e2e8f0" colspan="7">
                                <strong>{{ $topic->number }}.00 - {{ $topic->name }}</strong>
                            </td>
                        </tr>
        
                        @foreach($packages[$topic->number] as $package)
        
                            <tr>
                                <td style="padding: .5rem 1rem; border: 1px solid #bcc9da; width: 10%; text-align: left;">
                                    {{ $topic->number }}.{{ $package['display_number'] }}
                                </td>
        
                                <td style="padding: .5rem 1rem; border: 1px solid #e2e8f0; width: 90%;">
                                    <a 
                                        href="{{ env('APP_URL') }}/users/{{ $package['user_id'] }}/packages/{{ $package['id'] }}"
                                        style="color: #4299E1; text-decoration: none;"
                                    >
                                        {{ $package['lesson_name'] }}
                                    </a>
                                    <strong>{{ $package['completed_in_both'] ? '*' : '' }}</strong>
                                </td>
        
                                <td 
                                    style="padding: .5rem 1rem; border: 1px solid #e2e8f0; text-align: center; {{ !$package['eg3_t'] ? 'background-color: #CBD5E0;' : '' }}"
                                >{{ $package['theory_status_eg3'] }}</td>
        
                                <td 
                                    style="padding: .5rem 1rem; border: 1px solid #e2e8f0; text-align: center; {{ !$package['eg3_p'] ? 'background-color: #CBD5E0;' : '' }}"
                                >{{ $package['practical_status_eg3'] }}</td>
        
                                <td 
                                    style="padding: .5rem 1rem; border: 1px solid #e2e8f0; text-align: center; {{ !$package['eg4_t'] ? 'background-color: #CBD5E0;' : '' }}"
                                >{{ $package['theory_status_eg4'] }}</td>
        
                                <td 
                                    style="padding: .5rem 1rem; border: 1px solid #e2e8f0; text-align: center; {{ !$package['eg4_p'] ? 'background-color: #CBD5E0;' : '' }}"
                                >{{ $package['practical_status_eg4'] }}</td>
        
                                <td 
                                    style="padding: .5rem 1rem; border: 1px solid #e2e8f0; text-align: center;"
                                >
                                    {{ $package['status'] }}
                                </td>
                            </tr>
                        
                        @endforeach
        
                @endforeach
        
            </tbody>
        </table>

        <table style="width: 100%; font-family: sans-serif; table-layout: fixed; margin-top: 5rem;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; width: 50%;">
                        <strong>Recommendation for Promotion from: </strong>
                    </td>

                    <td style="vertical-align: middle; width: 25%;">
                        <span>____</span><span style="margin-left: .5rem;">EG03 to EG04</span>
                    </td>

                    <td style="vertical-align: middle; width: 25%;">
                        <span>____</span><span style="margin-left: .5rem;">EG04 to EG05</span>
                    </td>
                </tr>

                <tr>
                    <td style="vertical-align: top; padding-top: 3rem;">
                        <strong>Signature: </strong>____________________________
                    </td>

                    <td style="vertical-align: middle; padding-top: 3rem;">
                        <strong>Date: </strong>_______________
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>