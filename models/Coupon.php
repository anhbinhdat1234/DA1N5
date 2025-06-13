<?php
// models/Coupon.php

class Coupon extends BaseModel
{
    protected $table = 'coupons';

    public function all(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute([':id'=>$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO {$this->table}
          (code,type,value,min_order,usage_limit,start_at,end_at,status)
        VALUES
          (:code,:type,:value,:min_order,:usage_limit,:start_at,:end_at,:status)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
          ':code'        => strtoupper(trim($data['code'])),
          ':type'        => $data['type'],
          ':value'       => $data['value'],
          ':min_order'   => $data['min_order'],
          ':usage_limit' => $data['usage_limit'],
          ':start_at'    => $data['start_at']?: null,
          ':end_at'      => $data['end_at']  ?: null,
          ':status'      => $data['status']  ? 1 : 0,
        ]);
    }

    public function updateCoupon(int $id, array $data): bool
    {
        $sql = "UPDATE {$this->table} SET
          code=:code,type=:type,value=:value,min_order=:min_order,
          usage_limit=:usage_limit,start_at=:start_at,end_at=:end_at,status=:status
        WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
          ':code'        => strtoupper(trim($data['code'])),
          ':type'        => $data['type'],
          ':value'       => $data['value'],
          ':min_order'   => $data['min_order'],
          ':usage_limit' => $data['usage_limit'],
          ':start_at'    => $data['start_at']?: null,
          ':end_at'      => $data['end_at']  ?: null,
          ':status'      => $data['status']  ? 1 : 0,
          ':id'          => $id,
        ]);
    }

    public function deleteById(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id=:id");
        return $stmt->execute([':id'=>$id]);
    }

    /**
     * Validate coupon khi khách áp dụng
     */
    public function validate(string $code, float $cartTotal): ?array
    {
        $stmt = $this->pdo->prepare(
          "SELECT * FROM {$this->table} 
           WHERE code=:code AND status=1
             AND (start_at IS NULL OR start_at<=NOW())
             AND (end_at   IS NULL OR end_at  >=NOW())
           LIMIT 1"
        );
        $stmt->execute([':code'=>strtoupper(trim($code))]);
        $c = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$c) return null;
        if ($c['usage_limit']>0 && $c['used'] >= $c['usage_limit']) {
            return null;
        }
        if ($cartTotal < $c['min_order']) {
            return null;
        }
        return $c;
    }

    /** tăng 1 lượt dùng */
    public function incrementUsage(int $id): void
    {
        $this->pdo->prepare(
          "UPDATE {$this->table} SET used = used+1 WHERE id=:id"
        )->execute([':id'=>$id]);
    }
    //TỔNG SỐ COUPON
  //   public function count(): int
  // {
  //     $stmt = $this->pdo->query("SELECT COUNT(*) FROM {$this->table}");
  //     return (int)$stmt->fetchColumn();
  // }

}