<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class UploadTicket extends Model
{
  use SoftDeletes;
}
