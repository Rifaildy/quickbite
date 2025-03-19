@extends('layouts.buyer')

@section('title', 'Payment')

@section('buyer-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">Payment for Order #{{ $order->order_number }}</h1>
  <div class="btn-toolbar mb-2 mb-md-0">
      <a href="{{ route('buyer.orders.show', $order) }}" class="btn btn-sm btn-secondary">
          <i class="fas fa-arrow-left"></i> Back to Order
      </a>
  </div>
</div>

<div class="row justify-content-center">
  <div class="col-md-8">
      <div class="card mb-4">
          <div class="card-header">
              <h5 class="card-title mb-0">Order Summary</h5>
          </div>
          <div class="card-body">
              <div class="table-responsive">
                  <table class="table">
                      <thead>
                          <tr>
                              <th>Item</th>
                              <th>Price</th>
                              <th>Quantity</th>
                              <th class="text-end">Subtotal</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach($order->orderItems as $item)
                              <tr>
                                  <td>{{ $item->menu->name }}</td>
                                  <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                  <td>{{ $item->quantity }}</td>
                                  <td class="text-end">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                              </tr>
                          @endforeach
                      </tbody>
                      <tfoot>
                          <tr>
                              <th colspan="3" class="text-end">Total:</th>
                              <th class="text-end">Rp {{ number_format($order->total_price, 0, ',', '.') }}</th>
                          </tr>
                      </tfoot>
                  </table>
              </div>
          </div>
      </div>

      <div class="card">
          <div class="card-header">
              <h5 class="card-title mb-0">Payment Method</h5>
          </div>
          <div class="card-body">
              <div class="alert alert-info mb-4">
                  <p class="mb-0">
                      <i class="fas fa-info-circle me-2"></i>
                      Click the "Pay Now" button below to proceed with payment via Midtrans. You will be able to choose from various payment methods.
                  </p>
              </div>
              
              <div class="d-grid gap-2">
                  <button type="button" class="btn btn-primary btn-lg" id="pay-button">Pay Now</button>
              </div>
              
              <form id="payment-form" action="{{ route('buyer.orders.process-payment', $order) }}" method="POST">
                  @csrf
                  <input type="hidden" name="payment_type" id="payment-type">
                  <input type="hidden" name="transaction_id" id="transaction-id">
                  <input type="hidden" name="transaction_status" id="transaction-status">
              </form>
          </div>
      </div>
  </div>
</div>

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const payButton = document.getElementById('pay-button');
        const paymentForm = document.getElementById('payment-form');
        const paymentTypeInput = document.getElementById('payment-type');
        const transactionIdInput = document.getElementById('transaction-id');
        const transactionStatusInput = document.getElementById('transaction-status');
        
        payButton.addEventListener('click', function() {
            // Show Snap payment page
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    paymentTypeInput.value = result.payment_type;
                    transactionIdInput.value = result.transaction_id;
                    transactionStatusInput.value = result.transaction_status;
                    paymentForm.submit();
                },
                onPending: function(result) {
                    paymentTypeInput.value = result.payment_type;
                    transactionIdInput.value = result.transaction_id;
                    transactionStatusInput.value = result.transaction_status;
                    paymentForm.submit();
                },
                onError: function(result) {
                    paymentTypeInput.value = result.payment_type || 'unknown';
                    transactionIdInput.value = result.transaction_id || '';
                    transactionStatusInput.value = 'error';
                    paymentForm.submit();
                },
                onClose: function() {
                    alert('You closed the payment window without completing the payment. Please try again.');
                }
            });
        });
    });
</script>
@endpush
@endsection

