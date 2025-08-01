<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Payment
 *
 * @property int $id
 * @property int $invoice_id
 * @property string $payment_mode
 * @property float $amount
 * @property string $payment_date
 * @property int|null $transaction_id
 * @property string|null $meta
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|Payment newModelQuery()
 * @method static Builder|Payment newQuery()
 * @method static Builder|Payment query()
 * @method static Builder|Payment whereAmount($value)
 * @method static Builder|Payment whereCreatedAt($value)
 * @method static Builder|Payment whereId($value)
 * @method static Builder|Payment whereInvoiceId($value)
 * @method static Builder|Payment whereMeta($value)
 * @method static Builder|Payment whereNotes($value)
 * @method static Builder|Payment wherePaymentDate($value)
 * @method static Builder|Payment wherePaymentMode($value)
 * @method static Builder|Payment whereTransactionId($value)
 * @method static Builder|Payment whereUpdatedAt($value)
 *
 * @mixin Eloquent
 *
 * @property int $is_approved
 * @property-read string $payment_type
 * @property-read string $payments_mode
 * @property-read Invoice $invoice
 *
 * @method static Builder|Payment whereIsApproved($value)
 */
class Payment extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'payments';

    protected $fillable = [
        'invoice_id',
        'amount',
        'payment_date',
        'payment_mode',
        'transaction_id',
        'notes',
        'is_approved',
    ];

    protected $casts = [
        'invoice_id' => 'integer',
        'amount' => 'double',
        'payment_date' => 'datetime',
        'payment_mode' => 'integer',
        'transaction_id' => 'string',
        'meta' => 'json',
        'notes' => 'string',
        'user_id' => 'integer',
        'is_approved' => 'integer',
    ];

    public $appends = ['payments_mode'];

    const PAYMENT_ATTACHMENT = 'payment_attachment';

    const FULLPAYMENT = 2;

    const PARTIALLYPAYMENT = 3;

    const PAYMENT_TYPE = [
        self::FULLPAYMENT => 'Full Payment',
        self::PARTIALLYPAYMENT => 'Partially Payment',
    ];

    const PENDING = 0;

    const APPROVED = 1;

    const REJECTED = 2;

    const STATUS_ALL = 3;

    const PAID = 'Paid';

    const PROCESSING = 'Processing';

    const DENIED = 'Denied';

    const STATUS_ARR_ALL = 'All';

    const PAYMENT_STATUS = [
        self::STATUS_ALL => self::STATUS_ARR_ALL,
        self::PENDING => self::PROCESSING,
        self::APPROVED => self::PAID,
        self::REJECTED => self::DENIED,
    ];

    const PAYMENT_STATUS_NEW = [
        '' => self::STATUS_ARR_ALL,
        self::PENDING => self::PROCESSING,
        self::APPROVED => self::PAID,
        self::REJECTED => self::DENIED,
    ];

    const STATUS = [
        'RECEIVED_AMOUNT' => 'Received Amount',
        'PAID_AMOUNT' => 'Paid Amount',
        'DUE_AMOUNT' => 'Due Amount',
    ];

    const MANUAL = 1;

    const STRIPE = 2;

    const PAYPAL = 3;

    const CASH = 4;

    const RAZORPAY = 5;

    const PAYSTACK = 6;

    const MERCADOPAGO = 7;

    const ALL = 0;

    const PAYMENT_MODE = [
        self::ALL => 'All',
        self::MANUAL => 'Manual',
        self::STRIPE => 'Stripe',
        self::PAYPAL => 'Paypal',
        self::CASH => 'Cash',
        self::RAZORPAY => 'Razorpay',
        self::PAYSTACK => 'Paystack',
        self::MERCADOPAGO => 'Mercadopago',
    ];

    const PAYMENT_MODE_NEW = [
        '' => 'All',
        self::MANUAL => 'Manual',
        self::STRIPE => 'Stripe',
        self::PAYPAL => 'Paypal',
        self::CASH => 'Cash',
        self::RAZORPAY => 'Razorpay',
        self::PAYSTACK => 'Paystack'
    ];

    public static $rules = [
        'payment_type' => 'required',
        'amount' => 'required',
        'payment_mode' => 'required',
        'payment_attachment' => 'nullable|mimes:pdf,png,jpeg,jpg',
    ];

    public function getPaymentAttachmentAttribute(): string
    {
        /** @var Media $media */
        $media = $this->getMedia(self::PAYMENT_ATTACHMENT)->first();
        if ($media !== null) {
            return $media->getFullUrl();
        }

        return false;
    }

    public function getPaymentTypeAttribute(): string
    {
        return self::PAYMENT_MODE[$this->payment_mode];
    }

    public function getPaymentsModeAttribute(): string
    {
        return self::PAYMENT_MODE[$this->payment_mode];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
