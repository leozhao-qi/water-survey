<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

        <style>
            body { margin: 0; padding: 02; min.5width: 100%; width: 100% !important; height: 100% !important;}
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
        
        <p style="font-family: sans-serif; width: 100%; font-size: 1rem; margin-bottom: .25rem;">
            <strong>Name:</strong> 
            <a 
                href="{{ env('APP_URL') }}/users/{{ $user['id'] }}"
                style="color: #4299E1; text-decoration: none;"
            >{{ $user['fullname'] }}</a>
        </p>

        <p style="font-family: sans-serif; width: 100%; font-size: 1rem; margin-bottom: .25rem;">
            <strong>Appointment date:</strong> {{ $user->appointment_date->format('m/d/y') }}
        </p>

        <p style="font-family: sans-serif; width: 100%; font-size: 1rem; margin-bottom: 1rem;">
            <strong>Created on:</strong> {{ Carbon\Carbon::now()->format('m/d/y') }}
        </p>
        
        <table style="width: 100%; font-family: sans-serif;">
            <thead>
                <tr>
                    <th style="padding: .25rem .5rem; border: 1px solid #e2e8f0"></th>
        
                    <th style="padding: .25rem .5rem; border: 1px solid #e2e8f0;" colspan="2">
                        {{ strtoupper('Training sections / Lesson packages') }}
                    </th>
        
                    <th style="padding: .25rem .5rem; border: 1px solid #e2e8f0" colspan="2">EG03</th>
        
                    <th style="padding: .25rem .5rem; border: 1px solid #e2e8f0" colspan="2">EG04</th>
        
                    <th style="padding: .25rem .5rem; border: 1px solid #e2e8f0">Status</th>
                </tr>
        
                <tr>
                    <th style="padding: .25rem .5rem; border: 1px solid #e2e8f0"></th>
        
                    <th style="padding: .25rem .5rem; border: 1px solid #e2e8f0; text-align: left;" colspan="2">
                        All training identified scheduled to be completed in EG level but some maybe deferred to EG04 approved by a manager
                    </th>
        
                    <th style="padding: .25rem .5rem; border: 1px solid #e2e8f0">Theory</th>
        
                    <th style="padding: .25rem .5rem; border: 1px solid #e2e8f0">Practical</th>
        
                    <th style="padding: .25rem .5rem; border: 1px solid #e2e8f0">Theory</th>
        
                    <th style="padding: .25rem .5rem; border: 1px solid #e2e8f0">Practical</th>
        
                    <th style="padding: .25rem .5rem; border: 1px solid #e2e8f0"></th>
                </tr>
            </thead>
        
            <tbody>
        
                @foreach($topics as $topic)
        
                        <tr>
                            <td style="padding: .25rem .5rem; border: 1px solid #e2e8f0; text-align: center;">
                                <strong>{{ $topic->number }}.00</strong>
                            </td>
        
                            <td style="padding: .25rem .5rem; border: 1px solid #e2e8f0" colspan="7">
                                <strong>{{ $topic->name }}</strong>
                            </td>
                        </tr>
        
                        @foreach($packages[$topic->number] as $package)
        
                            <tr>
                                <td style="padding: .25rem .5rem; border: 1px solid #e2e8f0"></td>
        
                                <td style="padding: .25rem .5rem; border: 1px solid #bcc9da; width: 10%; text-align: center;">
                                    {{ $package['lesson_number'] }}
                                </td>
        
                                <td style="padding: .25rem .5rem; border: 1px solid #e2e8f0; width: 90%;">
                                    <a 
                                        href="{{ env('APP_URL') }}/users/{{ $package['user_id'] }}/packages/{{ $package['id'] }}"
                                        style="color: #4299E1; text-decoration: none;"
                                    >
                                        {{ $package['lesson_name'] }}
                                    </a>
                                    <strong>{{ $package['completed_in_both'] ? '*' : '' }}</strong>
                                </td>
        
                                <td 
                                    style="padding: .25rem .5rem; border: 1px solid #e2e8f0; {{ !$package['eg3_theory'] ? 'background-color: #CBD5E0;' : '' }}"
                                >{{ $package['theory_status_eg3'] }}</td>
        
                                <td 
                                    style="padding: .25rem .5rem; border: 1px solid #e2e8f0; {{ !$package['eg3_practical'] ? 'background-color: #CBD5E0;' : '' }}"
                                >{{ $package['practical_status_eg3'] }}</td>
        
                                <td 
                                    style="padding: .25rem .5rem; border: 1px solid #e2e8f0; {{ !$package['eg4_theory'] ? 'background-color: #CBD5E0;' : '' }}"
                                >{{ $package['theory_status_eg4'] }}</td>
        
                                <td 
                                    style="padding: .25rem .5rem; border: 1px solid #e2e8f0; {{ !$package['eg4_practical'] ? 'background-color: #CBD5E0;' : '' }}"
                                >{{ $package['practical_status_eg4'] }}</td>
        
                                <td 
                                    style="padding: .25rem .5rem; border: 1px solid #e2e8f0; text-align: center;"
                                >
                                    {{ $package['status'] }}
                                </td>
                            </tr>
                        
                        @endforeach
        
                @endforeach
        
            </tbody>
        </table>
    </body>
</html>