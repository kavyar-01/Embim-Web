        </div><!-- /.max-w-6xl -->
      </div><!-- /.flex-1.p-6 -->
      <?php require_once __DIR__ . '/footer.php'; ?>
    </main>
  </div>
</div>
<!-- Global Confirmation Modal -->
<div id="global-confirm-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 transition-opacity duration-200 opacity-0" style="backdrop-filter: blur(4px);">
  <div class="fixed inset-0 bg-gray-900/40" onclick="hideConfirmModal()"></div>
  <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 relative z-10 transform scale-95 transition-transform duration-200" id="modal-content-panel">
    <div class="flex items-center justify-center mb-4">
      <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
      </div>
    </div>
    <h3 class="text-lg font-bold text-gray-900 text-center mb-2">Save Changes?</h3>
    <p class="text-sm text-gray-500 text-center mb-6">Are you sure you want to save these changes? This action cannot be undone.</p>
    <div class="flex gap-3">
      <button type="button" onclick="hideConfirmModal()" class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-colors">Cancel</button>
      <button type="button" onclick="proceedConfirmModal()" class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-md transition-colors">Yes, Save</button>
    </div>
  </div>
</div>

<!-- Global Delete Modal -->
<div id="global-delete-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 transition-opacity duration-200 opacity-0" style="backdrop-filter: blur(4px);">
  <div class="fixed inset-0 bg-gray-900/40" onclick="hideDeleteModal()"></div>
  <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full pt-8 pb-8 px-8 relative z-10 transform scale-95 transition-transform duration-200" id="delete-modal-content-panel">
    <div class="flex items-center justify-center mb-6">
      <div class="w-20 h-20 rounded-full bg-red-50 flex items-center justify-center text-red-600">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
      </div>
    </div>
    <h3 class="text-2xl font-black text-gray-900 text-center mb-3" id="delete-modal-title">Are you sure?</h3>
    <p class="text-sm text-gray-500 text-center mb-8 leading-relaxed" id="delete-modal-desc">Are you sure you want to delete this data? This action cannot be undone.</p>
    <div class="flex gap-4 justify-center items-center">
      <button type="button" onclick="hideDeleteModal()" class="px-6 py-2.5 bg-transparent hover:bg-gray-50 text-gray-500 text-sm font-bold rounded-xl transition-colors">Cancel</button>
      <button type="button" onclick="proceedDeleteModal()" class="px-8 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl shadow-md transition-colors">Yes, Delete</button>
    </div>
  </div>
</div>

<script>
let pendingFormToSubmit = null;

function showConfirmModal(formElement) {
  pendingFormToSubmit = formElement;
  const modal = document.getElementById('global-confirm-modal');
  const panel = document.getElementById('modal-content-panel');
  modal.classList.remove('hidden');
  
  void modal.offsetWidth;
  
  modal.classList.remove('opacity-0');
  modal.classList.add('opacity-100');
  panel.classList.remove('scale-95');
  panel.classList.add('scale-100');
}

function hideConfirmModal() {
  const modal = document.getElementById('global-confirm-modal');
  const panel = document.getElementById('modal-content-panel');
  
  modal.classList.remove('opacity-100');
  modal.classList.add('opacity-0');
  panel.classList.remove('scale-100');
  panel.classList.add('scale-95');
  
  setTimeout(() => {
    modal.classList.add('hidden');
    if (pendingFormToSubmit && !document.getElementById('global-delete-modal').classList.contains('opacity-100')) {
        pendingFormToSubmit = null;
    }
  }, 200);
}

function proceedConfirmModal() {
  if (pendingFormToSubmit) {
    HTMLFormElement.prototype.submit.call(pendingFormToSubmit);
  }
}

function showDeleteModal(formElement, customDesc) {
  pendingFormToSubmit = formElement;
  if (customDesc) {
      document.getElementById('delete-modal-desc').innerText = customDesc;
  } else {
      document.getElementById('delete-modal-desc').innerText = "Are you sure you want to delete this data? This action cannot be undone.";
  }
  const modal = document.getElementById('global-delete-modal');
  const panel = document.getElementById('delete-modal-content-panel');
  modal.classList.remove('hidden');
  
  void modal.offsetWidth;
  
  modal.classList.remove('opacity-0');
  modal.classList.add('opacity-100');
  panel.classList.remove('scale-95');
  panel.classList.add('scale-100');
}

function hideDeleteModal() {
  const modal = document.getElementById('global-delete-modal');
  const panel = document.getElementById('delete-modal-content-panel');
  
  modal.classList.remove('opacity-100');
  modal.classList.add('opacity-0');
  panel.classList.remove('scale-100');
  panel.classList.add('scale-95');
  
  setTimeout(() => {
    modal.classList.add('hidden');
    pendingFormToSubmit = null;
  }, 200);
}

function proceedDeleteModal() {
  if (pendingFormToSubmit) {
    HTMLFormElement.prototype.submit.call(pendingFormToSubmit);
  }
}

// ── Booking Notification Polling ──
let adminLastBookingId = 0;

fetch('?page=api_check_bookings&last_id=0')
  .then(res => res.json())
  .then(data => {
    adminLastBookingId = data.max_id;
    setInterval(pollNewBookings, 10000); // Check every 10 seconds
  })
  .catch(err => console.error("Error init polling:", err));

function pollNewBookings() {
  if (adminLastBookingId === 0) return;
  fetch('?page=api_check_bookings&last_id=' + adminLastBookingId)
    .then(res => res.json())
    .then(data => {
      if (data.new_count > 0) {
        adminLastBookingId = data.max_id;
        showBookingNotification(data.new_count, data.bookings[data.bookings.length - 1]);
      }
    })
    .catch(err => console.error("Error polling:", err));
}

function showBookingNotification(count, latestBooking) {
  const toast = document.createElement('div');
  // Position top-center, translate-y start at -full
  toast.className = 'fixed top-5 left-1/2 -translate-x-1/2 z-[100] bg-white rounded-xl shadow-2xl border border-blue-100 p-4 max-w-sm w-full transform -translate-y-[150%] transition-transform duration-500 flex gap-4 items-start';
  
  toast.innerHTML = `
    <div class="flex-shrink-0 w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center shadow-inner">
      <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    </div>
    <div class="flex-1">
      <h4 class="text-sm font-bold text-gray-900">Payment Received!</h4>
      <p class="text-xs text-gray-500 mt-1">${count > 1 ? count + ' new payments' : 'A new payment'} from <span class="font-semibold text-gray-700">${latestBooking.full_name || 'Customer'}</span> for ${latestBooking.brand || ''} ${latestBooking.model || ''}.</p>
      <div class="mt-3 flex gap-3">
        <a href="?page=manage_payments" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-sm transition-colors">View Payments</a>
        <button onclick="this.closest('.fixed').style.transform='translate(-50%, -150%)'; setTimeout(()=>this.closest('.fixed').remove(), 500)" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition-colors">Dismiss</button>
      </div>
    </div>
  `;

  document.body.appendChild(toast);
  
  // Slide in down
  requestAnimationFrame(() => {
    requestAnimationFrame(() => {
      toast.style.transform = 'translate(-50%, 0)';
    });
  });

  // Auto remove after 5 seconds
  setTimeout(() => {
    if (toast.parentNode) {
      toast.style.transform = 'translate(-50%, -150%)';
      setTimeout(() => toast.remove(), 500);
    }
  }, 5000);
}

// ── Cancelled Booking Notification Polling ──
let adminLastCancelTime = '';

fetch('?page=api_check_cancelled_bookings')
  .then(res => res.json())
  .then(data => {
    adminLastCancelTime = data.last_time;
    setInterval(pollCancelledBookings, 10000); // Check every 10 seconds
  })
  .catch(err => console.error("Error init polling cancelled:", err));

function pollCancelledBookings() {
  if (!adminLastCancelTime) return;
  fetch('?page=api_check_cancelled_bookings&last_time=' + encodeURIComponent(adminLastCancelTime))
    .then(res => res.json())
    .then(data => {
      if (data.cancel_count > 0) {
        adminLastCancelTime = data.last_time;
        showCancelledNotification(data.cancel_count, data.bookings[data.bookings.length - 1]);
      }
    })
    .catch(err => console.error("Error polling cancelled:", err));
}

function showCancelledNotification(count, latestBooking) {
  const toast = document.createElement('div');
  // Position top-center, translate-y start at -full
  // We use top-24 so it doesn't overlap perfectly with new booking notification if they happen at same time
  toast.className = 'fixed top-24 left-1/2 -translate-x-1/2 z-[100] bg-white rounded-xl shadow-2xl border border-red-100 p-4 max-w-sm w-full transform -translate-y-[150%] transition-transform duration-500 flex gap-4 items-start';
  
  toast.innerHTML = `
    <div class="flex-shrink-0 w-10 h-10 bg-red-100 text-red-600 rounded-full flex items-center justify-center shadow-inner">
      <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </div>
    <div class="flex-1">
      <h4 class="text-sm font-bold text-gray-900">Booking Cancelled!</h4>
      <p class="text-xs text-gray-500 mt-1">${count > 1 ? count + ' bookings were cancelled' : 'A booking was cancelled'} by <span class="font-semibold text-gray-700">${latestBooking.full_name || 'Customer'}</span> for ${latestBooking.brand || ''} ${latestBooking.model || ''}.</p>
      <div class="mt-3 flex gap-3">
        <a href="?page=manage_bookings" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg shadow-sm transition-colors">View Bookings</a>
        <button onclick="this.closest('.fixed').style.transform='translate(-50%, -150%)'; setTimeout(()=>this.closest('.fixed').remove(), 500)" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition-colors">Dismiss</button>
      </div>
    </div>
  `;

  document.body.appendChild(toast);
  
  // Slide in down
  requestAnimationFrame(() => {
    requestAnimationFrame(() => {
      toast.style.transform = 'translate(-50%, 0)';
    });
  });

  // Auto remove after 5 seconds
  setTimeout(() => {
    if (toast.parentNode) {
      toast.style.transform = 'translate(-50%, -150%)';
      setTimeout(() => toast.remove(), 500);
    }
  }, 5000);
}
</script>
</body>
</html>
