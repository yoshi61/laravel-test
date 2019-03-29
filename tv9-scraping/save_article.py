# -*- coding: utf-8 -*-
from article import Article
import pymysql.cursors
from config import database
import helper

# database connection
connection = helper.connectDB(database)

class SaveArticle:
	def __init__(self):
		print("'saving' object created...")

	def saveArticle(self, article):
		"""check if article is already in database"""
		with connection.cursor() as cursor:
			cursor.execute("SELECT id FROM articles WHERE article_id = " + str(article.id))
			result = cursor.fetchall()

		if(len(result) == 0):
			"""If article not in database, save it"""
			with connection.cursor() as cursor:
				cursor.execute("INSERT INTO `" + database['db'] + "`.`articles` (`article_id`, `title`, `page_url`, `pic_url`, `intro`) VALUES (%s, %s, %s, %s, %s)", (article.id, article.pageTitleStr, article.pageUrl, article.imgUrl, article.pageIntro))
				connection.commit()
				print('Success!!! article ' + article.id + ' has been saved!')

		with connection.cursor() as cursor:
			cursor.execute("INSERT INTO `" + database['db'] + "`.`show_date_time` (`article_id`, `show_date_time`) VALUES (%s, %s)", (article.id, article.showDateTime))
			connection.commit()
			print('Success!!! show date time for ' + article.id + ' has been saved!')
