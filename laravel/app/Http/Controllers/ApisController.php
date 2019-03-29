<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApisController extends Controller
{
    public function todayAPI(){
		$allPageInfo = DB::select('SELECT * FROM show_date_time inner join articles on articles.article_id = show_date_time.article_id WHERE show_date_time >= NOW() + INTERVAL 1 HOUR AND show_date_time <= NOW() + INTERVAL 25 HOUR ORDER BY show_date_time ASC LIMIT 10');
        if(count($allPageInfo)){
            $res = $this->convertGallaryType($allPageInfo, $userId);
        }
        else{
            $res = $this->ifResultNull();
        }
	    return response()->json($res);
    }

    public function searchAPI(){
		$keyword = $_GET['keyword'];
		$userId = $_GET['userId'];
        // auto user registration
        $this->autoRegUsers($userId);

		$allPageInfo = DB::select('SELECT * FROM show_date_time inner join articles on articles.article_id = show_date_time.article_id WHERE title like "%'. $keyword .'%" ORDER BY show_date_time ASC LIMIT 10');
		$res = $this->convertGallaryType($allPageInfo, $userId);
	    return response()->json($res);
    }

    // add to reminder
	function addToRemindAPI(){
		$userId = $_GET['userId'];
		$articleId = $_GET['articleId'];
        // auto user registration
		$this->autoRegUsers($userId);

		$numOfRemind = DB::select('SELECT num_of_remind FROM articles WHERE user_id = ?', [$keyword]);
		$alreadyRegistered = DB::select('SELECT id FROM users inner join remind_list on users.user_id = remind_list.user_id');
        print_r($numOfRemind);
        print_r($alreadyRegistered);
		// if($numOfRemind > 9){
		// 	$arr = array('type'=>"text",'text'=>"リマインド登録が上限に達しました（10）");
		// 	$this->set('text',$arr);
		// }
		// elseif($alreadyRegistered){
		// 	$arr = array('type'=>"text",'text'=>"この番組はリマインドに登録済みです！");
		// 	$this->set('text',$arr);
		// }
		// else{
		// 	$this->Remind_list->addToRemindTable($userId, $articleId);
		// 	$numOfRemind = $this->Users->checkNumOfRemind($userId);
		// 	$this->Users->numOfRemindInc($userId);
		// 	$numOfRemind++;
		// 	$arr = array('type'=>"text",'text'=>"登録しました（" . (string)($numOfRemind) . ")");
		// 	$this->set('text',$arr);
		// }
	}


    // Auto register users
    function autoRegUsers($userId){
        $res = DB::select('SELECT id FROM users WHERE user_id = ?', [$userId]);
        // if not registered
        if(!count($res)){
            DB::insert('INSERT INTO users (user_id) values (?)', [$userId]);
        }
        return;
    }

    // If nothing can be fund in the database, reply message instead
	function ifResultNull(){
    	$arr = array('type'=>"text",'text'=>"Sorry! Your search did not match any documents!");
    	return $arr;
	}

    // fix the format of the string to be displayed as introduction
	function textFormatCheck($text){

		$text 	= stripslashes($text);
        //$text = preg_replace("/[^ぁ-んァ-ンーa-zA-Z0-9一-龠０-９\-\r]+/u", '', $text);
		mb_internal_encoding("UTF-8");
		$count 	= mb_strlen($text);
		if(40 < $count){
			$replace = mb_substr($text, 0 , 37 );
			return $replace = $replace."...";
		}
		return $text;
	}

	// fix the format of the string to be displayed as title
	function titleFormatCheck($text){

		$text 	= strip_tags($text);
		$text 	= stripslashes($text);
		mb_internal_encoding("UTF-8");
		$count 	= mb_strlen($text);
		if(40 < $count){
			$replace = mb_substr($text, 0 , 37 );
			return $replace = $replace."...";
		}
		return $text;
	}

	// convert all page info to a format that can be displayed as Gallary(slide window) on Facebook and LINE
	function convertGallaryType($allPageInfo, $userId){
		$buttonRemind = array(
				"type" => "postback",
				"attribute" => "api",
				"label" => "add reminder",
				"query" => "http://test.heteml.net/neco-hosted/remind/add?userId=",
				"action" => "postback",
				"data" => "type=api&query=[http://test.heteml.net/neco-hosted/remind/add?userId=]"
		);

		$arr = array('type'=>"selection",'altText'=>"displayed!",'selections'=>array());

		$count = 0;
		foreach($allPageInfo as $allPageInfo){
			$buttonRemind['query'] = "http://test.heteml.net/neco-hosted/remind/add?";
			$buttonRemind['data'] = "type=api&query=[http://test.heteml.net/neco-hosted/remind/add?";

			if($count >= 10){
				break;
			}
			$buttonRemind['query'] .= "articleId=" . (string)$allPageInfo->article_id;
			$buttonRemind['data'] .= "articleId=" . (string)$allPageInfo->article_id . "]";

			$imageUrl = $allPageInfo->pic_url;
			$tempAirDate = $allPageInfo->show_date_time;
			$text = substr($tempAirDate, 5, -3);
			$title = $allPageInfo->title;

			if(is_null($imageUrl)){
				$imageUrl = "https://stage-cdn.engage-bot.asia/b1525097ce/7e1f26de0b1e404ab0f98c4408b7bd91";
			}

			$datas = array('imageUrl'=>$imageUrl,'title'=>$this->titleFormatCheck($text),'text'=> $this->textFormatCheck($title),'buttons'=>array());

			$datas['buttons'][] = $buttonRemind;
			$arr['selections'][] = $datas;

			$count++;
		}
		return $arr;
	}
}
