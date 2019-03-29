<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApisController extends Controller
{
    public function today(){
		$allPageInfo = DB::select("SELECT * FROM show_date_time inner join articles on articles.article_id = show_date_time.article_id WHERE show_date_time >= NOW() + INTERVAL 1 HOUR AND show_date_time <= NOW() + INTERVAL 25 HOUR ORDER BY show_date_time ASC LIMIT 10");
// 		print_r($allPageInfo[0]->article_id);
		$res = $this->convertGallaryType($allPageInfo);
	    return response()->json($res);
    }

    	function ifResultNull(){
		$arr = array('type'=>"text",'text'=>"申し訳ありません。見つかりませんでした。");
		return $arr;
	}

	function textFormatCheck($text){

		$text 	= stripslashes($text);
		mb_internal_encoding("UTF-8");
		$count 	= mb_strlen($text);
		if(40 < $count){
			$replace = mb_substr($text, 0 , 37 );
			return $replace = $replace."...";
		}
		return $text;
	}

	//タイトルをラインギャラリーに表示できる形に成形します。
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

		//お気に入り登録できるギャラリーconverter
	function convertFavGallaryType1($allPageInfo){
		$buttons = array(
									array("label" => "この記事を読む","url" => "","action"=>"url"),
									array("label" => "お気に入り登録","attribute" => "api", "query" => "", "data" => "","action"=>"postback")
								);
		$arr = array('type'=>"selection",'altText'=>"記事を表示しました。",'selections'=>array());

		$count = 0;
		foreach($allPageInfo as $pageInfo){
			$imageUrl = "https://d322n64by2tkh8.cloudfront.net/public/img/logo/itsjob_header_logo.png";
			$text = $pageInfo->text;
			$title = $pageInfo->title;

			$datas = array('imageUrl'=>$imageUrl,'title'=>$this->titleFormatCheck($title),'text'=> $this->textFormatCheck($text),'buttons'=>array());

			$buttons[0]['url'] = $pageInfo->originalLink;
			$tmpUrl = explode("/", $pageInfo->URL);;
			$articleId = end($tmpUrl);
			$buttons[1]['query'] = 'http://44a6c383.ngrok.io/saveFavorite?articleId=' . (string)$articleId;
			$buttons[1]['data'] = 'type=api&query=[' . 'http://44a6c383.ngrok.io/saiyou/saveFavorite?articleId=' . (string)$articleId . ']';

			$datas['buttons'][] = $buttons[0];
			$datas['buttons'][] = $buttons[1];
			$arr['selections'][] = $datas;

			$count++;
		}
		return $arr;
	}

	#引数（タイトル、カテゴリー、サイトURL,画像URL）
	function convertGallaryType($allPageInfo){
		$buttonRemind = array(
				"type" => "postback",
				"attribute" => "api",
				"label" => "set reminder",
				"query" => "http://test.heteml.net/neco-hosted/remind/add?userId=",
				"action" => "postback",
				"data" => "type=api&query=[http://test.heteml.net/neco-hosted/remind/add?userId=]"
		);

		$arr = array('type'=>"selection",'altText'=>"記事を表示しました。",'selections'=>array());

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

            $testStr = 'koko:::';
            foreach ($_GET as $name => $value) {
                $testStr = $testStr . $name . ' : ' . $value . '\n';
            }
			$title = $testStr;//$allPageInfo->title;

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
