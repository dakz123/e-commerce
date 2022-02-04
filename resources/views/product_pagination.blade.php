<table class="table table-striped">
<thead>
    <tr>
    <th>ID</th>
      <th>Product_Name</th>
      <th>Product_Image</th>
         <th>Action</th>
      

 </tr>
  </thead>
  <tbody>
  @if(!empty($product) && $product->count())
   @foreach($product as $row)
   <tr>
 <td>{{ $row->id }}</td>
 <td>{{ $row->product_name }}</td>
 
 <td><img src="{{asset($row->product_image)}}"height="200" width="200" class="img-thumbnail"/></td>
 <td><i id="{{ $row->id }}" class="edit_product fas fa-edit text-success px-2 " style="cursor:pointer"></i>
 <i id="{{ $row->id }}" class="delete_product fas fa-times text-danger" style="cursor:pointer"></i> </td>
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
 {!! $product->links() !!}
 </div>
</div>