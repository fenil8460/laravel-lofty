                                @php
                                $day_total = 0;
                                $dails_total = 0;
                                $talktime_total = 0;
                                $qualifiedPitch_total = 0;
                                $hookAccepted_total = 0;
                                @endphp
                                <!-- Single reps report data -->
                                @foreach($r_data as $datas)
                                @php
                                $name = isset($datas->name) ? $datas->name : null;
                                $days = isset($t_days) ? $t_days : 0;
                                $day_total = $day_total + $days;
                                $dails = isset($datas->repname['dails']) ? $datas->repname['dails'] : 0;
                                $dails_total = $dails_total + $dails;
                                $talktime = isset($datas->repname['talktime']) ? round($datas->repname['talktime']/60) : 0;
                                $talktime_total = $talktime_total + $talktime;
                                $hookReject = isset($datas->sentiment['Hook Rejected']) ? $datas->sentiment['Hook Rejected'] : 0;
                                $decisionMaker = isset($datas->disposition['Decision Maker']) ? $datas->disposition['Decision Maker'] : 0;
                                $confirmation_call = isset($datas->disposition['Confirmation Call']) ? $datas->disposition['Confirmation Call'] : 0;
                                $influencer = isset($datas->disposition['Influencer']) ? $datas->disposition['Influencer'] : 0;
                                $demo = isset($datas->sentiment['Demo Scheduled']) ? $datas->sentiment['Demo Scheduled'] : 0;
                                $qualified_count = isset($datas->sentiment['Qualified Pitch']) ? $datas->sentiment['Qualified Pitch'] : 0;
                                $pitchRejected = isset($datas->sentiment['Pitch Rejected']) ? $datas->sentiment['Pitch Rejected'] : 0;
                                $qualifiedPitch = $qualified_count + $demo;
                                @endphp
                                <tr>
                                    <td value={{isset($datas->salesloft_user_id)?$datas->salesloft_user_id:''}}>{{$name}}</td>
                                    <td>{{$days}}</td>
                                    <td>{{number_format($dails)}}</td>
                                    <td>
                                        <!-- Dails/day team -->
                                        @php
                                        if($dails == 0 || $days == 0){
                                        echo 0;
                                        }else{
                                        $dails_day = number_format($dails / $days,2);
                                        echo $dails_day;
                                        }
                                        @endphp
                                    </td>
                                    <td>{{number_format($talktime)}}</td>
                                    <td>
                                        <!-- TT/day -->
                                        @php
                                        if($days == 0 || $talktime == 0){
                                        echo 0;
                                        }else{
                                        $tt_day = number_format($talktime / $days,2);
                                        echo $tt_day;
                                        }
                                        @endphp
                                    </td>
                                    <td>{{number_format($decisionMaker)}}</td>
                                    <td>{{number_format($influencer)}}</td>
                                    <td>
                                        <!-- Connections -->
                                        @php
                                        $connection = $decisionMaker + $influencer;
                                        echo number_format($connection);
                                        @endphp
                                    </td>
                                    <td>
                                        <!-- Connection Rate -->
                                        @php
                                        if($dails == 0 || $connection == 0){
                                        echo 0;
                                        }else{
                                        $connection_rate = number_format($connection/$dails * 100,2);
                                        echo $connection_rate.'%';
                                        }
                                        @endphp
                                    </td>
                                    <td>{{$hookReject}}</td>
                                    <td>
                                        <!-- Hook Accepted -->
                                        @php
                                        $hookAccepted = $pitchRejected + $qualifiedPitch;
                                        $hookAccepted_total = $hookAccepted_total + $hookAccepted;
                                        @endphp
                                        {{number_format($hookAccepted)}}
                                    </td>
                                    <td>
                                        <!-- % Hook Accepted -->
                                        @php
                                        if($hookAccepted == 0 || $connection == 0){
                                        echo 0;
                                        }else{
                                        $hookAccepted_per = number_format(($hookAccepted/$connection) * 100,2);
                                        echo $hookAccepted_per.'%';
                                        }
                                        @endphp
                                    </td>
                                    <td>{{$pitchRejected}}</td>
                                    <td>
                                        <!-- Qualified Pitch -->
                                        @php
                                        $qualifiedPitch_total = $qualifiedPitch_total + $qualifiedPitch;
                                        echo number_format($qualifiedPitch);
                                        @endphp
                                    </td>
                                    <td>
                                        <!-- %Qp -->
                                        @php
                                        if($qualifiedPitch == 0 || $hookAccepted == 0){
                                        echo 0;
                                        }else{
                                        $qp = number_format(($qualifiedPitch / $hookAccepted) * 100,2);
                                        echo $qp.'%';
                                        }
                                        @endphp
                                    </td>
                                    <td>
                                        <!-- % Qp to close -->
                                        @php
                                        if($qualifiedPitch == 0 || $demo == 0){
                                        echo 0;
                                        }else{
                                        $qpClose = number_format(($demo / $qualifiedPitch) * 100,2);
                                        echo $qpClose."%";
                                        }
                                        @endphp
                                    </td>
                                    <td>
                                        <!-- % Connection to close -->
                                        @php
                                        if($connection == 0 || $demo == 0){
                                        echo 0;
                                        }else{
                                        $connection_close = number_format(($demo / $connection) * 100,2);
                                        echo $connection_close."%";
                                        }
                                        @endphp
                                    </td>
                                    <td>{{$demo}}</td>
                                    <td>
                                        <!-- demo/days -->
                                        @php
                                        if($demo == 0 || $days == 0){
                                        echo 0;
                                        }else{
                                        $demo_days = number_format($demo / $days,2);
                                        echo $demo_days;
                                        }
                                        @endphp
                                    </td>
                                    <td>
                                        <!-- dails/demo -->
                                        @php
                                        if($dails == 0 || $demo == 0){
                                        echo 0;
                                        }else{
                                        $demo_dails = number_format($dails / $demo,2);
                                        echo $demo_dails;
                                        }
                                        @endphp
                                    </td>
                                    <td>{{number_format($confirmation_call)}}</td>
                                </tr>
                                @endforeach
                                <!-- all data total -->
                                @php
                                $total_days = isset($t_days) ? $t_days : $day_total;
                                @endphp
                                <tr>
                                    <th>Total</th>
                                    <td>{{$total_days}}</td>
                                    <td>{{number_format($dails_total)}}</td>
                                    <td>
                                        <!-- Dails/Day team  total-->
                                        @php
                                        if($total_days == 0 || $dails_total == 0){
                                        $dails_day_team = 0;
                                        }else{
                                        $dails_day_team = number_format($dails_total/$total_days,2);
                                        }
                                        echo $dails_day_team;
                                        @endphp
                                    </td>
                                    <td>{{number_format($talktime_total)}}</td>
                                    <td>
                                        <!-- TT/Day team  total-->
                                        @php
                                        if($total_days == 0 || $talktime_total == 0){
                                        $tt_day_total = 0;
                                        }else{
                                        $tt_day_total = number_format($talktime_total/$total_days,2);
                                        }
                                        echo $tt_day_total;
                                        @endphp
                                    </td>
                                    <td>{{number_format($r_count['decision_total'])}}</td>
                                    <td>{{number_format($r_count['influencer_total'])}}</td>
                                    <td>{{number_format($r_count['connection_total'])}}</td>
                                    <td>
                                        <!-- total connection rate -->
                                        @php
                                        if($r_count['connection_total'] == 0 || $dails_total == 0){
                                        $cRate_avg = 0;
                                        }else{
                                        $cRate_avg = round($r_count['connection_total']/$dails_total * 100,2);
                                        }
                                        echo number_format($cRate_avg,2).'%';
                                        @endphp
                                    </td>
                                    <td>{{number_format($r_count['hookRejected_total'])}}</td>
                                    <td>{{number_format($hookAccepted_total)}}</td>
                                    <td>
                                        <!-- %hook accepted total -->
                                        @php
                                        if($r_count['connection_total'] == 0 || $hookAccepted_total == 0){
                                        $hookacceptRate_avg = 0;
                                        }else{
                                        $hookacceptRate_avg = round($hookAccepted_total/$r_count['connection_total'] * 100,2);
                                        }
                                        echo number_format($hookacceptRate_avg,2).'%';
                                        @endphp
                                    </td>
                                    <td>{{number_format($r_count['pitchRejected_total'])}}</td>
                                    <td>{{number_format($qualifiedPitch_total)}}</td>
                                    <td>
                                        <!-- %Qp total -->
                                        @php
                                        if($qualifiedPitch_total == 0 || $hookAccepted_total == 0){
                                        $qp_avg = 0;
                                        }else{
                                        $qp_avg = round($qualifiedPitch_total/$hookAccepted_total * 100,2);
                                        }
                                        echo number_format($qp_avg,2).'%';
                                        @endphp
                                    </td>
                                    <td>
                                        <!-- %Qp to close total -->
                                        @php
                                        if($r_count['demo_total'] == 0 || $qualifiedPitch_total == 0){
                                        $qpClose_avg = 0;
                                        }else{
                                        $qpClose_avg = round($r_count['demo_total']/$qualifiedPitch_total*100,2);
                                        }
                                        echo number_format($qpClose_avg,2).'%';
                                        @endphp
                                    </td>
                                    <td>
                                        <!-- %connection to close total -->
                                        @php
                                        if($r_count['demo_total'] == 0 || $r_count['connection_total'] == 0){
                                        $connectionClose_avg = 0;
                                        }else{
                                        $connectionClose_avg = round($r_count['demo_total']/$r_count['connection_total'] * 100,2);
                                        }
                                        echo number_format($connectionClose_avg,2).'%';
                                        @endphp
                                    </td>
                                    <td>{{$r_count['demo_total']}}</td>
                                    <td>
                                        <!-- Demos/Day total -->
                                        @php
                                        if($r_count['demo_total'] == 0 || $total_days == 0){
                                        $demos_days = 0;
                                        }else{
                                        $demos_days = round($r_count['demo_total']/$total_days,2);
                                        }
                                        echo number_format($demos_days,2);
                                        @endphp
                                    </td>
                                    <td>
                                        <!-- dials/Demos total -->
                                        @php
                                        if($r_count['demo_total'] == 0 || $dails_total == 0){
                                        $dails_demo = 0;
                                        }else{
                                        $dails_demo = round($dails_total/$r_count['demo_total'],2);
                                        }
                                        echo number_format($dails_demo,2);
                                        @endphp
                                    </td>
                                    <td>{{number_format($r_count['confirmation_call_total'])}}</td>
                                </tr>