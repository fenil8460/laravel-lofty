                        @php
                        $decision_total = 0;
                        $influencer_total = 0;
                        $connection_total = 0;
                        $hookRejected_total = 0;
                        $demo_total = 0;
                        $c_dial_total = 0;
                        $c_total_leads = 0;
                        $hookAccepted_total = 0;
                        $pitchRejected_total = 0;
                        $qualifiedPitch_total = 0;
                        @endphp
                        <!-- cadence report data -->
                        @foreach($c_data as $datas)
                        @php

                        $c_dails = isset($datas->dails) ? $datas->dails : 0;
                        $c_decision = isset($datas->disposition['Decision Maker']) ? $datas->disposition['Decision Maker'] : 0;
                        $decision_total = $decision_total + $c_decision;
                        $c_influencer = isset($datas->disposition['Influencer']) ? $datas->disposition['Influencer'] : 0;
                        $influencer_total = $influencer_total + $c_influencer;
                        $c_hookRejected = isset($datas->sentiment['Hook Rejected']) ? $datas->sentiment['Hook Rejected'] : 0;
                        $hookRejected_total = $hookRejected_total + $c_hookRejected;
                        $c_pitchRejected = isset($datas->sentiment['Pitch Rejected']) ? $datas->sentiment['Pitch Rejected'] : 0;
                        $pitchRejected_total = $pitchRejected_total + $c_pitchRejected;
                        $c_qualified_Pitch = isset($datas->sentiment['Qualified Pitch']) ? $datas->sentiment['Qualified Pitch'] : 0;
                        $c_demo = isset($datas->sentiment['Demo Scheduled']) ? $datas->sentiment['Demo Scheduled'] : 0;
                        $demo_total = $demo_total + $c_demo;
                        $c_qualifiedPitch = $c_qualified_Pitch + $c_demo;
                        $qualifiedPitch_total = $qualifiedPitch_total + $c_qualifiedPitch;
                        @endphp
                        <tr>
                            <input type="hidden" name="" valeu="{{isset($datas->cadence_id) ? $datas->cadence_id : 0}}">
                            <td data-sl_account_id="{{$datas->salesLoft_account_id}}">{{$datas->name}}</td>
                            <td>
                                <!-- helper function (get data using peopleApi)-->
                                @php
                                $leads_count = isset($datas->leads) ? $datas->leads : 0;
                                $c_total_leads = $c_total_leads + $leads_count;
                                echo number_format($leads_count);
                                @endphp
                            </td>
                            <td>@php
                                echo number_format($c_dails);
                                $c_dial_total = $c_dial_total + $c_dails;
                                @endphp
                            </td>
                            <td>{{number_format($c_decision)}}</td>
                            <td>{{number_format($c_influencer)}}</td>
                            <td>
                                <!-- Connection -->
                                @php
                                $c_connection = $c_decision + $c_influencer;
                                $connection_total = $connection_total + $c_connection;
                                echo number_format($c_connection);
                                @endphp
                            </td>
                            <td>
                                <!-- Connection rate -->
                                @php
                                if($c_connection == 0 || $c_dails == 0){
                                echo 0;
                                }
                                else{
                                $c_connectionRate = $c_connection / $c_dails * 100;
                                echo number_format($c_connectionRate,2).'%';
                                }
                                @endphp
                            </td>
                            <td>{{$c_hookRejected}}</td>
                            <td>
                                <!-- Hook Accepted -->
                                @php
                                $c_hookAccepted = $c_pitchRejected + $c_qualifiedPitch;
                                $hookAccepted_total = $hookAccepted_total + $c_hookAccepted;
                                echo number_format($c_hookAccepted);
                                @endphp
                            </td>
                            <td>
                                <!-- % Hook Accepted -->
                                @php
                                if($c_hookAccepted == 0 || $c_connection == 0){
                                echo 0;
                                }
                                else{
                                $c_hookAccepted_per = number_format(($c_hookAccepted / $c_connection) * 100,2);
                                echo $c_hookAccepted_per.'%';
                                }
                                @endphp
                            </td>
                            <td>{{$c_pitchRejected}}</td>
                            <td>{{$c_qualifiedPitch}}</td>
                            <td>
                                <!-- % Qp -->
                                @php
                                if($c_qualifiedPitch == 0 || $c_hookAccepted == 0){
                                echo 0;
                                }else{
                                $qp = number_format(($c_qualifiedPitch / $c_hookAccepted)*100,2);
                                echo $qp.'%';
                                }
                                @endphp
                            </td>
                            <td>{{$c_demo}}</td>
                            <td>
                                <!-- dails/demo -->
                                @php
                                if($c_dails == 0 || $c_demo == 0){
                                echo 0;
                                }else{
                                $c_demo_dails = number_format($c_dails / $c_demo,2);
                                echo $c_demo_dails;
                                }
                                @endphp
                            </td>
                            <td>
                                <!-- % Qp to close -->
                                @php
                                if($c_demo == 0 || $c_qualifiedPitch == 0){
                                echo 0;
                                }else{
                                $c_qp_close = number_format(($c_demo / $c_qualifiedPitch)*100,2);
                                echo $c_qp_close."%";
                                }
                                @endphp
                            </td>
                            <td>
                                <!-- % Connection to close -->
                                @php
                                if($c_demo == 0 || $c_connection == 0){
                                echo 0;
                                }else{
                                $c_connection_close = number_format(($c_demo / $c_connection) * 100,2);
                                echo $c_connection_close."%";
                                }
                                @endphp
                            </td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        @endforeach
                        <tr>
                            <!--cadence report total data in some calculation is this blade file and some calculation available in controller-->
                            <th>Total</th>
                            <td>{{number_format($c_total_leads)}}</td>
                            <td>{{number_format($c_dial_total)}}</td>
                            <td>{{number_format($decision_total)}}</td>
                            <td>{{number_format($influencer_total)}}</td>
                            <td>{{number_format($connection_total)}}</td>
                            <td>
                                <!-- total connection rate -->
                                @php
                                if($connection_total == 0 || $c_dial_total == 0){
                                $cRate_avg = 0;
                                }else{
                                $cRate_avg = round($connection_total/$c_dial_total * 100,2);
                                }
                                echo number_format($cRate_avg,2).'%';
                                @endphp
                            </td>
                            <td>{{number_format($hookRejected_total)}}</td>
                            <td>{{number_format($hookAccepted_total)}}</td>
                            <td>
                                <!-- %hook accepted total -->
                                @php
                                if($connection_total == 0 || $hookAccepted_total == 0){
                                $hookacceptRate_avg = 0;
                                }else{
                                $hookacceptRate_avg = round($hookAccepted_total/$connection_total * 100,2);
                                }
                                echo number_format($hookacceptRate_avg,2).'%';
                                @endphp
                            </td>
                            <td>{{number_format($pitchRejected_total)}}</td>
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
                            <td>{{number_format($demo_total)}}</td>
                            <td>
                                <!-- dials/Demos total -->
                                @php
                                if($demo_total == 0 || $c_dial_total == 0){
                                $dails_demo = 0;
                                }else{
                                $dails_demo = round($c_dial_total/$demo_total,2);
                                }
                                echo number_format($dails_demo,2);
                                @endphp
                            </td>
                            <td>
                                <!-- %Qp to close total -->
                                @php
                                if($demo_total == 0 || $qualifiedPitch_total == 0){
                                $qpClose_avg = 0;
                                }else{
                                $qpClose_avg = round($demo_total/$qualifiedPitch_total*100,2);
                                }
                                echo number_format($qpClose_avg,2).'%';
                                @endphp
                            </td>
                            <td>
                                <!-- %connection to close total -->
                                @php
                                if($demo_total == 0 || $connection_total == 0){
                                $connectionClose_avg = 0;
                                }else{
                                $connectionClose_avg = round($demo_total/$connection_total * 100,2);
                                }
                                echo number_format($connectionClose_avg,2).'%';
                                @endphp
                            </td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>