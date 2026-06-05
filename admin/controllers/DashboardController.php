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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = $_POST;

            // Validation
            foreach (['brand','model','year','license_plate','price_per_day','transmission','fuel_type','seats'] as $f) {
                if (empty(trim($_POST[$f] ?? ''))) {
                    $errors[] = ucfirst(str_replace('_', ' ', $f)) . ' is required.';
                }
            }
            if (!empty($_POST['transmission']) && !in_array($_POST['transmission'], ['automatic','manual'], true))
                $errors[] = 'Invalid transmission value.';
            if (!empty($_POST['fuel_type']) && !in_array($_POST['fuel_type'], ['gasoline','diesel','electric','hybrid'], true))
                $errors[] = 'Invalid fuel type value.';
            if (!empty($_POST['year']) && ((int)$_POST['year'] < 1990 || (int)$_POST['year'] > 2099))
                $errors[] = 'Year must be between 1990 and 2099.';
            if (!empty($_POST['price_per_day']) && (float)$_POST['price_per_day'] < 0)
                $errors[] = 'Price per day must be a positive number.';

            // Photo upload
            $photoFilename = null;
            if (!empty($_FILES['photo']['name'])) {
                $allowed = ['image/jpeg','image/png','image/webp'];
                $mime    = mime_content_type($_FILES['photo']['tmp_name']);
                if (!in_array($mime, $allowed, true)) {
                    $errors[] = 'Photo must be JPG, PNG, or WEBP.';
                } elseif ($_FILES['photo']['size'] > 5 * 1024 * 1024) {
                    $errors[] = 'Photo must be smaller than 5MB.';
                } else {
                    $ext           = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                    $photoFilename = 'car_' . bin2hex(random_bytes(8)) . '_' . time() . '.' . $ext;
                    $uploadDir     = __DIR__ . '/../assets/images/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $photoFilename)) {
                        $errors[] = 'Failed to save photo. Check folder permissions.';
                        $photoFilename = null;
                    }
                }
            }

            if (empty($errors)) {
                $result = (new CarModel())->create([
                    'brand'         => trim($_POST['brand']),
                    'model'         => trim($_POST['model']),
                    'year'          => (int)$_POST['year'],
                    'license_plate' => strtoupper(trim($_POST['license_plate'])),
                    'price_per_day' => (float)$_POST['price_per_day'],
                    'transmission'  => $_POST['transmission'],
                    'fuel_type'     => $_POST['fuel_type'],
                    'seats'           => (int)$_POST['seats'],
                    'description'     => trim($_POST['description'] ?? ''),
                    'photo'           => $photoFilename,
                    'hl_drivetrain'   => trim($_POST['hl_drivetrain'] ?? 'FWD'),
                    'hl_body_style'   => trim($_POST['hl_body_style'] ?? 'Sedan'),
                    'hl_engine'       => trim($_POST['hl_engine'] ?? ''),
                    'hl_transmission' => trim($_POST['hl_transmission'] ?? 'Automatic'),
                ]);
                if ($result === 'duplicate') {
                    $errors[] = 'License plate already exists. Please use a unique plate number.';
                } elseif ($result === true) {
                    $success = 'Car listed successfully!'; $old = [];
                } else {
                    $errors[] = 'Failed to save car. Please try again.';
                }
            }
        }
        require_once __DIR__ . '/../views/add_car.php';
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

            // Validation
            foreach (['brand','model','year','license_plate','price_per_day','transmission','fuel_type','seats','status'] as $f) {
                if (empty(trim((string)($_POST[$f] ?? '')))) {
                    $errors[] = ucfirst(str_replace('_', ' ', $f)) . ' is required.';
                }
            }
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
                    $ext           = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                    $photoFilename = 'car_' . bin2hex(random_bytes(8)) . '_' . time() . '.' . $ext;
                    $uploadDir     = __DIR__ . '/../assets/images/';
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
                    'license_plate' => strtoupper(trim($_POST['license_plate'])),
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

        $validStatuses = ['', 'unpaid', 'paid', 'refunded'];
        if (!in_array($status, $validStatuses, true)) {
            $status = '';
        }

        $all         = $this->model->getPaymentsFiltered($status, $search);
        $total       = count($all);
        $totalPages  = max(1, (int) ceil($total / $this->perPage));
        $currentPage = max(1, min($totalPages, (int) ($_GET['p'] ?? 1)));
        $offset      = ($currentPage - 1) * $this->perPage;
        $payments    = array_slice($all, $offset, $this->perPage);
        $page        = 'manage_payments';
        require_once __DIR__ . '/../views/manage_payments.php';
    }
}