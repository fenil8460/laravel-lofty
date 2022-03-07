<table class="table dt-responsive nowrap">
    <thead>
        <tr>
            <th>User Id</th>
            <th>User Name</th>
            <th>User Email</th>
            <th>User Role</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $datas)
        <tr>
            <td>{{$datas->id}}</td>
            <td>{{$datas->name}}</td>
            <td>{{$datas->email}}</td>
            <td>{{$datas->user_role}}</td>
            <td class="table-action">
                <a class="action-icon" href={{"update/".$datas->id}}><i class="mdi mdi-pencil"></i></a>
                <a class="action-icon" id="deletedata" href={{"delete/".$datas->id}}><i class="mdi mdi-delete"></i></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="pagination-information">
    <div class="dataTables_info user-pagination">
        Showing {{$data->firstItem()}} to {{$data->lastItem()}} of {{$data->total()}} entries
    </div>
    <div class="pagination">
        {!! $data->links() !!}
    </div>
</div>