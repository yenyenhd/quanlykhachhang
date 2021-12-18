<?php
namespace App\Traits;
use Illuminate\Support\Facades\Log;

trait Delete {
    public function deleteTrait($id, $model)
    {
        try {
            $model->find($id)->delete();
            return response()->json([
                'code' => 200,
                'message' => 'success'
            ], 500);
        } catch (\Exception $exception) {
            Log::error('Message:' . $exception->getMessage(). 'Line: ' .$exception->getLine());
            return response()->json([
                'code' => 500,
                'message' => 'fail'
            ], 500);
        }
    }
}