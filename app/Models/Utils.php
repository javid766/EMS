<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Utils extends Model
{
    use HasFactory;

    public $CALL_TYPE_API = 'api';
    public $CALL_TYPE_DEFAULT = 'default';

    public $SP_ACTION_CREATE = 'i';
    public $SP_ACTION_UPDATE = 'u';
    public $SP_ACTION_DELETE = 'd';

    public $FIRST_LOGIN_WELCOME = 'Welcome Message';
    public $FIRST_LOGIN_INVALID = 'Invalid username /password. Please try again';
    public $FIRST_LOGIN_EXISTS  = 'User already Exists';

    public $API_VALIDATION_ERROR  = 'error';
    public $API_VALIDATION_SUCCESS  = 'success';

    public function returnResponseStatusMessage($status, $message, $type, $link) {

        if ($type == $this->CALL_TYPE_API) {

            return response([
                'message' => $message,
                'status' => $status,
            ]);

        } else {

            return redirect($link)->with($status, $message);
        }
    }

    public function returnResponseStatusMessageExtra($status, $message, $extra_label, $extra_value, $type, $link) {

        if ($type == $this->CALL_TYPE_API) {

            return response([
                'message' => $message,
                'status' => $status,
                $extra_label => $extra_value,
            ]);

        } else {

            return redirect($link)->with($status, $message);
        }
    }

    public function  saveDbLogs($logData){

        DB::table('aaLog')->insert($logData);
    }
}
