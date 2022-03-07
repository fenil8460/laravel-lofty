@extends('master')
@section('title-section')
<title>Lofty Data | Groups</title>
@endsection
@section('main-section')
<style>
    ul#ui-id-1 {
        width: 300px !important;
        background-color: #fff;
        border: 1px solid #dee2e6;
        padding: 0px;

    }

    ul#ui-id-1 li {
        list-style: none;
        padding: 5px 10px;
        transition: all 0.5s;
    }

    ul#ui-id-1 li:hover {
        background-color: #3d73dd;
        color: #fff;
    }

    label.form-label {
        width: 100%;
    }

    .needs-validation .form-switch {
        margin-bottom: 10px;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            @php
            $title = "Add Group";
            if(isset($data)){
            $title = "Update Group";
            }
            @endphp
            <h4 class="page-title">{{$title}}</h4>
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="tab-content">
                    @if(isset($data))
                    <form class="needs-validation" action="{{route('update-group')}}" method='post' novalidate>
                        <input type="hidden" name="group_id" value="{{$data[0]->id}}">
                        @else
                        <form class="needs-validation" action="{{route('add-group')}}" method='post' novalidate>
                            @endif
                            @csrf
                            <!--   -->
                            <div class="mb-3 col-xl-5">
                                <label class="form-label" for="validationCustom01">Group Name</label>
                                <input type="text" class="form-control group_name" name="group_name" value="{{isset($data[0]->group_name) ? $data[0]->group_name : old('group_name')}}" 
                                {{isset($data[0]->group_name) && $data[0]->group_name == 'All' ? 'readonly' : ''}}
                                id="validationCustom01" placeholder="Enter Group Name" required>
                                <div class="invalid-feedback">
                                    Please enter a groupname.
                                </div>
                            </div>
                            <input type="hidden" value="{{isset($data[0]->id) ? $data[0]->id : ''}}" name="group_id">
                            <div class="mb-3 col-xl-5">
                                <label class="form-label" for="validationCustom01">Assign Lofty Users to Group</label>
                                <select name="user_name[]" class="select2 form-control select2-multiple" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                                    @foreach($g_data['users'] as $datas)
                                    @if(isset($data[0]->user_id) && in_array($datas->id,$data[0]->user_id))
                                    <option selected value="{{$datas->id}}">{{$datas->name}}</option>
                                    @else
                                    <option value="{{$datas->id}}">{{$datas->name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Please enter a user.
                                </div>
                            </div>

                            <div class="mb-3 col-xl-5">
                                <label class="form-label" for="validationCustom01">Choose Cadences</label>
                                <select name="cadence[]" class="select2 form-control select2-multiple" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                                    @foreach($g_data['cadences'] as $datas)
                                    @if(isset($data[0]->cadence_ids) && in_array($datas->sl_cadence_id,$data[0]->cadence_ids))
                                    <option selected value="{{$datas->sl_cadence_id}}">{{$datas->name}}</option>
                                    @else
                                    <option value="{{$datas->sl_cadence_id}}">{{$datas->name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Please enter a user.
                                </div>
                            </div>

                            <div class="mb-3 col-xl-5">
                                <label class="form-label" for="validationCustom01">Choose SalesLoft Reps</label>
                                <select name="user_reps[]" class="select2 form-control select2-multiple" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                                    @foreach($g_data['slusers'] as $datas)
                                    @if(isset($data[0]->reps_ids) && in_array($datas->salesloft_user_id,$data[0]->reps_ids))
                                    <option selected value="{{$datas->salesloft_user_id}}">{{$datas->name}}</option>
                                    @else
                                    <option value="{{$datas->salesloft_user_id}}">{{$datas->name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Please enter a user.
                                </div>
                            </div>

                            <input type="hidden" name="user_id" id="user-id">
                            <button class="btn btn-primary btn-color" id="addgroupd" type="submit">{{$title}}</button>
                        </form>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    @endsection
    @section('script-section')
    <script>
        $('#user').attr('aria-expanded', 'true');
        $('#user').attr('style', 'color : #3d73dd !important');
        $('#group-link').attr('style', 'color : #3d73dd !important');
        $('#user').removeClass('collapsed');
        $('#sidebarTables').addClass('show');
    </script>
    @endsection
