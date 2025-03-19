@extends('layouts.seller')

@section('title', 'Scan Barcode')

@section('seller-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Scan Barcode</h1>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Verify Order Pickup</h5>
            </div>
            <div class="card-body">
                <p class="mb-4">Scan the customer's barcode to verify order pickup and mark the order as completed.</p>
                
                <div class="text-center mb-4">
                    <div id="scanner-container" class="mb-3">
                        <div id="scanner-placeholder" class="border rounded p-5 d-flex align-items-center justify-content-center" style="height: 300px;">
                            <div class="text-center">
                                <i class="fas fa-qrcode fa-5x mb-3 text-muted"></i>
                                <p>Camera access required for scanning</p>
                                <button id="start-scanner" class="btn btn-primary">Start Scanner</button>
                            </div>
                        </div>
                        <div id="scanner" style="display: none;"></div>
                    </div>
                </div>
                
                <div class="text-center mb-4">
                    <p>- OR -</p>
                </div>
                
                <form action="{{ route('seller.orders.verify-barcode') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="barcode" class="form-label">Enter Barcode Manually</label>
                        <input type="text" class="form-control @error('barcode') is-invalid @enderror" id="barcode" name="barcode" placeholder="Enter barcode">
                        @error('barcode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Verify & Complete Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startScannerBtn = document.getElementById('start-scanner');
        const scannerPlaceholder = document.getElementById('scanner-placeholder');
        const scannerContainer = document.getElementById('scanner');
        const barcodeInput = document.getElementById('barcode');
        
        let html5QrCode;
        
        startScannerBtn.addEventListener('click', function() {
            scannerPlaceholder.style.display = 'none';
            scannerContainer.style.display = 'block';
            
            html5QrCode = new Html5Qrcode("scanner");
            
            html5QrCode.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                (decodedText) => {
                    // Stop scanning
                    html5QrCode.stop().then(() => {
                        // Set the barcode value in the input
                        barcodeInput.value = decodedText;
                        // Submit the form
                        barcodeInput.form.submit();
                    }).catch((err) => {
                        console.error("Failed to stop scanner:", err);
                    });
                },
                (errorMessage) => {
                    // Handle scan error (ignore)
                }
            ).catch((err) => {
                console.error("Failed to start scanner:", err);
                alert("Could not start camera. Please check permissions or enter barcode manually.");
                scannerPlaceholder.style.display = 'flex';
                scannerContainer.style.display = 'none';
            });
        });
    });
</script>
@endpush
@endsection

