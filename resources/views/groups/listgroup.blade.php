@extends('master')
@section('title-section')
<title>Lofty Data | Users</title>
@endsection
@section('main-section')
<style>
    .page-title-box.save-btn {
        text-align: right;
    }
</style>
<div class="row align-items-center">
    <div class="col-12 col-md-6">
        <div class="page-title-box">
            <h4 class="page-title">Groups</h4>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="page-title-box save-btn">
            <a class="btn btn-primary btn-color" href="{{route('groups')}}"><i class="uil-user-plus"></i>Add Group</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane show active" id="striped-rows-preview">
                        <div class="table-responsive-sm">
                            <!-- ajax call in search input box -->
                            <label class="form-label show-entries">Show
                                <select name="basic-datatable_length" id="show-entries" aria-controls="basic-datatable" class="custom-select custom-select-sm form-control form-control-sm form-select form-select-sm">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select> entries</label>
                            <input type="text" value="{{isset($search)?$search:''}}" id="search-value" name="search_input" class="form-control searchinput" placeholder="Search" aria-label="Recipient's username">
                            <!-- <a  href="/insertrole">
                                    <button class="btn btn-primary adduserbtn">Add User</button>
                                </a> -->
                            <!-- end ajax call -->
                            <div id="table_data">
                                @include('groups.group')
                            </div>
                        </div> <!-- end table-responsive-->
                    </div>
                </div> <!-- end tab-content-->
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
@endsection
@section('script-section')
<script>
    //ajax call in searching 
    $(document).ready(function() {

        $(document).on('click', '#deletedata', function() {
            if (!confirm("Are you sure want to delete this user?")) {
                return false;
            }
        });

        $(document).on('click', '#show-entries', function(event) {
            event.preventDefault();
            var search = $('#search-value').val();
            var limit = $(this).find(":selected").val();
            fetch_data(1, search, limit);
        });

        $(document).on('click', 'a.page-link', function(event) {
            event.preventDefault();
            var search = $('#search-value').val();
            var limit = $('#show-entries').find(":selected").val();
            var page = $(this).attr('href').split('page=')[1];
            fetch_data(page, search, limit);
        });

        $(document).on('keyup', '#search-value', function(event) {
            event.preventDefault();
            var search = $('#search-value').val();
            var limit = $('#show-entries').find(":selected").val();
            fetch_data(1, search, limit);
        });
        var currentRequest = null;

        function fetch_data(page, search, limit) {
            var _token = $("input[name=_token]").val();
            currentRequest = $.ajax({
                url: "searchgroups?page=" + page + "&search=" + search + "&limit=" + limit,
                beforeSend: function() {
                    if (currentRequest != null) {
                        currentRequest.abort();
                    }
                },
                success: function(data) {
                    $('#table_data').html(data);
                    console.log(data);
                }
            });
        }

    });
</script>
@endsection