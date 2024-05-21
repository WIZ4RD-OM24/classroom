
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Codeigniter 4 Upload CSV to Database Example</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

	<div class="container">
		<div class="alert-block mt-3">
			<?php if (session()->has('message')){ ?>
			<div class="alert <?=session()->getFlashdata('alert-class') ?>">
				<?=session()->getFlashdata('message') ?>
			</div>
			<?php } ?>
			<?php $validation = \Config\Services::validation(); ?>
		</div>

		<form method="post" action="<?=base_url('/import-csv-to-database') ?>" enctype="multipart/form-data">
			<div class="form-group mb-2">
				<input type="file" name="file" id="file" class="form-control">
			</div>
			<div class="form-group">
				<input type="submit" name="submit" value="Upload CSV" class="btn btn-primary" />
			</div>
		</form>
	</div>
</body>

</html>
