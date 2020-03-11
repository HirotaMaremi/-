<?php

declare(strict_types=1);
namespace App\Entity;

use App\Util\Entity\CommonEntityBaseInterface;
use App\Util\Entity\CommonEntityBaseTrait;
use App\Util\Entity\IsValidInterface;
use App\Util\Entity\IsValidTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * 顧客マスタ
 * @ORM\Entity(repositoryClass="App\Repository\CustomerMstRepository")
 * @ORM\EntityListeners({"App\EntityListener\CommonEntityListener"})
 */
class CustomerMst implements CommonEntityBaseInterface, IsValidInterface
{
    use CommonEntityBaseTrait;
    use IsValidTrait;

    /**
     * ID
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"findCustomerMst", "makeDirectEdit", "customerList", "findEstimationAjax", "showIdentifiedCustomer", "reconcilePayment"})
     */
    private $id;

    /**
     * 会員コード//WEB会員ID
     * @var string
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank(message="会員コードを入力してください。")
     * @Assert\Length(max=32, maxMessage="会員コードは{{ limit }}文字以内で入力してください。")
     * @Groups({"customerList", "showIdentifiedCustomer"})
     */
    private $memberCode;

    /**
     * 姓
     * @var string
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank(message="姓を入力してください。")
     * @Assert\Length(max=32, maxMessage="姓は{{ limit }}文字以内で入力してください。")
     * @Groups({"customerList", "findCustomerMst", "findEstimationAjax", "showIdentifiedCustomer", "reconcilePayment"})
     */
    private $nameLast;

    /**
     * 名
     * @var string
     * @ORM\Column(type="string", length=32, nullable=true)
     * @Assert\Length(max=32, maxMessage="名は{{ limit }}文字以内で入力してください。")
     * @Groups({"customerList", "findCustomerMst", "findEstimationAjax", "showIdentifiedCustomer", "reconcilePayment"})
     */
    private $nameFirst;

    /**
     * 姓かな
     * @var string
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank(message="姓かなを入力してください。")
     * @Assert\Length(max=32, maxMessage="姓かなは{{ limit }}文字以内で入力してください。")
     * @Groups({"customerList", "findEstimationAjax", "showIdentifiedCustomer"})
     */
    private $nameLastKana;

    /**
     * 名かな
     * @var string
     * @ORM\Column(type="string", length=32, nullable=true)
     * @Assert\Length(max=32, maxMessage="名かなは{{ limit }}文字以内で入力してください。")
     * @Groups({"customerList", "findEstimationAjax", "showIdentifiedCustomer"})
     */
    private $nameFirstKana;

    /**
     * 連絡先メールアドレス
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max=255, maxMessage="連絡先メールアドレスは{{ limit }}文字以内で入力してください。")
     * @Assert\Email(message="連絡先メールアドレスの形式が不正です。")
     * @Groups({"customerList", "findCustomerMst", "findEstimationAjax", "showIdentifiedCustomer"})
     */
    private $contactEmail;

    /**
     * 連絡先メールアドレス２
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max=255, maxMessage="連絡先メールアドレス２は{{ limit }}文字以内で入力してください。")
     * @Assert\Email(message="連絡先メールアドレスの形式が不正です。")
     * @Groups({"customerList", "findEstimationAjax", "showIdentifiedCustomer"})
     */
    private $contactEmail2;

    /**
     * 連絡先メールアドレス３
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max=255, maxMessage="連絡先メールアドレス３は{{ limit }}文字以内で入力してください。")
     * @Assert\Email(message="連絡先メールアドレスの形式が不正です。")
     * @Groups({"customerList", "findEstimationAjax", "showIdentifiedCustomer"})
     */
    private $contactEmail3;

    /**
     * 郵便番号
     * @var string
     * @ORM\Column(type="string", length=8, nullable=true)
     * @Assert\Length(max=8, maxMessage="郵便番号は{{ limit }}文字以内で入力してください。")
     * @Groups({"findCustomerMst", "findEstimationAjax"})
     */
    private $zipCode;

    /**
     * 都道府県
     * @var string
     * @ORM\Column(type="string", length=32, nullable=true)
     * @Assert\Length(max=32, maxMessage="都道府県は{{ limit }}文字以内で入力してください。")
     * @Groups({"customerList", "findCustomerMst", "findEstimationAjax", "showIdentifiedCustomer"})
     */
    private $prefecture;

    /**
     * 市区町村
     * @var string
     * @ORM\Column(type="string", length=32, nullable=true)
     * @Assert\Length(max=32, maxMessage="市区町村は{{ limit }}文字以内で入力してください。")
     * @Groups({"customerList", "findCustomerMst", "findEstimationAjax", "showIdentifiedCustomer"})
     */
    private $city;

    /**
     * 番地
     * @var string
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Assert\Length(max=64, maxMessage="番地は{{ limit }}文字以内で入力してください。")
     * @Groups({"customerList", "findCustomerMst", "findEstimationAjax", "showIdentifiedCustomer"})
     */
    private $address1;

    /**
     * 建物名など
     * @var string
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Assert\Length(max=64, maxMessage="建物名などは{{ limit }}文字以内で入力してください。")
     * @Groups({"customerList", "findCustomerMst", "findEstimationAjax", "showIdentifiedCustomer"})
     */
    private $address2;

    /**
     * 会社名
     * @var string
     * @ORM\Column(type="string", length=32, nullable=true)
     * @Assert\Length(max=32, maxMessage="会社名は{{ limit }}文字以内で入力してください。")
     * @Groups({"customerList", "findCustomerMst", "findEstimationAjax", "showIdentifiedCustomer", "reconcilePayment"})
     */
    private $corporateName;

    /**
     * 会社名かな
     * @var string
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Assert\Length(max=64, maxMessage="会社名かなは{{ limit }}文字以内で入力してください。")
     */
    private $corporateNameKana;

    /**
     * 部課名
     * @var string
     * @ORM\Column(type="string", length=32, nullable=true)
     * @Assert\Length(max=32, maxMessage="部課名は{{ limit }}文字以内で入力してください。")
     * @Groups({"findCustomerMst", "findEstimationAjax"})
     */
    private $departmentName;

    /**
     * 電話番号
     * @var string
     * @ORM\Column(type="string", length=13, nullable=true)
     * @Assert\Length(max=13, maxMessage="電話番号は{{ limit }}文字以内で入力してください。")
     * @Groups({"customerList", "findCustomerMst", "findEstimationAjax", "showIdentifiedCustomer"})
     */
    private $tel;

    /**
     * 携帯電話番号
     * @var string
     * @ORM\Column(type="string", length=13, nullable=true)
     * @Assert\Length(max=13, maxMessage="携帯電話番号は{{ limit }}文字以内で入力してください。")
     * @Groups({"customerList", "findEstimationAjax", "showIdentifiedCustomer"})
     */
    private $mobilePhone;

    /**
     * FAX
     * @var string
     * @ORM\Column(type="string", length=13, nullable=true)
     * @Assert\Length(max=13, maxMessage="FAXは{{ limit }}文字以内で入力してください。")
     * @Groups({"findEstimationAjax"})
     */
    private $fax;

    /**
     * 前回担当営業
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $lastHandledBy;

    /**
     * 前回担当部署
     * @var Department
     * @ORM\ManyToOne(targetEntity="Department")
     * @ORM\JoinColumn(nullable=true)
     */
    private $lastHandledDepartment;

    /**
     * 初回担当営業
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $firstHandledBy;

    /**
     * 初回担当部署
     * @var Department
     * @ORM\ManyToOne(targetEntity="Department")
     * @ORM\JoinColumn(nullable=true)
     */
    private $firstHandledDepartment;

    /**
     * 初回見積送信日時
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $firstHandledAt;

    /**
     * 登録部署
     * @var Department
     * @ORM\ManyToOne(targetEntity="Department")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdDepartment;

    /**
     * 通常ポイント
     * @var int
     * @ORM\Column(type="integer")
     * @Assert\GreaterThanOrEqual(0, message="数量は{{ compared_value }}以上を入力してください。")
     * @Groups({"findEstimationAjax"})
     */
    private $point;

    /**
     * プレミアムポイント
     * @var int
     * @ORM\Column(type="integer")
     * @Assert\GreaterThanOrEqual(0, message="数量は{{ compared_value }}以上を入力してください。")
     * @Groups({"findEstimationAjax"})
     */
    private $premiumPoint;

    /**
     * 連絡方法
     * @var CommunicationMethod
     * @ORM\ManyToOne(targetEntity="CommunicationMethod")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"findEstimationAjax"})
     */
    private $communicationMethod;

    /**
     * メールマガジンを配信するか
     * @var bool
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isSentMailMagazine = false;

    /**
     * 締め日
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $deadLine;

    /**
     * 支払い日
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $paidDate;

    /**
     * 紹介者
     * @var CustomerMst
     * @ORM\ManyToOne(targetEntity="CustomerMst")
     * @ORM\JoinColumn(nullable=true)
     */
    private $invitedBy;

    /**
     * 生年月日
     * @var \DateTime
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"showIdentifiedCustomer"})
     */
    private $birthDay;

    /**
     * 性別
     * @var Sex
     * @ORM\ManyToOne(targetEntity="Sex")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"showIdentifiedCustomer"})
     */
    private $sex;

    /**
     * 本登録したかどうか
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $isRegistered = false;

    /**
     * 備考
     * @var string
     * @ORM\Column(type="text", nullable=true, length=65535)
     * @Assert\Length(max=65535, maxMessage="備考は{{ limit }}文字以内で入力してください。")
     */
    private $remark;

    /**
     * 最終ログイン日時
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * ハッシュ
     * @var string
     * @ORM\Column(type="string", length=64)
     */
    private $hash;

    /**
     * 見積タリフ区分
     * @var TariffType
     * @ORM\ManyToOne(targetEntity="TariffType")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"findEstimationAjax"})
     */
    private $estimationTariffType;

    /**
     * @var DeliveryAddressMst[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="DeliveryAddressMst", mappedBy="customerMst")
     */
    private $deliveryAddressMsts;

    /**
     * @var Inquiry[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="Inquiry", mappedBy="customerMst")
     */
    private $inquiries;

    /**
     * @var Estimation[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="Estimation", mappedBy="customerMst")
     */
    private $estimations;

    /**
     * @var Payment[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="Payment", mappedBy="customer")
     */
    private $payments;

    /**
     * @var SenderAddressMst[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="SenderAddressMst", mappedBy="customerMst")
     */
    private $senderAddressMsts;

    public function __construct()
    {
        $this->deliveryAddressMsts = new ArrayCollection();
        $this->inquiries = new ArrayCollection();
        $this->estimations = new ArrayCollection();
        $this->payments = new ArrayCollection();
        $this->senderAddressMsts = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getMemberCode(): ?string
    {
        return $this->memberCode;
    }

    /**
     * @param null|string $memberCode
     */
    public function setMemberCode(?string $memberCode): void
    {
        $this->memberCode = $memberCode;
    }

    /**
     * @return string
     */
    public function getNameLast(): ?string
    {
        return $this->nameLast;
    }

    /**
     * @param string $nameLast
     */
    public function setNameLast(?string $nameLast): void
    {
        $this->nameLast = $nameLast;
    }

    /**
     * @return string
     */
    public function getNameFirst(): ?string
    {
        return $this->nameFirst;
    }

    /**
     * @param string $nameFirst
     */
    public function setNameFirst(?string $nameFirst): void
    {
        $this->nameFirst = $nameFirst;
    }

    /**
     * @return string
     */
    public function getNameLastKana(): ?string
    {
        return $this->nameLastKana;
    }

    /**
     * @param string $nameLastKana
     */
    public function setNameLastKana(?string $nameLastKana): void
    {
        $this->nameLastKana = $nameLastKana;
    }

    /**
     * @return string
     */
    public function getNameFirstKana(): ?string
    {
        return $this->nameFirstKana;
    }

    /**
     * @param string $nameFirstKana
     */
    public function setNameFirstKana(?string $nameFirstKana): void
    {
        $this->nameFirstKana = $nameFirstKana;
    }

    /**
     * @return string
     */
    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    /**
     * @param string $contactEmail
     */
    public function setContactEmail(?string $contactEmail): void
    {
        $this->contactEmail = $contactEmail;
    }

    /**
     * @return string
     */
    public function getContactEmail2(): ?string
    {
        return $this->contactEmail2;
    }

    /**
     * @param string $contactEmail2
     */
    public function setContactEmail2(?string $contactEmail2): void
    {
        $this->contactEmail2 = $contactEmail2;
    }

    /**
     * @return string
     */
    public function getContactEmail3(): ?string
    {
        return $this->contactEmail3;
    }

    /**
     * @param string $contactEmail3
     */
    public function setContactEmail3(?string $contactEmail3): void
    {
        $this->contactEmail3 = $contactEmail3;
    }

    /**
     * @return string
     */
    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     */
    public function setZipCode(?string $zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    /**
     * @return string
     */
    public function getPrefecture(): ?string
    {
        return $this->prefecture;
    }

    /**
     * @param string $prefecture
     */
    public function setPrefecture(?string $prefecture): void
    {
        $this->prefecture = $prefecture;
    }

    /**
     * @return string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    /**
     * @param string $address1
     */
    public function setAddress1(?string $address1): void
    {
        $this->address1 = $address1;
    }

    /**
     * @return string
     */
    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    /**
     * @param string $address2
     */
    public function setAddress2(?string $address2): void
    {
        $this->address2 = $address2;
    }

    /**
     * @return string
     */
    public function getCorporateName(): ?string
    {
        return $this->corporateName;
    }

    /**
     * @param string $corporateName
     */
    public function setCorporateName(?string $corporateName): void
    {
        $this->corporateName = $corporateName;
    }

    /**
     * @return string
     */
    public function getCorporateNameKana(): ?string
    {
        return $this->corporateNameKana;
    }

    /**
     * @param string $corporateNameKana
     */
    public function setCorporateNameKana(?string $corporateNameKana): void
    {
        $this->corporateNameKana = $corporateNameKana;
    }

    /**
     * @return string
     */
    public function getDepartmentName(): ?string
    {
        return $this->departmentName;
    }

    /**
     * @param string $departmentName
     */
    public function setDepartmentName(?string $departmentName): void
    {
        $this->departmentName = $departmentName;
    }

    /**
     * @return string
     */
    public function getTel(): ?string
    {
        return $this->tel;
    }

    /**
     * @param string $tel
     */
    public function setTel(?string $tel): void
    {
        $this->tel = $tel;
    }

    /**
     * @return string
     */
    public function getMobilePhone(): ?string
    {
        return $this->mobilePhone;
    }

    /**
     * @param string $mobilePhone
     */
    public function setMobilePhone(?string $mobilePhone): void
    {
        $this->mobilePhone = $mobilePhone;
    }

    /**
     * @return string
     */
    public function getFax(): ?string
    {
        return $this->fax;
    }

    /**
     * @param string $fax
     */
    public function setFax(?string $fax): void
    {
        $this->fax = $fax;
    }

    /**
     * @return User|null
     */
    public function getLastHandledBy(): ?User
    {
        return $this->lastHandledBy;
    }

    /**
     * @param User|null $lastHandledBy
     */
    public function setLastHandledBy(?User $lastHandledBy): void
    {
        $this->lastHandledBy = $lastHandledBy;
    }

    /**
     * @return Department|null
     */
    public function getLastHandledDepartment(): ?Department
    {
        return $this->lastHandledDepartment;
    }

    /**
     * @param Department|null $lastHandledDepartment
     */
    public function setLastHandledDepartment(?Department $lastHandledDepartment): void
    {
        $this->lastHandledDepartment = $lastHandledDepartment;
    }

    /**
     * @return User|null
     */
    public function getFirstHandledBy(): ?User
    {
        return $this->firstHandledBy;
    }

    /**
     * @param User|null $firstHandledBy
     */
    public function setFirstHandledBy(?User $firstHandledBy): void
    {
        $this->firstHandledBy = $firstHandledBy;
    }

    /**
     * @return Department|null
     */
    public function getFirstHandledDepartment(): ?Department
    {
        return $this->firstHandledDepartment;
    }

    /**
     * @param Department|null $firstHandledDepartment
     */
    public function setFirstHandledDepartment(?Department $firstHandledDepartment): void
    {
        $this->firstHandledDepartment = $firstHandledDepartment;
    }

    /**
     * @return \DateTime|null
     */
    public function getFirstHandledAt(): ?\DateTime
    {
        return $this->firstHandledAt;
    }

    /**
     * @param \DateTime|null $firstHandledAt
     */
    public function setFirstHandledAt(?\DateTime $firstHandledAt): void
    {
        $this->firstHandledAt = $firstHandledAt;
    }

    /**
     * @return Department|null
     */
    public function getCreatedDepartment(): ?Department
    {
        return $this->createdDepartment;
    }

    /**
     * @param Department|null $createdDepartment
     */
    public function setCreatedDepartment(?Department $createdDepartment): void
    {
        $this->createdDepartment = $createdDepartment;
    }

    /**
     * @return int|null
     */
    public function getPoint(): ?int
    {
        return $this->point;
    }

    /**
     * @param int|null $point
     */
    public function setPoint(?int $point): void
    {
        $this->point = $point;
    }

    /**
     * @return int|null
     */
    public function getPremiumPoint(): ?int
    {
        return $this->premiumPoint;
    }

    /**
     * @param int|null $premiumPoint
     */
    public function setPremiumPoint(?int $premiumPoint): void
    {
        $this->premiumPoint = $premiumPoint;
    }

    /**
     * @return CommunicationMethod|null
     */
    public function getCommunicationMethod(): ?CommunicationMethod
    {
        return $this->communicationMethod;
    }

    /**
     * @param CommunicationMethod|null $communicationMethod
     */
    public function setCommunicationMethod(?CommunicationMethod $communicationMethod): void
    {
        $this->communicationMethod = $communicationMethod;
    }

    /**
     * @return bool|null
     */
    public function isSentMailMagazine(): ?bool
    {
        return $this->isSentMailMagazine;
    }

    /**
     * @param bool|null $isSentMailMagazine
     */
    public function setIsSentMailMagazine(?bool $isSentMailMagazine): void
    {
        $this->isSentMailMagazine = $isSentMailMagazine;
    }

    /**
     * @return string|null
     */
    public function getDeadLine(): ?string
    {
        return $this->deadLine;
    }

    /**
     * @param string|null $deadLine
     */
    public function setDeadLine(?string $deadLine): void
    {
        $this->deadLine = $deadLine;
    }

    /**
     * @return string|null
     */
    public function getPaidDate(): ?string
    {
        return $this->paidDate;
    }

    /**
     * @param string|null $paidDate
     */
    public function setPaidDate(?string $paidDate): void
    {
        $this->paidDate = $paidDate;
    }

    /**
     * @return CustomerMst|null
     */
    public function getInvitedBy(): ?CustomerMst
    {
        return $this->invitedBy;
    }

    /**
     * @param CustomerMst|null $invitedBy
     */
    public function setInvitedBy(?CustomerMst $invitedBy): void
    {
        $this->invitedBy = $invitedBy;
    }

    /**
     * @return \DateTime|null
     */
    public function getBirthDay(): ?\DateTime
    {
        return $this->birthDay;
    }

    /**
     * @param \DateTime|null $birthDay
     */
    public function setBirthDay(?\DateTime $birthDay): void
    {
        $this->birthDay = $birthDay;
    }

    /**
     * @return Sex|null
     */
    public function getSex(): ?Sex
    {
        return $this->sex;
    }

    /**
     * @param Sex|null $sex
     */
    public function setSex(?Sex $sex): void
    {
        $this->sex = $sex;
    }

    /**
     * @return bool|null
     */
    public function isRegistered(): ?bool
    {
        return $this->isRegistered;
    }

    /**
     * @param bool|null $isRegistered
     */
    public function setIsRegistered(?bool $isRegistered): void
    {
        $this->isRegistered = $isRegistered;
    }

    /**
     * @return null|string
     */
    public function getRemark(): ?string
    {
        return $this->remark;
    }

    /**
     * @param null|string $remark
     */
    public function setRemark(?string $remark): void
    {
        $this->remark = $remark;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    /**
     * @param \DateTimeInterface|null $lastLogin
     */
    public function setLastLogin(?\DateTimeInterface $lastLogin): void
    {
        $this->lastLogin = $lastLogin;
    }

    /**
     * @return string
     */
    public function getHash(): ?string
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     */
    public function setHash(?string $hash): void
    {
        $this->hash = $hash;
    }

    /**
     * @return TariffType
     */
    public function getEstimationTariffType(): ?TariffType
    {
        return $this->estimationTariffType;
    }

    /**
     * @param TariffType $estimationTariffType
     */
    public function setEstimationTariffType(?TariffType $estimationTariffType): void
    {
        $this->estimationTariffType = $estimationTariffType;
    }

    /**
     * @return DeliveryAddressMst[]|ArrayCollection
     */
    public function getDeliveryAddressMsts()
    {
        return $this->deliveryAddressMsts;
    }

    /**
     * @return Inquiry[]|ArrayCollection
     */
    public function getInquiries()
    {
        return $this->inquiries;
    }

    /**
     * @return Estimation[]|ArrayCollection
     */
    public function getEstimations()
    {
        return $this->estimations;
    }

    /**
     * @return Payment[]|ArrayCollection
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * @return SenderAddressMst[]|ArrayCollection
     */
    public function getSenderAddressMsts()
    {
        return $this->senderAddressMsts;
    }

    /**
     * 預り金を取得する
     * @return int
     */
    public function getDeposit(): int
    {
        $paidAmount = 0;
        $appliedAmount = 0;
        foreach ($this->getPayments() as $p) {
            $paidAmount += $p->getPaidAmount();
            foreach ($p->getPaymentEstimationRelations() as $rel) {
                $appliedAmount += $rel->getAppliedAmount();
            }
        }

        return $paidAmount - $appliedAmount;
    }

    public function __toString(): string
    {
        return (string)$this->getId();
    }

    public function getViewLabel(): string
    {
        return $this->getId().':'.$this->getNameLast().' '.$this->getNameFirst();
    }
}
