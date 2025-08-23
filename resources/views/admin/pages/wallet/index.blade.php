@extends('admin.master.main')
@section('content')

<style>
  body {
    font-family: 'Inter', sans-serif !important;
  }

  .card {
    width: 80% !important;
    margin: 2rem auto !important;
    padding: 1.5rem !important;
    border-radius: 1rem !important;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1) !important;
    transition: all 0.3s ease !important;
  }

  .card:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1), 0 4px 6px rgba(0, 0, 0, 0.05) !important;
  }

  .card-header {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    margin-bottom: 1rem !important;
  }

  .card-header h2 {
    font-size: 1.125rem !important;
    color: #4B5563 !important;
    font-weight: 600 !important;
  }

  .wallet-icon {
    width: 40px !important;
    height: 40px !important;
    border-radius: 50% !important;
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    color: #3B82F6 !important;
    font-size: 1.2rem !important;
  }

  .balance-section {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    margin-bottom: 1.5rem !important;
  }

  .balance-amount {
    font-size: 2rem !important;
    font-weight: 700 !important;
    color: #1F2937 !important;
    display: flex !important;
    align-items: center !important;
  }

  .balance-amount span {
    margin-right: 0.25rem !important;
    color: #6B7280 !important;
  }

  .eye-btn {
    background: none !important;
    border: none !important;
    cursor: pointer !important;
    font-size: 1.25rem !important;
    color: #9CA3AF !important;
  }

  .eye-btn:hover {
    color: #6B7280 !important;
  }

  .btn {
    flex: 1 !important;
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    padding: 0.75rem 1rem !important;
    font-weight: 600 !important;
    border-radius: 0.5rem !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    color: #ffffff !important;
    border: none !important;
  }

  .btn:hover {
    transform: translateY(-1px) !important;
  }

  .btn:active {
    transform: translateY(0) !important;
  }

  .btn-green {
    background-color: #10B981 !important;
  }

  .btn-green:hover {
    background-color: #059669 !important;
  }

  .btn-red {
    background-color: #EF4444 !important;
  }

  .btn-red:hover {
    background-color: #DC2626 !important;
  }

  .card-footer {
    margin-top: 1.5rem !important;
    padding-top: 1rem !important;
    border-top: 1px solid #E5E7EB !important;
  }

  .footer-row {
    display: flex !important;
    justify-content: space-between !important;
    font-size: 0.875rem !important;
    color: #6B7280 !important;
    margin-bottom: 0.5rem !important;
  }

  .footer-row span.status {
    color: #10B981 !important;
    font-weight: 500 !important;
  }

  .footer-row span.status i {
    margin-left: 0.25rem !important;
  }

  .buttons-container {
    display: flex !important;
    gap: 0.75rem !important;
  }

  /* ---- Modal styles ---- */
  .modal-overlay {
    position: fixed !important;
    inset: 0 !important;
    background: rgba(0, 0, 0, 0.45) !important;
    display: none !important;
    align-items: center !important;
    justify-content: center !important;
    z-index: 9999 !important;
  }

  .modal-overlay.active {
    display: flex !important;
  }

  .modal-card {
    width: 100% !important;
    max-width: 520px !important;
    background: #172a30 !important;
    border-radius: 1rem !important;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25) !important;
    padding: 1.25rem !important;
    animation: pop 0.15s ease-out !important;
  }

  @keyframes pop {
    from {
      transform: scale(0.98);
      opacity: 0.7;
    }

    to {
      transform: scale(1);
      opacity: 1;
    }
  }

  .modal-header {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    margin-bottom: 0.75rem !important;
  }

  .modal-title {
    font-size: 1.125rem !important;
    font-weight: 700 !important;
    color: #ffffff !important;
  }

  .modal-close {
    background: transparent !important;
    border: none !important;
    font-size: 1.25rem !important;
    color: #6B7280 !important;
    cursor: pointer !important;
  }

  .form-group {
    margin-bottom: 1rem !important;
  }

  .form-label {
    display: block !important;
    font-size: 0.875rem !important;
    color: #ffffff !important;
    margin-bottom: 0.375rem !important;
    font-weight: 600 !important;
  }

  .form-input {
    width: 100% !important;
    padding: 0.75rem !important;
    border: 1px solid #E5E7EB !important;
    border-radius: 0.5rem !important;
    outline: none !important;
    font-weight: 600 !important;
    color: #111827 !important;
  }

  .form-input:focus {
    border-color: #3B82F6 !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
  }

  .pay-grid {
    display: grid !important;
    gap: 0.5rem !important;
  }

  .radio-option {
    display: flex !important;
    align-items: center !important;
    gap: 0.75rem !important;
    border: 1px solid #E5E7EB !important;
    border-radius: 0.75rem !important;
    padding: 0.6rem 0.75rem !important;
    cursor: pointer !important;
    transition: border-color .15s ease !important;
  }

  .radio-option:hover {
    border-color: #9CA3AF !important;
  }

  .radio-option input {
    accent-color: #3B82F6 !important;
  }

  .radio-title {
    font-weight: 700 !important;
    color: #ffffff !important;
  }

  .radio-desc {
    font-size: 0.75rem !important;
    color: #6B7280 !important;
  }

  .modal-actions {
    display: flex !important;
    gap: 0.5rem !important;
    margin-top: 1rem !important;
  }

  .btn-outline {
    border: 1px solid #D1D5DB !important;
    border-radius: 0.5rem !important;
    padding: 0.6rem 0.9rem !important;
    font-weight: 600 !important;
    cursor: pointer !important;
  }
</style>

<div class="card">
  <!-- Header -->
  <div class="card-header">
    <h2>Your Balance</h2>
    <div class="wallet-icon"><i class="fas fa-wallet"></i></div>
  </div>

  <!-- Balance -->
  <div class="balance-section">
    <div class="balance-amount">
      <span>$</span>
      <span id="balance">{{$user->balance}}</span>
    </div>
    <button id="toggleEye" class="eye-btn">
      <i id="eyeIcon" class="fas fa-eye"></i>
    </button>
  </div>

  <!-- Buttons -->
  <div class="buttons-container">
    <button id="openAddMoney" class="btn btn-green">
      <i class="fas fa-plus-circle" style="margin-right:0.5rem;"></i> Add Money
    </button>
    <button id="openWithdraw" class="btn btn-red">
      <i class="fas fa-exchange-alt" style="margin-right:0.5rem;"></i> Withdraw
    </button>
  </div>

  <!-- Footer info -->
  <div class="card-footer">
    <div class="footer-row">
      <span>Account Status</span>
      <span class="status">Verified <i class="fas fa-check-circle"></i></span>
    </div>
    <div class="footer-row">
      <span>Daily Limit</span>
      <span>$5,000.00</span>
    </div>
  </div>
</div>

<!-- ===== Add Money Modal ===== -->
<div id="addMoneyModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="addMoneyTitle">
  <div class="modal-card">
    <div class="modal-header">
      <div id="addMoneyTitle" class="modal-title">Add Money</div>
      <button class="modal-close" id="closeAddMoney" aria-label="Close">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <form id="addMoneyForm" method="POST" action="{{ route('payment.checkout') }}">
      @csrf
      <input type="hidden" name="type" value="credit">
      <div class="form-group">
        <label class="form-label" for="amount">Enter amount</label>
        <div style="display:flex; gap:.5rem; align-items:center;">
          <span style="padding:.65rem .65rem; border:1px solid #E5E7EB; border-radius:.5rem; background:#F9FAFB;">$</span>
          <input id="amount" name="amount" type="number" min="1" step="0.01" placeholder="0.00" class="form-input" required />
        </div>
      </div>

      <div class="form-group">
        <span class="form-label">Select withdrawal method</span>
        <div class="pay-grid">
          <!-- Stripe -->
          <label class="radio-option flex items-center gap-3 p-3 border rounded-md cursor-pointer hover:bg-gray-50">
            <input type="radio" name="method" value="stripe" required />
            <img src="https://cdn-icons-png.flaticon.com/512/5968/5968382.png" width="28" height="28" alt="Stripe">
            <div>
              <div class="radio-title font-semibold">Stripe</div>
              <div class="radio-desc text-sm text-gray-500">Pay securely with your card via Stripe</div>
            </div>
          </label>

        </div>
      </div>

      <div class="modal-actions">
        <button type="button" class="btn-outline" id="cancelAddMoney">Cancel</button>
        <button type="submit" class="btn btn-green">Add Money</button>
      </div>
    </form>
  </div>
</div>

<!-- ===== Withdraw Money Modal ===== -->
<div id="withdrawModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="withdrawTitle">
  <div class="modal-card">
    <div class="modal-header">
      <div id="withdrawTitle" class="modal-title">Withdraw Money</div>
      <button class="modal-close" id="closeWithdraw" aria-label="Close">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <form id="withdrawForm" method="POST" action="{{ route('payment.checkout') }}">
      @csrf
      <input type="hidden" name="type" value="debit">
      <div class="form-group">
        <label class="form-label" for="withdrawAmount">Enter amount</label>
        <div style="display:flex; gap:.5rem; align-items:center;">
          <span style="padding:.65rem .65rem; border:1px solid #E5E7EB; border-radius:.5rem; background:#F9FAFB;">$</span>
          <input id="withdrawAmount" name="amount" type="number" min="1" step="0.01" placeholder="0.00" class="form-input" required />
        </div>
      </div>

      <div class="form-group">
        <span class="form-label">Select withdrawal method</span>
        <div class="pay-grid">
          <!-- Stripe -->
          <label class="radio-option flex items-center gap-3 p-3 border rounded-md cursor-pointer hover:bg-gray-50">
            <input type="radio" name="method" value="stripe" required />
            <img src="https://cdn-icons-png.flaticon.com/512/5968/5968382.png" width="28" height="28" alt="Stripe">
            <div>
              <div class="radio-title font-semibold">Stripe</div>
              <div class="radio-desc text-sm text-gray-500">Pay securely with your card via Stripe</div>
            </div>
          </label>

        </div>
      </div>

      <div class="modal-actions">

        <button type="submit" class="btn btn-red">Withdraw</button>
      </div>
    </form>
  </div>
</div>

<script>
  // Eye toggle
  const toggleEye = document.getElementById('toggleEye');
  const eyeIcon = document.getElementById('eyeIcon');
  const balance = document.getElementById('balance');
  let isVisible = true;
  toggleEye.addEventListener('click', () => {
    isVisible = !isVisible;
    if (isVisible) {
      balance.textContent = '1,234.43';
      eyeIcon.classList.remove('fa-eye-slash');
      eyeIcon.classList.add('fa-eye');
    } else {
      balance.textContent = '******';
      eyeIcon.classList.remove('fa-eye');
      eyeIcon.classList.add('fa-eye-slash');
    }
  });

  // ---- Add Money Modal ----
  const openAddMoney = document.getElementById('openAddMoney');
  const addMoneyModal = document.getElementById('addMoneyModal');
  const closeAddMoney = document.getElementById('closeAddMoney');
  const cancelAddMoney = document.getElementById('cancelAddMoney');
  const addMoneyForm = document.getElementById('addMoneyForm');
  const amountInput = document.getElementById('amount');

  function openModal(modal, input) {
    modal.classList.add('active');
    if (input) input.focus();
  }

  function closeModal(modal, form) {
    modal.classList.remove('active');
    if (form) form.reset();
  }

  openAddMoney.addEventListener('click', () => openModal(addMoneyModal, amountInput));
  closeAddMoney.addEventListener('click', () => closeModal(addMoneyModal, addMoneyForm));
  cancelAddMoney.addEventListener('click', () => closeModal(addMoneyModal, addMoneyForm));
  addMoneyModal.addEventListener('click', (e) => {
    if (e.target === addMoneyModal) closeModal(addMoneyModal, addMoneyForm);
  });

  addMoneyForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const data = new FormData(addMoneyForm);
    const amount = parseFloat(data.get('amount') || '0');
    const method = data.get('method');

    if (!amount || amount <= 0) {
      alert('Enter valid amount.');
      return;
    }
    if (!method) {
      alert('Select payment method.');
      return;
    }

    // ✅ Now submit to Laravel (backend will handle success page)
    this.submit();
  });

  // ---- Withdraw Modal ----
  const openWithdraw = document.getElementById('openWithdraw');
  const withdrawModal = document.getElementById('withdrawModal');
  const closeWithdraw = document.getElementById('closeWithdraw');
  const cancelWithdraw = document.getElementById('cancelWithdraw');
  const withdrawForm = document.getElementById('withdrawForm');
  const withdrawAmount = document.getElementById('withdrawAmount');

  openWithdraw.addEventListener('click', () => openModal(withdrawModal, withdrawAmount));
  closeWithdraw.addEventListener('click', () => closeModal(withdrawModal, withdrawForm));
  cancelWithdraw.addEventListener('click', () => closeModal(withdrawModal, withdrawForm));
  withdrawModal.addEventListener('click', (e) => {
    if (e.target === withdrawModal) closeModal(withdrawModal, withdrawForm);
  });

  withdrawForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const data = new FormData(withdrawForm);
    const amount = parseFloat(data.get('amount') || '0');
    const method = data.get('method');
    if (!amount || amount <= 0) {
      alert('Enter valid amount.');
      return;
    }
    if (!method) {
      alert('Select withdrawal method.');
      return;
    }
    alert(`Withdraw Money\nAmount: $${amount.toFixed(2)}\nMethod: ${method}`);
    closeModal(withdrawModal, withdrawForm);
  });

  // Escape key close
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      if (addMoneyModal.classList.contains('active')) closeModal(addMoneyModal, addMoneyForm);
      if (withdrawModal.classList.contains('active')) closeModal(withdrawModal, withdrawForm);
    }
  });
</script>

@endsection