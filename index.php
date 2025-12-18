<?php

    require "composer/vendor/autoload.php";

    use Endroid\QrCode\Builder\Builder;
    use Endroid\QrCode\Encoding\Encoding;
    use Endroid\QrCode\ErrorCorrectionLevel;
    use Endroid\QrCode\Label\LabelAlignment;
    use Endroid\QrCode\Label\Font\OpenSans;
    use Endroid\QrCode\RoundBlockSizeMode;
    use Endroid\QrCode\Writer\PngWriter;
    use Endroid\QrCode\Logo\Logo; //for logo
    use Endroid\QrCode\Color\Color; // to add custom color style

// ... inside the $builder constructor ...
$imagepath = $_GET['img'] ?? null;
$imagedirectory = 'qr-code-images';

//varibles to hold the last submitted form data
$filled_data = $_GET['data'] ?? '';
$filled_label = $_GET['label'] ?? '';

if(isset($_POST['submit'])){
    $qr_code_data = $_POST['qr-code-data'];
    $qr_code_label = $_POST['qr-code-label'];

    //save submitted data
    $filled_data = $qr_code_data;
    $filled_label = $qr_code_label;

    $builder = new Builder(
        writer: new PngWriter(),
        writerOptions: [],
        validateResult: false,
        data: $qr_code_data,
        encoding: new Encoding('UTF-8'),
        errorCorrectionLevel: ErrorCorrectionLevel::High,
        size: 300,
        margin: 10,
        roundBlockSizeMode: RoundBlockSizeMode::Margin,
        logoResizeToWidth: 50,
        logoPunchoutBackground: true,
        labelText: $qr_code_label,
        labelFont: new OpenSans(20),
        labelAlignment: LabelAlignment::Center,
    );

    $result = $builder->build();

// Save it to a file
$filename = 'qrcode-' . uniqid() . '.png';
$path = __DIR__.'/' . $imagedirectory . '/' . $filename;
$result->saveToFile($path);

$newimagepath = $imagedirectory . '/' . $filename;

$redirecturl = $_SERVER['PHP_SELF'] . '?img=' . urlencode($newimagepath) . '&&data=' . urlencode($qr_code_data) . '&&label=' . urlencode($qr_code_label);

header('location: ' . $redirecturl);
exit;

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR-Code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <div class="container" style="margin-top: 70px;">
        <form method="post">
            <div class="mb-3">
                <label for="qr-code-data" class="form-label">Enter your text to generate QR-Code</label>
                <input type="text" class="form-control" id="qr-code-data" name="qr-code-data" value="<?php echo htmlspecialchars($filled_data); ?>" required placeholder="Enter your text">
            </div>
            <div class="mb-3">
                <label for="qr-code-label" class="form-label">Enter your QR-Code label (Optional)</label>
                <input type="text" class="form-control" id="qr-code-label" name="qr-code-label" value="<?php echo htmlspecialchars($filled_label); ?>" placeholder="Enter your label">
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Generate</button>
        </form>
        <?php
            if($imagepath){
        ?>
            <h3>Your Generated QR-Code</h3>
            <img src="<?php echo $imagepath ?>" alt="Your generated QR-Code image">
        <?php
            }
            else{
                echo "no";
            }
        ?>
    </div>
</body>
</html>