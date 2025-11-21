<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class GlJournal
 *
 * @property $id
 * @property $company_id
 * @property $transaction_date
 * @property $debit
 * @property $credit
 * @property $description
 * @property $created_by
 * @property $updated_by
 * @property $deleted_at
 * @property $created_at
 * @property $updated_at
 *
 * @property Company $company
 * @property User $user
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class GlJournal extends BaseModel
{
    use SoftDeletes;

    static $rules = [
        'company_id' => 'required',
        'transaction_date' => 'required',
        'debit' => 'required',
        'credit' => 'required',
        'description' => 'string',
    ];

    protected $perPage = 10;
    static $document_no_padding = 4;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['company_id', 'transaction_date', 'debit', 'credit', 'description'];
    protected $guarded = [];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class, 'company_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function created_by_details()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updated_by_details()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by', 'id');
    }

    /**
     * Get all of the comments for the GlJournal
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gljournallines()
    {
        return $this->hasMany(GlJournalLine::class, 'gl_journal_id', 'id');
    }

    // Method to calculate the debit sum for all related lines
    public function getDebitSumAttribute()
    {
        return $this->gljournallines()->sum('debit'); // Sums up the 'debit' column in the related lines
    }
    // Method to calculate the debit sum for all related lines
    public function getCreditSumAttribute()
    {
        return $this->gljournallines()->sum('credit'); // Sums up the 'debit' column in the related lines
    }
    static function generate_document_no($company_id){
        $last = GlJournal::where('company_id', $company_id)->orderByDesc('id')->first();
        $prefix = 'GL-';
        if($last){
            $document_no = $last->document_no;
            $document_no = explode('-', $document_no);
            $document_no = intval($document_no[1]);
            $document_no++;
            $document_no = str_pad($document_no, self::$document_no_padding, '0', STR_PAD_LEFT); // '0001'
            $document_no = $prefix.$document_no;
        }else{
            $document_no = 1;
            $document_no = str_pad($document_no, self::$document_no_padding, '0', STR_PAD_LEFT); // '0001'
            $document_no = $prefix.$document_no;
        }
        return $document_no;
    }
    static function auto_create($payload){
        $document_no = self::generate_document_no($payload['company_id']);

        return GlJournal::create([
            'company_id' => $payload['company_id'],
            'created_by' => $payload['created_by'],
            'transaction_date' => $payload['transaction_date'],
            'status' => $payload['status'],
            'document_no' => $document_no,
        ]);
    }
    static function update_glJournal($data, $glJournal){

        $total_debit = 0;
        $total_credit = 0;

        foreach($data['rows']??[] as $row){
            $total_debit+=$row['debit'];
            $total_credit+=$row['credit'];
            GlJournalLine::where('id', $row['row_id'])->update([
                'account_id' => $row['account_id'],
                'product_id' => $row['product_id'],
                'partner_id' => $row['partner_id'],
                'debit' => $row['debit'],
                'credit' => $row['credit'],
                'quantity' => $row['quantity'],
                'description' => $row['description'],
            ]);
        }
        GlJournal::where('id', $glJournal->id)
        // $glJournal
        ->update([
            'transaction_date' => $data['transaction_date'],
            // 'debit' => $data['debit'],
            // 'credit' => $data['credit'],
            'debit' => $total_debit,
            'credit' => $total_credit,
            'description' => $data['description'],
            'status' => $data['status'],
            'updated_by' => $data['updated_by']
        ]);

        return true;
    }

}
