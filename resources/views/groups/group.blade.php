<table class="table dt-responsive nowrap">
    <thead>
        <tr>
            <th>Group Id</th>
            <th>Group Name</th>
            <th>User Name</th>
            <!-- <th>Campaigns</th>
            <th>Reps</th> -->
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
      @foreach($listgroup as $data)
      @php
      $campaigns = "Disabled";
      $reps = "Disabled";
      if($data->campaigns == 1){
        $campaigns = "Enabled";
      }
      if($data->reps == 1){
        $reps = "Enabled";
      }
      @endphp
        <tr>
            <td>{{$data->id}}</td>
            <td>{{$data->group_name}}</td>
            <td>{{preg_replace('/(?<!\d),|,(?!\d{3})/', ', ', $data->user_name)}}</td>
            <td class="table-action">
                <a class="action-icon" href="{{route('viewspecific-group',['id'=> $data->id])}}"><i class="mdi mdi-pencil"></i></a>
                <a class="action-icon" {{($data->group_name == 'All') ? "id=all-delete" : ''}} id="deletedata" href="{{route('delete-group',['id'=> $data->id])}}"><i class="mdi mdi-delete"></i></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="pagination-information">
    <div class="dataTables_info user-pagination">
        Showing {{$listgroup->firstItem()}} to {{$listgroup->lastItem()}} of {{$listgroup->total()}} entries
    </div>
    <div class="pagination">
        {!! $listgroup->links() !!}
    </div>
</div>