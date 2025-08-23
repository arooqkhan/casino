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
        box-shadow: 0 4px 6px rgba(0,0,0,0.05), 0 1px 3px rgba(0,0,0,0.1) !important;
        transition: all 0.3s ease !important;
    }

    .card:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 10px 15px rgba(0,0,0,0.1), 0 4px 6px rgba(0,0,0,0.05) !important;
    }

    .card-header {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        margin-bottom: 1rem !important;
    }

    .card-header h2 {
        font-size: 1.125rem !important;
        color: #4B5563 !important; /* gray-600 */
        font-weight: 600 !important;
    }

    .wallet-icon {
        width: 40px !important;
        height: 40px !important;
        border-radius: 50% !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        
        color: #3B82F6 !important; /* blue-500 */
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
        color: #1F2937 !important; /* gray-800 */
        display: flex !important;
        align-items: center !important;
    }

    .balance-amount span {
        margin-right: 0.25rem !important;
        color: #6B7280 !important; /* gray-500 */
    }

    .eye-btn {
        background: none !important;
        border: none !important;
        cursor: pointer !important;
        font-size: 1.25rem !important;
        color: #9CA3AF !important; /* gray-400 */
    }

    .eye-btn:hover {
        color: #6B7280 !important; /* gray-600 */
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
        border-top: 1px solid #E5E7EB !important; /* gray-200 */
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
</style>

<div class="card">
    <!-- Header -->
    <div class="card-header">
        <h2>Your Balance</h2>
        <div class="wallet-icon">
            <i class="fas fa-wallet"></i>
        </div>
    </div>

    <!-- Balance -->
    <div class="balance-section">
        <div class="balance-amount">
            <span>$</span>
            <span id="balance">1,234.43</span>
        </div>
        <button id="toggleEye" class="eye-btn">
            <i id="eyeIcon" class="fas fa-eye"></i>
        </button>
    </div>

    <!-- Buttons -->
    <div class="buttons-container">
        <button class="btn btn-green">
            <i class="fas fa-plus-circle" style="margin-right:0.5rem;"></i> Add Money
        </button>
        <button class="btn btn-red">
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

<script>
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
</script>

@endsection
