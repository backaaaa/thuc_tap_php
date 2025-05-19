<?php
class AffiliatePartner
{
    public const PLATFORM_NAME = "VietLink Affiliate";

    protected $name;
    protected $email;
    protected $commissionRate;
    protected $isActive;

    public function __construct(string $name, string $email, float $commissionRate, bool $isActive = true)
    {
        $this -> name = $name;
        $this -> email = $email;
        $this -> commissionRate = $commissionRate;
        $this -> isActive = $isActive;
    }

    public function __destruct()
    {
        echo "[LOG] AffiliatePartner '{$this->name}' đã bị hủy. <br>";
    }

    public function calculateCommission($orderValue): float
    {
        if(!$this->isActive){
            return 0;
        }
        return $orderValue * ($this->commissionRate / 100);
    }

    public function getSummary(): string
    {
        return sprintf(
            "CTV: %s | Email: %s | Hoa hồng: %.2f%% | Trạng thái: %s | Nền tảng: %s",
            $this -> name,
            $this -> email,
            $this -> commissionRate,
            $this -> isActive ? "Hoạt động" : "Ngưng hoạt động",
            self::PLATFORM_NAME
        );
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}

class PremiumAffiliatePartner extends AffiliatePartner
{
    protected float $bonusPerOrder;

    public function __construct(string $name, string $email, float $commissionRate, float $bonusPerOrder, bool $isActive = true)
    {
        parent::__construct($name, $email, $commissionRate, $isActive);
        $this->bonusPerOrder = $bonusPerOrder;
    }

    public function calculateCommission($orderValue): float
    {
        if(!$this->isActive){
            return 0;
        }
        $baseCommission = parent::calculateCommission($orderValue);
        return $baseCommission + $this->bonusPerOrder;
    }

    
    public function getSummary(): string 
    {
        return parent::getSummary() . sprintf(" | Bonus cố định: %.0f VNĐ <br>", $this->bonusPerOrder);
    }

    public function __destruct() 
    {
        echo "[LOG] PremiumAffiliatePartner '{$this->name}' đã bị hủy. <br>";
        parent::__destruct();
    }
}

class AffiliateManager
{
    private array $partners = [];

    public function addPartner($affiliate): void
    {
        $this->partners[] = $affiliate;
    }

    public function listPartners()
    {
        echo "Danh sách CTV đang quản lý <br>";
        foreach($this->partners as $partner){
            echo $partner->getSummary(). "<br>";
        }

    }

    public function totalCommission($orderValue)
    {
        $total = 0;
        foreach($this->partners as $partner){
            $total += $partner->calculateCommission($orderValue);
        }
        return $total;
    }
}


$manager = new AffiliateManager();

$affiliate1 = new AffiliatePartner("Đinh Thị A", "aph123@gmail.com", 5.0);
$affiliate2 = new AffiliatePartner("Đinh Thị B", "bph321@gmail.com", 7.5);
$affiliate3 = new PremiumAffiliatePartner("Đinh Thị C", "CaoCap@gmail.com", 10.0, 150000);

$manager->addPartner($affiliate1);
$manager->addPartner($affiliate2);
$manager->addPartner($affiliate3);

$orderValue = 2_000_000;

echo "Hoa hồng từng CTV cho đơn hàng {$orderValue} VNĐ <br>";
foreach ([$affiliate1, $affiliate2, $affiliate3] as $affiliate) {
    $commission = $affiliate->calculateCommission($orderValue);
    echo $affiliate->getSummary() . sprintf(" | Hoa hồng: %.0f VNĐ <br>", $commission);
}

$totalCommission = $manager->totalCommission($orderValue);
echo "Tổng hoa hồng hệ thống phải chi trả: " . number_format($totalCommission, 0, ',', '.') . " VNĐ<br>";
