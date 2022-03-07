@php
$dails_total = 0;
$qualifiedPitch_total = 0;
$hookAccepted_total = 0;
$talktime_total = 0;
@endphp
<!-- Executive report data -->
@foreach($e_data as $datas)
@php
$days = isset($datas->days) ? $datas->days : 0;
$talktime = isset($datas->talktime) ? round($datas->talktime/60) : 0;
$talktime_total = $talktime_total + $talktime;
$hookReject = isset($datas->sentiment['Hook Rejected']) ? $datas->sentiment['Hook Rejected'] : 0;
$decisionMaker = isset($datas->disposition['Decision Maker']) ? $datas->disposition['Decision Maker'] : 0;
$influencer = isset($datas->disposition['Influencer']) ? $datas->disposition['Influencer'] : 0;
$dails = isset($datas->dails) ? $datas->dails : 0;
$dails_total = $dails_total + $dails;
$demo = isset($datas->sentiment['Demo Scheduled']) ? $datas->sentiment['Demo Scheduled'] : 0;
$qualified_count = isset($datas->sentiment['Qualified Pitch']) ? $datas->sentiment['Qualified Pitch'] : 0;
$pitchRejected = isset($datas->sentiment['Pitch Rejected']) ? $datas->sentiment['Pitch Rejected'] : 0;
$qualifiedPitch = $qualified_count + $demo;
@endphp
<tr>
    <td>{{isset($datas->team_total) ? $datas->team_total : null}}</td>
    <td></td>
    <td>{{$days}}</td>
    <td>{{number_format($dails)}}</td>
    <td>
        <!-- Dails/day team -->
        @php
        if($dails == 0 || $days == 0){
        echo 0;
        }else{
        $dails_day = Round($dails / $days,2);
        echo number_format($dails_day,2);
        }
        @endphp
    </td>
    <td></td>
    <td>{{number_format($talktime)}}</td>
    <td>
        <!-- TT/day -->
        @php
        if($days == 0 || $talktime == 0){
        echo 0;
        }else{
        $tt_day = $talktime / $days;
        echo number_format($tt_day,2);
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
        $hookAccepted_per = number_format($hookAccepted/$connection * 100,2);
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
        $qp = number_format($qualifiedPitch / $hookAccepted * 100,2);
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
        $qpClose = number_format($demo / $qualifiedPitch * 100,2);
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
        $connection_close = number_format($demo / $connection * 100,2);
        echo $connection_close."%";
        }
        @endphp
    </td>
</tr>
@endforeach
<tr>
    <!--executive report total count in some calculation is this blade file and some calculation available in controller-->
    <th>Total</th>
    <td></td>
    <td>{{number_format($executive_count['day_total'])}}</td>
    <td>{{number_format($dails_total)}}</td>
    <td>
        <!-- Dails/Day team  total-->
        @php
        if($executive_count['day_total'] == 0 || $dails_total == 0){
        $dails_day_team = 0;
        }else{
        $dails_day_team = number_format($dails_total/$executive_count['day_total'],2);
        }
        echo $dails_day_team;
        @endphp
    </td>
    <td></td>
    <td>{{number_format($talktime_total)}}</td>
    <td>
        <!-- TT/Day team  total-->
        @php
        if($executive_count['day_total'] == 0 || $talktime_total == 0){
        $tt_day_total = 0;
        }else{
        $tt_day_total = number_format($talktime_total/$executive_count['day_total'],2);
        }
        echo $tt_day_total;
        @endphp
    </td>
    <td>{{number_format($executive_count['decision_total'])}}</td>
    <td>{{number_format($executive_count['influencer_total'])}}</td>
    <td>{{number_format($executive_count['connection_total'])}}</td>
    <td>
        <!-- total connection rate -->
        @php
        if($executive_count['connection_total'] == 0 || $dails_total == 0){
        $cRate_avg = 0;
        }else{
        $cRate_avg = round($executive_count['connection_total']/$dails_total * 100,2);
        }
        echo number_format($cRate_avg,2).'%';
        @endphp
    </td>
    <td>{{number_format($executive_count['hookRejected_total'])}}</td>
    <td>{{number_format($hookAccepted_total)}}</td>
    <td>
        <!-- %hook accepted total -->
        @php
        if($executive_count['connection_total'] == 0 || $hookAccepted_total == 0){
        $hookacceptRate_avg = 0;
        }else{
        $hookacceptRate_avg = round($hookAccepted_total/$executive_count['connection_total'] * 100,2);
        }
        echo number_format($hookacceptRate_avg,2).'%';
        @endphp
    </td>
    <td>{{number_format($executive_count['pitchRejected_total'])}}</td>
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
        if($executive_count['demo_total'] == 0 || $qualifiedPitch_total == 0){
        $qpClose_avg = 0;
        }else{
        $qpClose_avg = round($executive_count['demo_total']/$qualifiedPitch_total*100,2);
        }
        echo number_format($qpClose_avg,2).'%';
        @endphp
    </td>
    <td>
        <!-- %connection to close total -->
        @php
        if($executive_count['demo_total'] == 0 || $executive_count['connection_total'] == 0){
        $connectionClose_avg = 0;
        }else{
        $connectionClose_avg = round($executive_count['demo_total']/$executive_count['connection_total'] * 100,2);
        }
        echo number_format($connectionClose_avg,2).'%';
        @endphp
    </td>
</tr>