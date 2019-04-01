# -*- coding: utf-8 -*-
import sys
import re
from bs4 import BeautifulSoup
import requests
import chardet
# import urllib
# from urllib.parse import urlparse
from bs4 import NavigableString, Declaration, Comment
import helper
from config import config
import datetime

#Articleクラス
class Article:
        """記事データを扱うクラスid(string)と記事のURLを渡してオブジェクトを生成してください"""
        def __init__(self, id, URL, showDateTime):
            #自身のidをセット
            self.id = id;
            #記事のURLをセット
            self.pageUrl = URL
            self.showDateTime = showDateTime.strftime('%Y-%m-%d %H:%M:%S')
            #URLでリクエストしてHTMLを取得
            resp = requests.get(URL)
            #HTMLをパース
            soup = BeautifulSoup(resp.text, "html.parser")
            #記事のtitleをセット
            try:
                self.setPageTitle(soup)
            except:
                self.pageTitleStr = config['altTxt']
                print('Fail to get title from ' + str(self.id) + ', set nothing found instead!')
            #記事の画像URLをセット
            try:
                self.setImgUrl(soup)
            except:
                self.imgUrl = config['altImg']
                print('Fail' + ' to get image from ' + str(self.id) + ', set to default image')
            #記事の本文をセット
            try:
                self.setPageIntro(soup)
            except:
                self.pageIntro = config['altTxt']
                print('Fail to get introduction from ' + str(self.id) + ', set nothing found instead!')

        #soupをセレクターなどで辿って、titleを見つけてセット
        def setPageTitle(self,soup):
                self.pageTitleStr = soup.find("meta", property="og:title").get("content")

        #soupをセレクターなどで辿って、imag URLを見つけてセット
        def setImgUrl(self, soup):
                self.imgUrl = soup.find("meta", property="og:image").get("content")

        #soupをセレクターなどで辿って、本文をセット
        def setPageIntro(self, soup):
                self.pageIntro = soup.find("meta", property="og:description").get("content")

        #デバッグ用にオブジェクトの中身をprint out
        def printAll(self):
                print("[ID]：" + self.id + "\n")
                print("[showDateTime] :" + self.showDateTime + "\n")
                print("[title]：" + self.pageTitleStr + "\n")
                print("[URL]：" + self.pageUrl + "\n")
                print("[imgURL] :" + self.imgUrl + "\n")
                print("[pageIntro] :" + self.pageIntro + "\n")

# show = Article('165954', 'https://www.yourtv.com.au/program/ellen/165954/', '1992919')
# show.printAll()
