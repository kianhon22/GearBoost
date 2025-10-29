export function useNotification() {
  let notificationComponent = null;

  const show = (message, type = 'success', duration = 3000) => {

    const notification = document.createElement('div');
    notification.className = `fixed top-5 right-5 px-6 py-4 rounded-lg font-semibold shadow-xl z-[9999] transition-all ${
      type === 'success' 
        ? 'bg-green-100 text-green-800 border border-green-200' 
        : type === 'error'
        ? 'bg-red-100 text-red-800 border border-red-200'
        : type === 'warning'
        ? 'bg-yellow-100 text-yellow-800 border border-yellow-200'
        : 'bg-blue-100 text-blue-800 border border-blue-200'
    }`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => notification.remove(), duration);
  };

  return {
    showNotification: show
  };
}

// Global helper for non-Vue contexts
window.showNotification = function(message, type = 'success', duration = 3000) {
  const notification = document.createElement('div');
  notification.className = `fixed top-5 right-5 px-6 py-4 rounded-lg font-semibold shadow-xl z-[9999] transition-all ${
    type === 'success' 
      ? 'bg-green-100 text-green-800 border border-green-200' 
      : type === 'error'
      ? 'bg-red-100 text-red-800 border border-red-200'
      : type === 'warning'
      ? 'bg-yellow-100 text-yellow-800 border border-yellow-200'
      : 'bg-blue-100 text-blue-800 border border-blue-200'
  }`;
  notification.textContent = message;
  document.body.appendChild(notification);
  
  setTimeout(() => notification.remove(), duration);
};

// Global custom confirm dialog
window.showCustomConfirm = function(title, message, onConfirm) {

  const overlay = document.createElement('div');
  overlay.className = 'fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50';
  
  const dialog = document.createElement('div');
  dialog.className = 'bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 transform transition-all';
  dialog.innerHTML = `
    <div class="flex flex-col items-center text-center">
      <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mb-4">
        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
      </div>
      <h3 class="text-2xl font-bold text-gray-900 mb-2">${title}</h3>
      <p class="text-gray-600 mb-6">${message}</p>
      <div class="flex gap-3 w-full">
        <button id="confirm-cancel" class="flex-1 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-semibold transition-colors">
          Cancel
        </button>
        <button id="confirm-ok" class="flex-1 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold transition-colors">
          Confirm
        </button>
      </div>
    </div>
  `;
  
  overlay.appendChild(dialog);
  document.body.appendChild(overlay);
  
  document.getElementById('confirm-ok').addEventListener('click', function() {
    overlay.remove();
    if (onConfirm) onConfirm();
  });
  
  document.getElementById('confirm-cancel').addEventListener('click', function() {
    overlay.remove();
  });
  
  overlay.addEventListener('click', function(e) {
    if (e.target === overlay) {
      overlay.remove();
    }
  });
};