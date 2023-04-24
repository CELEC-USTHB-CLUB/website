<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CELEC Invtiations scanner</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
	

</head>
<body>
	<div class="container mt-7">
		<div class="row">
			<div class="row">
				<button type="button" onclick="renderInvitationsScanner()" class="btn btn-light">
					Scan and verify Invitation
					<div class="spinner-border text-primary" id="signatureSipnner" role="status">
					  <span class="visually-hidden">Loading...</span>
					</div>

				</button>
			</div>
			<div class="row mt-3">
				<button type="button" class="btn btn-light">Scan checkin</button>
			</div>
			<div class="row mt-3">
				<button type="button" class="btn btn-light">Scan checkout</button>
			</div>
		</div>
	</div>
<div id="reader" width="600px" height="600px"></div>
</body>
<script type="text/javascript" src="https://unpkg.com/html5-qrcode"></script>
<!-- JavaScript Bundle with Popper -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

<script type="text/javascript">
	
let html5QrcodeScanner = new Html5QrcodeScanner(
  "reader",
  { fps: 10, qrbox: {width: 250, height: 250} },
  false);
	
let url = "http://api.celec-club.com";
	
var scanMethod = null;
var previousDecodedText = null;

// fetch(url+"/api/invitation/signature/paper/check?signature=CELEC-64scb5db40233", {
// 				headers: {
//       					'Content-Type': 'application/json'
//     			},
//     			method: 'POST',
//     			cache: 'no-cache'
// 			}).then(function(response) {
// 			    if(response.status !== 200) {
// 			        alert("error");
// 			    }
// 				return response.json();
// 				// html5QrcodeScanner.clear();
// 			}).then(function(r) {
// 			    alert(r)
// 			});

function renderInvitationsScanner() {
   
   html5QrcodeScanner.render(function(decodedText) {
       if(previousDecodedText !== decodedText) {
            fetch(url+"/api/invitation/signature/paper/check?signature=CELEC-64scb5db40233", {
				headers: {
      					'Content-Type': 'application/json'
    			},
    			method: 'POST',
    			cache: 'no-cache'
			})
			.then(function(response) {
			    if(response.status !== 200) {
			        alert("error");
			    }
				return response.json();
			})
			.then(function(response) {
				alert(response);
				// html5QrcodeScanner.clear();
			});
        }
   });
}	


// html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>
</html>

