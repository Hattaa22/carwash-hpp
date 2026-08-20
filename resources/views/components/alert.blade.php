{{-- Alert Component - resources/views/components/alert.blade.php --}}

@if(session('success') || session('error') || session('warning') || session('info'))
<div class="mb-6">
    @if(session('success'))
    <div id="alert-success" class="flex items-center p-4 mb-4 text-green-800 border border-green-300 rounded-lg bg-green-50 transform transition-all duration-300 ease-in-out" role="alert">
        <div class="flex-shrink-0">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3 text-sm font-medium">
            {{ session('success') }}
        </div>
        <button type="button" onclick="closeAlert('alert-success')" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8 transition-colors duration-200" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div id="alert-error" class="flex items-center p-4 mb-4 text-red-800 border border-red-300 rounded-lg bg-red-50 transform transition-all duration-300 ease-in-out" role="alert">
        <div class="flex-shrink-0">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3 text-sm font-medium">
            {{ session('error') }}
        </div>
        <button type="button" onclick="closeAlert('alert-error')" class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 transition-colors duration-200" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    @endif

    @if(session('warning'))
    <div id="alert-warning" class="flex items-center p-4 mb-4 text-yellow-800 border border-yellow-300 rounded-lg bg-yellow-50 transform transition-all duration-300 ease-in-out" role="alert">
        <div class="flex-shrink-0">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3 text-sm font-medium">
            {{ session('warning') }}
        </div>
        <button type="button" onclick="closeAlert('alert-warning')" class="ml-auto -mx-1.5 -my-1.5 bg-yellow-50 text-yellow-500 rounded-lg focus:ring-2 focus:ring-yellow-400 p-1.5 hover:bg-yellow-200 inline-flex h-8 w-8 transition-colors duration-200" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    @endif

    @if(session('info'))
    <div id="alert-info" class="flex items-center p-4 mb-4 text-blue-800 border border-blue-300 rounded-lg bg-blue-50 transform transition-all duration-300 ease-in-out" role="alert">
        <div class="flex-shrink-0">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3 text-sm font-medium">
            {{ session('info') }}
        </div>
        <button type="button" onclick="closeAlert('alert-info')" class="ml-auto -mx-1.5 -my-1.5 bg-blue-50 text-blue-500 rounded-lg focus:ring-2 focus:ring-blue-400 p-1.5 hover:bg-blue-200 inline-flex h-8 w-8 transition-colors duration-200" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    @endif
</div>

{{-- Auto-hide alerts after 5 seconds --}}
<script>
function closeAlert(alertId) {
    const alert = document.getElementById(alertId);
    if (alert) {
        alert.style.transform = 'translateX(100%)';
        alert.style.opacity = '0';
        setTimeout(() => {
            alert.remove();
        }, 300);
    }
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('[id^="alert-"]');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert && alert.parentNode) {
                closeAlert(alert.id);
            }
        }, 5000);
    });
});

// Custom alert function for JavaScript usage
function showAlert(type, message, duration = 5000) {
    const alertTypes = {
        success: {
            bgColor: 'bg-green-50',
            textColor: 'text-green-800',
            borderColor: 'border-green-300',
            buttonBg: 'bg-green-50',
            buttonText: 'text-green-500',
            buttonHover: 'hover:bg-green-200',
            icon: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                  </svg>`
        },
        error: {
            bgColor: 'bg-red-50',
            textColor: 'text-red-800',
            borderColor: 'border-red-300',
            buttonBg: 'bg-red-50',
            buttonText: 'text-red-500',
            buttonHover: 'hover:bg-red-200',
            icon: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                  </svg>`
        },
        warning: {
            bgColor: 'bg-yellow-50',
            textColor: 'text-yellow-800',
            borderColor: 'border-yellow-300',
            buttonBg: 'bg-yellow-50',
            buttonText: 'text-yellow-500',
            buttonHover: 'hover:bg-yellow-200',
            icon: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                  </svg>`
        },
        info: {
            bgColor: 'bg-blue-50',
            textColor: 'text-blue-800',
            borderColor: 'border-blue-300',
            buttonBg: 'bg-blue-50',
            buttonText: 'text-blue-500',
            buttonHover: 'hover:bg-blue-200',
            icon: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                  </svg>`
        }
    };

    const alertConfig = alertTypes[type] || alertTypes.info;
    const alertId = `alert-${type}-${Date.now()}`;
    
    const alertHTML = `
        <div id="${alertId}" class="flex items-center p-4 mb-4 ${alertConfig.textColor} border ${alertConfig.borderColor} rounded-lg ${alertConfig.bgColor} transform transition-all duration-300 ease-in-out" role="alert">
            <div class="flex-shrink-0">
                ${alertConfig.icon}
            </div>
            <div class="ml-3 text-sm font-medium">
                ${message}
            </div>
            <button type="button" onclick="closeAlert('${alertId}')" class="ml-auto -mx-1.5 -my-1.5 ${alertConfig.buttonBg} ${alertConfig.buttonText} rounded-lg focus:ring-2 focus:ring-${type}-400 p-1.5 ${alertConfig.buttonHover} inline-flex h-8 w-8 transition-colors duration-200" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;

    // Insert alert at the top of the page
    const alertContainer = document.querySelector('.alert-container') || document.body;
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = alertHTML;
    const alertElement = tempDiv.firstElementChild;
    
    if (document.querySelector('.alert-container')) {
        alertContainer.insertBefore(alertElement, alertContainer.firstChild);
    } else {
        document.body.insertBefore(alertElement, document.body.firstChild);
    }

    // Auto-hide after specified duration
    if (duration > 0) {
        setTimeout(() => {
            closeAlert(alertId);
        }, duration);
    }

    return alertId;
}

// Validation error alerts
@if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        const errors = @json($errors->all());
        errors.forEach(error => {
            showAlert('error', error, 7000);
        });
    });
@endif
</script>

{{-- CSS for animations --}}
<style>
@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

[id^="alert-"] {
    animation: slideInRight 0.3s ease-out;
}

.alert-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    max-width: 400px;
    width: 100%;
}

@media (max-width: 640px) {
    .alert-container {
        top: 10px;
        right: 10px;
        left: 10px;
        max-width: none;
    }
}
</style>
@endif