<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CELEC certifications system</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row mt-5 bg-light bg-gradient pt-3 pb-5 rounded ">
            <div class="col mt-5 text-center">
                <p class="h5">
                    <i style="color: rgb(0, 156, 0)" class="bi bi-shield-fill-check"></i> This certification was awarded to mr/ms {{$certification->fullname}} by <a href="https://www.facebook.com/CELECUSTHB">CELEC club</a>.
                </p>
                <small class="muted text-end">
                    This certification is verified by CELEC computer signature system  {{$certification->signature}}
                </small>
            </div>
        </div>
    </div>
</body>
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>

</html>