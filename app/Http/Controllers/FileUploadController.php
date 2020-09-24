<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FileUploadController extends Controller
{
    //
    public function fileUpload(Request $request){
        $validate = Validator::make($request->all(), ['file_path'=> 'mimes:pdf']);

        // If the file is not PDF, return 422 error.
        if ($validate->fails()) {
            return response()->json([
                'errors' => "File doesn't match",
            ], 422);
        }

        if($request->file()) {
            DB::beginTransaction();

            try{
                $fileName = $request->file('file_path')->getClientOriginalName();
                $fileSize = $request->file('file_path')->getSize();

                // If the file doesn't contain the word "Proposal", just ignore the PDF.
                if(strpos($fileName, 'Proposal') === false){
                    return true;
                }

                $filePath = $request->file('file_path')->storeAs('uploads', $fileName, 'public');
                $dataFile = [
                    'name' => $fileName,
                    'size' => $fileSize,
                    'file_path' => '/storage/' . $filePath
                ];

                // Check if we already have the file with the same name and size (the `name` and `size` columns in our DB table).
                $getFile = File::where([
                    ['name', $fileName],
                    ['size', $fileSize]
                ])->first();

                // dd($getFile);
                if (!empty($getFile)) {
                    // Update File
                    $getFile->update($dataFile);

                    return true;
                }

                // Create File
                File::firstOrCreate($dataFile);

                return true;
            } catch (\Exception $e) {

                DB::rollBack();
                return response()->json([
                    'error' => $e->getMessage()
                ]);
            }

        }
   }
}
