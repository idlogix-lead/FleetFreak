<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SidebarItems extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Get the user that owns the SidebarItems
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(SidebarGroups::class, 'sidebar_group_id', 'id');
    }
}
