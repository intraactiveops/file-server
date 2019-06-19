<?php
use App\FileList;
use App\FileAccessHistory;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    echo 'test';
});
Route::post('/', function () {
    // return view('welcome');
    echo 'post test';
});
Route::post('v1/get-ticket', "UploadTicketController@getTicket");
Route::post('v1/upload', "FileListController@upload");

Route::get('files/{filename}', function ($filename)
{
  $explodedFileName = explode('.', $filename);
  $path = storage_path('app/uploaded_files/'.$explodedFileName[1].'/' . $filename);
  $fileList = (new App\FileList())->where('name', $filename);
  if(!$fileList->count()){
    //TODO prevent brute force huessing of links
    abort(404);
  }
  if (!File::exists($path)) {
      abort(404);
  }

  $file = File::get($path);
  $type = File::mimeType($path);

  $response = Response::make($file, 200);
  $response->header("Content-Type", $type);
  $fileAccessHistory = new App\FileAccessHistory();
  $fileAccessHistory->name = $filename;
  $fileAccessHistory->ip_address = getenv('REMOTE_ADDR');
  $fileAccessHistory->save();
  return $response;
});