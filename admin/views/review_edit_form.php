<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6 pb-10">

  <div>
    <a href="?page=manage_reviews" class="back-link">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Back to Reviews
    </a>
  </div>

  <div class="page-heading">
    <h1>Edit Review</h1>
    <p>Review ID: <?= $review['id'] ?> — <?= htmlspecialchars($review['customer_name']) ?></p>
  </div>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      <ul style="margin:0;padding-left:16px;">
        <?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <div class="card" style="max-width:600px;">
    <div class="card-header"><span class="card-title">Review Details</span></div>
    <div class="card-body">
      <form method="POST" action="?page=edit_review&id=<?= $review['id'] ?>">

        <!-- Read Only Info -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6 border border-gray-100">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <div class="text-xs text-gray-500 uppercase font-semibold mb-1">Customer</div>
              <div class="font-medium text-gray-900"><?= htmlspecialchars($review['customer_name']) ?></div>
            </div>
            <div>
              <div class="text-xs text-gray-500 uppercase font-semibold mb-1">Vehicle</div>
              <div class="font-medium text-blue-600"><?= htmlspecialchars($review['car_name']) ?></div>
            </div>
          </div>
        </div>

        <!-- Rating -->
        <div style="margin-bottom:18px;">
          <label class="form-label" for="rating">Rating (1-5 Stars) <span style="color:#ef4444;">*</span></label>
          <?php $currentRating = (int)($_POST['rating'] ?? $review['rating'] ?? 5); ?>
          <input type="hidden" name="rating" id="review-rating-val" value="<?= $currentRating ?>">
          
          <div class="flex items-center gap-2" id="star-container">
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <button type="button"
                      data-star="<?= $i ?>"
                      onclick="setRating(<?= $i ?>)"
                      onmouseover="hoverRating(<?= $i ?>)"
                      onmouseout="resetHover()"
                      class="star-btn h-10 w-10 rounded-xl flex items-center justify-center transition-all duration-150 <?= $i <= $currentRating ? 'bg-amber-50 border border-amber-300' : 'bg-gray-50 border border-gray-200 hover:bg-amber-50 hover:border-amber-300' ?>">
                  <svg class="h-6 w-6 star-icon transition-colors duration-150 <?= $i <= $currentRating ? 'text-amber-400' : 'text-gray-300' ?>" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                  </svg>
              </button>
            <?php endfor; ?>
            <span id="rating-label" class="text-sm font-bold text-amber-500 ml-2"></span>
          </div>
        </div>

        <!-- Comment -->
        <div style="margin-bottom:24px;">
          <label class="form-label" for="comment">Comment</label>
          <textarea name="comment" id="comment" class="form-control" rows="4"
                    placeholder="Enter review comment..."><?= htmlspecialchars($_POST['comment'] ?? $review['comment'] ?? '') ?></textarea>
        </div>

        <div style="display:flex;gap:10px;">
          <button type="submit" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Save Changes
          </button>
          <a href="?page=manage_reviews" class="btn btn-ghost">Cancel</a>
        </div>

      </form>
    </div>
  </div>

</div>

<script>
let _currentRating = <?= $currentRating ?>;
const ratingLabels = {
    1: 'Terrible',
    2: 'Poor',
    3: 'Fair',
    4: 'Good',
    5: 'Excellent',
};

function renderStars(filled) {
    document.querySelectorAll('.star-btn').forEach(function (btn) {
        const star = parseInt(btn.dataset.star);
        const icon = btn.querySelector('.star-icon');
        if (star <= filled) {
            icon.classList.remove('text-gray-300');
            icon.classList.add('text-amber-400');
            btn.classList.add('bg-amber-50', 'border-amber-300');
            btn.classList.remove('bg-gray-50', 'border-gray-200');
        } else {
            icon.classList.add('text-gray-300');
            icon.classList.remove('text-amber-400');
            btn.classList.remove('bg-amber-50', 'border-amber-300');
            btn.classList.add('bg-gray-50', 'border-gray-200');
        }
    });
}

function setRating(val) {
    _currentRating = val;
    document.getElementById('review-rating-val').value = val;
    document.getElementById('rating-label').textContent = ratingLabels[val] || '';
    renderStars(val);
}

function hoverRating(val) {
    renderStars(val);
}

function resetHover() {
    renderStars(_currentRating);
}

// init label
document.getElementById('rating-label').textContent = ratingLabels[_currentRating] || '';
</script>

<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
