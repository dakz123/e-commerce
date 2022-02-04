<table class="table table-striped">
<thead>
    <tr>
    <th>ID</th>
      <th>Category_Name</th>
      <th>Category_Description</th>
      <th>Category_Image</th>
         <th>Action</th>
      

 </tr>
  </thead>
  <tbody>
  @if(!empty($category) && $category->count())
   @foreach($category as $row)
   <tr>
 <td>{{ $row->id }}</td>
 <td>{{ $row->category_name }}</td>
 <td>{{ $row->category_description }}</td>
 <td><img src="{{asset($row->category_image)}}"height="100" width="100" class="img-thumbnail"/></td>
 <td><i id="{{ $row->id }}" class="edit_category fas fa-edit text-success px-2 " style="cursor:pointer"></i>
 <i id="{{ $row->id }}" class="delete_category fas fa-times text-danger" style="cursor:pointer"></i> </td>
   </tr>
   @endforeach
 @else
 <tr>
 <td colspan="4">No data found.</td>
 </tr>
 @endif
  </tbody>

</table>
<div class="row">  
 <div class="col-lg-12">
 {!! $category->links() !!}
 </div>
</div>