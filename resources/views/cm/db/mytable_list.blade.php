@extends("cm.layouts.app")

@section("content")
<div class="row">
  <div class="col s12 m2">
    Table List | 
  </div>
  <div class="col s12 m2">
    for the app id: {{\Auth::user()->active_app_id}}
  </div>
  <div class="col s12 m8">
    <div class="btn-group" style="float:right"><a class="waves-effect waves-light btn blue darken-2" onclick="createTable()">Create New Table</a></div>
  </div>
</div>
<div class="row">
	<div class="col s12">
		<table class="responsive-table">
			<thead>
				<tr>
					<th>Sr</th>
					<th>Table Name</th>
          <th colspan="9">Actions</th>
          <th colspan="2">Export</th>
          <th colspan="2">Import - Create</th>
          <th colspan="2">Import - Update</th>
				</tr>
			</thead>
			<tbody>
        @foreach($tables as $key => $table)
        <tr id="r{{($key + 1)}}">
          <td>{{ ($key + 1) }}</td>
          <td>{{ $table }}</td>
          <td><a href="JavaScript:void(0);" onclick="addFields('{{$table}}')">Add Fields</a></td>
          <td><a href="JavaScript:void(0);" onclick="renameField('{{$table}}')">Rename Field</a></td>
          <td><a href="JavaScript:void(0);" onclick="deleteField('{{$table}}')">Delete Field</a></td>
          <td><a href="JavaScript:void(0);" onclick="addIndex('{{$table}}')">Add Index</a></td>
          <td><a href="JavaScript:void(0);" onclick="removeIndex('{{$table}}')">Remove Index</a></td>
          <td><a href="{{route('c.db.crud.table')}}?table={{$table}}">CRUD</a></td>
          <td><a href="JavaScript:void(0);" onclick="renameTable('{{$table}}', {{$key}})" >Rename Table</a></td>
          <td><a href="JavaScript:void(0);" onclick="truncate('{{$table}}', {{$key}})" >Truncate Table</a></td>
          <td><a href="JavaScript:void(0);" onclick="deleteTable('{{$table}}', {{$key}})" >Delete Table</a></td>
          <td><a href="{{route('c.csv.export', ['table'=>$table])}}">CSV</a></td>
          <td><a href="{{route('c.json.export', ['table'=>$table])}}">JSON</a></td>

          <td>
            <form id="createCSV{{($key + 1)}}" method="post" action="{{route('c.csv.import.create')}}" enctype="multipart/form-data" autocomplete="off" style="display: none;">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="table" value="{{$table}}">
                        <input type="file" name="createCSV" id="ccf{{($key + 1)}}" onchange="$('#createCSV{{($key + 1)}}').submit()">
                    </form>
            <label for="ccf{{($key + 1)}}" class="link"><a>CSV</a></label>
          </td>
          <td>
            <form id="createJSON{{($key + 1)}}" method="post" action="{{route('c.json.import.create')}}" enctype="multipart/form-data" autocomplete="off" style="display: none;">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="table" value="{{$table}}">
                        <input type="file" name="createJSON" id="cjf{{($key + 1)}}" onchange="$('#createJSON{{($key + 1)}}').submit()">
                    </form>
            <label for="cjf{{($key + 1)}}" class="link"><a>JSON</a></label>
          </td>

          <td>
            <form id="updateCSV{{($key + 1)}}" method="post" action="{{route('c.csv.import.update')}}" enctype="multipart/form-data" autocomplete="off" style="display: none;">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="table" value="{{$table}}">
                        <input type="file" name="updateCSV" id="ucf{{($key + 1)}}" onchange="$('#updateCSV{{($key + 1)}}').submit()">
                    </form>
            <label for="ucf{{($key + 1)}}" class="link"><a>CSV</a></label>
          </td>
          <td>
            <form id="updateJSON{{($key + 1)}}" method="post" action="{{route('c.json.import.update')}}" enctype="multipart/form-data" autocomplete="off" style="display: none;">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="table" value="{{$table}}">
                        <input type="file" name="updateJSON" id="ujf{{($key + 1)}}" onchange="$('#updateJSON{{($key + 1)}}').submit()">
                    </form>
            <label for="ujf{{($key + 1)}}" class="link"><a>JSON</a></label>
          </td>
        </tr>
        @endforeach
			</tbody>
		</table>
	</div>
</div>
<script>
  var key ="";
  function renameTable(table, key){
    key = key;
    $(".selectedTable").val(table);
    $("#renameTable").modal('open');
  }
  function renameTableRequest(){
    $.post("{{route('c.db.rename.table')}}", $("#renameTableForm").serialize(), function(data){
       if(data.status == "success"){
          $('#renameTable').modal('toggle');
          $("#r"+String(key+1) + " td:nth(1)").html(data.new_name);
          var ht = '<div class="alert alert-success text-center"><strong>Success!</strong> Table name changed successfully!</div>';
            $('#alrt').html(ht);
        }else{
          var ht = '<div class="alert alert-danger text-center"><strong>Failed!</strong> '+data.status+'</div>';
            $('#alrt').html(ht);
        }
    });
  }
  function truncate(table, key){
    var check = confirm("Are you sure you want to truncate this table");
    if(check){
      $.post("{{route('c.truncate.table')}}", {"table":table,"_token":"{{csrf_token()}}"}, function(data){
        if(data.status == "success"){
          var ht = '<div class="alert alert-success text-center"><strong>Success!</strong> Table '+table+' truncated successfully!</div>';
            $('#alrt').html(ht);
        }else{
          var ht = '<div class="alert alert-danger text-center"><strong>Failed!</strong> Table '+table+' truncate was not successfully!</div>';
            $('#alrt').html(ht);
        }
      });
    }
  }
  function deleteTable(table, key){
    var check = confirm("Are you sure you want to delete this table");
    if(check){
      $.post("{{route('c.delete.table')}}", {"table":table,"_token":"{{csrf_token()}}"}, function(data){
        if(data.status == "success"){
          $("#r"+String(key+1)).remove();
          var ht = '<div class="alert alert-success text-center"><strong>Success!</strong> Table '+table+' deleted successfully!</div>';
            $('#alrt').html(ht);
        }else{
          var ht = '<div class="alert alert-danger text-center"><strong>Failed!</strong> Table '+table+' deletion was not successfully!</div>';
            $('#alrt').html(ht);
        }
      });
    }
  }
  function createTable(){
    $("#createTable").modal('open');
  }
  function addFields(table){
    $(".selectedTable").val(table);
    $("#addFields").modal('open');
  }
  function renameField(table){
    $(".selectedTable").val(table);
    $.get("{{route('c.db.get.columns')}}", {"table":table}, function(data){$(".field_names").html(data);$('select').formSelect();});
    $("#renameField").modal('open');
  }
  function renameFieldRequest(){
    $.post("{{route('c.db.rename.column.submit')}}", $("#renameFieldForm").serialize(), function(data){
       if(data.status == "success"){
          $('#renameField').modal('toggle');
          var ht = '<div class="alert alert-success text-center"><strong>Success!</strong> Column name changed successfully!</div>';
            $('#alrt').html(ht);
        }else{
          var ht = '<div class="alert alert-danger text-center"><strong>Failed!</strong> '+data.status+'</div>';
            $('#alrt').html(ht);
        }
    });
  }
  function deleteField(table){
    $(".selectedTable").val(table);
    $.get("{{route('c.db.get.columns')}}", {"table":table}, function(data){$(".field_names").html(data);$('select').formSelect();});
    $("#deleteField").modal('open');
  }
  function deleteFieldRequest(){
    $.post("{{route('c.db.delete.column.submit')}}", $("#deleteFieldForm").serialize(), function(data){
       if(data.status == "success"){
          $('#deleteField').modal('toggle');
          var ht = '<div class="alert alert-success text-center"><strong>Success!</strong> Column deleted successfully!</div>';
            $('#alrt').html(ht);
        }else{
          var ht = '<div class="alert alert-danger text-center"><strong>Failed!</strong> '+data.status+'</div>';
            $('#alrt').html(ht);
        }
    });
  }
  function addIndex(table){
    $(".selectedTable").val(table);
    $.get("{{route('c.db.get.columns')}}", {"table":table}, function(data){$(".field_names").html(data);$('select').formSelect();});
    $("#addIndex").modal('open');
  }
  function addIndexRequest(){
    $.post("{{route('c.db.add.index.submit')}}", $("#addIndexForm").serialize(), function(data){
       if(data.status == "success"){
          $('#addIndex').modal('toggle');
          var ht = '<div class="alert alert-success text-center"><strong>Success!</strong> Index added successfully!</div>';
            $('#alrt').html(ht);
        }else{
          var ht = '<div class="alert alert-danger text-center"><strong>Failed!</strong> '+data.status+'</div>';
            $('#alrt').html(ht);
        }
    });
  }
  function removeIndex(table){
    $(".add-index").html("Remove Index");
    $("#addIndexForm").attr('action', '{{route('c.db.remove.index.submit')}}');
    $("#ais").attr('onclick', 'removeIndexRequest()');
    addIndex(table);
  }
  function removeIndexRequest(){
    $.post("{{route('c.db.remove.index.submit')}}", $("#addIndexForm").serialize(), function(data){
       if(data.status == "success"){
          $('#addIndex').modal('toggle');
          var ht = '<div class="alert alert-success text-center"><strong>Success!</strong> Index deleted successfully!</div>';
            $('#alrt').html(ht);
        }else{
          var ht = '<div class="alert alert-danger text-center"><strong>Failed!</strong> '+data.status+'</div>';
            $('#alrt').html(ht);
        }
    });
  }
  function importCreate(table){
    $(".selectedTable").val(table);
    $("#importCreate").modal('open');
  }
</script>

<!-- Modal -->
<div id="importCreate" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form id="renameTableForm" method="post" action="{{route('c.db.rename.table')}}">
        <input type="hidden" name="table" class="selectedTable" />
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <div class="modal-header">
          <h4 class="modal-title">Import </h4>
        </div>
        <div class="modal-body">
          <input type="text" class="validate" name="new_name" required autofocus/>
        </div>
        <div class="modal-footer">
          <button type="submit" class="waves-effect waves-light btn blue darken-2">Update</button>
          <button type="button" class="modal-close waves-effect waves-light btn blue darken-2">Cancel</button>
        </div>
      </form>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="renameTable" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form id="renameTableForm" method="post" action="{{route('c.db.rename.table')}}">
        <input type="hidden" name="table" class="selectedTable" />
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <div class="modal-header">
          <h4 class="modal-title">New Table Name</h4>
        </div>
        <div class="modal-body">
          <input type="text" class="validate" name="new_name" required autofocus/>
        </div>
        <div class="modal-footer">
          <button type="submit" class="waves-effect waves-light btn blue darken-2">Update</button>
          <button type="button" class="modal-close waves-effect waves-light btn blue darken-2">Cancel</button>
        </div>
      </form>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="createTable" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form method="get" action="{{route('c.db.new.table')}}" >
        <div class="modal-header">
          <h4 class="modal-title">Number of fields</h4>
        </div>
        <div class="modal-body">
          <input type="number" class="validate" name="fn" required autofocus/>
        </div>
        <div class="modal-footer">
          <button type="submit" class="waves-effect waves-light btn blue darken-2">Go</button>
          <button type="button" class="modal-close waves-effect waves-light btn blue darken-2">Cancel</button>
        </div>
      </form>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="addFields" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form method="get" action="{{route('c.db.add.columns')}}" >
        <input type="hidden" name="table" class="selectedTable" />
        <div class="modal-header">
          <h4 class="modal-title">Number of fields</h4>
        </div>
        <div class="modal-body">
          <input type="number" class="validate" name="fn" required autofocus/>
        </div>
        <div class="modal-footer">
          <button type="submit" class="waves-effect waves-light btn blue darken-2">Go</button>
          <button type="button" class="modal-close waves-effect waves-light btn blue darken-2">Cancel</button>
        </div>
      </form>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="renameField" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form id="renameFieldForm" >
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <input type="hidden" name="table" class="selectedTable" />
        <div class="modal-header">
          <h4 class="modal-title">Rename Field</h4>
        </div>
        <div class="modal-body">
          <div class="input-field">
            <select id="field_names" name="old_field_name" class="validate field_names" autofocus></select>
            <label for="field_names">Select the old field</label>
          </div>
          <div class="input-field">
            <input type="text" class="validate" name="new_field_name" required/>
            <label for="new_field_name">New name of the field</label>
          </div>
        </div>
      </form>
        <div class="modal-footer">
          <button type="submit" class="waves-effect waves-light btn blue darken-2" onclick="renameFieldRequest()">Rename</button>
          <button type="button" class="modal-close waves-effect waves-light btn blue darken-2">Cancel</button>
        </div>
    </div>

  </div>
</div>


<!-- Modal -->
<div id="deleteField" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form id="deleteFieldForm" >
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <input type="hidden" name="table" class="selectedTable" />
        <div class="modal-header">
          <h4 class="modal-title">Delete Field</h4>
        </div>
        <div class="modal-body">
          <label for="field_names">Select the old field</label>
          <select id="field_names" name="field_name" class="validate field_names" autofocus></select>
        </div>
        </form>
        <div class="modal-footer">
          <button type="submit" class="waves-effect waves-light btn blue darken-2" onclick="deleteFieldRequest()">Delete</button>
          <button type="button" class="modal-close waves-effect waves-light btn blue darken-2">Cancel</button>
        </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="addIndex" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form id="addIndexForm" >
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <input type="hidden" name="table" class="selectedTable" />
        <div class="modal-header">
          <h4 class="modal-title add-index">Add Index</h4>
        </div>
        <div class="modal-body">
          <label for="field_names">Select the field</label>
          <select id="field_names" name="field_name" class="validate field_names" autofocus></select>
          <label for="index_name" class="ri">Select the index</label>
          <select name="index_name" class="validate ri">
            <option value="primary">PRIMARY</option>
            <option value="unique">UNIQUE</option>
            <option value="index">INDEX</option>
          </select>
        </div>
      </form>
        <div class="modal-footer">
          <button type="submit" class="waves-effect waves-light btn blue darken-2 add-index" id="ais" onclick="addIndexRequest()">Add Index</button>
          <button type="button" class="modal-close waves-effect waves-light btn blue darken-2">Cancel</button>
        </div>
    </div>

  </div>
</div>
@endsection