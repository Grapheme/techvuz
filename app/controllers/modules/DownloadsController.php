<?php

class DownloadsController extends BaseController {
	
	public function __construct(){
		
		$this->beforeFilter('downloads');
	}
	
	public function getIndex(){
		
		$dir = public_path().Config::get('app-default.upload_dir')."/";

		if (!file_exists($dir) or !is_dir($dir)):
			mkdir($dir);
		endif;

		$req_path = Input::get('path');
		$path = $dir.$req_path;
		$directories_array = File::directories($path);
		$files_array = File::files($path);
		if($req_path != "")
		{
			$ex_path = explode("/", $req_path);
			unset($ex_path[count($ex_path)-1]);
			$back_link = implode("/", $ex_path);
		}
		foreach($directories_array as $dir)
		{
			$url = $req_path."/".basename($dir);
			$dirs[$url] = basename($dir);
		}
		foreach($files_array as $file)
		{
			$url = URL::to(Config::get('app-default.upload_dir').$req_path."/".basename($file));
			$files[$url] = array('name' => basename($file), 'size' => round(File::size($file)/1024, 2));
		}

		return View::make('modules.downloads.index', compact('dirs','files','back_link'));
	}

	public function postUpload(){
		
		$file = Input::file('file');
		$path = Input::get('path');
 
		$destinationPath = public_path().Config::get('app-default.upload_dir').$path;
		$extension =$file->getClientOriginalExtension();
		$filename = time()."_".str_random(40).".".$extension; 
		$upload_success = Input::file('file')->move($destinationPath, $filename);
		 
		if( $upload_success ) {
		   return Response::json('success', 200);
		} else {
		   return Response::json('error', 400);
		}
	}
	
	public function redactorUploadedImages(){
		
		$uploadPath = public_path('uploads');
		if(!file_exists($uploadPath)):
			echo json_encode(array());
			exit;
		endif;
		$fullList[0] = $fileList = array('thumb'=>'','image'=>'','title'=>'Изображение','folder'=>'Миниатюры');
		if($listDir = scandir($uploadPath)):
			$index = 0;
			foreach($listDir as $number => $file):
				if(is_file($uploadPath.'/'.$file)):
					$thumbnail = $uploadPath.'/thumbnail/thumb_'.$file;
					if(file_exists($thumbnail) && is_file($thumbnail)):
						$fileList['thumb'] = url('uploads/thumbnail/thumb_'.$file);
					endif;
					$fileList['image'] = url('uploads/'.$file);
					$fullList[$index] = $fileList;
					$index++;
				endif;
			endforeach;
		endif;
		echo json_encode($fullList);
	}
	
	public function redactorUploadImage(){
		
		$uploadPath = public_path('uploads');
		if(Input::hasFile('file')):
			$fileName = str_random(16).'.'.Input::file('file')->getClientOriginalExtension();
			if(!File::exists($uploadPath.'/thumbnail')):
				File::makeDirectory($uploadPath.'/thumbnail',0777,TRUE);
			endif;
			ImageManipulation::make(Input::file('file')->getRealPath())->resize(100,100)->save($uploadPath.'/thumbnail/thumb_'.$fileName);
			ImageManipulation::make(Input::file('file')->getRealPath())->resize(600,600)->save($uploadPath.'/'.$fileName);
			#$file = array('filelink'=>url('uploads/'.$fileName));
			$file = array('filelink'=>'/uploads/'.$fileName);
			echo stripslashes(json_encode($file));
		else:
			exit('Нет файла для загрузки!');
		endif;
	}
	
	public function postUploadCatalogProductImages($product_id = NULL){
		
		if(Input::hasFile('file')):
			$this->moduleActionPermission('catalogs','download');
			if(!is_null($product_id)):
				$product = Product::where('user_id',Auth::user()->id)->find($product_id);
			else:
				$product = NULL;
			endif;
			if(AuthAccount::isAdminLoggined()):
				$dirPath = 'public/uploads/catalogs';
			elseif(AuthAccount::isUserLoggined()):
				$dirPath = 'usersfiles/account-'.Auth::user()->id.'/catalogs';
			else:
				$dirPath = 'usersfiles/temporary/catalogs';
			endif;
			$dirFullPath = base_path($dirPath);
			
			if(!File::isDirectory($dirFullPath.'/thumbnail')):
				File::makeDirectory($dirFullPath.'/thumbnail',0777,TRUE);
			endif;
			$fileName = str_random(24).'.'.Input::file('file')->getClientOriginalExtension();
			ImageManipulation::make(Input::file('file')->getRealPath())->resize(100,100,TRUE)->save($dirFullPath.'/thumbnail/'.$fileName);
			Input::file('file')->move($dirFullPath,$fileName);
			
			$productID = (!is_null($product)) ? $product->id : 0;
			$productTitle = (!is_null($product)) ? $product->title : '';
			$module = Module::where('name', 'catalogs')->first();
			$maxSortValue = (int)Image::where('item_id',$productID)->where('module_id',$module->id)->max('sort')+1;
			
			$newImageData = array('module_id' => $module->id,'item_id' => $productID,'user_id'=>Auth::user()->id,'sort' => $maxSortValue,'title' => $productTitle,'description' => '','attributes' => '[]','publication' => 1,
				'paths' => json_encode(array('image' => $dirPath.'/'.$fileName,'thumbnail'=> $dirPath.'/thumbnail/'.$fileName)));
			$newImage = Image::create($newImageData);
			if(is_null($product)):
				$FreeImagesIDs = Image::where('user_id',Auth::user()->id)->where('module_id',$module->id)->where('item_id',0)->lists('id');
				Session::put($module->url.'_product', $FreeImagesIDs);
			endif;
			return Response::json(array('status'=>TRUE,'responseText'=>'Файл загружен'),200);
		else:
			return Response::json(array('status'=>FALSE,'responseText'=>'Файл не загружен'),400);
		endif;
		
	}
	
}