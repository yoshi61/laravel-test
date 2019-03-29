# -*- coding: utf-8 -*-
from article import Article
import pymysql.cursors
from config import database
import helper

#サーバー接続
connection = helper.connectDB(database)

#チャンネルネコをデーターベースに保存するためのオブジェクト。
class SaveArticle:
	def __init__(self):
		print("'saving' object created...")

	def saveArticle(self, article):
		"""Validation　idが無ければそのまま保存する　あればデーターを更新する"""
		with connection.cursor() as cursor:
			cursor.execute("SELECT id FROM articles WHERE article_id = " + str(article.id))
			result = cursor.fetchall()

		if(len(result) == 0):
			"""データーベースになかった場合は追加する"""
			with connection.cursor() as cursor:
				cursor.execute("INSERT INTO `" + database['db'] + "`.`articles` (`article_id`, `title`, `page_url`, `pic_url`, `intro`) VALUES (%s, %s, %s, %s, %s)", (article.id, article.pageTitleStr, article.pageUrl, article.imgUrl, article.pageIntro))
				connection.commit()
				print('Success!!! article ' + article.id + ' has been saved!')

		with connection.cursor() as cursor:
			cursor.execute("INSERT INTO `" + database['db'] + "`.`show_date_time` (`article_id`, `show_date_time`) VALUES (%s, %s)", (article.id, article.showDateTime))
			connection.commit()
			print('Success!!! show date time for ' + article.id + ' has been saved!')
