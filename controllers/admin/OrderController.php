<?php
require_once PATH_MODEL . 'Order.php';
require_once PATH_MODEL . 'OrderItem.php';

class OrderController
{
  private $order;
  private $orderItem;

  public function __construct()
  {
    $this->order = new Order();
    $this->orderItem = new OrderItem();
  }

  // Hiển thị danh sách đơn hàng
  public function index()
  {
    $orders = $this->order->getAll();
    $view = 'orders/index';
    $title = 'Danh sách đơn hàng';
    require_once PATH_VIEW_ADMIN_MAIN;
  }

  // Hiển thị chi tiết đơn hàng
  public function show()
  {
    $id = $_GET['id'] ?? null;
    $order = $this->order->findById($id);
    $orderItems = $this->orderItem->getByOrderIdWithProductDetail($id);
    $view = 'orders/show';
    $title = 'Chi tiết đơn hàng';
    require_once PATH_VIEW_ADMIN_MAIN;
  }

  // Cập nhật trạng thái
  public function updateStatus()
  {
    $id = $_GET['id'] ?? null;
    $status = $_POST['status'] ?? 'pending';
    $order = $this->order->findById($id);
    if (!$order) {
      $_SESSION['success'] = false;
      $_SESSION['msg'] = 'Không tìm thấy đơn hàng!';
      header('Location: ' . BASE_URL_ADMIN . '&action=orders-index');
      exit();
    }
    $this->order->updateStatus($id, $status);
    $_SESSION['success'] = true;
    $_SESSION['msg'] = 'Cập nhật trạng thái thành công!';
    header('Location: ' . BASE_URL_ADMIN . '&action=orders-show&id=' . $id);
    exit();
  }

  // Xóa đơn hàng
  public function delete()
  {
    $id = $_GET['id'] ?? null;
    $this->order->deleteById($id);
    $_SESSION['success'] = true;
    $_SESSION['msg'] = 'Xóa đơn hàng thành công!';
    header('Location: ' . BASE_URL_ADMIN . '&action=orders-index');
    exit();
  }
}
