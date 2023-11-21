<?php 
include('db_connect.php');
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM files where id=".$_GET['id']);
	if($qry->num_rows > 0){
		foreach($qry->fetch_array() as $k => $v){
			$meta[$k] = $v;
		}
	}
}
?>
<div class="container-fluid">
	<form action="" id="manage-files">
		<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] :'' ?>">
		<input type="hidden" name="folder_id" value="<?php echo isset($_GET['fid']) ? $_GET['fid'] :'' ?>">
		<!-- <div class="form-group">
			<label for="name" class="control-label">File Name</label>
			<input type="text" name="name" id="name" value="<?php //echo isset($meta['name']) ? $meta['name'] :'' ?>" class="form-control">
		</div> -->
		<?php if(!isset($_GET['id']) || empty($_GET['id'])): ?>
		<div class="input-group mb-3">
		  <div class="input-group-prepend">
		    <span class="input-group-text">Upload</span>
		  </div>
		  <div class="custom-file">
		    <input type="file" class="custom-file-input" name="upload" id="upload" onchange="displayname(this,$(this))">
		    <label class="custom-file-label" for="upload">Choose file</label>
		  </div>
		</div>
	<?php endif; ?>
		
		<div class="form-group">
			<label for="subject">Subject</label>
			<input type="text" name="subject" id="subject" class="form-control" value="<?php echo isset($meta['subject']) ? $meta['subject']: '' ?>" required>
		</div>
		<div class="form-group">
			<label for="author">Author</label>
			<input type="text" name="author" id="author" class="form-control" value="<?php echo isset($meta['author']) ? $meta['author']: '' ?>" required>
		</div>
		<div class="form-group">
			<label for="auth_contact">Author's Contact</label>
			<input type="text" name="auth_contact" id="auth_contact" class="form-control" value="<?php echo isset($meta['auth_contact']) ? $meta['auth_contact']: '' ?>" required>
		</div>
		<div class="form-group">
			<label for="auth_address">Author's Address</label>
			<input type="text" name="auth_address" id="auth_address" class="form-control" value="<?php echo isset($meta['auth_address']) ? $meta['auth_address']: '' ?>" required>
		</div>
		<div class="form-group">
			<label for="handler">Handler</label>
			<input type="text" name="handler" id="handler" class="form-control" value="<?php echo isset($meta['handler']) ? $meta['handler']: '' ?>" required>
		</div>
		<div class="form-group">
			<label for="date_received" class="control-label">Date Received</label>
			<input type="date" name="date_received" id="date_received" class="form-control rounded-0" max="<?php echo date("Y-m-d");?>" value="<?php echo isset($date_received) ? $date_received : ''; ?>">
		</div>
		<div class="form-group">
			<label for="date_closed" class="control-label">Date Closed</label>
			<input type="date" name="date_closed" id="date_closed" class="form-control rounded-0" max="<?php echo date("Y-m-d");?>" value="<?php echo isset($date_closed) ? $date_closed : ''; ?>">
		</div>
		<div class="form-group">
			<label for="doc_type">Type of Document</label>
			<select name="doc_type" id="doc_type" class="custom-select">
			<option value="Classified">Classified</option>
			<option value="Directive">Directive</option>
			<option value="Internal">Internal</option>
			<option value="Non-Classified">Non-Classified</option>
			</select>
		</div>
		<div class="form-group">
			<label for="status">Status</label>
			<select name="status" id="status" class="custom-select">
			<option value="Received" <?php echo isset($status) && $status == 'Received' ? 'selected' : '' ?>>Received</option>
			<option value="Pending" <?php echo isset($status) && $status == 'Pending' ? 'selected' : '' ?>>Pending</option>
			<option value="Returned" <?php echo isset($status) && $status == 'Returned' ? 'selected' : '' ?>>Returned</option>
			<option value="Submitted" <?php echo isset($status) && $status == 'Submitted' ? 'selected' : '' ?>>Submitted</option>
			<option value="Closed" <?php echo isset($status) && $status == 'Closed' ? 'selected' : '' ?>>Closed</option>
			</select>
		</div>
		<div class="form-group">
			<label for="is_public" class="control-label"><input type="checkbox" name="is_public" id="is_public"><i> Share to All Users</i></label>
		</div>
		<div class="form-group" id="msg"></div>

	</form>
</div>
<script>
	$(document).ready(function(){
		$('#manage-files').submit(function(e){
			e.preventDefault()
			start_load();
		$('#msg').html('')
		$.ajax({
			url:'ajax.php?action=save_files',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(typeof resp != undefined){
					resp = JSON.parse(resp);
					if(resp.status == 1){
						alert_toast("New File successfully added.",'success')
						setTimeout(function(){
							location.reload()
						},1500)
					}else{
						$('#msg').html('<div class="alert alert-danger">'+resp.msg+'</div>')
						end_load()
					}
				}
			}
		})
		})
	})
	function displayname(input,_this) {
			    if (input.files && input.files[0]) {
			        var reader = new FileReader();
			        reader.onload = function (e) {
            			_this.siblings('label').html(input.files[0]['name'])
			            
			        }

			        reader.readAsDataURL(input.files[0]);
			    }
			}
</script>