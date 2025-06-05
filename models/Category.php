<?php

class Category extends BaseModel
{
  protected $table = 'categories';

  /**
   * Lấy 3 danh mục mà trong đó có sản phẩm tạo mới nhất
   * Chỉ trả về trường name và image_url (giả sử bạn đã thêm cột image_url vào bảng categories)
   *
   * @return array
   */
  public function getTop3ByLatestProduct(): array
  {
    $sql = "
          SELECT
            c.name,
            c.image_url
          FROM {$this->table} c
          JOIN products p
            ON p.category_id = c.id
          GROUP BY c.id, c.name, c.image_url
          ORDER BY MAX(p.created_at) DESC
          LIMIT 3
        ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function getAll(): array
  {
    $sql = "SELECT * FROM {$this->table}";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

}
