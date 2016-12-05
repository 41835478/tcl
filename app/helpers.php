<?php
	
	//打印测试数据函数
	function p($s){

		echo '<pre>';
			var_dump($s);
		echo '</pre>';
	}

	// 获取最后一条sql
	function lastSql(){

		$sql = DB::getQueryLog();

		$query = end($sql);

		return $query;
	}

	//处理权限信息，按功能分类
	function getPermissionByModel($permissions){

		/*处理步骤：1、将所有权限slug获取得到新数组，将其中每一个元素按'.'分割，获得新数组。
				 2、去除新数组中重复元素。
				 3、循环新数组，将权限信息每一个权限slug与slug前缀匹配。
				 4、最终数组键为slug'.'前的部分，值为与前缀匹配的数组。*/

		$slug            = array(); //定义slug数组
		$slug_pre        = array(); //定义slug前缀数组
		$permissions_del = array(); //返回的处理后的数组
		$chunk_num       = 0;       //权限分2个区域，每个区域数目应为总数目的一半

		foreach ($permissions as $key => $permission) {
            
            $slug[] = $permission->slug;
        }

        // 遍历slug数组，只保留'.'前面的前缀
        foreach ($slug as $key => $value) {
        	
        	$pre = explode('.', $value);
        	$slug[$key] = $pre[0];
        }

        //去除重复元素
        $slug_pre = collect($slug)->unique();
        $chunk_num = ((int)($slug_pre->count()/2)) + 1;

        // dd(((int)($slug_pre->count()/2)) + 1);

        //遍历权限前缀数组，配置所有权限信息并分类
        foreach ($slug_pre as $key => $value) {

    		$filtered = $permissions->filter(function ($item) use ($value) {
    			
    			// 获得权限前缀
    			$pre = explode('.', $item->slug);
    			// p($pre[0]);
    			if($pre[0] == $value){

    				return $item;
    			}   			
			});
    		
			// dd($filtered->all());

    		$permissions_del[$value] = $filtered->all();			
        }

        return collect($permissions_del)->chunk($chunk_num);
	}

	/**
     * Get the validation rules that apply to the request.
     * 返回关联表指定字段
     * $relation:定义的关联名称,如:hasOneShop
     * Array $columns 指定的字段
     * @return array
     */
	function tableUnionDesign($relation, Array $columns){

		$desing = array();

		$desing[$relation] = (function($query) use ($columns){
		            $query->select($columns);
		        });

        return $desing;
	}

	/**
     * 返回允许用户添加的用户
     * $role_id:角色ID
     * @return array
     */
	function getUserAddAllowList($role_id){

		// dd($role_id);
		//返回允许添加用户列表
		$allow_list = array();

		switch ($role_id) {
			case '1':
				# 超级管理员，可添加所有用户
				$allow_list = ['1', '2', '3', '4', '5', '6', '7', '8', '9'];
			break;

			case '2':
				# 总部管理员，可添加除超级管理员和总部管理员以外用户
				$allow_list = ['3', '4', '5', '6', '7', '8', '9'];
			break;

			case '6':
				# 门店店长，可添加属于本门店店员
			$allow_list = ['7'];
			break;
			
			default:
				# 不允许添加用户
			 	$allow_list = [];
			break;
		}
		return $allow_list;
	}

	/**
 	* 返回可读性更好的文件尺寸
 	*/
	function human_filesize($bytes, $decimals = 2)
	{
	    $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB'];
	    $factor = floor((strlen($bytes) - 1) / 3);
	
	    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) .@$size[$factor];
	}

	/**
	 * 判断文件的MIME类型是否为图片
	 */
	function is_image($mimeType)
	{
	    return starts_with($mimeType, 'image/');
	}

	/**
	 * 获得车源编号
	 */
	function getCarCode()
	{
		$datetime = new DateTime('06/06/16');

		dd($datetime);
		p(Carbon::createFromDate('2016', '01', '02', 'Asia/Shanghai'));
		p(Carbon::now()->year);
		p(Carbon::now()->month);
		p(Carbon::now()->day);
		p(Carbon::now()->hour);
		dd(Carbon::today());
	    return ;
	}