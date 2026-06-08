<?php
require_once __DIR__ . '/../models/DashboardModel.php';
require_once __DIR__ . '/../models/CarModel.php';

class DashboardController {
    private DashboardModel $model;
    private int $perPage = 10;
    public function __construct() { $this->model = new DashboardModel(); }

    public function dashboard(): void {
        $cars = $this->model->getCars();
        $bookings = $this->model->getBookings();
        $month = $_GET['month'] ?? date('Y-m');
        $stats = $this->model->getStats($cars, $bookings, $month);
        $chartData = $this->model->getRevenueChartData($month);
        $page = 'dashboard';
        require_once __DIR__ . '/../views/dashboard.php';
    }

    public function addCar(): void {
        $success = ''; $errors = []; $old = []; $page = 'add_car';

        $carModel = new CarModel();
        $uniqueCars = $this->getUniqueCarTemplates($carModel->getAll());

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = $_POST;
            $licensePlate = $this->normalizeLicensePlate($_POST['license_plate'] ?? '');

            // Validation
            foreach (['brand','model','year','license_plate','price_per_day','transmission','fuel_type','seats','hl_engine'] as $f) {
                if (empty(trim($_POST[$f] ?? ''))) {
                    $errors[] = ucfirst(str_replace('_', ' ', $f)) . ' is required.';
                }
            }
            if (!empty(trim($_POST['license_plate'] ?? '')) && $licensePlate === null)
                $errors[] = 'License plate format must be like D 1234 ABM.';
            if (!empty($_POST['transmission']) && !in_array($_POST['transmission'], ['automatic','manual'], true))
                $errors[] = 'Invalid transmission value.';
            if (!empty($_POST['fuel_type']) && !in_array($_POST['fuel_type'], ['gasoline','diesel','electric','hybrid'], true))
                $errors[] = 'Invalid fuel type value.';
            if (!empty($_POST['year']) && ((int)$_POST['year'] < 1990 || (int)$_POST['year'] > 2099))
                $errors[] = 'Year must be between 1990 and 2099.';
            if (!empty($_POST['price_per_day']) && (float)$_POST['price_per_day'] < 0)
                $errors[] = 'Price per day must be a positive number.';
            if (!empty($_POST['license_plate']) && !preg_match('/[0-9]/', $_POST['license_plate']))
                $errors[] = 'License plate must contain at least one number.';

            // Photo upload
            $photoFilename = null;
            if (empty($_FILES['photo']['name'])) {
                $errors[] = 'Car photo is required. Please upload a photo.';
            } else {
                $allowed = ['image/jpeg','image/png','image/webp'];
                $mime    = mime_content_type($_FILES['photo']['tmp_name']);
                if (!in_array($mime, $allowed, true)) {
                    $errors[] = 'Photo must be JPG, PNG, or WEBP.';
                } elseif ($_FILES['photo']['size'] > 5 * 1024 * 1024) {
                    $errors[] = 'Photo must be smaller than 5MB.';
                } else {
                    $photoFilename = basename($_FILES['photo']['name']);
                    $uploadDir     = __DIR__ . '/../../assets/images/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $photoFilename)) {
                        $errors[] = 'Failed to save photo. Check folder permissions.';
                        $photoFilename = null;
                    }
                }
            }

            if (empty($errors)) {
                $result = $carModel->create([
                    'brand'         => trim($_POST['brand']),
                    'model'         => trim($_POST['model']),
                    'year'          => (int)$_POST['year'],
                    'license_plate' => $licensePlate,
                    'price_per_day' => (float)$_POST['price_per_day'],
                    'transmission'  => $_POST['transmission'],
                    'fuel_type'     => $_POST['fuel_type'],
                    'seats'           => (int)$_POST['seats'],
                    'description'     => trim($_POST['description'] ?? ''),
                    'photo'           => $photoFilename,
                    'hl_drivetrain'   => trim($_POST['hl_drivetrain'] ?? 'FWD'),
                    'hl_body_style'   => trim($_POST['hl_body_style'] ?? 'Sedan'),
                    'hl_engine'       => trim($_POST['hl_engine'] ?? ''),
                ]);
                if ($result === 'duplicate') {
                    $errors[] = 'License plate already exists. Please use a unique plate number.';
                } elseif ($result === true) {
                    $success = 'Car listed successfully!'; $old = [];
                    $uniqueCars = $this->getUniqueCarTemplates($carModel->getAll());
                } else {
                    $errors[] = 'Failed to save car. Please try again.';
                }
            }
        }
        require_once __DIR__ . '/../views/add_car.php';
    }

    private function getUniqueCarTemplates(array $cars): array {
        $uniqueCars = [];
        $seen = [];

        foreach ($cars as $car) {
            $key = strtolower($car['brand'] . '|' . $car['model']);
            if (isset($seen[$key])) {
                continue;
            }

            $seen[$key] = true;
            $uniqueCars[] = $car;
        }

        return $uniqueCars;
    }

    private function normalizeLicensePlate(string $plate): ?string {
        $plate = strtoupper(preg_replace('/\s+/', '', trim($plate)));

        if ($plate === '' || !preg_match('/^([A-Z]{1,2})([0-9]{1,4})([A-Z]{0,3})$/', $plate, $matches)) {
            return null;
        }

        return trim($matches[1] . ' ' . $matches[2] . ' ' . $matches[3]);
    }

    public function editCar(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ?page=manage_cars');
            exit;
        }

        $carModel = new CarModel();
        $car = $carModel->findById($id);
        if (!$car) {
            header('Location: ?page=manage_cars');
            exit;
        }

        $success = ''; $errors = []; $old = $car; $page = 'edit_car';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = array_merge($old, $_POST); // Merge for form repopulation
            $licensePlate = $this->normalizeLicensePlate($_POST['license_plate'] ?? '');

            // Validation
            foreach (['brand','model','year','license_plate','price_per_day','transmission','fuel_type','seats','status','hl_engine'] as $f) {
                if (empty(trim((string)($_POST[$f] ?? '')))) {
                    $errors[] = ucfirst(str_replace('_', ' ', $f)) . ' is required.';
                }
            }
            if (!empty(trim((string)($_POST['license_plate'] ?? ''))) && $licensePlate === null)
                $errors[] = 'License plate format must be like D 1234 ABM.';
            if (!empty($_POST['transmission']) && !in_array($_POST['transmission'], ['automatic','manual'], true))
                $errors[] = 'Invalid transmission value.';
            if (!empty($_POST['fuel_type']) && !in_array($_POST['fuel_type'], ['gasoline','diesel','electric','hybrid'], true))
                $errors[] = 'Invalid fuel type value.';
            if (!empty($_POST['status']) && !in_array($_POST['status'], ['available','booked','maintenance'], true))
                $errors[] = 'Invalid status value.';
            if (!empty($_POST['year']) && ((int)$_POST['year'] < 1990 || (int)$_POST['year'] > 2099))
                $errors[] = 'Year must be between 1990 and 2099.';
            if (!empty($_POST['price_per_day']) && (float)$_POST['price_per_day'] < 0)
                $errors[] = 'Price per day must be a positive number.';

            // Photo upload
            $photoFilename = $car['photo'];
            $newPhotoUploaded = false;
            if (!empty($_FILES['photo']['name'])) {
                $allowed = ['image/jpeg','image/png','image/webp'];
                $mime    = mime_content_type($_FILES['photo']['tmp_name']);
                if (!in_array($mime, $allowed, true)) {
                    $errors[] = 'Photo must be JPG, PNG, or WEBP.';
                } elseif ($_FILES['photo']['size'] > 5 * 1024 * 1024) {
                    $errors[] = 'Photo must be smaller than 5MB.';
                } else {
                    $photoFilename = basename($_FILES['photo']['name']);
                    $uploadDir     = __DIR__ . '/../../assets/images/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $photoFilename)) {
                        $errors[] = 'Failed to save photo. Check folder permissions.';
                        $photoFilename = $car['photo']; // revert to old photo
                    } else {
                        $newPhotoUploaded = true;
                    }
                }
            }

            if (empty($errors)) {
                $updateData = [
                    'brand'         => trim($_POST['brand']),
                    'model'         => trim($_POST['model']),
                    'year'          => (int)$_POST['year'],
                    'license_plate' => $licensePlate,
                    'price_per_day' => (float)$_POST['price_per_day'],
                    'transmission'  => $_POST['transmission'],
                    'fuel_type'     => $_POST['fuel_type'],
                    'seats'           => (int)$_POST['seats'],
                    'description'     => trim($_POST['description'] ?? ''),
                    'status'          => $_POST['status'],
                    'hl_drivetrain'   => trim($_POST['hl_drivetrain'] ?? 'FWD'),
                    'hl_body_style'   => trim($_POST['hl_body_style'] ?? 'Sedan'),
                    'hl_engine'       => trim($_POST['hl_engine'] ?? ''),
                    'hl_transmission' => trim($_POST['hl_transmission'] ?? 'Automatic')
                ];
                if ($newPhotoUploaded) {
                    $updateData['photo'] = $photoFilename;
                }

                $result = $carModel->update($id, $updateData);
                if ($result === 'duplicate') {
                    $errors[] = 'License plate already exists. Please use a unique plate number.';
                } elseif ($result === true) {
                    header('Location: ?page=manage_cars');
                    exit;
                } else {
                    $errors[] = 'Failed to update car. Please try again.';
                }
            }
        }
        require_once __DIR__ . '/../views/edit_car.php';
    }

    public function deleteCar(): void {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $carModel = new CarModel();
            $carModel->delete($id);
        }
        header('Location: ?page=manage_cars');
        exit;
    }

    public function manageCars(): void {
        $search       = trim($_GET['search']       ?? '');
        $status       = trim($_GET['status']       ?? '');
        $transmission = trim($_GET['transmission'] ?? '');
        $fuelType     = trim($_GET['fuel_type']    ?? '');
        $priceMin     = trim($_GET['price_min']    ?? '');
        $priceMax     = trim($_GET['price_max']    ?? '');
        $yearMin      = trim($_GET['year_min']     ?? '');
        $yearMax      = trim($_GET['year_max']     ?? '');

        // Sanitasi enum
        if (!in_array($status,       ['', 'available', 'booked', 'maintenance'], true))       $status       = '';
        if (!in_array($transmission, ['', 'automatic', 'manual'], true))                      $transmission = '';
        if (!in_array($fuelType,     ['', 'gasoline', 'diesel', 'electric', 'hybrid'], true)) $fuelType     = '';
        if ($priceMin !== '' && !is_numeric($priceMin)) $priceMin = '';
        if ($priceMax !== '' && !is_numeric($priceMax)) $priceMax = '';
        if ($yearMin  !== '' && !is_numeric($yearMin))  $yearMin  = '';
        if ($yearMax  !== '' && !is_numeric($yearMax))  $yearMax  = '';

        $carModel = new CarModel();
        $globalAll = $carModel->getAll();
        
        $stats = [
            'available'   => 0,
            'booked'      => 0,
            'maintenance' => 0,
            'automatic'   => 0,
            'manual'      => 0
        ];
        
        foreach ($globalAll as $c) {
            if ($c['status'] === 'available') $stats['available']++;
            if ($c['status'] === 'booked') $stats['booked']++;
            if ($c['status'] === 'maintenance') $stats['maintenance']++;
            if ($c['transmission'] === 'automatic') $stats['automatic']++;
            if ($c['transmission'] === 'manual') $stats['manual']++;
        }

        $all = $carModel->getAllFiltered(
            $search, $status, $transmission, $fuelType,
            $priceMin, $priceMax, $yearMin, $yearMax
        );

        $perPage     = 8; // Membatasi hanya 8 mobil
        $total       = count($all);
        $totalPages  = max(1, (int) ceil($total / $perPage));
        $currentPage = max(1, min($totalPages, (int) ($_GET['p'] ?? 1)));
        $offset      = ($currentPage - 1) * $perPage;
        $cars        = array_slice($all, $offset, $perPage);

        $filterVars = compact(
            'search', 'status', 'transmission', 'fuelType',
            'priceMin', 'priceMax', 'yearMin', 'yearMax'
        );

        $page = 'manage_cars';
        require_once __DIR__ . '/../views/manage_cars.php';
    }

    public function manageBookings(): void {
        $status = trim($_GET['status'] ?? '');
        $search = trim($_GET['search'] ?? '');

        $validStatuses = ['', 'pending', 'confirmed', 'ongoing', 'completed', 'cancelled'];
        if (!in_array($status, $validStatuses, true)) {
            $status = '';
        }

        $all         = $this->model->getBookingsFiltered($status, $search);
        
        $globalAll   = $this->model->getBookings();
        $stats = [
            'pending'   => 0,
            'confirmed' => 0,
            'ongoing'   => 0,
            'completed' => 0,
            'cancelled' => 0,
        ];
        foreach ($globalAll as $b) {
            $s = $b['status'] ?? 'pending';
            if (isset($stats[$s])) {
                $stats[$s]++;
            }
        }

        $total       = count($all);
        $totalPages  = max(1, (int) ceil($total / $this->perPage));
        $currentPage = max(1, min($totalPages, (int) ($_GET['p'] ?? 1)));
        $offset      = ($currentPage - 1) * $this->perPage;
        $bookings    = array_slice($all, $offset, $this->perPage);
        $page        = 'manage_bookings';
        require_once __DIR__ . '/../views/manage_bookings.php';
    }

    public function managePayments(): void {
        $status = trim($_GET['status'] ?? '');
        $search = trim($_GET['search'] ?? '');
        $month  = trim($_GET['month'] ?? '');

        $validStatuses = ['', 'unpaid', 'paid', 'refunded'];
        if (!in_array($status, $validStatuses, true)) {
            $status = '';
        }

        $all         = $this->model->getPaymentsFiltered($status, $search, $month);
        
        // Calculate stats using only the month filter (ignoring status/search so cards reflect the selected month)
        $allForStats = $this->model->getPaymentsFiltered('', '', $month);
        $stats = ['total' => count($allForStats), 'paid' => 0, 'unpaid' => 0, 'refunded' => 0];
        foreach ($allForStats as $p) {
            $st = $p['payment_status'] ?? 'unpaid';
            if (isset($stats[$st])) {
                $stats[$st]++;
            }
        }

        $total       = count($all);
        $totalPages  = max(1, (int) ceil($total / $this->perPage));
        $currentPage = max(1, min($totalPages, (int) ($_GET['p'] ?? 1)));
        $offset      = ($currentPage - 1) * $this->perPage;
        $payments    = array_slice($all, $offset, $this->perPage);
        $page        = 'manage_payments';
        require_once __DIR__ . '/../views/manage_payments.php';
    }

    public function exportPayments(): void {
        $status = trim($_GET['status'] ?? '');
        $search = trim($_GET['search'] ?? '');
        $month  = trim($_GET['month'] ?? '');

        $validStatuses = ['', 'unpaid', 'paid', 'refunded'];
        if (!in_array($status, $validStatuses, true)) {
            $status = '';
        }

        $all = $this->model->getPaymentsFiltered($status, $search, $month);

        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=Payments_Report_" . date('Ymd_His') . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta charset="UTF-8">';
        echo '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Payments Report</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->';
        echo '</head><body>';
        
        echo '<h2 style="font-family: Arial, sans-serif; color: #1f2937;">Payments Report</h2>';
        echo '<p style="font-family: Arial, sans-serif; color: #6b7280; font-size: 12px;">Generated at: ' . date('d M Y H:i:s') . '</p>';

        echo '<table border="1" cellpadding="5" cellspacing="0" style="font-family: Arial, sans-serif; font-size: 13px; border-collapse: collapse; width: 100%; border: 1px solid #e5e7eb;">';
        echo '<thead style="background-color: #3b82f6; color: #ffffff;">';
        echo '<tr>';
        echo '<th style="text-align: center; padding: 10px; border: 1px solid #d1d5db;">Booking ID</th>';
        echo '<th style="text-align: left; padding: 10px; border: 1px solid #d1d5db;">Customer</th>';
        echo '<th style="text-align: left; padding: 10px; border: 1px solid #d1d5db;">Car</th>';
        echo '<th style="text-align: right; padding: 10px; border: 1px solid #d1d5db;">Total Price (Rp)</th>';
        echo '<th style="text-align: center; padding: 10px; border: 1px solid #d1d5db;">Payment Method</th>';
        echo '<th style="text-align: center; padding: 10px; border: 1px solid #d1d5db;">Status</th>';
        echo '<th style="text-align: center; padding: 10px; border: 1px solid #d1d5db;">Paid At</th>';
        echo '<th style="text-align: center; padding: 10px; border: 1px solid #d1d5db;">Created At</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($all as $index => $p) {
            $bg = ($index % 2 === 0) ? '#f9fafb' : '#ffffff';
            echo '<tr style="background-color: ' . $bg . ';">';
            echo '<td style="text-align: center; border: 1px solid #d1d5db;">' . $p['booking_id'] . '</td>';
            echo '<td style="border: 1px solid #d1d5db;">' . htmlspecialchars($p['customer_name']) . '</td>';
            echo '<td style="border: 1px solid #d1d5db;">' . htmlspecialchars($p['car_name']) . '</td>';
            echo '<td style="text-align: right; border: 1px solid #d1d5db; font-weight: bold;">' . number_format($p['total_price'], 0, ',', '.') . '</td>';
            
            $method = $p['payment_method'] ? ucwords(str_replace('_', ' ', $p['payment_method'])) : '-';
            echo '<td style="text-align: center; border: 1px solid #d1d5db;">' . htmlspecialchars($method) . '</td>';
            
            $statusColor = match($p['payment_status']) {
                'paid' => '#166534',
                'unpaid' => '#b45309',
                'refunded' => '#374151',
                default => '#374151'
            };
            $statusBg = match($p['payment_status']) {
                'paid' => '#dcfce7',
                'unpaid' => '#fef3c7',
                'refunded' => '#f3f4f6',
                default => '#f3f4f6'
            };
            echo '<td style="text-align: center; border: 1px solid #d1d5db;"><span style="color: '.$statusColor.'; background-color: '.$statusBg.'; padding: 4px 10px; border-radius: 4px; font-weight: bold; text-transform: uppercase; font-size: 11px;">' . htmlspecialchars($p['payment_status']) . '</span></td>';
            
            $paidAt = $p['paid_at'] ? date('d M Y H:i', strtotime($p['paid_at'])) : '-';
            echo '<td style="text-align: center; border: 1px solid #d1d5db;">' . $paidAt . '</td>';
            echo '<td style="text-align: center; border: 1px solid #d1d5db;">' . date('d M Y H:i', strtotime($p['created_at'])) . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table></body></html>';
        exit;
    }

    public function paymentDetail(): void {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ?page=manage_payments');
            exit;
        }

        $payment = $this->model->getPaymentById($id);
        if ($payment === null) {
            header('Location: ?page=manage_payments&error=not_found');
            exit;
        }

        $page = 'manage_payments';
        require_once __DIR__ . '/../views/payment_detail.php';
    }

    public function editPayment(): void {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ?page=manage_payments');
            exit;
        }

        $payment = $this->model->getPaymentById($id);
        if ($payment === null) {
            header('Location: ?page=manage_payments&error=not_found');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = trim($_POST['payment_status'] ?? '');
            
            if ($this->model->updatePaymentStatus($id, $status)) {
                header('Location: ?page=payment_detail&id=' . $id . '&updated=1');
                exit;
            }
            $error = "Failed to update payment status. Invalid status selected.";
        }

        $page = 'manage_payments';
        require_once __DIR__ . '/../views/payment_edit_form.php';
    }
}
