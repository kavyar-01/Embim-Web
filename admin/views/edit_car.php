<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="max-w-4xl space-y-8 pb-12">

  <div>
    <div class="flex items-center gap-4">
        <a href="?page=manage_cars" class="p-2 bg-gray-100 rounded-full hover:bg-gray-200 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Edit Car</h1>
    </div>
    <p class="text-gray-500 text-sm mt-2 ml-12">Modify the details below to update the car information.</p>
  </div>

  <?php if (!empty($success)): ?>
  <div class="bg-green-100 border border-green-300 text-green-800 rounded-md px-4 py-3 text-sm font-medium flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <?= htmlspecialchars($success) ?>
  </div>
  <?php endif; ?>

  <?php if (!empty($errors)): ?>
  <div class="bg-red-50 border border-red-300 text-red-700 rounded-md px-4 py-3 text-sm space-y-1">
    <?php foreach ($errors as $e): ?>
    <p class="flex items-center gap-2">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      <?= htmlspecialchars($e) ?>
    </p>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <form action="?page=edit_car&id=<?= $old['id'] ?>" method="POST" enctype="multipart/form-data" class="space-y-8" onsubmit="event.preventDefault(); showConfirmModal(this);">

    <!-- Photo Upload -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">Car Photo</label>
      <label for="photo" id="photo-label" class="flex flex-col items-center justify-center w-full border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors overflow-hidden">
        
        <div id="photo-placeholder" class="<?= !empty($old['photo']) ? 'hidden' : '' ?>">
          <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center mb-4 mx-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
            </svg>
          </div>
          <p class="font-medium text-sm text-gray-700">Click to upload a new photo</p>
          <p class="text-xs text-gray-500 mt-1">Leave empty to keep current photo</p>
        </div>

        <div id="photo-preview-wrap" class="<?= empty($old['photo']) ? 'hidden' : '' ?> w-full">
          <img id="photo-preview" src="<?= !empty($old['photo']) ? '../assets/images/' . htmlspecialchars($old['photo']) : '' ?>" alt="Preview" class="max-h-64 mx-auto rounded-lg object-contain" />
          <p id="photo-name" class="text-xs text-gray-500 mt-3">Current Photo</p>
          <p class="text-xs text-blue-500 mt-1">Click to change photo</p>
        </div>

        <input type="file" id="photo" name="photo" accept="image/*" class="hidden" />
      </label>
    </div>
    <script>
      document.getElementById('photo').addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('photo-placeholder').classList.add('hidden');
          document.getElementById('photo-preview-wrap').classList.remove('hidden');
          document.getElementById('photo-preview').src = e.target.result;
          document.getElementById('photo-name').textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
        };
        reader.readAsDataURL(file);
      });
    </script>

    <!-- Brand & Model -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label for="brand" class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
        <input type="text" id="brand" name="brand" value="<?= htmlspecialchars($old['brand'] ?? '') ?>" placeholder="e.g. BMW, Toyota, Tesla..."
          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required />
      </div>
      <div>
        <label for="model" class="block text-sm font-medium text-gray-700 mb-1">Model</label>
        <input type="text" id="model" name="model" value="<?= htmlspecialchars($old['model'] ?? '') ?>" placeholder="e.g. Camry, Model 3, M4..."
          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required />
      </div>
    </div>

    <!-- Year, License Plate, Price Per Day, Status -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div>
        <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Year</label>
        <input type="number" id="year" name="year" value="<?= htmlspecialchars($old['year'] ?? '') ?>" placeholder="e.g. 2024" min="1990" max="2099"
          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required />
      </div>
      <div>
        <label for="license_plate" class="block text-sm font-medium text-gray-700 mb-1">License Plate</label>
        <input type="text" id="license_plate" name="license_plate" value="<?= htmlspecialchars($old['license_plate'] ?? '') ?>" placeholder="e.g. D 1234 ABM" maxlength="12" pattern="[A-Za-z]{1,2}\s*[0-9]{1,4}\s*[A-Za-z]{0,3}" title="Format plat: D 1234 ABM" oninput="this.value = this.value.toUpperCase()"
          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required />
      </div>
      <div>
        <label for="price_per_day" class="block text-sm font-medium text-gray-700 mb-1">Price Per Day (Rp)</label>
        <input type="number" id="price_per_day" name="price_per_day" value="<?= htmlspecialchars($old['price_per_day'] ?? '') ?>" placeholder="e.g. 500000" min="0" step="0.01"
          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required />
      </div>
      <div>
        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
        <select id="status" name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            <option value="available" <?= ($old['status'] ?? '') === 'available' ? 'selected' : '' ?>>Available</option>
            <option value="booked" <?= ($old['status'] ?? '') === 'booked' ? 'selected' : '' ?>>Booked</option>
            <option value="maintenance" <?= ($old['status'] ?? '') === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
        </select>
      </div>
    </div>

    <!-- Transmission, Fuel Type, Seats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div>
        <label for="transmission" class="block text-sm font-medium text-gray-700 mb-1">Transmission</label>
        <select id="transmission" name="transmission"
          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
          <option value="" disabled <?= empty($old['transmission']) ? 'selected' : '' ?>>Select transmission</option>
          <option value="automatic" <?= ($old['transmission'] ?? '') === 'automatic' ? 'selected' : '' ?>>Automatic</option>
          <option value="manual"    <?= ($old['transmission'] ?? '') === 'manual'    ? 'selected' : '' ?>>Manual</option>
        </select>
      </div>
      <div>
        <label for="fuel_type" class="block text-sm font-medium text-gray-700 mb-1">Fuel Type</label>
        <select id="fuel_type" name="fuel_type"
          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
          <option value="" disabled <?= empty($old['fuel_type']) ? 'selected' : '' ?>>Select fuel type</option>
          <option value="gasoline" <?= ($old['fuel_type'] ?? '') === 'gasoline' ? 'selected' : '' ?>>Gasoline</option>
          <option value="diesel"   <?= ($old['fuel_type'] ?? '') === 'diesel'   ? 'selected' : '' ?>>Diesel</option>
          <option value="electric" <?= ($old['fuel_type'] ?? '') === 'electric' ? 'selected' : '' ?>>Electric</option>
          <option value="hybrid"   <?= ($old['fuel_type'] ?? '') === 'hybrid'   ? 'selected' : '' ?>>Hybrid</option>
        </select>
      </div>
      <div>
        <label for="seats" class="block text-sm font-medium text-gray-700 mb-1">Seats</label>
        <input type="number" id="seats" name="seats" value="<?= htmlspecialchars($old['seats'] ?? '5') ?>" placeholder="e.g. 5" min="1" max="20"
          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required />
      </div>
    </div>

    <!-- Description -->
    <div class="pt-6 border-t border-gray-200">
      <h3 class="text-lg font-bold text-gray-900 mb-4">Vehicle Highlights</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div>
          <label for="hl_drivetrain" class="block text-sm font-medium text-gray-700 mb-1">Drivetrain</label>
          <select id="hl_drivetrain" name="hl_drivetrain" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="FWD" <?= ($old['drivetrain'] ?? '') === 'FWD' ? 'selected' : '' ?>>FWD (Front Wheel Drive)</option>
            <option value="RWD" <?= ($old['drivetrain'] ?? '') === 'RWD' ? 'selected' : '' ?>>RWD (Rear Wheel Drive)</option>
            <option value="AWD" <?= ($old['drivetrain'] ?? '') === 'AWD' ? 'selected' : '' ?>>AWD/4WD (All Wheel Drive)</option>
          </select>
        </div>
        <div>
          <label for="hl_body_style" class="block text-sm font-medium text-gray-700 mb-1">Body Style</label>
          <select id="hl_body_style" name="hl_body_style" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="Sedan" <?= ($old['body_style'] ?? '') === 'Sedan' ? 'selected' : '' ?>>Sedan</option>
            <option value="SUV" <?= ($old['body_style'] ?? '') === 'SUV' ? 'selected' : '' ?>>SUV</option>
            <option value="Sports" <?= ($old['body_style'] ?? '') === 'Sports' ? 'selected' : '' ?>>Sports</option>
          </select>
        </div>
        <div>
          <label for="hl_engine" class="block text-sm font-medium text-gray-700 mb-1">Engine Detail</label>
          <input type="text" id="hl_engine" name="hl_engine" value="<?= htmlspecialchars($old['engine'] ?? '') ?>" placeholder="e.g. 2.0L Turbo 4-Cylinder"
            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required />
        </div>
        <div>
          <label for="hl_transmission" class="block text-sm font-medium text-gray-700 mb-1">Transmission Detail</label>
          <select id="hl_transmission" name="hl_transmission" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="Automatic" <?= ($old['hl_transmission'] ?? '') === 'Automatic' ? 'selected' : '' ?>>Automatic</option>
            <option value="Manual" <?= ($old['hl_transmission'] ?? '') === 'Manual' ? 'selected' : '' ?>>Manual</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Description -->
    <div>
      <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
      <textarea id="description" name="description" rows="5"
        placeholder="Describe the car, its condition, notable features, and any other relevant information..."
        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
    </div>

    <!-- Submit -->
    <div class="flex items-center gap-4">
      <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-6 py-3 rounded-md transition-colors shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        Save Changes
      </button>
      <a href="?page=manage_cars" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-6 py-3 rounded-md transition-colors">
        Cancel
      </a>
    </div>

  </form>
</div>
<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
