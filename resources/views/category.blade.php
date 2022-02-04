@extends('layouts.app')
@section('content')
<!--Add Category Modal -->
<div class="modal fade" id="AddCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form method="post" id="add_form" enctype="multipart/form-data" action="">
        
        <div class="alert" id="message" style="display: none"></div>
        
        <div class="form-group" >
            <label for="category_name">Category_Name:</label>
            <input type="text" name="category_name" id="category_name" class="form-control" >
        </div>
        <div class="form-group" >
            <label for="category_description">Category_Description:</label>
            <textarea name="category_description" id="category_description" class="form-control" ></textarea>
        </div>
       
        <div class="form-group">
            <label for="category_image">Category_Image:</label>
            <input type="file" name="category_image" id="category_image" class="form-control" >
        </div>
        
       
        </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <button type="submit" class=" add_category btn btn-primary">Save </button>
        
      </div>
      </form>
      
    </div>
  </div>
</div>
<!--End Add Category Modal --> 


<!--Edit Category Modal -->
<div class="modal fade" id="EditCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form  method="post" id="update_form" enctype="multipart/form-data" action="">
     
     
        <div class="alert" id="message_edit" style="display: none"></div>
        <input type="hidden" id="edit_category_id"/>
        <div class="form-group" >
            <label for="edit_category_name">Category_Name:</label>
            <input type="text" name="edit_category_name" id="edit_category_name" class="form-control" >
        </div>
        <div class="form-group" >
            <label for="edit_category_description">Category_Description:</label>
            <textarea name="edit_category_description" id="edit_category_description" class="form-control" ></textarea>
        </div>
       
        <div class="form-group">
            <label for="edit_category_image">Category_Image:</label>
            <input type="file" name="edit_category_image" id="edit_category_image" class="form-control" >
        </div>
      
       </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class=" update_category btn btn-primary">Update</button>

      </div>
      </form>
    </div>
  </div>
</div>
<!--End Edit Category Modal -->

<!-- Delete Category Modal -->
<div class="modal fade" id="DeleteCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <input type="hidden" id="delete_category_id"/>
      <h4>Are you sure ? want to delete this data ?</h4>
     
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary delete_category_data">Delete</button>
      </div>
    </div>
  </div>
</div>
<!--End Delete Category Modal -->
<div class="container py-2">
    <div class="row">
        <div class="col-md-12">
          <div id="success_message"></div>
<div class="card">
    <div class="card-header">
        <h6>
           Category 
           <button class="btn btn-primary fas fa-plus   float-right" data-toggle="modal" data-target="#AddCategoryModal" >Category</button>  
        </h6>

    </div>
    <div class="card-body">
    <div id="table_data">
 @include('category_pagination')
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
  fetchcategory();
  $(document).on('click', '.pagination a', function(event){
  event.preventDefault(); 
  var page = $(this).attr('href').split('page=')[1];
  fetchcategory(page);
 });
 
 function fetchcategory(page)
 {
  $.ajax({
    type:"GET",
    url:"category/create?page="+page,
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
 url:"/category",
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
  $('#AddCategoryModal').modal('hide');
  $('#AddCategoryModal').find('input').val('');
  fetchcategory();
 }
})
});


//Data Edition
$(document).on('click','.edit_category', function (e) {
    e.preventDefault();
    let category_id = $(this).attr("id");
   // var stud_id=$(this).val();
    
    $('#EditCategoryModal').modal('show');
    $.ajax({
      type: "GET",
      url: "/category/"+category_id+"/edit",
      success: function (response) {
        //console.log(response);
        if(response.status==404){
         
          $('#success_message').html('');
          $('#success_message').addClass('alert alert-danger');
          $('#success_message').text(response.message);
          $('#success_message').delay(10000).fadeOut('slow');
        }
        else{
          
          $('#edit_category_name').val(response.category.category_name);
          $('#edit_category_description').val(response.category.category_description);
          
          $('#edit_category_image').val('');
          $('#edit_category_id').val(category_id);
        }
        
      }
    });
   });
   // Data Updation
$(document).on('submit','#update_form', function (e) {
    e.preventDefault();
    let category_id=$('#edit_category_id').val();
    
    
    let data = new FormData();
    data.append('edit_category_name', $('#edit_category_name').val());
    data.append('edit_category_description', $('#edit_category_description').val());
   
        data.append('edit_category_image', edit_category_image.files[0]);
        data.append('_method', 'put');
        
    $.ajaxSetup({
    headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$.ajax({
                    url: "/category/"+category_id,
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
   $('#EditCategoryModal').modal('hide');
  $('#EditCategoryModal').find('input').val('');
  fetchcategory(); 
                    }
               });
});
//Delete Data
$(document).on('click','.delete_category', function (e) {
    e.preventDefault();
    
    //var stud_id=$(this).val();
    let category_id = $(this).attr("id");
    $('#delete_category_id').val(category_id);
    $('#DeleteCategoryModal').modal('show'); 
   });
   $(document).on('click','.delete_category_data', function (e) {
    e.preventDefault();
    $(this).text('Deleting...')
    let category_id=$('#delete_category_id').val();
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
    $.ajax({
      type: "DELETE",
      url: "/category/"+category_id,
       success: function (response) {
        
        $('#DeleteCategoryModal').modal('hide'); 
        
        fetchcategory();
      }
    });
   });

    });
</script>

@endsection



