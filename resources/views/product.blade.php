@extends('layouts.app')
@section('content')
<!--Add Product Modal -->
<div class="modal fade" id="AddProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form method="post" id="add_form" enctype="multipart/form-data" action="">
        
        <div class="alert" id="message" style="display: none"></div>
        <div class="form-group">
            <label for="category">Category:</label>
            <select  name="category" id= "category" class="form-control">
                <option value="">Please Select a Category</option>
                @foreach($category as $row)
<option value="{{$row->id}}">{{$row->category_name}}</option>
                @endforeach
                  </select>
        </div>
        <div class="form-group" >
            <label for="product_name">Product_Name:</label>
            <input type="text" name="product_name" id="product_name" class="form-control" >
        </div>
       
        <div class="form-group">
            <label for="product_image">Product_Image:</label>
            <input type="file" name="product_image" id="product_image" class="form-control" >
        </div>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class=" add_product btn btn-primary">Save </button>
      </div>
      </form>
    </div>
  </div>
</div>
<!--End Add Product Modal -->


<!--Edit Product Modal -->
<div class="modal fade" id="EditProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form  method="post" id="update_form" enctype="multipart/form-data" action="">
     
     
     <div class="alert" id="message_edit" style="display: none"></div>
     <input type="hidden" id="edit_product_id"/>
     <div class="form-group">
         <label for="category">Category:</label>
         <select  name="edit_category" id= "edit_category" class="form-control">
             <option value="">Please Select a Category</option>
             @foreach($category as $row)
<option value="{{$row->id}}">{{$row->category_name}}</option>
                @endforeach
               </select>
     </div>
     <div class="form-group" >
         <label for="edit_product_name">Product Name:</label>
         <input type="text" name="edit_product_name" id="edit_product_name" class="form-control" >
     </div>
    
     <div class="form-group">
         <label for="edit_product_image"> Product Image:</label>
         <input type="file" name="edit_product_image" id="edit_product_image" class="form-control" >
     </div>
    
    
    
 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class=" update_product btn btn-primary">Update</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!--End Edit Product Modal -->


<!-- Delete Product Modal -->
<div class="modal fade" id="DeleteProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <input type="hidden" id="delete_product_id"/>
      <h4>Are you sure ? want to delete this data ?</h4>
     
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class=" delete_product_data btn btn-primary">Delete</button>
      </div>
    </div>
  </div>
</div>
<!--End Delete Product Modal -->

<div class="container py-2">
    <div class="row">
        <div class="col-md-12">
          <div id="success_message"></div>
<div class="card">
    <div class="card-header">
        <h4>
Product
        <button class="btn btn-primary fas fa-plus   float-right" data-toggle="modal" data-target="#AddProductModal" >Product</button> 
        </h4>

    </div>
    <div class="card-body">
    <div id="table_data">
 @include('product_pagination')
    </div>


    </div>
</div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function(){
  fetchproduct();
  $(document).on('click', '.pagination a', function(event){
  event.preventDefault(); 
  var page = $(this).attr('href').split('page=')[1];
  fetchproduct(page);
 });
 
 function fetchproduct(page)
 {
  $.ajax({
    type:"GET",
    url:"product/create?page="+page,
   success:function(data)
   {
    $('#table_data').html(data);
   }
  });
 }

 $('#add_form').on('submit', function(e){

e.preventDefault();
$.ajaxSetup({
    headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$.ajax({
 url:"/product",
 method:"POST",
 data:new FormData(this),
 dataType:'JSON',
 contentType: false,
 cache: false,
 processData: false,
 success:function(data)
 {
     console.log(data);
  $('#message').css('display', 'block');
  $('#message').html(data.message);
  $('#message').addClass(data.class_name);
  $('#AddProductModal').modal('hide');
  $('#AddProductModal').find('input').val('');
  fetchproduct();
 }
})
});
//Data Edition
$(document).on('click','.edit_product', function (e) {
    e.preventDefault();
    let product_id = $(this).attr("id");
   // var stud_id=$(this).val();
    
    $('#EditProductModal').modal('show');
    $.ajax({
      type: "GET",
      url: "/product/"+product_id+"/edit",
      success: function (response) {
        //console.log(response);
        if(response.status==404){
         
          $('#success_message').html('');
          $('#success_message').addClass('alert alert-danger');
          $('#success_message').text(response.message);
          $('#success_message').delay(10000).fadeOut('slow');
        }
        else{
          
          $('#edit_category').val(response.product.category_id);
          $('#edit_product_name').val(response.product.product_name);
          
          $('#edit_product_image').val('');
          $('#edit_product_id').val(product_id);
        }
        
      }
    });
   });
    // Data Updation
$(document).on('submit','#update_form', function (e) {
    e.preventDefault();
    let product_id=$('#edit_product_id').val();
    
    
    let data = new FormData();
    data.append('edit_category_id', $('#edit_category').val());
    data.append('edit_product_name', $('#edit_product_name').val());
   
        data.append('edit_product_image', edit_product_image.files[0]);
        data.append('_method', 'put');
        
    $.ajaxSetup({
    headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$.ajax({
                    url: "/product/"+product_id,
                    type: 'post',
                    data: data,
                    cache: false,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        $('#message_edit').css('display', 'block');
  $('#message_edit').html(response.message);
   $('#message_edit').addClass(response.class_name);
   $('#EditProductModal').modal('hide');
  $('#EditProductModal').find('input').val('');
  fetchproduct(); 
                    }
               });
});
//Delete Data
$(document).on('click','.delete_product', function (e) {
    e.preventDefault();
    
    //var stud_id=$(this).val();
    let product_id = $(this).attr("id");
    $('#delete_product_id').val(product_id);
    $('#DeleteProductModal').modal('show'); 
   });
   $(document).on('click','.delete_product_data', function (e) {
    e.preventDefault();
    $(this).text('Deleting...')
    let product_id=$('#delete_product_id').val();
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
    $.ajax({
      type: "DELETE",
      url: "/product/"+product_id,
       success: function (response) {
        
        $('#DeleteProductModal').modal('hide'); 
        
        fetchproduct();
      }
    });
   });
    });
</script>


@endsection

