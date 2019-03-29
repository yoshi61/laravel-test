# scraping-template
template for basic scraping

## 目的
- ![chatbot](https://raw.githubusercontent.com/yoshi61/scraping-template/master/IMG_1428.jpg)
- 画像のようなChat Botで表示されてる”画像”、”タイトル”、”説明”、”リンク”を含むウィンドウを作るためのスクレイピングをテンプレート化したものです


## 使い方
1. pythonなどをインストールして、MAMPあるいはレンタルサーバーでmySQL環境を整える
2. mySQLにmakeTable.sqlをインポートして基本的なテーブルを作成する
3. config.pyにdatabaseの情報とグローバル定数を記入する
4. スクレイピングしたいウェブページをブラウザーで開き、右クリックでページのソースをみる
5. article.pyを開き、sampleを参考にしてセレクターを使いsetXXXX関数を全て完成させる
6. min.pyを開き、sampleを参考にしてスクレイピングを完成させる

## 環境
- python 3.60
- pip 19.0.2
- MySQL  5.6.35
- beautifulsoup4 4.7.1     # via bs4
- bs4  0.0.1
- certifi  2019.3.9         # via requests
- chardet  3.0.4
- idna  2.8                 # via requests
- pymysql  0.9.3
- requests  2.21.0
- soupsieve  1.8            # via beautifulsoup4
- urllib3  1.24.1           # via requests


## ファイル説明

- config.py
    - databaseのアカウント情報を記入する場所
    - 全体で使うグローバル定数を定義する場所

- makeTable.sql
    - databaseにインポートしてテーブルを作成するためのコード

- requirements.in
    - 必要なライブラリーをここに記入

- article.py
    - 記事を保管するためのクラス

- save_article.py
    - articleを渡してdatabaseに保存するためのクラス

- main.py
    - スクレイピングのコードをここに書く
