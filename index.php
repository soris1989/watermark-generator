<?php
session_start();
ob_start();

require_once __DIR__ . '/./vendor/autoload.php';
require_once __DIR__ . '/./inc/utils.php';
require_once __DIR__ . '/./inc/intro.php';

$successMsg = getFlash(FLASH_MESSAGE);
?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>מחולל סימני מים בתמונה</title>

    <!-- Bootstrap core-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css" />
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6 mx-auto">
                <form action="" method="post"  enctype="multipart/form-data">
                    <h1 class="mb-3 text-primary">מחולל סימני מים בתמונה</h1>

                    <?php if (isset($errorMsg) && $errorMsg) { ?>
                        <div class="alert alert-danger"><?= $errorMsg ?></div>
                    <?php } else if ($successMsg) { ?>
                        <div class="alert alert-success"><?= $successMsg ?></div>
                    <?php } ?>
                    <div class="mb-3">
                        <label for="upload-image">תמונה</label>
                        <input type="file" class="form-control" id="upload-image" name="upload_image" />
                    </div>
                    <div class="mb-3">
                        <label for="upload-watermark">סימן מים</label>
                        <input type="file" class="form-control" id="upload-watermark" name="upload_watermark" />
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary">שלח</button>
                </form>
            </div>
        </div>
    </div>

     <!-- Jquery -->
     <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5 -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
ob_end_flush();