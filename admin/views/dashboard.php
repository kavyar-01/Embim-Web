<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">Dashboard</h1>
      <p class="text-gray-500 text-sm mt-1">Overview of your car rental fleet.</p>
    </div>
    <form action="" method="GET" class="flex items-center gap-3">
      <input type="hidden" name="page" value="dashboard">
      <label for="month" class="text-sm font-semibold text-gray-700">Filter Revenue:</label>
      <input type="month" name="month" id="month" value="<?= htmlspecialchars($month) ?>" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/50 shadow-sm transition cursor-pointer">
    </form>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <?php $cards = [
      ['label'=>'Total Cars',        'value'=>$stats['total_cars'],       'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 17l-1-4H5l-1-3h12l-1 3h-3l-1 4H9zm0 0h6M6 10V7a2 2 0 012-2h8a2 2 0 012 2v3"/>'],
      ['label'=>'Active Bookings',   'value'=>$stats['active_bookings'],  'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'],
      ['label'=>'Revenue This Month','value'=>'Rp. '.number_format($stats['revenue'],0,',','.'), 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-2.21 0-4 .895-4 2s1.79 2 4 2 4 .895 4 2-1.79 2-4 2m0-8v1m0 10v1M8 10H7m10 0h-1"/>'],
      ['label'=>'Available Cars',    'value'=>$stats['available_cars'],   'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
    ]; foreach ($cards as $c): ?>
    <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-[0_0_15px_rgba(59,130,246,0.1)] hover:shadow-[0_0_20px_rgba(59,130,246,0.25)] transition-shadow duration-300">
      <div class="flex items-center justify-between pb-2">
        <span class="text-sm font-medium text-gray-500"><?= $c['label'] ?></span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><?= $c['icon'] ?></svg>
      </div>
      <div class="text-2xl font-bold text-gray-900"><?= $c['value'] ?></div>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-[0_0_15px_rgba(59,130,246,0.1)] hover:shadow-[0_0_20px_rgba(59,130,246,0.25)] transition-shadow duration-300">
    <h2 class="text-base font-semibold text-gray-900 mb-4">Revenue Chart (<?= htmlspecialchars(date('F Y', strtotime($month . '-01'))) ?>)</h2>
    <div class="w-full h-72">
      <canvas id="revenueChart"></canvas>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-[0_0_15px_rgba(59,130,246,0.1)] hover:shadow-[0_0_20px_rgba(59,130,246,0.25)] transition-shadow duration-300 relative overflow-hidden flex flex-col">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-semibold text-gray-900">Recent Bookings</h2>
        <div class="flex items-center gap-2">
          <button id="btn-prev-bookings" class="p-1 rounded bg-gray-100 text-gray-500 hover:bg-blue-50 hover:text-blue-600 transition disabled:opacity-30 disabled:cursor-not-allowed"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg></button>
          <button id="btn-next-bookings" class="p-1 rounded bg-gray-100 text-gray-500 hover:bg-blue-50 hover:text-blue-600 transition disabled:opacity-30 disabled:cursor-not-allowed"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg></button>
        </div>
      </div>
      <div class="relative flex-1 overflow-hidden">
        <div id="slider-bookings" class="flex transition-transform duration-500 ease-in-out w-full">
          <?php $bookingChunks = array_chunk(array_slice($bookings, 0, 20), 4); ?>
          <?php if (empty($bookingChunks)): ?>
            <div class="w-full shrink-0 text-sm text-gray-500 text-center py-4">No recent bookings</div>
          <?php endif; ?>
          <?php foreach ($bookingChunks as $chunk): ?>
          <div class="w-full shrink-0 space-y-4 px-1">
            <?php foreach ($chunk as $b): ?>
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 last:border-0 last:pb-0">
              <div><p class="font-medium text-sm text-gray-900"><?= htmlspecialchars($b['customer_name']) ?></p><p class="text-xs text-gray-500"><?= htmlspecialchars($b['car_name']) ?></p></div>
              <div class="text-right"><p class="font-medium text-sm text-gray-900">Rp. <?= number_format($b['total_price'],0,',','.') ?></p><p class="text-xs text-gray-500"><?= ucfirst($b['status']) ?></p></div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-[0_0_15px_rgba(59,130,246,0.1)] hover:shadow-[0_0_20px_rgba(59,130,246,0.25)] transition-shadow duration-300 relative overflow-hidden flex flex-col">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-semibold text-gray-900">Recent Cars</h2>
        <div class="flex items-center gap-2">
          <button id="btn-prev-cars" class="p-1 rounded bg-gray-100 text-gray-500 hover:bg-blue-50 hover:text-blue-600 transition disabled:opacity-30 disabled:cursor-not-allowed"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg></button>
          <button id="btn-next-cars" class="p-1 rounded bg-gray-100 text-gray-500 hover:bg-blue-50 hover:text-blue-600 transition disabled:opacity-30 disabled:cursor-not-allowed"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg></button>
        </div>
      </div>
      <div class="relative flex-1 overflow-hidden">
        <div id="slider-cars" class="flex transition-transform duration-500 ease-in-out w-full">
          <?php $carChunks = array_chunk(array_slice($cars, 0, 20), 4); ?>
          <?php if (empty($carChunks)): ?>
            <div class="w-full shrink-0 text-sm text-gray-500 text-center py-4">No recent cars</div>
          <?php endif; ?>
          <?php foreach ($carChunks as $chunk): ?>
          <div class="w-full shrink-0 space-y-4 px-1">
            <?php foreach ($chunk as $c): ?>
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 last:border-0 last:pb-0">
              <div><p class="font-medium text-sm text-gray-900"><?= htmlspecialchars($c['brand'].' '.$c['model']) ?></p><p class="text-xs text-gray-500"><?= $c['year'] ?> &bull; <?= ucfirst($c['transmission']) ?></p></div>
              <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium <?= $c['status']==='available' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' ?>"><?= ucfirst($c['status']) ?></span>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');
    
    const chartData = <?= json_encode($chartData) ?>;
    
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: chartData.labels,
        datasets: [{
          label: 'Daily Revenue (Rp)',
          data: chartData.data,
          borderColor: '#3b82f6',
          backgroundColor: gradient,
          borderWidth: 3,
          pointBackgroundColor: '#ffffff',
          pointBorderColor: '#2563eb',
          pointBorderWidth: 2,
          pointRadius: 4,
          pointHoverRadius: 6,
          fill: true,
          tension: 0.4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: '#1e293b',
            titleFont: { size: 13 },
            bodyFont: { size: 14, weight: 'bold' },
            padding: 12,
            displayColors: false,
            callbacks: {
              label: function(context) {
                return 'Rp. ' + context.parsed.y.toLocaleString('id-ID');
              }
            }
          }
        },
        scales: {
          x: {
            grid: { display: false, drawBorder: false },
            ticks: { color: '#94a3b8' }
          },
          y: {
            border: { display: false },
            grid: { color: '#f1f5f9', borderDash: [5, 5] },
            ticks: {
              color: '#94a3b8',
              callback: function(value) {
                if (value >= 1000000) return (value / 1000000) + 'M';
                if (value >= 1000) return (value / 1000) + 'k';
                return value;
              }
            },
            beginAtZero: true
          }
        }
      }
    });

    // Slider Logic
    function initSlider(sliderId, prevBtnId, nextBtnId) {
      const slider = document.getElementById(sliderId);
      if (!slider) return;
      const prevBtn = document.getElementById(prevBtnId);
      const nextBtn = document.getElementById(nextBtnId);
      const totalSlides = slider.children.length;
      let currentSlide = 0;

      function updateSlider() {
        slider.style.transform = `translateX(-${currentSlide * 100}%)`;
        if(prevBtn) prevBtn.disabled = currentSlide === 0;
        if(nextBtn) nextBtn.disabled = currentSlide >= totalSlides - 1 || totalSlides === 0;
      }

      if(prevBtn) prevBtn.addEventListener('click', () => {
        if (currentSlide > 0) { currentSlide--; updateSlider(); }
      });
      if(nextBtn) nextBtn.addEventListener('click', () => {
        if (currentSlide < totalSlides - 1) { currentSlide++; updateSlider(); }
      });

      updateSlider();
    }

    initSlider('slider-bookings', 'btn-prev-bookings', 'btn-next-bookings');
    initSlider('slider-cars', 'btn-prev-cars', 'btn-next-cars');
  });
</script>
<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
