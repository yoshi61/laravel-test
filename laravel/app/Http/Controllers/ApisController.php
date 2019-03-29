<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApisController extends Controller
{
    public function today(){
		$allPageInfo = DB::select("SELECT * FROM show_date_time inner join articles on articles.article_id = show_date_time.article_id WHERE show_date_time >= NOW() + INTERVAL 1 HOUR AND show_date_time <= NOW() + INTERVAL 25 HOUR ORDER BY show_date_time ASC LIMIT 10");
// 		print_r($allPageInfo[0]->article_id);
        $data = '{
  "type": "selection",
  "altText": "今夜の知らせ",
  "selections": [
    {
      "imageUrl": "https://rr.img.naver.jp/mig?src=http%3A%2F%2Fimgcc.naver.jp%2Fkaze%2Fmission%2FUSER%2F20170531%2F41%2F4653891%2F68%2F1080x1080xe2693842358779ae371f39.jpg&twidth=60&theight=60&qlt=80&res_format=jpg&op=sc",
      "title": "トピック",
      "text": "川栄李奈や内田理央に石田ゆり子も…あの人達の「胸キュン姿」",
      "buttons": [
        {
          "label": "見に行く",
          "url": "https://matome.naver.jp/odai/2149619967702657301",
          "action": "url"
        }
      ]
    },
    {
      "imageUrl": "https://rr.img.naver.jp/mig?src=http%3A%2F%2Fimgcc.naver.jp%2Fkaze%2Fmission%2FUSER%2F20170531%2F58%2F5842128%2F85%2F120x120xc25f819bbe99b2e1106144df.jpg&twidth=60&theight=60&qlt=80&res_format=jpg&op=sc",
      "title": "トピック",
      "text": "Twitter民が呟く『胸がざわついた話』に…お、おう（汗）",
      "buttons": [
        {
          "label": "見に行く",
          "url": "https://matome.naver.jp/odai/2149620556808147601",
          "action": "url"
        }
      ]
    },
    {
      "imageUrl": "https://rr.img.naver.jp/mig?src=https%3A%2F%2Fimages-fe.ssl-images-amazon.com%2Fimages%2FI%2F416Oq-2DKhL.jpg&twidth=60&theight=60&qlt=80&res_format=jpg&op=sc",
      "title": "トピック",
      "text": "emmaに鈴木愛理も...「スタイルブック」が可愛すぎる女性芸…",
      "buttons": [
        {
          "label": "見に行く",
          "url": "https://matome.naver.jp/odai/2149587469268218301",
          "action": "url"
        }
      ]
    },
    {
      "imageUrl": "https://rr.img.naver.jp/mig?src=http%3A%2F%2Fapi-prev.aflo.com%2Fo%2Fetjwnwwp%2Faflo_mrva144360.jpg&twidth=60&theight=60&qlt=80&res_format=jpg&op=sc",
      "title": "トピック",
      "text": "違法カジノ問題から涙の復帰戦Ｖ…バドミントン桃田賢斗に応援の声…",
      "buttons": [
        {
          "label": "見に行く",
          "url": "https://matome.naver.jp/odai/2149627147156123201",
          "action": "url"
        }
      ]
    },
    {
      "imageUrl": "https://rr.img.naver.jp/mig?src=https%3A%2F%2Fimages-fe.ssl-images-amazon.com%2Fimages%2FI%2F413WqAw2ToL.jpg&twidth=60&theight=60&qlt=80&res_format=jpg&op=sc",
      "title": "トピック",
      "text": "マサイ族とも一般人とも仲良しに...芸人の「コミュ力高い」エピ…",
      "buttons": [
        {
          "label": "見に行く",
          "url": "https://matome.naver.jp/odai/2149627132756104701",
          "action": "url"
        }
      ]
    }
  ]
}';
		$res = $this->convertGallaryType($allPageInfo);
	    return data;//response()->json($res);
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
