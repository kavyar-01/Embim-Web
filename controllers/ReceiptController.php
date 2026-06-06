<?php

require_once 'models/BookingModel.php';
require_once 'models/UserModel.php'; // Assuming there is a UserModel or we can query user directly

class ReceiptController {

    public function download() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $bookingId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $bookingModel = new BookingModel();
        $booking = $bookingModel->getBookingById($bookingId);

        if (!$booking || (int)$booking['user_id'] !== (int)$_SESSION['user_id']) {
            die("Booking not found or you don't have access.");
        }

        if (!in_array($booking['status'], ['confirmed', 'completed','ongoing'])) {
            die("Receipts are only available for confirmed bookings.");
        }

        // Get User details
        $db = (new Database())->getConnection();
        $stmt = $db->prepare("SELECT full_name, email, phone, `address` FROM users WHERE id = ?");
        $stmt->execute([$booking['user_id']]);
        $user = $stmt->fetch();

        $this->generateReceiptImage($booking, $user);
    }

    private function generateReceiptImage($booking, $user) {
        $width = 600;
        $height = 1200;
        $img = imagecreatetruecolor($width, $height);

        $bg = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);
        $gray = imagecolorallocate($img, 100, 100, 100);
        $lightGray = imagecolorallocate($img, 200, 200, 200);

        imagefilledrectangle($img, 0, 0, $width, $height, $bg);

        // Define fonts (using built-in fonts for simplicity to avoid needing TTF files, 
        // though TTF looks better. I'll use imagestring for simplicity, or TTF if available.
        // Let's stick to imagestring for max compatibility, but scaling it up.)
        
        $y = 30;
        
        // Header
        $this->drawCenteredText($img, 5, "EMBIM", $y, $black, $width);
        $y += 30;
        $this->drawCenteredText($img, 3, "Easy Mobility Booking In Minutes", $y, $gray, $width);
        $y += 20;
        $this->drawCenteredText($img, 3, "Jl. Sukaasih V No.115 RT. 04/08, Sindang Jaya, Kec. Mandalajati, Kota Bandung", $y, $gray, $width);
        $y += 20;
        $this->drawCenteredText($img, 3, "cs@embim.id  .  +62821-4484-5847", $y, $gray, $width);
        $y += 40;

        imageline($img, 30, $y, $width - 30, $y, $lightGray);
        $y += 20;

        // Info
        $dateStr = date('d/m/Y H:i', strtotime($booking['created_at'])) . " WIB";
        $receiptNo = "RCP-" . str_pad($booking['id'], 6, '0', STR_PAD_LEFT);
        $bookingNo = "EMBIM-" . str_pad($booking['id'], 6, '0', STR_PAD_LEFT);

        imagestring($img, 4, 40, $y, $dateStr, $gray);
        $this->drawRightText($img, 4, $receiptNo, $y, $gray, $width - 40);
        $y += 20;
        imagestring($img, 4, 40, $y, "No. Booking:", $gray);
        $this->drawRightText($img, 4, $bookingNo, $y, $gray, $width - 40);
        $y += 40;

        imageline($img, 30, $y, $width - 30, $y, $lightGray);
        $y += 30;

        // Data Penyewa
        imagestring($img, 5, 40, $y, "DATA PENYEWA", $black);
        $y += 30;
        $this->drawRow($img, 4, "Nama", $user['full_name'], $y, $gray, $black, $width);
        $this->drawRow($img, 4, "Email", $user['email'], $y, $gray, $black, $width);
        $this->drawRow($img, 4, "Telepon", $user['phone'] ?? '-', $y, $gray, $black, $width);
        $this->drawRow($img, 4, "Alamat", $user['address'] ?? '-', $y, $gray, $black, $width);
        
        $y += 20;
        imageline($img, 30, $y, $width - 30, $y, $lightGray);
        $y += 30;

        // Detail Booking
        imagestring($img, 5, 40, $y, "DETAIL BOOKING", $black);
        $y += 30;
        $carName = $booking['brand'] . ' ' . $booking['model'];
        $this->drawRow($img, 4, "Kendaraan", $carName, $y, $gray, $black, $width);
        $this->drawRow($img, 4, "Tgl. Mulai", date('d F Y', strtotime($booking['start_date'])), $y, $gray, $black, $width);
        $this->drawRow($img, 4, "Tgl. Selesai", date('d F Y', strtotime($booking['end_date'])), $y, $gray, $black, $width);
        $this->drawRow($img, 4, "Durasi Sewa", $booking['total_days'] . " Hari", $y, $gray, $black, $width);

        $y += 20;
        imageline($img, 30, $y, $width - 30, $y, $lightGray);
        $y += 30;

        // Rincian Pembayaran
        imagestring($img, 5, 40, $y, "RINCIAN PEMBAYARAN", $black);
        $y += 30;
        $this->drawRow($img, 4, "Harga/hari", "Rp " . number_format($booking['price_per_day'], 0, ',', '.'), $y, $gray, $black, $width);
        $this->drawRow($img, 4, "Durasi", $booking['total_days'] . " hari", $y, $gray, $black, $width);
        $this->drawRow($img, 4, "Subtotal", "Rp " . number_format($booking['total_price'], 0, ',', '.'), $y, $gray, $black, $width);
        $method = strtoupper(str_replace('_', ' ', $booking['payment_method']));
        $this->drawRow($img, 4, "Metode Bayar", $method, $y, $gray, $black, $width);
        $this->drawRow($img, 4, "Status Bayar", "Lunas", $y, $gray, $black, $width);

        $y += 20;
        imageline($img, 30, $y, $width - 30, $y, $lightGray);
        $y += 30;

        // Total
        imagestring($img, 5, 40, $y, "TOTAL", $black);
        $this->drawRightText($img, 5, "Rp " . number_format($booking['total_price'], 0, ',', '.'), $y, $black, $width - 40);
        $y += 40;

        imageline($img, 30, $y, $width - 30, $y, $lightGray);
        $y += 30;

        // Fake Barcode
        $this->drawFakeBarcode($img, 40, $y, $width - 80, 50, $black);
        $y += 60;

        $this->drawCenteredText($img, 3, $bookingNo . "  .  " . $receiptNo, $y, $gray, $width);
        $y += 40;

        $this->drawCenteredText($img, 3, "Terima kasih telah menggunakan EMBIM.", $y, $gray, $width);
        $y += 20;
        $this->drawCenteredText($img, 3, "Struk ini merupakan bukti pembayaran yang sah.", $y, $gray, $width);
        $y += 30;
        
        date_default_timezone_set('Asia/Jakarta');
        $this->drawCenteredText($img, 3, "Dicetak: " . date('d/m/Y H:i') . " WIB  .  cs@embim.id", $y, $gray, $width);

        // Crop the image height if necessary
        $finalImg = imagecreatetruecolor($width, $y + 40);
        imagecopy($finalImg, $img, 0, 0, 0, 0, $width, $y + 40);
        imagedestroy($img);

        if (ob_get_length()) {
            ob_clean();
        }

        header('Content-Type: image/png');
        header('Content-Disposition: attachment; filename="struk_booking_' . $bookingNo . '.png"');
        imagepng($finalImg);
        imagedestroy($finalImg);
        exit;
    }

    private function drawRow($img, $font, $label, $value, &$y, $color1, $color2, $width) {
        imagestring($img, $font, 40, $y, $label, $color1);
        $this->drawRightText($img, $font, $value, $y, $color2, $width - 40);
        $y += 25;
    }

    private function drawCenteredText($img, $font, $text, $y, $color, $width) {
        $fw = imagefontwidth($font);
        $tw = strlen($text) * $fw;
        $x = ($width - $tw) / 2;
        imagestring($img, $font, $x, $y, $text, $color);
    }

    private function drawRightText($img, $font, $text, $y, $color, $rightX) {
        $fw = imagefontwidth($font);
        $tw = strlen($text) * $fw;
        $x = $rightX - $tw;
        imagestring($img, $font, $x, $y, $text, $color);
    }

    private function drawFakeBarcode($img, $x, $y, $width, $height, $color) {
        $currX = $x;
        srand(12345); // deterministic
        while ($currX < $x + $width) {
            $w = rand(1, 4);
            $space = rand(1, 3);
            if ($currX + $w > $x + $width) {
                $w = $x + $width - $currX;
            }
            imagefilledrectangle($img, $currX, $y, $currX + $w - 1, $y + $height, $color);
            $currX += $w + $space;
        }
    }
}
